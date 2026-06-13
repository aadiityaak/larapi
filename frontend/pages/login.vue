<template>
  <div v-if="loginStyle === 'loading'" class="min-h-screen bg-gradient-to-br from-slate-50/15 via-blue-50/15 to-indigo-100/15 dark:from-zinc-900/15 dark:via-zinc-800/15 dark:to-zinc-700/15">
    <div class="flex items-center justify-center h-screen">
      <div class="backdrop-blur-md bg-white/20 dark:bg-zinc-900/20 border border-slate-200/30 dark:border-zinc-700/30 rounded-2xl p-8 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
        <div class="flex flex-col items-center gap-4">
          <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
            <Icon name="lucide:loader-2" class="w-8 h-8 text-white animate-spin" />
          </div>
          <div class="text-center">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-1">Loading...</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">Memuat pengaturan login</p>
            
            <!-- Progress bar -->
            <div class="mt-4 w-48 h-1 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
              <div 
                class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-300 ease-out"
                :style="`width: ${loadingProgress}%`"
              ></div>
            </div>
            <div class="text-xs text-slate-400 dark:text-slate-500 mt-1">
              {{ Math.round(loadingProgress) }}%
            </div>
          </div>
          
          <!-- Skip button untuk fallback -->
          <button 
            @click="skipLoading"
            class="mt-4 px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors underline"
          >
            Lewati dan gunakan pengaturan default
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <div v-else class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-700" :style="backgroundStyle">
    <!-- Background Overlay untuk readability jika ada background image -->
    <div v-if="backgroundImage" class="absolute inset-0 bg-black/20 dark:bg-black/40 z-0"></div>
    
    <!-- Modern Background Pattern - hanya tampil jika tidak ada background image -->
    <div v-if="!backgroundImage" class="absolute inset-0 overflow-hidden z-0">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-indigo-600/20 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-purple-400/20 to-pink-600/20 rounded-full blur-3xl"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-br from-green-400/10 to-blue-600/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 flex items-center justify-center min-h-screen" :class="loginStyle === 'side' ? 'p-0' : 'p-4'">
      <div class="w-full" :class="loginStyle === 'center' ? 'max-w-md' : ''">

        <!-- Login Form Card untuk Center Layout -->
        <div 
          v-if="loginStyle === 'center'"
          class="backdrop-blur-md border rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20"
          :class="backgroundImage 
            ? 'bg-white/30 dark:bg-zinc-900/30 border-white/40 dark:border-zinc-700/40' 
            : 'bg-white/20 dark:bg-zinc-900/20 border-slate-200/30 dark:border-zinc-700/30'"
        >
          <LoginCenter />
        </div>
        
        <!-- Side Layout - Full Width untuk Layout Side -->
        <div v-if="loginStyle === 'side'" class="w-full">
          <LoginSide />
        </div>

        <!-- Footer - hanya untuk center layout -->
        <div v-if="loginStyle === 'center'" class="text-center mt-6">
          <p class="text-xs text-slate-500 dark:text-slate-400">
            © 2025 Asisten Notaris by sucitek.com. All rights reserved.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const client = useSanctumClient();
const toast = useToast();
const { public: config } = useRuntimeConfig();
const isDev = process.dev || import.meta.env.DEV;

const loginStyle = ref('loading');
const backgroundImage = ref('');
const backgroundSettings = ref({});
const loadingProgress = ref(0);

// Progress bar animation
const startProgressAnimation = () => {
  const interval = setInterval(() => {
    if (loadingProgress.value < 90) {
      loadingProgress.value += Math.random() * 20
    }
    if (loginStyle.value !== 'loading') {
      loadingProgress.value = 100
      setTimeout(() => clearInterval(interval), 300)
    }
  }, 200)
}

const selectedLoginComponent = computed(() => {
  return loginStyle.value === 'side' ? LoginSide : LoginCenter;
});

// Computed untuk background style
const backgroundStyle = computed(() => {
  const style = {};
  
  // Handle background image
  if (backgroundImage.value) {
    // Jika backgroundImage.value adalah URL lengkap, gunakan langsung
    // Jika hanya nama file, tambahkan path storage
    const imageUrl = backgroundImage.value.startsWith('http') 
      ? backgroundImage.value 
      : backgroundImage.value.startsWith('/storage/') 
        ? backgroundImage.value
        : `/storage/${backgroundImage.value}`;
        
    style.backgroundImage = `url(${imageUrl})`;
    style.backgroundSize = 'cover';
    style.backgroundPosition = 'center';
    style.backgroundRepeat = 'no-repeat';
  }
  
  // Handle background color (overlay dengan gradient default jika ada)
  if (backgroundSettings.value.color) {
    if (backgroundImage.value) {
      // Jika ada image, tambahkan color sebagai overlay
      style.backgroundColor = backgroundSettings.value.color;
    } else {
      // Jika tidak ada image, ganti gradient dengan solid color
      style.background = backgroundSettings.value.color;
    }
  }
  
  return style;
});

const fetchLoginSettings = async () => {
  try {
    // Ambil pengaturan background (yang juga berisi style login)
    const response = await client('/settings/background');
    
    // Set login style dari response dengan fallback yang jelas
    const newStyle = response.style || 'center';
    loginStyle.value = newStyle;
    
    // Set background settings
    backgroundSettings.value = response;
    
    // Set background image jika ada
    if (response.image && response.image !== '' && response.image !== null) {
      backgroundImage.value = response.image;
    } else {
      backgroundImage.value = '';
    }
    
    // Complete loading dengan memastikan style sudah ter-update
    loadingProgress.value = 100;
    
    // Pastikan reactive update sudah selesai sebelum melanjutkan
    await nextTick();
    
    // Return success indicator
    return 'success';
    
  } catch (error) {
    // Default fallback - pastikan selalu ada fallback
    loadingProgress.value = 100;
    setTimeout(() => {
      loginStyle.value = 'center';
      backgroundSettings.value = {};
      backgroundImage.value = '';
    }, 300);
    
    // Tampilkan toast error jika diperlukan
    if (error.response?.status !== 404) {
      toast.add({ 
        severity: 'warn', 
        summary: 'Warning', 
        detail: 'Menggunakan pengaturan login default', 
        life: 3000 
      });
    }
    
    // Return error indicator
    return 'error';
  }
};

// Timeout fallback jika API terlalu lama
let timeoutId;

const skipLoading = () => {
  if (timeoutId) {
    clearTimeout(timeoutId);
  }
  loadingProgress.value = 100
  setTimeout(() => {
    loginStyle.value = 'center'
  }, 300)
}

const initializeLogin = async () => {
  // Start progress animation
  startProgressAnimation()
  
  // Set timeout 5 detik untuk fallback
  let apiCompleted = false;
  const timeoutPromise = new Promise((resolve) => {
    timeoutId = setTimeout(() => {
      if (!apiCompleted) {
        loadingProgress.value = 100;
        setTimeout(() => {
          loginStyle.value = 'center';
          backgroundSettings.value = {};
          backgroundImage.value = '';
          resolve('timeout');
        }, 300);
      } else {
        resolve('ignored');
      }
    }, 5000);
  });

  // Race antara API call dan timeout
  const result = await Promise.race([
    fetchLoginSettings().then(res => {
      apiCompleted = true;
      return res;
    }),
    timeoutPromise
  ]);
  
  // Jika API call berhasil, cancel timeout untuk mencegah override
  if (result !== 'timeout') {
    if (timeoutId) {
      clearTimeout(timeoutId);
    }
  }
};

onMounted(initializeLogin);
</script>
