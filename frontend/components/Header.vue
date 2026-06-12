<template>
  <header class="m-4">
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-4 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <div class="flex justify-between items-center">
        <!-- Left Section -->
        <div class="flex items-center gap-4">
        <button 
          @click="toggleSidebar" 
          class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-all duration-200 group"
        >
          <Icon 
            v-if="collapsed" 
            name="lucide:panel-left-open" 
            size="1.1em" 
            class="text-slate-600 dark:text-slate-300 group-hover:text-slate-800 dark:group-hover:text-white transition-colors" 
          />
          <Icon 
            v-else 
            name="lucide:panel-left-close" 
            size="1.1em" 
            class="text-slate-600 dark:text-slate-300 group-hover:text-slate-800 dark:group-hover:text-white transition-colors" 
          />
        </button>
        
        <div>
          <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $route.meta.title }}</h1>
        </div>
      </div>

      <!-- Right Section -->
      <div class="flex items-center gap-3">
        <!-- Notifications -->
        <Notifikasi />
        
        <!-- Dark Mode Toggle -->
        <button 
          @click="toggleDarkMode()" 
          class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-all duration-200 group"
        >
          <Icon 
            v-if="isDark" 
            name="lucide:sun" 
            class="text-amber-500 group-hover:text-amber-600 transition-colors" 
            size="1.1em" 
          />
          <Icon 
            v-else 
            name="lucide:moon" 
            class="text-slate-600 dark:text-slate-300 group-hover:text-slate-800 dark:group-hover:text-white transition-colors" 
            size="1.1em" 
          />
        </button>
        
        <!-- User Menu -->
        <div class="relative z-50">
          <button 
            @click="toggleMenu" 
            class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-zinc-800 transition-all duration-200 group"
          >
            <div class="relative">
              <div v-if="avatar" class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 p-0.5">
                <img :src="avatar" :alt="user.name" class="w-full h-full rounded-full object-cover bg-white" />
              </div>
              <div v-else class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                <span class="text-white font-bold text-xs">{{ user.name.charAt(0).toUpperCase() }}</span>
              </div>
              <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-green-500 border-2 border-white dark:border-zinc-900 rounded-full"></div>
            </div>
            <Icon name="lucide:chevron-down" size="0.8em" class="text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors" />
          </button>
          
          <!-- Custom Dropdown Menu -->
          <Teleport to="body">
            <Transition name="dropdown">
              <div 
                v-if="showMenu"
                :style="dropdownStyle"
                class="fixed min-w-[280px] backdrop-blur-xl bg-white/95 dark:bg-zinc-900/95 border border-slate-200/50 dark:border-zinc-700/50 rounded-xl shadow-xl shadow-slate-900/10 dark:shadow-zinc-900/20 overflow-hidden z-[9999]"
                @click.stop
              >
              <!-- User Profile Header -->
              <div class="px-4 py-4 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-zinc-800 dark:to-zinc-700 border-b border-slate-200 dark:border-zinc-600">
                <div class="flex items-center gap-3">
                  <div class="relative">
                    <div v-if="avatar" class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 p-0.5">
                      <img :src="avatar" :alt="user.name" class="w-full h-full rounded-full object-cover bg-white" />
                    </div>
                    <div v-else class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                      <span class="text-white font-bold text-lg">{{ user.name.charAt(0).toUpperCase() }}</span>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-zinc-700 rounded-full"></div>
                  </div>
                  <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-slate-800 dark:text-white text-base truncate">{{ user.name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 truncate">{{ user.position || 'Staff' }}</p>
                    <div class="flex items-center gap-1 mt-1">
                      <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                      <span class="text-xs text-green-600 dark:text-green-400 font-medium">Online</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Menu Items -->
              <div class="p-2">
                <NuxtLink
                  to="/profile"
                  @click="closeMenu"
                  class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-zinc-700 transition-all duration-200 group"
                >
                  <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-zinc-800 flex items-center justify-center group-hover:bg-slate-200 dark:group-hover:bg-zinc-600 transition-colors">
                    <Icon name="lucide:user" class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300" size="0.9em" />
                  </div>
                  <span class="text-slate-700 dark:text-slate-300 font-medium">Edit Profile</span>
                </NuxtLink>
                
                <NuxtLink
                  to="/settings"
                  @click="closeMenu"
                  class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-zinc-700 transition-all duration-200 group"
                >
                  <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-zinc-800 flex items-center justify-center group-hover:bg-slate-200 dark:group-hover:bg-zinc-600 transition-colors">
                    <Icon name="lucide:settings" class="text-slate-600 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300" size="0.9em" />
                  </div>
                  <span class="text-slate-700 dark:text-slate-300 font-medium">Settings</span>
                </NuxtLink>
              </div>
              
              <!-- Logout Section -->
              <div class="px-2 pb-2">
                <div class="h-px bg-slate-200 dark:bg-zinc-700 my-2"></div>
                <button
                  @click="handleLogout"
                  class="w-full flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 group"
                >
                  <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center group-hover:bg-red-200 dark:group-hover:bg-red-900/50 transition-colors">
                    <Icon name="lucide:log-out" class="text-red-600 dark:text-red-400" size="0.9em" />
                  </div>
                  <span class="text-red-700 dark:text-red-400 font-medium">Logout</span>
                </button>
              </div>
            </div>
            </Transition>
          </Teleport>
        </div>
        </div>
      </div>
    </div>
  </header>
</template><script setup>
const userStore = useUserStore();
const sidebarStore = useSidebarStore();
const sanctumUser = useSanctumUser();
const showMenu = ref(false);
const collapsed = computed(() => sidebarStore.collapsed);
const dropdownStyle = ref({});

const { logout } = useSanctumAuth();
const { isDark, toggleDarkMode } = useDarkMode();

// Computed properties untuk user data yang reactive
const user = computed(() => {
  return {
    name: userStore.user.name || sanctumUser.value?.name || 'User',
    avatar: userStore.user.avatar || sanctumUser.value?.avatar || '',
    position: sanctumUser.value?.position || 'Staff'
  };
});

const avatar = computed(() => {
  const avatarUrl = user.value.avatar;
  return avatarUrl ? `${avatarUrl}` : '';
});

const toggleSidebar = () => sidebarStore.toggleCollapsed();

const toggleMenu = (event) => {
  if (!showMenu.value) {
    // Calculate position when opening
    const rect = event.currentTarget.getBoundingClientRect();
    dropdownStyle.value = {
      top: `${rect.bottom + 8}px`,
      right: `${window.innerWidth - rect.right}px`
    };
  }
  showMenu.value = !showMenu.value;
};

const closeMenu = () => {
  showMenu.value = false;
};

const handleLogout = async () => {
  closeMenu();
  await logout();
};

// Close menu when clicking outside
onMounted(() => {
  document.addEventListener('click', (event) => {
    const target = event.target;
    if (!target.closest('.relative')) {
      showMenu.value = false;
    }
  });
});

// Sync user data between sanctum user and user store (two-way)
watch(sanctumUser, (newUser) => {
  if (newUser) {
    userStore.setUser({
      name: newUser.name || '',
      avatar: newUser.avatar || '',
      email: newUser.email || '',
      phone: newUser.phone || '',
      address: newUser.address || ''
    });
  }
}, { immediate: true, deep: true });

// Also watch userStore changes to update sanctumUser (when updated from profile page)
watch(() => userStore.user, (newUserData) => {
  if (sanctumUser.value && newUserData) {
    // Update sanctumUser ref with new data from store
    sanctumUser.value = {
      ...sanctumUser.value,
      ...newUserData
    };
  }
}, { deep: true });
</script>

<style scoped>
/* Dropdown transition */
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.dropdown-enter-from {
  opacity: 0;
  transform: translateY(-10px) scale(0.95);
}

.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px) scale(0.95);
}

.dropdown-enter-to,
.dropdown-leave-from {
  opacity: 1;
  transform: translateY(0) scale(1);
}
</style>
