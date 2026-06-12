<template>
  <div class="space-y-6">
    <!-- Modern Navigation Header -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-4 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <div class="flex gap-3">
        <NuxtLink 
          to="/" 
          class="px-4 py-2 bg-gradient-to-r from-slate-500 to-gray-600 hover:from-slate-600 hover:to-gray-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 text-sm font-medium"
        >
          <Icon name="lucide:home" class="w-4 h-4" />
          <span class="hidden md:inline">Dashboard</span>
        </NuxtLink>
      </div>
    </div>

    <!-- Modern Layout with Glass Morphism -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <!-- Sidebar Navigation -->
      <div class="lg:col-span-1">
        <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 overflow-hidden">
          <div class="p-6 bg-gradient-to-br from-slate-50/50 to-gray-50/50 dark:from-zinc-800/50 dark:to-zinc-700/50 border-b border-slate-200/50 dark:border-zinc-700/50">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-gradient-to-br from-slate-500 to-gray-600 rounded-lg shadow-lg">
                <Icon name="lucide:settings" class="w-5 h-5 text-white" />
              </div>
              <div>
                <h2 class="text-lg font-semibold text-slate-800 dark:text-white">Pengaturan</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Kelola sistem</p>
              </div>
            </div>
          </div>
          
          <nav class="p-2">
            <ul class="space-y-1">
              <li v-for="item in items" :key="item.to">
                <NuxtLink 
                  :to="item.to"
                  class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group hover:bg-slate-100/80 dark:hover:bg-zinc-800/80"
                  active-class="bg-gradient-to-r from-blue-500/10 to-indigo-500/10 dark:from-blue-500/20 dark:to-indigo-500/20 text-blue-700 dark:text-blue-300 border border-blue-200/50 dark:border-blue-700/50 shadow-sm"
                  exact-active-class="bg-gradient-to-r from-blue-500/10 to-indigo-500/10 dark:from-blue-500/20 dark:to-indigo-500/20 text-blue-700 dark:text-blue-300 border border-blue-200/50 dark:border-blue-700/50 shadow-sm"
                >
                  <div class="p-1.5 rounded-lg bg-slate-100 dark:bg-zinc-800 group-hover:bg-slate-200 dark:group-hover:bg-zinc-700 transition-colors">
                    <Icon :name="item.icon" class="w-4 h-4 text-slate-600 dark:text-slate-400" />
                  </div>
                  <span class="text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white">{{ item.label }}</span>
                </NuxtLink>
              </li>
            </ul>
          </nav>
        </div>
      </div>

      <!-- Main Content Area -->
      <div class="lg:col-span-3">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const items = ref([
{ label: 'Umum', icon: 'lucide:settings', to: '/settings' },
{ label: 'Favicon', icon: 'lucide:file-image', to: '/settings/favicon' },
{ label: 'Login', icon: 'lucide:door-open', to: '/settings/login' },
{ label: 'Bank', icon: 'lucide:building-2', to: '/settings/bank' },
{ label: 'Post', icon: 'lucide:file-text', to: '/posts' },
{ label: 'Notifikasi', icon: 'lucide:bell', to: '/settings/notifications' },
])
</script>