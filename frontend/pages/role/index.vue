<template>
  <div class="space-y-6">
    <!-- Modern Header Section with Glass Morphism -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <div class="flex justify-between items-center gap-4">
        <div class="flex items-center gap-4">
          <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg">
            <Icon name="lucide:user-round-cog" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Manajemen Jabatan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola jabatan dan hak akses sistem</p>
          </div>
        </div>
        
        <div class="flex items-center gap-3">
          <Button 
            @click="openDialog(null, 'Tambah Role')" 
            class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 h-10"
          >
            <Icon name="lucide:plus" size="1.1em" />
            <span class="hidden sm:inline font-medium">Tambah Jabatan</span>
          </Button>
        </div>
      </div>

      <!-- Breadcrumb Navigation -->
      <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 mt-4 pt-4 border-t border-slate-200/50 dark:border-zinc-700/50">
        <NuxtLink to="/" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors flex items-center gap-1">
          <Icon name="lucide:home" class="w-4 h-4" />
          <span class="hidden sm:inline">Dashboard</span>
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-3 h-3" />
        <NuxtLink to="/karyawan" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
          <span class="hidden sm:inline">Karyawan</span>
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-3 h-3" />
        <span class="text-slate-900 dark:text-white font-medium">Jabatan</span>
      </div>
    </div>

    <!-- Modern Data Table -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 overflow-hidden">
      <div v-if="roles && roles.length > 0">
        <DataTable 
          :value="roles" 
          class="modern-table"
          size="large" 
          striped-rows
          :pt="{
            wrapper: 'border-0 bg-transparent',
            header: 'bg-gradient-to-r from-slate-50 to-purple-50 dark:from-zinc-800 dark:to-zinc-700 border-b border-slate-200/50 dark:border-zinc-600/50',
            bodyRow: 'hover:bg-slate-50/50 dark:hover:bg-zinc-700/30 transition-all duration-200',
            column: { 
              headerContent: 'font-semibold text-slate-700 dark:text-slate-200 text-sm uppercase tracking-wide',
              bodyCell: 'px-6 py-4 border-b border-slate-100/50 dark:border-zinc-700/50'
            }
          }"
        >
          <Column header="ID" class="w-24">
            <template #body="slotProps">
              <div class="font-mono text-sm text-slate-500 dark:text-slate-400 bg-slate-100/50 dark:bg-zinc-800/50 px-2 py-1 rounded-md inline-block">
                #{{ slotProps.data.id }}
              </div>
            </template>
          </Column>

          <Column header="Nama Jabatan" class="min-w-[200px]">
            <template #body="slotProps">
              <div class="cursor-pointer group p-2 rounded-lg hover:bg-purple-50/50 dark:hover:bg-purple-900/20 transition-all duration-200">
                <div class="flex items-center gap-3">
                  <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-lg">
                    <Icon name="lucide:shield-check" class="w-4 h-4 text-white" />
                  </div>
                  <div>
                    <span class="font-semibold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                      {{ slotProps.data.name }}
                    </span>
                    <div class="flex flex-wrap gap-1 mt-1">
                      <div class="text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1">
                        <Icon name="lucide:users" class="w-3 h-3" />
                        <span>{{ slotProps.data.permissions?.length || 0 }} hak akses</span>
                      </div>
                      <Badge 
                        v-if="slotProps.data.show_in_jobdesk"
                        class="bg-emerald-500 text-white text-[10px] px-2 py-0.5 rounded-full"
                      >
                        Muncul di Jobdesk
                      </Badge>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </Column>

          <Column header="Hak Akses" class="min-w-[300px]">
            <template #body="slotProps">
              <div class="flex flex-wrap gap-1 max-w-md">
                <Badge 
                  v-for="permission in (slotProps.data.permissions || []).slice(0, 3)" 
                  :key="permission.id"
                  class="bg-gradient-to-r from-blue-500 to-purple-600 text-white text-xs px-2 py-1 rounded-full shadow-sm"
                >
                  {{ permission.name }}
                </Badge>
                <Badge 
                  v-if="(slotProps.data.permissions || []).length > 3"
                  class="bg-gradient-to-r from-slate-500 to-gray-600 text-white text-xs px-2 py-1 rounded-full shadow-sm"
                >
                  +{{ (slotProps.data.permissions || []).length - 3 }} lainnya
                </Badge>
                <Badge 
                  v-if="!(slotProps.data.permissions || []).length"
                  class="bg-gradient-to-r from-red-500 to-pink-600 text-white text-xs px-2 py-1 rounded-full shadow-sm"
                >
                  Tidak ada hak akses
                </Badge>
              </div>
            </template>
          </Column>

          <Column header="" class="w-32">
            <template #body="slotProps">
              <div class="flex justify-end gap-2">
                <Button 
                  class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200"
                  size="small" 
                  @click="openDialog(slotProps.data, 'Edit Role')"
                  v-tooltip.top="'Edit Jabatan'"
                >
                  <Icon name="lucide:pencil" class="w-3 h-3" />
                </Button>
                <Button 
                  class="p-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200"
                  size="small" 
                  @click="deleteRole(slotProps.data.id)"
                  v-tooltip.top="'Hapus Jabatan'"
                >
                  <Icon name="lucide:trash" class="w-3 h-3" />
                </Button>
              </div>
            </template>
          </Column>
        </DataTable>
      </div>
      
      <!-- Empty State -->
      <div v-else class="p-12 text-center">
        <div class="p-6 bg-gradient-to-br from-slate-100 to-purple-50 dark:from-zinc-800 dark:to-zinc-700 rounded-2xl inline-block mb-4">
          <Icon name="lucide:user-round-x" class="text-6xl text-slate-400 dark:text-slate-500" />
        </div>
        <h3 class="text-xl font-semibold text-slate-600 dark:text-slate-400 mb-2">Tidak Ada Jabatan</h3>
        <p class="text-slate-500 dark:text-slate-500 mb-6">Belum ada jabatan yang tersedia</p>
        <Button 
          @click="openDialog(null, 'Tambah Role')"
          class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200"
        >
          <Icon name="lucide:plus" class="mr-2" />
          Tambah Jabatan Pertama
        </Button>
      </div>
    </div>

    <!-- Modern Dialog Edit/Add -->
    <Dialog 
      v-model:visible="visible" 
      modal 
      :header="dialogTitle" 
      class="modern-dialog"
      :style="{ width: '95vw', 'max-width': '60rem' }"
    >
      <div class="space-y-6">
        <!-- Role Name Section -->
        <div class="backdrop-blur-xl bg-gradient-to-br from-purple-50/80 to-pink-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-purple-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
          <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-lg">
              <Icon name="lucide:tag" class="w-5 h-5 text-white" />
            </div>
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Jabatan</h3>
          </div>
          
          <label for="role_name" class="text-slate-600 dark:text-slate-400">
            <Icon name="lucide:tag" class="inline-block mr-1" />
            Nama Jabatan
          </label>  
          <InputText 
            id="role_name" 
            v-model="form.name" 
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-purple-500 dark:focus:border-purple-400" 
          />
          
          <div class="flex items-center gap-3 mt-4 p-3 bg-white/50 dark:bg-zinc-800/50 rounded-xl border border-purple-200/30 dark:border-zinc-600/30">
            <Checkbox 
              v-model="form.show_in_jobdesk" 
              :binary="true" 
              inputId="show_in_jobdesk" 
              class="rounded border-slate-300"
            />
            <label for="show_in_jobdesk" class="text-sm font-medium text-slate-700 dark:text-slate-300 cursor-pointer flex-1">
              Tampilkan Jabatan ini di Pilihan Jobdesk
              <p class="text-xs text-slate-500 dark:text-slate-400 font-normal">Jika dicentang, karyawan dengan jabatan ini akan muncul di pilihan penanggung jawab jobdesk</p>
            </label>
          </div>
            
        </div>

        <!-- Capabilities Section -->
        <div class="backdrop-blur-xl bg-gradient-to-br from-blue-50/80 to-indigo-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-blue-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-lg">
                <Icon name="lucide:shield-check" class="w-5 h-5 text-white" />
              </div>
              <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Hak Akses</h3>
            </div>
            
            <div class="text-sm text-slate-600 dark:text-slate-400 bg-slate-100/50 dark:bg-zinc-800/50 px-3 py-1 rounded-full">
              {{ form.capabilities.length }} dipilih
            </div>
          </div>

          <!-- Capabilities by Group -->
          <div class="space-y-4">
            <div 
              v-for="(caps, group) in groupedCapabilities" 
              :key="group" 
              class="backdrop-blur-sm bg-white/50 dark:bg-zinc-800/50 border border-slate-200/50 dark:border-zinc-600/50 rounded-xl p-4 shadow-sm"
            >
              <div class="flex items-center justify-between mb-3">
                <h4 class="font-bold text-sm uppercase text-slate-700 dark:text-slate-300 flex items-center gap-2">
                  <Icon :name="getGroupIcon(group)" class="w-4 h-4" />
                  {{ group }}
                </h4>
                <div class="text-xs text-slate-500 dark:text-slate-400">
                  {{ caps.filter(cap => form.capabilities.includes(cap.name)).length }}/{{ caps.length }}
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div v-for="cap in caps" :key="cap.id" class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-50/50 dark:hover:bg-zinc-700/30 transition-colors">
                  <Checkbox 
                    v-model="form.capabilities" 
                    :value="cap.name" 
                    :inputId="String(cap.id)" 
                    class="rounded border-slate-300"
                  />
                  <label 
                    :for="String(cap.id)" 
                    class="text-sm text-slate-700 dark:text-slate-300 cursor-pointer flex-1"
                  >
                    {{ cap.name }}
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 pt-6 border-t border-slate-200/50 dark:border-zinc-700/50">
          <Button 
            @click="visible = false"
            class="px-6 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-slate-700 dark:text-slate-300 rounded-xl transition-all duration-200 flex items-center gap-2"
            size="small"
          >
            <Icon name="lucide:x" class="w-4 h-4" />
            Batal
          </Button>

          <Button 
            @click="submitEdit" 
            class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
            size="small"
          >
            <Icon name="lucide:save" class="w-4 h-4" />
            Simpan
          </Button>
        </div>
      </div>
    </Dialog>
    
    <Toast />
  </div>
</template>

<script lang="ts" setup>
definePageMeta({ title: 'Role Management' })

// PrimeVue & Nuxt Composables
const toast = useToast()
const client = useSanctumClient()

// State
const visible = ref(false)
const dialogTitle = ref('')
const form = ref({ id: null, name: '', show_in_jobdesk: false, capabilities: [] as string[] })
const selectAll = ref(false)

// Data
const roles = ref([])
const allCapabilities = ref([])

// Initial fetch
const fetchRoles = async () => {
  const res = await client('/api/roles')
  roles.value = res
}
const fetchCapabilities = async () => {
  const res = await client('/api/capabilities')
  allCapabilities.value = res
}

onMounted(() => {
  fetchRoles()
  fetchCapabilities()
})

// Dialog Handler
const openDialog = (role: any, title: string) => {
  form.value = {
    id: role?.id ?? null,
    name: role?.name ?? '',
    show_in_jobdesk: role?.show_in_jobdesk === 1 || role?.show_in_jobdesk === true,
    capabilities: role?.permissions?.map((p: any) => p.name) ?? []
  }
  dialogTitle.value = title
  visible.value = true
}

// Submit Handler
const submitEdit = async () => {
  try {
    const method = form.value.id ? 'PUT' : 'POST'
    const url = form.value.id
      ? `/api/roles/${form.value.id}`
      : '/api/roles'

    await client(url, {
      method,
      body: {
        name: form.value.name,
        show_in_jobdesk: form.value.show_in_jobdesk,
        capabilities: form.value.capabilities,
      },
    })

    toast.add({
      severity: 'success',
      summary: 'Berhasil',
      detail: form.value.id ? 'Role berhasil diperbarui' : 'Role berhasil ditambahkan',
      life: 3000,
    })

    visible.value = false
    await fetchRoles()
  } catch (err: any) {
    toast.add({
      severity: 'error',
      summary: 'Gagal',
      detail: 'Gagal menyimpan role',
      life: 3000,
    })
  }
}

// Delete Handler
const deleteRole = async (id: number) => {
  try {
    await client(`/api/roles/${id}`, { method: 'DELETE' })
    toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Role dihapus', life: 3000 })
    await fetchRoles()
  } catch (err) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal menghapus role', life: 3000 })
  }
}

// Pengelompokan capabilities
const groupedCapabilities = computed(() => {
  const groups: Record<string, any[]> = {}
  allCapabilities.value.forEach(cap => {
    const [prefix] = cap.name.split(':')
    if (!groups[prefix]) groups[prefix] = []
    groups[prefix].push(cap)
  })
  return groups
})

// Helper function to get group icons
const getGroupIcon = (group: string) => {
  const iconMap: Record<string, string> = {
    'user': 'lucide:users',
    'menu': 'lucide:menu',
    'role': 'lucide:shield-check',
    'order': 'lucide:shopping-cart',
    'jobdesk': 'lucide:clipboard-list',
    'product': 'lucide:package',
    'customer': 'lucide:user-check',
    'settings': 'lucide:settings',
    'dashboard': 'lucide:layout-dashboard',
    'report': 'lucide:file-text'
  }
  return iconMap[group] || 'lucide:key'
}

</script>

<style scoped>
/* Modern Table Styling */
:deep(.modern-table) {
  background: transparent;
  position: relative;
}

:deep(.modern-table .p-datatable-wrapper) {
  background: transparent;
  border-radius: 0;
}

:deep(.modern-table .p-datatable-thead > tr > th) {
  background: linear-gradient(135deg, rgb(248 250 252) 0%, rgb(243 232 255) 100%);
  color: rgb(51 65 85);
  font-weight: 600;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border-bottom: 1px solid rgb(226 232 240 / 0.5);
  padding: 1rem 1.5rem;
  position: relative;
}

:deep(.dark .modern-table .p-datatable-thead > tr > th) {
  background: linear-gradient(135deg, rgb(39 39 42) 0%, rgb(63 63 70) 100%);
  color: rgb(203 213 225);
  border-bottom: 1px solid rgb(63 63 70 / 0.5);
}

:deep(.modern-table .p-datatable-tbody > tr > td) {
  border-bottom: 1px solid rgb(241 245 249 / 0.5);
  padding: 1rem 1.5rem;
  color: rgb(71 85 105);
  background: transparent;
}

:deep(.dark .modern-table .p-datatable-tbody > tr > td) {
  border-bottom: 1px solid rgb(39 39 42 / 0.5);
  color: rgb(203 213 225);
}

:deep(.modern-table .p-datatable-tbody > tr:hover) {
  background: linear-gradient(135deg, rgb(248 250 252 / 0.5) 0%, rgb(243 232 255 / 0.3) 100%);
}

:deep(.dark .modern-table .p-datatable-tbody > tr:hover) {
  background: linear-gradient(135deg, rgb(39 39 42 / 0.3) 0%, rgb(63 63 70 / 0.2) 100%);
}

:deep(.modern-table .p-datatable-tbody > tr:last-child > td) {
  border-bottom: none;
}

/* Modern Dialog Styling */
:deep(.modern-dialog .p-dialog) {
  backdrop-filter: blur(16px);
  background: rgb(255 255 255 / 0.95);
  border: 1px solid rgb(226 232 240 / 0.5);
  border-radius: 1rem;
  box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
}

:deep(.dark .modern-dialog .p-dialog) {
  background: rgb(24 24 27 / 0.95);
  border: 1px solid rgb(63 63 70 / 0.5);
}

:deep(.modern-dialog .p-dialog-header) {
  background: linear-gradient(135deg, rgb(243 232 255), rgb(250 245 255));
  border-bottom: 1px solid rgb(226 232 240 / 0.5);
  border-radius: 1rem 1rem 0 0;
  padding: 1.5rem;
}

:deep(.dark .modern-dialog .p-dialog-header) {
  background: linear-gradient(135deg, rgb(39 39 42), rgb(63 63 70));
  border-bottom: 1px solid rgb(63 63 70 / 0.5);
}

:deep(.modern-dialog .p-dialog-title) {
  color: rgb(30 41 59);
  font-weight: 600;
  font-size: 1.125rem;
}

:deep(.dark .modern-dialog .p-dialog-title) {
  color: rgb(248 250 252);
}

:deep(.modern-dialog .p-dialog-content) {
  padding: 1.5rem;
  background: transparent;
}

/* Input Field Styling */
:deep(.p-inputtext) {
  border-radius: 0.75rem;
  border: 1px solid rgb(203 213 225);
  background: white;
  transition: all 0.2s ease;
}

:deep(.dark .p-inputtext) {
  border: 1px solid rgb(63 63 70);
  background: rgb(24 24 27);
  color: rgb(248 250 252);
}

:deep(.p-inputtext:focus) {
  border-color: rgb(147 51 234);
  box-shadow: 0 0 0 3px rgb(147 51 234 / 0.1);
}

/* Checkbox Styling */
:deep(.p-checkbox) {
  border-radius: 0.375rem;
}

:deep(.p-checkbox:not(.p-disabled):hover) {
  border-color: rgb(147 51 234);
}

:deep(.p-checkbox.p-highlight) {
  background: linear-gradient(135deg, rgb(147 51 234), rgb(219 39 119));
  border-color: rgb(147 51 234);
}

/* Badge styling */
:deep(.p-badge) {
  font-weight: 500;
  padding: 0.25rem 0.75rem;
  border-radius: 0.5rem;
}

/* Button hover effects */
:deep(.p-button) {
  transition: all 0.2s ease;
}

/* Card hover effects */
.backdrop-blur-xl {
  transition: all 0.3s ease;
}

/* Smooth animations */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
