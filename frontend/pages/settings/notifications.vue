<template>
  <SettingsLayout>
    <div class="space-y-6">
      <!-- Modern Header Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
        <div class="flex items-center gap-4">
          <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg">
            <Icon name="lucide:bell" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Pengaturan Notifikasi</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola template pesan notifikasi sistem</p>
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
          <span class="text-slate-900 dark:text-white font-medium">Notifications</span>
        </div>
      </div>

      <!-- Modern Form Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 overflow-hidden">
        <Form @submit="handleSubmit" ref="form" class="p-6" enctype="multipart/form-data">
          <div class="space-y-6">
            <!-- New Order Notification -->
            <div class="backdrop-blur-xl bg-gradient-to-br from-blue-50/80 to-indigo-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-blue-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-lg">
                  <Icon name="lucide:shopping-cart" class="w-5 h-5 text-white" />
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Order Baru</h3>
                  <p class="text-sm text-slate-500 dark:text-slate-400">Template notifikasi untuk order baru</p>
                </div>
              </div>

              <div class="space-y-2">
                <label for="new_order" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Template Pesan</label>
                <div class="bg-white/80 dark:bg-zinc-800/80 rounded-xl border border-blue-300 dark:border-blue-600/50 backdrop-blur-sm overflow-hidden">
                  <Editor 
                    name="new_order" 
                    v-model="state.new_order" 
                    editorStyle="height: 250px; background: transparent;"
                    :pt="{
                      root: { class: 'w-full' },
                      content: { class: 'bg-transparent border-0' }
                    }"
                  />
                </div>
              </div>
            </div>

            <!-- Project Assignment Notification -->
            <div class="backdrop-blur-xl bg-gradient-to-br from-green-50/80 to-emerald-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-green-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg shadow-lg">
                  <Icon name="lucide:user-plus" class="w-5 h-5 text-white" />
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Order Baru Staff</h3>
                  <p class="text-sm text-slate-500 dark:text-slate-400">Template notifikasi untuk penugasan staff</p>
                </div>
              </div>

              <div class="space-y-2">
                <label for="project_assignment" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Template Pesan</label>
                <div class="bg-white/80 dark:bg-zinc-800/80 rounded-xl border border-green-300 dark:border-green-600/50 backdrop-blur-sm overflow-hidden">
                  <Editor 
                    name="project_assignment" 
                    v-model="state.project_assignment" 
                    editorStyle="height: 250px; background: transparent;"
                    :pt="{
                      root: { class: 'w-full' },
                      content: { class: 'bg-transparent border-0' }
                    }"
                  />
                </div>
              </div>
            </div>

            <!-- Follow-up Project Notification -->
            <div class="backdrop-blur-xl bg-gradient-to-br from-amber-50/80 to-yellow-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-amber-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-lg shadow-lg">
                  <Icon name="lucide:clock" class="w-5 h-5 text-white" />
                </div>
                <div>
                  <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Follow-Up Order</h3>
                  <p class="text-sm text-slate-500 dark:text-slate-400">Template notifikasi untuk follow-up order</p>
                </div>
              </div>

              <div class="space-y-2">
                <label for="followup_project" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Template Pesan</label>
                <div class="bg-white/80 dark:bg-zinc-800/80 rounded-xl border border-amber-300 dark:border-amber-600/50 backdrop-blur-sm overflow-hidden">
                  <Editor 
                    name="followup_project" 
                    v-model="state.followup_project" 
                    editorStyle="height: 250px; background: transparent;"
                    :pt="{
                      root: { class: 'w-full' },
                      content: { class: 'bg-transparent border-0' }
                    }"
                  />
                </div>
              </div>
            </div>

            <!-- Help Information -->
            <div class="backdrop-blur-xl bg-gradient-to-br from-slate-50/80 to-gray-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-slate-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-start gap-3">
                <Icon name="lucide:info" class="w-5 h-5 text-slate-600 dark:text-slate-400 flex-shrink-0 mt-0.5" />
                <div class="text-sm text-slate-700 dark:text-slate-300">
                  <p class="font-medium mb-2">Petunjuk Template Notifikasi:</p>
                  <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Gunakan HTML untuk formatting pesan yang lebih baik</li>
                    <li>Template akan digunakan untuk mengirim notifikasi via email atau WhatsApp</li>
                    <li>Pastikan pesan jelas dan informatif untuk penerima</li>
                    <li>Gunakan variabel dinamis jika diperlukan (sesuai dengan sistem backend)</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-slate-200/50 dark:border-zinc-700/50">
            <Button 
              v-if="!isLoading" 
              type="submit" 
              class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 font-medium"
            >
              <Icon name="lucide:save" class="w-4 h-4" />
              Simpan Template
            </Button>
            
            <Button 
              v-else 
              disabled
              class="px-6 py-3 bg-gradient-to-r from-slate-400 to-slate-500 text-white rounded-xl shadow-lg flex items-center gap-2 font-medium"
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
definePageMeta({title: 'Notifikasi'})
const client = useSanctumClient()
const toast = useToast()
const isLoading = ref(false)
const error = ref('') as any
const state = ref({
  new_order: '',
  project_assignment: '',
  followup_project: '',
}) as any;

onMounted(async () => {
  try {
    const response = await client('/settings');
      state.value = response
  } catch (error: any) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to fetch settings: ' + error.message, life: 3000 });
  }

})
const handleSubmit = async () => {
  isLoading.value = true;
  const formData = new FormData();
  Object.entries(state.value).forEach(([key, value]) => {
    if (value !== null && value !== undefined) {
      formData.append(key, value as string);
    }
  });
  try {
    await client('/settings', {
      method: 'POST',
      body: formData,
    });
    toast.add({ severity: 'success', summary: 'Success', detail: 'Settings updated successfully!', life: 3000 });
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update settings.', life: 3000 });
  }
  isLoading.value = false
}
</script>
