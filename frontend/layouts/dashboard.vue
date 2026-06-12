<template>
  <div class="dashboard h-screen">
    <main class="bg-slate-100 dark:bg-zinc-950 h-full">
      <div class="flex h-full">
        <SidebarMenu />
        <!-- Content area - full width on mobile, with sidebar space on desktop -->
        <div class="flex-1 min-w-0 overflow-hidden transition-all duration-500 ease-in-out">
          <Header />
          <div class="h-[calc(100vh-6rem)] overflow-y-auto px-4 pb-4">
            <div class="bg-white/50 dark:bg-zinc-900/50 backdrop-blur-sm rounded-2xl border border-white/20 dark:border-zinc-800/50 shadow-xl min-h-full">
              <div class="p-4 relative">
                <slot />
                
                <!-- Version Info positioned at bottom right of content area -->
                <div class="mt-2">
                  <VersionInfo />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
const logoStore = useLogoStore()
const sidebarStore = useSidebarStore()

const getMimeType = (url: string): string => {
  if (url.endsWith('.png')) return 'image/png'
  if (url.endsWith('.jpg') || url.endsWith('.jpeg')) return 'image/jpeg'
  if (url.endsWith('.ico')) return 'image/x-icon'
  return 'image/png'
}

watch(
  () => logoStore.logo,
  (newLogo) => {
    if (newLogo) {
      useHead({
        link: [
          {
            rel: 'icon',
            type: getMimeType(newLogo),
            href: newLogo
          }
        ]
      })
    }
  },
  { immediate: true }
)
</script>



<style scoped>
/* Optional: Additional custom styles can go here */
</style>