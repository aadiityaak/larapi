<template>
  <SettingsLayout>
    <div class="space-y-6">
      <!-- Modern Header Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
        <div class="flex items-center gap-4">
          <div class="p-3 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl shadow-lg">
            <Icon name="lucide:file-image" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Pengaturan Favicon</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola ikon aplikasi dan favicon website</p>
          </div>
        </div>

        <!-- Breadcrumb Navigation -->
        <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 mt-4 pt-4 border-t border-slate-200/50 dark:border-zinc-700/50">
          <NuxtLink to="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1">
            <Icon name="lucide:home" class="w-4 h-4" />
            <span class="hidden sm:inline">Dashboard</span>
          </NuxtLink>
          <Icon name="lucide:chevron-right" class="w-3 h-3" />
          <NuxtLink to="/settings" class="hover:text-slate-900 dark:hover:text-white transition-colors">Settings</NuxtLink>
          <Icon name="lucide:chevron-right" class="w-3 h-3" />
          <span class="text-slate-900 dark:text-white font-medium">Favicon</span>
        </div>
      </div>

      <!-- Modern Form Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 overflow-hidden">
        <Form @submit="handleSubmitFavicon" ref="form" class="p-6">
          <div class="space-y-6">
            <!-- Upload Section -->
            <div class="backdrop-blur-xl bg-gradient-to-br from-orange-50/80 to-red-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-orange-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg shadow-lg">
                  <Icon name="lucide:upload" class="w-5 h-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Upload Favicon</h3>
              </div>

              <div class="space-y-4">
                <div>
                  <label for="favicon" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Pilih File Favicon</label>
                  <div class="border-2 border-dashed border-orange-300 dark:border-orange-600/50 rounded-xl p-6 bg-orange-50/50 dark:bg-orange-900/10 hover:bg-orange-100/50 dark:hover:bg-orange-900/20 transition-colors">
                    <FileUpload 
                      ref="fileupload" 
                      mode="basic" 
                      id="favicon" 
                      name="favicon" 
                      accept="image/*" 
                      :maxFileSize="500000" 
                      @select="handleFileUpload"
                      class="w-full"
                      :pt="{
                        root: { class: 'w-full' },
                        input: { class: 'w-full px-4 py-3 rounded-xl border-orange-300 dark:border-orange-600 focus:border-orange-500 dark:focus:border-orange-400 bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm text-sm' },
                        chooseButton: { class: 'px-6 py-3 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm font-medium' }
                      }"
                    />
                  </div>
                </div>

                <div class="p-4 bg-orange-100/50 dark:bg-orange-900/20 rounded-lg">
                  <div class="flex items-start gap-3">
                    <Icon name="lucide:info" class="w-5 h-5 text-orange-600 dark:text-orange-400 flex-shrink-0 mt-0.5" />
                    <div class="text-sm text-orange-700 dark:text-orange-300">
                      <p class="font-medium mb-1">Persyaratan File:</p>
                      <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Format: PNG, JPG, ICO, atau SVG</li>
                        <li>Ukuran maksimal: 500KB</li>
                        <li>Dimensi yang disarankan: 32x32px atau 64x64px</li>
                        <li>Background transparan lebih disukai</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Preview Section -->
            <div v-if="previewFavicon" class="backdrop-blur-xl bg-gradient-to-br from-blue-50/80 to-indigo-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-blue-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-lg">
                  <Icon name="lucide:eye" class="w-5 h-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Preview Favicon</h3>
              </div>

              <div class="flex items-center gap-6">
                <div class="bg-white dark:bg-zinc-800 p-4 rounded-xl shadow-lg border border-slate-200 dark:border-zinc-700">
                  <img :src="previewFavicon" alt="Favicon Preview" class="w-16 h-16 object-contain" />
                </div>
                <div class="text-sm text-slate-600 dark:text-slate-400">
                  <p class="font-medium text-slate-800 dark:text-white mb-1">Preview dalam berbagai ukuran:</p>
                  <div class="flex items-center gap-3">
                    <img :src="previewFavicon" alt="16x16" class="w-4 h-4 object-contain bg-white dark:bg-zinc-800 rounded border" />
                    <img :src="previewFavicon" alt="24x24" class="w-6 h-6 object-contain bg-white dark:bg-zinc-800 rounded border" />
                    <img :src="previewFavicon" alt="32x32" class="w-8 h-8 object-contain bg-white dark:bg-zinc-800 rounded border" />
                    <img :src="previewFavicon" alt="48x48" class="w-12 h-12 object-contain bg-white dark:bg-zinc-800 rounded border" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-slate-200/50 dark:border-zinc-700/50">
            <Button 
              type="submit" 
              class="px-6 py-3 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 font-medium"
            >
              <Icon name="lucide:save" class="w-4 h-4" />
              Simpan Favicon
            </Button>
          </div>
        </Form>
      </div>
    </div>
    
    <Toast />
  </SettingsLayout>
</template>

<script setup lang="ts">
definePageMeta({ title: 'Setting Favicon' })
const router = useRouter()
const toast = useToast()
const client = useSanctumClient()
const logoStore = useLogoStore()

const favicon = ref<File | null>(null)
const previewFavicon = ref<string | null>(null)

// Fetch existing favicon settings
const fetchFaviconSettings = async () => {
  try {
    const response = await client('/settings/favicon')

    const { favicon: image } = response
    if (image) {
      previewFavicon.value = image
      logoStore.setLogo(image)
    }
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal memuat favicon', life: 3000 })
  }
}

// Handle file upload
const handleFileUpload = (event: any) => {
  nextTick(() => {
    const file = event.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = () => {
      favicon.value = file;
      previewFavicon.value = URL.createObjectURL(file);
    };
    reader.readAsDataURL(file);
  });
};

// Handle form submission
const handleSubmitFavicon = async () => {
  try {
    if (!favicon.value) {
      toast.add({ severity: 'warn', summary: 'Peringatan', detail: 'Silakan pilih file favicon!', life: 3000 })
      return
    }

    const formData = new FormData()
    formData.append('favicon', favicon.value)

    await client('/settings/favicon', {
      method: 'POST',
      body: formData,
    })

    toast.add({ severity: 'success', summary: 'Success', detail: 'Favicon berhasil diperbarui!', life: 3000 })
    await fetchFaviconSettings()
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal menyimpan favicon', life: 3000 })
  }
}

onMounted(fetchFaviconSettings)
</script>
