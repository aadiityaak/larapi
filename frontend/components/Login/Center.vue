<template>
  <!-- Error Messages -->
  <div v-if="route.query.redirect && route.query.redirect !== '/'" class="p-4">
    <div class="p-4 bg-gradient-to-r from-amber-50/10 to-orange-50/10 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200/50 dark:border-amber-800/50 rounded-xl">
      <div class="flex items-center gap-3">
        <Icon name="lucide:info" class="text-amber-600 dark:text-amber-400" size="1.2em" />
        <div class="text-sm text-amber-700 dark:text-amber-300">
          Hmmm, sepertinya anda mencoba mengakses halaman
          <em class="font-medium">"{{ route.query.redirect }}"</em>, silahkan login terlebih dahulu
          untuk melanjutkan.
        </div>
      </div>
    </div>
  </div>
  
  <div v-if="loginError" class="p-4">
    <div class="p-4 bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200/50 dark:border-red-800/50 rounded-xl">
      <div class="flex items-center gap-3">
        <Icon name="lucide:alert-circle" class="text-red-600 dark:text-red-400" size="1.2em" />
        <div class="text-sm text-red-700 dark:text-red-300">{{ loginError }}</div>
      </div>
    </div>
  </div>
  
  <!-- Login Form -->
  <form @submit.prevent="handleLogin" ref="form" class="p-6">
    <!-- Logo Section -->
    <div v-if="logo" class="text-center mb-6">
      <div class="inline-block p-3 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-zinc-800 dark:to-zinc-700 rounded-2xl shadow-sm">
        <img :src="logo" alt="Logo" class="w-16 h-auto" />
      </div>
    </div>
    
    <!-- Welcome Text -->
    <div class="text-center mb-8">
      <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Masuk ke Akun</h2>
      <p class="text-sm text-slate-500 dark:text-slate-400">Gunakan kredensial Anda untuk mengakses dashboard</p>
    </div>
    
    <!-- Form Fields -->
    <div class="space-y-6">
      <div class="space-y-2">
        <label for="email" class="text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2">
          <Icon name="lucide:mail" size="0.9em" />
          Email Address
        </label>
        <InputText 
          id="email" 
          v-model="credentials.email" 
          type="email" 
          class="w-full !rounded-xl !border-slate-300 dark:!border-zinc-600 !bg-white dark:!bg-zinc-800 focus:!border-blue-500 focus:!ring-blue-500" 
          placeholder="Masukkan email Anda"
          required
        />
      </div>
      
      <div class="space-y-2">
        <label for="password" class="text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2">
          <Icon name="lucide:lock" size="0.9em" />
          Password
        </label>
        <Password 
          id="password" 
          v-model="credentials.password" 
          :feedback="false" 
          toggleMask 
          class="w-full" 
          :inputStyle="{ 
            width: '100%', 
            borderRadius: '0.75rem',
            borderColor: '#d1d5db',
            backgroundColor: '#ffffff'
          }"
          :pt="{
            input: { 
              class: '!rounded-xl !border-slate-300 dark:!border-zinc-600 !bg-white dark:!bg-zinc-800 focus:!border-blue-500 focus:!ring-blue-500'
            }
          }"
          placeholder="Masukkan password Anda"
          required
        />
      </div>
    </div>
    
    <!-- Login Button -->
    <div class="mt-8">
      <Button 
        type="submit" 
        :disabled="isLoading"
        class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 disabled:from-slate-400 disabled:to-slate-500 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center gap-3 font-medium"
      >
        <Icon 
          :name="isLoading ? 'lucide:loader-2' : 'lucide:log-in'" 
          :class="isLoading ? 'animate-spin' : ''" 
          size="1.1em" 
        />
        <span>{{ isLoading ? 'Memproses...' : 'Masuk ke Dashboard' }}</span>
      </Button>
    </div>
    
    <!-- Additional Links -->
    <div class="mt-6 text-center">
      <NuxtLink 
        to="/forgot-password" 
        class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors inline-flex items-center gap-1"
      >
        <Icon name="lucide:help-circle" size="0.8em" />
        Lupa password?
      </NuxtLink>
    </div>
  </form>
</template>

<script setup lang="ts">
  const client = useSanctumClient()
  const isLoading = ref(false)
  const { login } = useSanctumAuth()
  const route = useRoute()
  const logo = ref('')

  const credentials = ref({
    email: '',
    password: '',
  })

  const loginError = ref('')

  onMounted(async () => {
    try {
      // Fetch favicon/logo
      const faviconResponse = await client('/api/settings/favicon')
      if (faviconResponse.favicon) {
        logo.value = faviconResponse.favicon
      }
    } catch (error) {
      console.error('Gagal mengambil pengaturan favicon:', error)
    }
  })

  async function handleLogin() {
    isLoading.value = true
    try {
      await login(credentials.value)
      loginError.value = ''
      isLoading.value = false
    } catch (err: any) {
      loginError.value = err.response._data.message
      isLoading.value = false
      // if csrf token expired
      if (err.response.status === 419) {
        // delete cookies laravel_session and XSRF-TOKEN
        document.cookie = 'laravel_session=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
        document.cookie = 'XSRF-TOKEN=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
        handleLogin()
      }
    }
  }
</script>
