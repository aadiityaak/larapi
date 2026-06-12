<template>
  <!-- Mobile Backdrop Overlay -->
  <Transition
    enter-active-class="transition-opacity duration-300 ease-out"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition-opacity duration-200 ease-in"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="!collapsed"
      @click="sidebarStore.toggle()"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 md:hidden"
    ></div>
  </Transition>

  <aside
    :class="[
      'fixed z-50 top-0 left-0 transition-all duration-500 ease-in-out md:relative md:z-auto',
      collapsed ? 'w-20 max-w-20' : 'w-[280px] md:w-[280px]',
      'md:translate-x-0',
      // Sembunyikan sidebar di mobile ketika collapsed, di desktop tetap terlihat
      collapsed ? '-translate-x-full md:translate-x-0' : 'translate-x-0'
    ]">
    <div class="flex flex-col md:mt-4 md:mb-4 md:ms-4 h-[100vh] md:h-[calc(100vh-2rem)] rounded-2xl transition-all duration-500 ease-in-out bg-gradient-to-b from-white to-slate-50 dark:from-zinc-900 dark:to-zinc-800 md:sticky top-4 shadow-xl border border-slate-200/50 dark:border-zinc-700/50 backdrop-blur-xl overflow-hidden">
      <!-- Header Section -->
      <div :class="[
        'flex items-center justify-between py-4 shrink-0 border-b border-slate-200/50 dark:border-zinc-700/50',
        collapsed ? 'px-0' : 'px-6'
      ]">
        <div v-if="!collapsed" class="flex items-center gap-3">
          <div v-if="logoStore.logo" class="p-2 flex items-center bg-gradient-to-br from-zinc-50 to-zinc-100 rounded-xl shadow-lg">
            <img :src="logoStore.logo" alt="Logo" class="w-6 h-6 object-contain" />
          </div>
          <div v-else class="p-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
            <Icon name="lucide:briefcase" class="w-6 h-6 text-white" />
          </div>
          <div class="flex flex-col items-start">
            <div :title="appName" class="font-bold text-lg text-slate-800 dark:text-white truncate w-full " style="white-space: nowrap;max-width: 150px;">{{ appName }}</div>
            <span class="text-xs text-slate-500 dark:text-slate-400">Management</span>
          </div>
        </div>
        
        <!-- Collapsed Header -->
        <div v-else class="flex justify-center w-full">
          <div v-if="logoStore.logo" class="p-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
            <img :src="logoStore.logo" alt="Logo" class="w-6 h-6 object-contain" />
          </div>
          <div v-else class="p-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
            <Icon name="lucide:briefcase" class="w-6 h-6 text-white" />
          </div>
        </div>
      </div>

      <!-- Navigation Menu -->
      <div :class="[
        'flex-1 overflow-y-auto py-4 scrollbar-thin scrollbar-thumb-slate-300 dark:scrollbar-thumb-zinc-600',
        collapsed ? 'px-2 overflow-x-visible' : 'px-4 overflow-x-hidden'
      ]">
        <nav class="space-y-2" :class="collapsed ? 'w-full' : ''">
          <template v-for="item in filteredItems" :key="item.key">
            <!-- Collapsed Menu Items -->
            <template v-if="collapsed">
              <!-- Single Menu Item (Collapsed) -->
              <div v-if="!item.items" class="relative group">
                <NuxtLink
                  :to="item.to"
                  class="flex items-center justify-center p-3 rounded-xl hover:bg-slate-100 dark:hover:bg-zinc-700/50 transition-all duration-200 relative"
                  :class="{
                    'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg': item.to === activePage,
                    'text-slate-700 dark:text-slate-200': item.to !== activePage
                  }"
                  @mouseenter="showTooltip($event, item.label)"
                  @mouseleave="hideTooltip"
                >
                  <Icon v-if="item.icon" :name="item.icon" class="w-5 h-5" />
                </NuxtLink>
              </div>
              
              <!-- Parent Menu Item with Submenu (Collapsed) -->
              <div v-else class="group">
                <button
                  @click="toggleExpanded(item.key)"
                  @mouseenter="showTooltip($event, item.label)"
                  @mouseleave="hideTooltip"
                  class="w-full flex items-center justify-center p-3 rounded-xl hover:bg-slate-100 dark:hover:bg-zinc-700/50 transition-all duration-200"
                  :class="{
                    'bg-slate-100 dark:bg-zinc-700/50': hasActiveSubItem(item) || expandedKeys[item.key]
                  }"
                >
                  <Icon v-if="item.icon" :name="item.icon" class="w-5 h-5 text-slate-600 dark:text-slate-300" />
                </button>
                
                <!-- Submenu Inline -->
                <Transition
                  enter-active-class="transition-all duration-300 ease-out"
                  enter-from-class="transform -translate-y-2 opacity-0"
                  enter-to-class="transform translate-y-0 opacity-100"
                  leave-active-class="transition-all duration-200 ease-in"
                  leave-from-class="transform translate-y-0 opacity-100"
                  leave-to-class="transform -translate-y-2 opacity-0"
                >
                  <div v-if="expandedKeys[item.key]" class="mt-2 space-y-1">
                    <NuxtLink
                      v-for="subItem in item.items"
                      :key="subItem.label"
                      :to="subItem.to"
                      @mouseenter="showTooltip($event, subItem.label)"
                      @mouseleave="hideTooltip"
                      class="flex items-center justify-center p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-zinc-700/50 transition-all duration-200 relative group"
                      :class="{
                        'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg': subItem.to === activePage,
                        'text-slate-600 dark:text-slate-300': subItem.to !== activePage
                      }"
                    >
                      <Icon v-if="subItem.icon" :name="subItem.icon" class="w-4 h-4" />
                    </NuxtLink>
                  </div>
                </Transition>
              </div>
            </template>

            <!-- Expanded Menu Items -->
            <template v-else>
              <!-- Parent Menu Item -->
              <div v-if="item.items" class="group">
                <button 
                  @click="toggleExpanded(item.key)"
                  class="w-full flex items-center justify-between p-1 text-left rounded-xl hover:bg-slate-100 dark:hover:bg-zinc-700/50 transition-all duration-200 group"
                  :class="{
                    'bg-slate-100 dark:bg-zinc-700/50': expandedKeys[item.key]
                  }"
                >
                  <div class="flex items-center">
                    <div class="p-2 rounded-lg bg-slate-200/50 dark:bg-zinc-600/50 mr-3 group-hover:bg-slate-300/50 dark:group-hover:bg-zinc-500/50 transition-colors">
                      <Icon v-if="item.icon" :name="item.icon" class="w-4 h-4 text-slate-600 dark:text-slate-300" />
                    </div>
                    <span class="font-medium text-slate-700 dark:text-slate-200">{{ item.label }}</span>
                  </div>
                  <Icon 
                    :name="expandedKeys[item.key] ? 'lucide:chevron-down' : 'lucide:chevron-right'" 
                    class="w-4 h-4 text-slate-500 dark:text-slate-400 transition-transform duration-200"
                    :class="{ 'rotate-180': expandedKeys[item.key] }"
                  />
                </button>
                
                <!-- Submenu -->
                <Transition
                  enter-active-class="transition-all duration-300 ease-out"
                  enter-from-class="transform -translate-y-2 opacity-0"
                  enter-to-class="transform translate-y-0 opacity-100"
                  leave-active-class="transition-all duration-200 ease-in"
                  leave-from-class="transform translate-y-0 opacity-100"
                  leave-to-class="transform -translate-y-2 opacity-0"
                >
                  <div v-if="expandedKeys[item.key]" class="ml-4 mt-2 space-y-1 border-l-2 border-slate-200 dark:border-zinc-700 pl-4">
                    <NuxtLink
                      v-for="subItem in item.items"
                      :key="subItem.label"
                      :to="subItem.to"
                      class="flex items-center px-3 py-2.5 rounded-lg text-sm hover:bg-slate-100 dark:hover:bg-zinc-700/50 transition-all duration-200 group relative"
                      :class="{
                        'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg': subItem.to === activePage,
                        'text-slate-600 dark:text-slate-300': subItem.to !== activePage
                      }"
                    >
                      <div 
                        class="p-1.5 rounded-md mr-3 transition-colors"
                        :class="{
                          'bg-white/20': subItem.to === activePage,
                          'bg-slate-200/50 dark:bg-zinc-600/50 group-hover:bg-slate-300/50 dark:group-hover:bg-zinc-500/50': subItem.to !== activePage
                        }"
                      >
                        <Icon v-if="subItem.icon" :name="subItem.icon" class="w-3.5 h-3.5" />
                      </div>
                      <span class="font-medium">{{ subItem.label }}</span>
                      <div v-if="subItem.to === activePage" class="absolute right-2 w-2 h-2 bg-white rounded-full shadow-sm"></div>
                    </NuxtLink>
                  </div>
                </Transition>
              </div>

              <!-- Single Menu Item -->
              <NuxtLink
                v-else
                :to="item.to"
                class="flex items-center p-1 rounded-xl hover:bg-slate-100 dark:hover:bg-zinc-700/50 transition-all duration-200 group relative"
                :class="{
                  'bg-gradient-to-r from-blue-500 to-purple-600 text-white shadow-lg': item.to === activePage,
                  'text-slate-700 dark:text-slate-200': item.to !== activePage
                }"
              >
                <div 
                  class="p-2 rounded-lg mr-3 transition-colors"
                  :class="{
                    'bg-white/20': item.to === activePage,
                    'bg-slate-200/50 dark:bg-zinc-600/50 group-hover:bg-slate-300/50 dark:group-hover:bg-zinc-500/50': item.to !== activePage
                  }"
                >
                  <Icon v-if="item.icon" :name="item.icon" class="w-4 h-4" />
                </div>
                <span class="font-medium">{{ item.label }}</span>
                <Badge v-if="item.badge" class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full" :value="item.badge" />
                <div v-if="item.to === activePage" class="absolute right-3 w-2 h-2 bg-white rounded-full shadow-sm"></div>
              </NuxtLink>
            </template>
          </template>
        </nav>
      </div>

      <!-- User Profile Section -->
      <div class="border-t border-slate-200/50 dark:border-zinc-700/50 px-4">
        <NuxtLink 
          v-if="!collapsed"
          to="/profile" 
          class="flex items-center p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-zinc-700/50 transition-all duration-200 group"
        >
          <div class="flex-shrink-0">
            <div v-if="avatarImage" class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 p-0.5">
              <img :src="avatarImage" alt="Avatar" class="w-full h-full rounded-full object-cover bg-white" />
            </div>
            <div v-else class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
              <span class="text-white font-bold text-sm">{{ userName.charAt(0).toUpperCase() }}</span>
            </div>
          </div>
          <div class="ml-3 flex-1 min-w-0">
            <p class="font-semibold text-slate-800 dark:text-white truncate">{{ userName }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ user?.role?.[0] || 'User' }}</p>
          </div>
          <Icon name="lucide:chevron-right" class="w-4 h-4 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors" />
        </NuxtLink>
        
        <!-- Collapsed User Profile -->
        <div v-else class="relative group">
          <NuxtLink 
            to="/profile"
            @mouseenter="showTooltip($event, `${userName} - ${user?.role?.[0] || 'User'}`)"
            @mouseleave="hideTooltip"
            class="flex justify-center my-3 rounded-xl hover:bg-slate-100 dark:hover:bg-zinc-700/50 transition-all duration-200"
          >
            <div v-if="avatarImage" class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 p-0.5">
              <img :src="avatarImage" alt="Avatar" class="w-full h-full rounded-full object-cover bg-white" />
            </div>
            <div v-else class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
              <span class="text-white font-bold text-xs">{{ userName.charAt(0).toUpperCase() }}</span>
            </div>
          </NuxtLink>
        </div>
      </div>
    </div>
  </aside>

  <!-- Global Tooltip -->
  <Teleport to="body" v-if="tooltipVisible && collapsed">
    <div 
      class="fixed pointer-events-none z-[9999] transition-opacity duration-200"
      :style="{
        left: tooltipPosition.x + 'px',
        top: tooltipPosition.y + 'px',
        transform: 'translateY(-50%)'
      }"
    >
      <div class="bg-slate-800 dark:bg-zinc-700 text-white text-sm px-3 py-2 rounded-lg shadow-xl border border-slate-700 dark:border-zinc-600 whitespace-nowrap">
        {{ tooltipText }}
        <div class="absolute left-0 top-1/2 transform -translate-x-1 -translate-y-1/2 w-2 h-2 bg-slate-800 dark:bg-zinc-700 rotate-45 border-l border-b border-slate-700 dark:border-zinc-600"></div>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
const userStore = useUserStore()
const sidebarStore = useSidebarStore()
const logoStore = useLogoStore()
const client = useSanctumClient()

const route = useRoute()
const collapsed = computed(() => sidebarStore.collapsed)

const appName = ref('')
const user = useSanctumUser() as any
const userCapabilities = computed(() => user.value?.capabilities || [])

const hasCapability = (requiredCaps: string[]) =>
  requiredCaps.some(cap => userCapabilities.value.includes(cap))

const userName = computed(() => userStore.user.name)
const avatarImage = computed(() => userStore.user.avatar || '')
const activePage = ref(route.path)
const expandedKeys = ref({} as Record<string | number, boolean>)

// Tooltip state
const tooltipVisible = ref(false)
const tooltipText = ref('')
const tooltipPosition = ref({ x: 0, y: 0 })

const showTooltip = (event: MouseEvent, text: string) => {
  if (!collapsed.value) return
  
  const rect = (event.target as HTMLElement).getBoundingClientRect()
  tooltipText.value = text
  tooltipPosition.value = {
    x: rect.right + 8,
    y: rect.top + rect.height / 2
  }
  tooltipVisible.value = true
}

const hideTooltip = () => {
  tooltipVisible.value = false
}

const toggleExpanded = (key: string | number) => {
  expandedKeys.value[key] = !expandedKeys.value[key]
}

const hasActiveSubItem = (item: any) => {
  if (!item.items) return false
  return item.items.some((subItem: any) => subItem.to === activePage.value)
}

// Struktur Menu
const items = ref([
  {
    key: 0,
    label: 'Dashboard',
    icon: 'lucide:home',
    to: '/',
    capabilities: ['menu:dashboard'],
  },
  {
    key: 1,
    label: 'Konsumen',
    icon: 'lucide:user',
    to: '/konsumen',
    capabilities: ['menu:customers'],
  },
  {
    key: 2,
    label: 'Order',
    icon: 'lucide:file-chart-line',
    to: '/order',
    capabilities: ['menu:orders'],
  },
  {
    key: 3,
    label: 'Jobdesk',
    to: '/jobdesk',
    icon: 'lucide:list-checks',
    capabilities: ['menu:jobdesks'],
  },
  {
    key: 4,
    label: 'Pengaturan',
    icon: 'lucide:settings',
    capabilities: ['menu:settings'],
    items: [
      { 
        label: 'General', 
        to: '/settings',
        icon: 'lucide:settings',
        capabilities: ['menu:settings'],
      },
      { 
        label: 'Produk', 
        to: '/produk',
        icon: 'lucide:package',
        capabilities: ['menu:products'],
      },
      { 
        label: 'Kustomisasi', 
        to: '/data',
        icon: 'lucide:database',
        capabilities: ['menu:settings'],
      },
      { 
        label: 'Karyawan', 
        to: '/karyawan',
        icon: 'lucide:users',
        capabilities: ['menu:settings'],
      },
    ]
  }
])

onMounted(async () => {
  try {
    const response = await client('/api/settings')
    sidebarStore.setAppName(response.app_name)
    appName.value = response.app_name
  } catch (error) {
    console.error('Failed to fetch settings:', error)
  }

  try {
    const faviconResponse = await client('/api/settings/favicon')
    if (faviconResponse.favicon) {
      logoStore.setLogo(faviconResponse.favicon)
    }
  } catch (error) {
    console.error('Error fetching favicon:', error)
  }

  items.value.forEach((item: any) => {
    if (item.items) {
      item.items.forEach((subItem: any) => {
        if (subItem.to === activePage.value) {
          expandedKeys.value[item.key] = true
        }
      })
    }
  })

  if (activePage.value === '/') {
    expandedKeys.value[0] = true
  }
})

// Filter dengan Hak Akses
const filteredItems = computed(() => filterItemsWithAccess(items.value))

function filterItemsWithAccess(items: any[]): any[] {
  return items
    .map(item => {
      const hasAccess = !item.capabilities || hasCapability(item.capabilities)

      if (item.items) {
        const filteredChildren = filterItemsWithAccess(item.items)

        if (hasAccess || filteredChildren.length > 0) {
          return {
            ...item,
            items: filteredChildren
          }
        }

        return null
      }

      return hasAccess ? item : null
    })
    .filter(Boolean)
}

watch(route, (newRoute) => {
  activePage.value = newRoute.path
})

watch(() => sidebarStore.app_name, (newName) => {
  appName.value = newName
})
</script>

<style scoped>
/* Tooltip styling improvements */
.group:hover [class*="opacity-0"] {
  transition-delay: 0.3s;
}

/* Ensure tooltips are properly positioned above other elements */
.z-\[9999\] {
  z-index: 9999 !important;
}

/* Custom scrollbar for navigation */
.scrollbar-thin {
  scrollbar-width: thin;
}

.scrollbar-thumb-slate-300 {
  scrollbar-color: rgb(203 213 225) transparent;
}

.dark .scrollbar-thumb-zinc-600 {
  scrollbar-color: rgb(82 82 91) transparent;
}

/* Smooth transitions for collapsed state */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Prevent tooltip content from being clipped */
.overflow-x-visible {
  overflow-x: visible !important;
}

/* Enhanced tooltip arrow styling */
.rotate-45 {
  transform: rotate(45deg);
}
</style>
