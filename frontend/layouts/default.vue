<template>
  <div class="default">
    <div class="flex flex-col min-h-screen justify-between" :style="{ 'background-color': backgroundColor }">
      <hr class="border-zinc-200 dark:border-zinc-800" />
      <div class="grow dark:bg-zinc-950 flex flex-col justify-center bg-login relative" :style="{ 'background-image': `url(${backgroundImage})` }">
        <!-- Overlay -->
        <div class="absolute inset-0" :style="{ 'background-color': getTransparentColor(backgroundColor) }"></div>

        <div class="flex flex-col justify-center relative">
          <slot />
        </div>
        
        <!-- Version Info -->
        <div class="absolute bottom-4 right-4 z-10">
          <VersionInfo />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const client = useSanctumClient()
const backgroundColor = ref('')
const backgroundImage = ref(null)
const logoStore = computed(() => useLogoStore()) as any

useHead({
  link: [
    { rel: 'icon', type: 'image/png', href: logoStore.logo || '/favicon.ico' }
  ]
})

onMounted(async () => {
  try {
    const response = await client('/settings/background')
    const { color, image } = response
    backgroundColor.value = color || ''
    if (image) {
      backgroundImage.value = image // URL gambar dari server
    }
  } catch (error) {
    console.error('Error fetching background settings:', error)
  }
})

// Fungsi untuk mengubah warna HEX ke RGBA dengan opacity
const getTransparentColor = (hex: string, opacity = 0.5) => {
  if (!hex) return `rgba(0, 0, 0, ${opacity})` // Default hitam transparan
  const match = hex.match(/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i)
  if (!match) return hex // Jika bukan HEX valid, kembalikan warna asli

  const r = parseInt(match[1], 16)
  const g = parseInt(match[2], 16)
  const b = parseInt(match[3], 16)
  return `rgba(${r}, ${g}, ${b}, ${opacity})`
}
</script>

<style scoped>
.bg-login {
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}
</style>
