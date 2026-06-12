<template>
  <SettingsLayout>
    <div class="space-y-6">
      <!-- Modern Header Section with Glass Morphism -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
        <div class="flex items-center gap-4">
          <div class="p-3 bg-gradient-to-br from-slate-500 to-gray-600 rounded-xl shadow-lg">
            <Icon name="lucide:settings" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Pengaturan Sistem</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola konfigurasi aplikasi dan pengaturan umum</p>
          </div>
        </div>

        <!-- Breadcrumb Navigation -->
        <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 mt-4 pt-4 border-t border-slate-200/50 dark:border-zinc-700/50">
          <NuxtLink to="/" class="hover:text-slate-600 dark:hover:text-slate-400 transition-colors flex items-center gap-1">
            <Icon name="lucide:home" class="w-4 h-4" />
            <span class="hidden sm:inline">Dashboard</span>
          </NuxtLink>
          <Icon name="lucide:chevron-right" class="w-3 h-3" />
          <span class="text-slate-900 dark:text-white font-medium">Settings</span>
        </div>
      </div>

      <!-- Modern Form Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 overflow-hidden">
        <Form @submit="handleSubmit" ref="form" class="p-6" enctype="multipart/form-data">
          <Tabs value="0" class="w-full">
            <TabList class="mb-6 p-1 bg-slate-100/80 dark:bg-zinc-800/80 rounded-xl inline-flex">
              <Tab 
                value="0" 
                class="px-6 py-3 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2"
                :class="{ 
                  'bg-white dark:bg-zinc-700 text-slate-900 dark:text-white shadow-sm': true,
                  'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white': false 
                }"
              >
                <Icon name="lucide:settings-2" class="w-4 h-4" />
                Umum
              </Tab>
              <Tab 
                value="1" 
                class="px-6 py-3 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white"
              >
                <Icon name="lucide:file-text" class="w-4 h-4" />
                Data
              </Tab>
              <Tab 
                value="2" 
                class="px-6 py-3 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white"
              >
                <Icon name="lucide:server-cog" class="w-4 h-4" />
                Backend
              </Tab>
            </TabList>
            
            <TabPanels>
              <TabPanel value="0">
                <!-- General Settings Section -->
                <div class="space-y-6">
                  <div class="backdrop-blur-xl bg-gradient-to-br from-blue-50/80 to-indigo-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-blue-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                      <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-lg">
                        <Icon name="lucide:app-window" class="w-5 h-5 text-white" />
                      </div>
                      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Aplikasi</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div class="modern-input">
                        <label for="app_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama App</label>
                        <InputText 
                          id="app_name" 
                          name="app_name" 
                          v-model="state.app_name" 
                          class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 dark:focus:border-blue-400 px-4 py-3 text-sm bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm" 
                          placeholder="Masukkan nama aplikasi"
                        />
                      </div>

                      <div class="modern-input">
                        <label for="app_code" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Kode App</label>
                        <InputText 
                          id="app_code" 
                          name="app_code" 
                          v-model="state.app_code" 
                          class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 dark:focus:border-blue-400 px-4 py-3 text-sm bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm" 
                          placeholder="Masukkan kode aplikasi"
                        />
                      </div>

                      <div class="modern-input md:col-span-2">
                        <label for="app_description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Deskripsi App</label>
                        <InputText 
                          id="app_description" 
                          name="app_description" 
                          v-model="state.app_description" 
                          class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 dark:focus:border-blue-400 px-4 py-3 text-sm bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm" 
                          placeholder="Masukkan deskripsi aplikasi"
                        />
                      </div>
                    </div>
                  </div>

                  <div class="backdrop-blur-xl bg-gradient-to-br from-green-50/80 to-emerald-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-green-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                      <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg shadow-lg">
                        <Icon name="lucide:building" class="w-5 h-5 text-white" />
                      </div>
                      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Perusahaan</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                      <div class="modern-input">
                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                        <InputText 
                          id="email" 
                          name="email" 
                          type="email"
                          v-model="state.email" 
                          class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 dark:focus:border-green-400 px-4 py-3 text-sm bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm" 
                          placeholder="contoh@perusahaan.com"
                        />
                      </div>

                      <div class="modern-input">
                        <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">No Telepon</label>
                        <InputText 
                          id="phone" 
                          name="phone" 
                          type="tel"
                          v-model="state.phone" 
                          class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 dark:focus:border-green-400 px-4 py-3 text-sm bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm" 
                          placeholder="08xx xxxx xxxx"
                        />
                      </div>

                      <div class="modern-input">
                        <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Alamat Kantor</label>
                        <InputText 
                          id="address" 
                          name="address" 
                          v-model="state.address" 
                          class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 dark:focus:border-green-400 px-4 py-3 text-sm bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm" 
                          placeholder="Masukkan alamat kantor"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </TabPanel>
              
              <TabPanel value="1">
                <!-- Data Settings Section -->
                <div class="space-y-6">
                  <div class="backdrop-blur-xl bg-gradient-to-br from-purple-50/80 to-pink-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-purple-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                      <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-lg">
                        <Icon name="lucide:file-plus" class="w-5 h-5 text-white" />
                      </div>
                      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">File Data</h3>
                    </div>

                    <div class="modern-input">
                      <label for="pdf_sample" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">PDF Sample</label>
                      <InputText 
                        type="file" 
                        id="pdf_sample" 
                        @change="handleFileChange('pdf_sample', $event)" 
                        class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-purple-500 dark:focus:border-purple-400 px-4 py-3 text-sm bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 dark:file:bg-purple-900/50 dark:file:text-purple-300" 
                        accept=".pdf"
                      />
                    </div>

                    <div class="mt-4 p-4 bg-purple-100/50 dark:bg-purple-900/20 rounded-lg">
                      <p class="text-sm text-purple-700 dark:text-purple-300 flex items-center gap-2">
                        <Icon name="lucide:info" class="w-4 h-4" />
                        Upload file PDF sebagai template atau sample dokumen
                      </p>
                    </div>
                  </div>
                </div>
              </TabPanel>

              <TabPanel value="2">
                <!-- Backend Maintenance Section -->
                <div class="space-y-6">
                  <div class="backdrop-blur-xl bg-gradient-to-br from-amber-50/80 to-orange-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-amber-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center gap-3 mb-6">
                      <div class="p-2 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-lg">
                        <Icon name="lucide:server-cog" class="w-5 h-5 text-white" />
                      </div>
                      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Maintenance Backend</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                      <Button :disabled="isRunning('migrate')" @click="run('migrate')" class="justify-start gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-green-600 text-white">
                        <Icon v-if="!isRunning('migrate')" name="lucide:database" class="w-4 h-4" />
                        <Icon v-else name="lucide:loader-2" class="w-4 h-4 animate-spin" />
                        Jalankan Migrate
                      </Button>

                      <Button :disabled="isRunning('optimize-clear')" @click="run('optimize-clear')" class="justify-start gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 text-white">
                        <Icon v-if="!isRunning('optimize-clear')" name="lucide:broom" class="w-4 h-4" />
                        <Icon v-else name="lucide:loader-2" class="w-4 h-4 animate-spin" />
                        Optimize Clear
                      </Button>

                      <Button :disabled="isRunning('cache-clear')" @click="run('cache-clear')" class="justify-start gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-violet-600 text-white">
                        <Icon v-if="!isRunning('cache-clear')" name="lucide:trash-2" class="w-4 h-4" />
                        <Icon v-else name="lucide:loader-2" class="w-4 h-4 animate-spin" />
                        Cache Clear
                      </Button>

                      <Button :disabled="isRunning('config-clear')" @click="run('config-clear')" class="justify-start gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white">
                        <Icon v-if="!isRunning('config-clear')" name="lucide:sliders-horizontal" class="w-4 h-4" />
                        <Icon v-else name="lucide:loader-2" class="w-4 h-4 animate-spin" />
                        Config Clear
                      </Button>

                      <Button :disabled="isRunning('route-clear')" @click="run('route-clear')" class="justify-start gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-teal-600 text-white">
                        <Icon v-if="!isRunning('route-clear')" name="lucide:routes" class="w-4 h-4" />
                        <Icon v-else name="lucide:loader-2" class="w-4 h-4 animate-spin" />
                        Route Clear
                      </Button>

                      <Button :disabled="isRunning('view-clear')" @click="run('view-clear')" class="justify-start gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-purple-500 to-fuchsia-600 text-white">
                        <Icon v-if="!isRunning('view-clear')" name="lucide:layout-panel-top" class="w-4 h-4" />
                        <Icon v-else name="lucide:loader-2" class="w-4 h-4 animate-spin" />
                        View Clear
                      </Button>

                      <Button :disabled="isRunning('queue-restart')" @click="run('queue-restart')" class="justify-start gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-amber-600 to-yellow-600 text-white">
                        <Icon v-if="!isRunning('queue-restart')" name="lucide:repeat" class="w-4 h-4" />
                        <Icon v-else name="lucide:loader-2" class="w-4 h-4 animate-spin" />
                        Queue Restart
                      </Button>

                      <Button :disabled="isRunning('storage-link')" @click="run('storage-link')" class="justify-start gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-lime-600 to-green-700 text-white">
                        <Icon v-if="!isRunning('storage-link')" name="lucide:link" class="w-4 h-4" />
                        <Icon v-else name="lucide:loader-2" class="w-4 h-4 animate-spin" />
                        Storage Link
                      </Button>
                    </div>

                    <div v-if="lastOutput" class="mt-4 p-3 rounded-lg bg-slate-100/60 dark:bg-zinc-800/60 text-xs text-slate-700 dark:text-slate-300 whitespace-pre-wrap">
                      {{ lastOutput }}
                    </div>
                  </div>
                </div>
              </TabPanel>
            </TabPanels>
          </Tabs>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-slate-200/50 dark:border-zinc-700/50">
            <Button 
              v-if="!isLoading" 
              type="submit" 
              class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
              size="small"
            >
              <Icon name="lucide:save" class="w-4 h-4" />
              Simpan Pengaturan
            </Button>
            
            <Button 
              v-else 
              disabled
              class="px-6 py-2 bg-gradient-to-r from-slate-400 to-slate-500 text-white rounded-xl shadow-lg flex items-center gap-2"
              size="small" 
            >
              <Icon name="lucide:loader-2" class="w-4 h-4 animate-spin" />
              Menyimpan...
            </Button>
          </div>
        </Form>
      </div>
    </div>
    
    <Toast />
  </SettingsLayout>
</template>

<script setup lang="ts">
definePageMeta({ title: 'Settings' })
const router = useRouter()
const toast = useToast()
const client = useSanctumClient()
const isLoading = ref(false)
const { setAppName } = useSidebarStore()
const running = ref<string | null>(null)
const lastOutput = ref('')
const state = ref({
  app_name: '',
  app_description: '',
  address: '',
  pdf_sample: null,
  email: '',
    phone: '',
}) as any
onMounted(async () => {
  try {
    const response = await client('/api/settings');
    state.value = response
    if (response.pekerjaan) {
      state.value.pekerjaan = JSON.stringify(JSON.parse(response.pekerjaan), null, 2);
    }
  } catch (error: any) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to fetch settings: ' + error.message, life: 3000 });
  }
})

const handleFileChange = (field: string, event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length) {
    state.value[field] = target.files[0]; // Store the file in the state
  }
}

const handleSubmit = async () => {
  isLoading.value = true;
  const formData = new FormData();
  Object.entries(state.value).forEach(([key, value]) => {
    if (key === 'pekerjaan' && typeof value === 'string') {
      formData.append(key, JSON.stringify(JSON.parse(value), null, 2));
    } else if (value !== null && value !== undefined) {
      formData.append(key, value as string | Blob);
    }
  });

  setAppName(state.value.app_name);

  try {
    await client('/api/settings', {
      method: 'POST',
      body: formData,
    });
    toast.add({ severity: 'success', summary: 'Success', detail: 'Settings updated successfully!', life: 3000 });
    router.push('/settings');
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update settings.', life: 3000 });
  }
  isLoading.value = false;
}

const isRunning = (action: string) => running.value === action
const run = async (action: string) => {
  running.value = action
  lastOutput.value = ''
  try {
    const res = await client(`/api/maintenance/${action}`, { method: 'POST' })
    if (res?.ok) {
      lastOutput.value = res.output || ''
      toast.add({ severity: 'success', summary: 'Berhasil', detail: `Aksi ${action} berhasil.`, life: 3000 })
    } else {
      toast.add({ severity: 'warn', summary: 'Gagal', detail: res?.message || `Aksi ${action} gagal.`, life: 4000 })
    }
  } catch (e: any) {
    toast.add({ severity: 'error', summary: 'Error', detail: e?.message || `Aksi ${action} error.`, life: 4000 })
  } finally {
    running.value = null
  }
}
</script>

<style scoped>
.modern-input {
  position: relative;
}

.modern-input input {
  transition: all 0.2s ease;
  border: 1px solid rgb(203 213 225);
}

.modern-input input:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  border-color: rgb(59 130 246);
}

.dark .modern-input input {
  border-color: rgb(82 82 91);
  color: white;
}

.dark .modern-input input:focus {
  border-color: rgb(96 165 250);
  box-shadow: 0 0 0 3px rgba(96 165 250, 0.1);
}

.modern-input input::placeholder {
  color: rgb(148 163 184);
}

.dark .modern-input input::placeholder {
  color: rgb(100 116 139);
}

/* File input specific styles */
.modern-input input[type="file"] {
  cursor: pointer;
}

.modern-input input[type="file"]::-webkit-file-upload-button {
  cursor: pointer;
}

.breadcrumb-separator {
  margin: 0 0.5rem;
  color: rgb(148 163 184);
}

.dark .breadcrumb-separator {
  color: rgb(100 116 139);
}

.tab-button {
  position: relative;
  overflow: hidden;
}

.tab-button::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(99, 102, 241, 0.1));
  opacity: 0;
  transition: all 0.3s ease;
}

.tab-button:hover::before {
  opacity: 1;
}

.section-card {
  position: relative;
  overflow: hidden;
}

.section-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
  opacity: 0;
  transition: all 0.3s ease;
}

.section-card:hover::before {
  opacity: 1;
}

/* Additional modern styling */
.modern-input label {
  font-weight: 500;
  margin-bottom: 0.5rem;
}

.modern-input input {
  font-size: 0.875rem;
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(4px);
}

.dark .modern-input input {
  background: rgba(39, 39, 42, 0.8);
}
</style>
