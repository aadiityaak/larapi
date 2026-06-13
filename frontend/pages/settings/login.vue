<template>
  <SettingsLayout>
    <div class="space-y-6">
      <!-- Modern Header Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
        <div class="flex items-center gap-4">
          <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
            <Icon name="lucide:door-open" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Pengaturan Login</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola tampilan dan gaya halaman login</p>
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
          <span class="text-slate-900 dark:text-white font-medium">Login</span>
        </div>
      </div>

      <!-- Modern Form Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 overflow-hidden">
        <Form @submit="handleSubmitBackground" ref="form" class="p-6">
          <div class="space-y-6">
            <!-- Login Style Section -->
            <div class="backdrop-blur-xl bg-gradient-to-br from-indigo-50/80 to-purple-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-indigo-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-lg">
                  <Icon name="lucide:layout" class="w-5 h-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Gaya Layout</h3>
              </div>

              <div class="space-y-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Pilih Style Login</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <label class="relative cursor-pointer">
                    <input type="radio" v-model="loginStyle" value="center" class="sr-only" />
                    <div class="p-4 rounded-xl border-2 transition-all duration-200 bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm"
                         :class="loginStyle === 'center' 
                           ? 'border-indigo-500 dark:border-indigo-400 bg-indigo-50/80 dark:bg-indigo-900/20' 
                           : 'border-slate-300 dark:border-zinc-600 hover:border-indigo-300 dark:hover:border-indigo-600'">
                      <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-900/50">
                          <Icon name="lucide:align-center" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                          <div class="font-medium text-slate-800 dark:text-white">Login Tengah</div>
                          <div class="text-xs text-slate-500 dark:text-slate-400">Form login di tengah halaman</div>
                        </div>
                      </div>
                    </div>
                  </label>

                  <label class="relative cursor-pointer">
                    <input type="radio" v-model="loginStyle" value="side" class="sr-only" />
                    <div class="p-4 rounded-xl border-2 transition-all duration-200 bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm"
                         :class="loginStyle === 'side' 
                           ? 'border-indigo-500 dark:border-indigo-400 bg-indigo-50/80 dark:bg-indigo-900/20' 
                           : 'border-slate-300 dark:border-zinc-600 hover:border-indigo-300 dark:hover:border-indigo-600'">
                      <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-indigo-100 dark:bg-indigo-900/50">
                          <Icon name="lucide:panel-left" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                          <div class="font-medium text-slate-800 dark:text-white">Login Samping</div>
                          <div class="text-xs text-slate-500 dark:text-slate-400">Form login di sisi kiri/kanan</div>
                        </div>
                      </div>
                    </div>
                  </label>
                </div>
              </div>
            </div>

            <!-- Background Color Section -->
            <div class="backdrop-blur-xl bg-gradient-to-br from-emerald-50/80 to-teal-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-emerald-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg shadow-lg">
                  <Icon name="lucide:palette" class="w-5 h-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Warna Background</h3>
              </div>

              <div class="space-y-4">
                <label for="bg_color" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Pilih Warna Latar Belakang</label>
                <div class="p-4 bg-white/80 dark:bg-zinc-800/80 rounded-xl border border-emerald-300 dark:border-emerald-600/50 backdrop-blur-sm">
                  <ColorPicker 
                    v-model="backgroundColor" 
                    format="hex" 
                    class="w-full"
                    :pt="{
                      root: { class: 'w-full' },
                      input: { class: 'w-full px-4 py-3 rounded-xl border-emerald-300 dark:border-emerald-600 focus:border-emerald-500 dark:focus:border-emerald-400 bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm text-sm' }
                    }"
                  />
                </div>
              </div>
            </div>

            <!-- Background Image Section -->
            <div class="backdrop-blur-xl bg-gradient-to-br from-rose-50/80 to-pink-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-rose-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-gradient-to-br from-rose-500 to-pink-600 rounded-lg shadow-lg">
                  <Icon name="lucide:image" class="w-5 h-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Gambar Background</h3>
              </div>

              <div class="space-y-4">
                <label for="bg_image" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Upload Gambar Latar Belakang</label>
                <div class="border-2 border-dashed border-rose-300 dark:border-rose-600/50 rounded-xl p-6 bg-rose-50/50 dark:bg-rose-900/10 hover:bg-rose-100/50 dark:hover:bg-rose-900/20 transition-colors">
                  <FileUpload 
                    ref="fileupload" 
                    mode="basic" 
                    id="bg_image" 
                    name="bg_image" 
                    accept="image/*" 
                    :maxFileSize="1000000" 
                    @select="handleFileUpload"
                    class="w-full"
                    :pt="{
                      root: { class: 'w-full' },
                      input: { class: 'w-full px-4 py-3 rounded-xl border-rose-300 dark:border-rose-600 focus:border-rose-500 dark:focus:border-rose-400 bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm text-sm' },
                      chooseButton: { class: 'px-6 py-3 bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm font-medium' }
                    }"
                  />
                </div>

                <div class="p-4 bg-rose-100/50 dark:bg-rose-900/20 rounded-lg">
                  <div class="flex items-start gap-3">
                    <Icon name="lucide:info" class="w-5 h-5 text-rose-600 dark:text-rose-400 flex-shrink-0 mt-0.5" />
                    <div class="text-sm text-rose-700 dark:text-rose-300">
                      <p class="font-medium mb-1">Persyaratan Gambar:</p>
                      <ul class="list-disc list-inside space-y-1 text-xs">
                        <li>Format: JPG, PNG, atau WebP</li>
                        <li>Ukuran maksimal: 1MB</li>
                        <li>Resolusi yang disarankan: 1920x1080px atau lebih</li>
                        <li>Gambar akan digunakan sebagai background login</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Preview Section -->
            <div v-if="previewImage" class="backdrop-blur-xl bg-gradient-to-br from-blue-50/80 to-cyan-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-blue-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg shadow-lg">
                  <Icon name="lucide:eye" class="w-5 h-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Preview Background</h3>
              </div>

              <div class="bg-white dark:bg-zinc-800 p-4 rounded-xl shadow-lg border border-slate-200 dark:border-zinc-700">
                <img :src="previewImage" alt="Background Preview" class="w-full max-w-md mx-auto rounded-lg shadow-md object-cover" />
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-slate-200/50 dark:border-zinc-700/50">
            <Button 
              type="submit" 
              class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 font-medium"
            >
              <Icon name="lucide:save" class="w-4 h-4" />
              Simpan Pengaturan
            </Button>
          </div>
        </Form>
      </div>
    </div>
    
    <Toast />
  </SettingsLayout>
</template>

<script setup lang="ts">
definePageMeta({ title: 'Setting Background' })
const router = useRouter()
const toast = useToast()
const client = useSanctumClient()

// State untuk menyimpan data latar belakang dan style login
const backgroundColor = ref('')
const backgroundImage = ref<File | null>(null)
const previewImage = ref<string | null>(null)
const loginStyle = ref('center') // Default ke 'Tengah'

// Fetch existing background settings
const fetchBackgroundSettings = async () => {
  try {
    const response = await client('/settings/background')
    const { color, image, style } = response
    backgroundColor.value = color || ''
    loginStyle.value = style || 'center'
    if (image) {
      previewImage.value = image // URL gambar dari server
    }
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal memuat pengaturan latar belakang', life: 3000 })
  }
}

// Handle file upload
const handleFileUpload = (event: any) => {
  nextTick(() => {
    const file = event.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = () => {
      backgroundImage.value = file;
      previewImage.value = URL.createObjectURL(file);
    };
    reader.readAsDataURL(file);
  });
};

// Handle form submission
const handleSubmitBackground = async () => {
  try {
    const formData = new FormData()
    formData.append('color', backgroundColor.value)
    formData.append('style', loginStyle.value)
    if (backgroundImage.value) {
      formData.append('image', backgroundImage.value)
    }

    await client('/settings/background', {
      method: 'POST',
      body: formData,
    })

    toast.add({ severity: 'success', summary: 'Success', detail: 'Pengaturan latar belakang berhasil disimpan!', life: 3000 })
    await fetchBackgroundSettings()
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal menyimpan pengaturan latar belakang', life: 3000 })
  }
}

onMounted(fetchBackgroundSettings)
</script>
