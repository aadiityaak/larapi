<template>
  <div class="space-y-6">
    <!-- Modern Header Section with Glass Morphism -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <div class="flex justify-between items-center gap-4">
        <div class="flex items-center gap-4">
          <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg">
            <Icon name="lucide:database" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Data Produk</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola meta data dan tipe data produk</p>
          </div>
        </div>
        
        <div class="flex items-center gap-3">
          <NuxtLink 
            to="/"
            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-all duration-200 flex items-center gap-2 h-10"
          >
            <Icon name="lucide:home" size="1.1em" class="text-slate-600 dark:text-slate-300" />
            <span class="hidden sm:inline font-medium text-slate-700 dark:text-slate-300">Dashboard</span>
          </NuxtLink>
          
          <Button 
            @click="openDialog('', 'goToEdit', 'Tambah data', user)" 
            class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 h-10"
          >
            <Icon name="lucide:plus" size="1.1em" />
            <span class="hidden sm:inline font-medium">Tambah Data</span>
          </Button>
        </div>
      </div>

      <!-- Breadcrumb Navigation -->
      <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 mt-4 pt-4 border-t border-slate-200/50 dark:border-zinc-700/50">
        <NuxtLink to="/" class="hover:text-amber-600 dark:hover:text-amber-400 transition-colors flex items-center gap-1">
          <Icon name="lucide:home" class="w-4 h-4" />
          <span class="hidden sm:inline">Dashboard</span>
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-3 h-3" />
        <span class="text-slate-900 dark:text-white font-medium">Data Produk</span>
      </div>
    </div>

    <!-- Modern Search Section -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-4 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <div class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
          <InputText 
            id="name_filter" 
            size="small" 
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-amber-500 dark:focus:border-amber-400" 
            v-model="nameFilter"
            @input="filterData"
            placeholder="Cari berdasarkan nama data..."
          />
        </div>
        <div class="flex gap-2">
          <Button 
            @click="resetFilter" 
            v-if="nameFilter || phoneFilter" 
            class="px-4 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-xl shadow-lg transition-all duration-200 flex items-center gap-2"
            size="small"
          >
            <Icon name="lucide:x" class="w-4 h-4" />
            <span class="font-medium">Reset</span>
          </Button>
        </div>
      </div>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="backdrop-blur-xl bg-red-50/80 dark:bg-red-900/20 border border-red-200/50 dark:border-red-700/50 rounded-2xl p-6 shadow-lg">
      <div class="flex items-center">
        <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg mr-3">
          <Icon name="lucide:alert-circle" class="w-5 h-5 text-red-500" />
        </div>
        <div>
          <h3 class="font-semibold text-red-800 dark:text-red-200">Error Loading Data</h3>
          <p class="text-red-600 dark:text-red-300">{{ error }}</p>
        </div>
      </div>
    </div>

    <!-- Modern Data Table -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 overflow-hidden">
      <div v-if="data?.data && data.data.length > 0">
        <DataTable 
          :value="data.data" 
          class="modern-table"
          size="large" 
          striped-rows
          :pt="{
            wrapper: 'border-0 bg-transparent',
            header: 'bg-gradient-to-r from-slate-50 to-amber-50 dark:from-zinc-800 dark:to-zinc-700 border-b border-slate-200/50 dark:border-zinc-600/50',
            bodyRow: 'hover:bg-slate-50/50 dark:hover:bg-zinc-700/30 transition-all duration-200',
            column: { 
              headerContent: 'font-semibold text-slate-700 dark:text-slate-200 text-sm uppercase tracking-wide',
              bodyCell: 'px-6 py-4 border-b border-slate-100/50 dark:border-zinc-700/50'
            }
          }"
        >
          <Column header="Data" class="min-w-[300px]">
            <template #body="slotProps">
              <div class="group p-2 rounded-lg hover:bg-amber-50/50 dark:hover:bg-amber-900/20 transition-all duration-200">
                <div class="flex items-center gap-3">
                  <div class="relative group-avatar">
                    <Avatar 
                      shape="circle" 
                      size="large"
                      class="bg-gradient-to-br from-amber-500 to-orange-600 text-white font-semibold ring-2 ring-white dark:ring-zinc-700 shadow-lg transition-transform duration-200"
                    >
                      {{ slotProps.data.name.charAt(0) }}
                    </Avatar>
                  </div>
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="font-semibold text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                        {{ slotProps.data.name }}
                      </span>
                    </div>
                    <div class="text-sm text-slate-600 dark:text-slate-400 flex items-center gap-1 mt-1">
                      <Icon name="lucide:tag" class="w-3 h-3" />
                      {{ slotProps.data.type.charAt(0).toUpperCase() + slotProps.data.type.slice(1) }}
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </Column>

          <Column header="Tipe Data" class="w-40">
            <template #body="slotProps">
              <Badge
                :class="{
                  'bg-gradient-to-r from-blue-500 to-indigo-600 text-white': slotProps.data.type === 'text',
                  'bg-gradient-to-r from-green-500 to-emerald-600 text-white': slotProps.data.type === 'number',
                  'bg-gradient-to-r from-purple-500 to-pink-600 text-white': slotProps.data.type === 'select',
                  'bg-gradient-to-r from-amber-500 to-orange-600 text-white': slotProps.data.type === 'date',
                  'bg-gradient-to-r from-slate-500 to-gray-600 text-white': !['text', 'number', 'select', 'date'].includes(slotProps.data.type)
                }"
                class="shadow-sm"
              >
                <span class="text-xs font-medium px-2 py-1">
                  {{ slotProps.data.type.charAt(0).toUpperCase() + slotProps.data.type.slice(1) }}
                </span>
              </Badge>
            </template>
          </Column>

          <Column header="" class="w-[80px]">
            <template #body="slotProps">
              <div class="relative">
                <button
                  @click.stop="toggleActionMenu(slotProps.data.id)"
                  :value-action-button="slotProps.data.id"
                  :class="[
                    'p-2 rounded-lg transition-colors relative focus:outline-none focus:ring-2 focus:ring-amber-500',
                    activeActionMenu === slotProps.data.id
                      ? 'bg-amber-100 hover:bg-amber-200 dark:bg-amber-900/30 dark:hover:bg-amber-900/50'
                      : 'bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700'
                  ]"
                  title="Menu Aksi"
                >
                  <Icon 
                    name="lucide:more-vertical" 
                    size="1em" 
                    :class="[
                      'transition-colors',
                      activeActionMenu === slotProps.data.id
                        ? 'text-amber-600 dark:text-amber-400'
                        : 'text-slate-600 dark:text-slate-300'
                    ]"
                  />
                  <!-- Active indicator -->
                  <div 
                    v-if="activeActionMenu === slotProps.data.id"
                    class="absolute -top-1 -right-1 w-2 h-2 bg-amber-500 rounded-full animate-pulse shadow-lg"
                  ></div>
                  
                  <!-- Badge indicator for active menu -->
                  <div 
                    v-if="activeActionMenu === slotProps.data.id"
                    class="absolute -top-2 -left-2 w-1 h-1 bg-green-400 rounded-full animate-ping"
                  ></div>
                </button>
                
                <!-- Sticky Action Menu with Teleport -->
                <Teleport to="body">
                  <Transition name="action-menu">
                    <div
                      v-if="activeActionMenu === slotProps.data.id"
                      :style="getActionMenuPosition(null, slotProps.data.id)"
                      class="fixed z-[999999] min-w-[220px] backdrop-blur-xl bg-white/95 dark:bg-zinc-900/95 border border-slate-200/50 dark:border-zinc-700/50 rounded-xl shadow-xl shadow-slate-900/10 dark:shadow-zinc-900/20 overflow-hidden action-menu-content"
                      @click.stop
                    >
                    <!-- Header Info -->
                    <div class="p-3 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-zinc-800 dark:to-zinc-700 border-b border-slate-200/50 dark:border-zinc-600/50">
                      <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                          <span class="text-white font-bold text-xs">{{ slotProps.data.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                          <div class="font-medium text-slate-800 dark:text-white text-sm truncate">{{ slotProps.data.name }}</div>
                          <div class="text-xs text-slate-500 dark:text-slate-400">Type: {{ slotProps.data.type }}</div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Action Menu Items -->
                    <div class="p-1">
                      <button
                        @click="() => { openDialog(slotProps.data, 'goToEdit', 'Edit Data', user); closeActionMenu(); }"
                        class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors group action-menu-item"
                        @keydown.enter="() => { openDialog(slotProps.data, 'goToEdit', 'Edit Data', user); closeActionMenu(); }"
                        tabindex="0"
                      >
                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors">
                          <Icon name="lucide:pencil" size="0.9em" class="text-blue-600 dark:text-blue-400" />
                        </div>
                        <div class="flex-1">
                          <div class="font-medium text-slate-800 dark:text-white text-sm">Edit Data</div>
                          <div class="text-xs text-slate-500 dark:text-slate-400">Ubah informasi data</div>
                        </div>
                      </button>
                      
                      <div class="my-1 h-px bg-slate-200 dark:bg-zinc-700"></div>
                      
                      <button
                        v-if="isLoading[slotProps.data.id]"
                        class="w-full flex items-center gap-3 px-3 py-2.5 text-left rounded-lg opacity-50 cursor-not-allowed"
                        disabled
                        tabindex="-1"
                      >
                        <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                          <Icon name="lucide:loader-2" size="0.9em" class="text-red-600 dark:text-red-400 animate-spin" />
                        </div>
                        <div class="flex-1">
                          <div class="font-medium text-slate-800 dark:text-white text-sm">Menghapus...</div>
                          <div class="text-xs text-slate-500 dark:text-slate-400">Sedang memproses</div>
                        </div>
                      </button>
                      
                      <button
                        v-else
                        @click="() => { deleteData(slotProps.data.id); closeActionMenu(); }"
                        class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors group action-menu-item"
                        @keydown.enter="() => { deleteData(slotProps.data.id); closeActionMenu(); }"
                        tabindex="0"
                      >
                        <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center group-hover:bg-red-200 dark:group-hover:bg-red-900/50 transition-colors">
                          <Icon name="lucide:trash" size="0.9em" class="text-red-600 dark:text-red-400" />
                        </div>
                        <div class="flex-1">
                          <div class="font-medium text-slate-800 dark:text-white text-sm">Hapus Data</div>
                          <div class="text-xs text-slate-500 dark:text-slate-400">Menghapus secara permanen</div>
                        </div>
                      </button>
                    </div>
                    </div>
                  </Transition>
                </Teleport>
              </div>
            </template>
          </Column>
        </DataTable>
      </div>
      
      <!-- Empty State -->
      <div v-else class="p-12 text-center">
        <div class="p-6 bg-gradient-to-br from-slate-100 to-amber-50 dark:from-zinc-800 dark:to-zinc-700 rounded-2xl inline-block mb-4">
          <Icon name="lucide:database-x" class="text-6xl text-slate-400 dark:text-slate-500" />
        </div>
        <h3 class="text-xl font-semibold text-slate-600 dark:text-slate-400 mb-2">Tidak Ada Data</h3>
        <p class="text-slate-500 dark:text-slate-500 mb-6">Belum ada data produk yang tersedia</p>
        <Button 
          @click="openDialog('', 'goToEdit', 'Tambah data', user)"
          class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200"
        >
          <Icon name="lucide:plus" class="mr-2" />
          Tambah Data Pertama
        </Button>
      </div>
    </div>

    <!-- Modern Pagination -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-4 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <Pagination :value="data" :page="page" @pageChange="onPageChange"/>
    </div>
  </div>

  <!-- Modern Dialog -->
  <Dialog 
    v-model:visible="visible" 
    modal 
    :header="modalData.title" 
    class="modern-dialog"
    :style="{ width: '95vw', 'max-width': '50rem' }"
  >
    <DataEdit 
      v-if="modalKomponen === 'goToEdit'" 
      :datas="modalData" 
      @error="onError" 
      @addData="onAddData" 
      @updateData="onUpdateData" 
      @refreshData="onRefreshData" 
      @closeDialog="visible = false"
    />
  </Dialog>

  <!-- Delete Confirmation Dialog -->
  <Dialog 
    v-model:visible="showDeleteConfirm" 
    modal 
    header="Konfirmasi Hapus"
    :style="{ width: '90vw', 'max-width': '28rem' }"
    :pt="{
      root: { class: '!rounded-2xl !border-0 !shadow-2xl' },
      header: { class: '!bg-gradient-to-r !from-slate-50 !to-slate-100 dark:!from-zinc-800 dark:!to-zinc-700 !rounded-t-2xl !border-b !border-slate-200 dark:!border-zinc-600 !p-6' },
      content: { class: '!p-6 !bg-white dark:!bg-zinc-900' },
      footer: { class: '!bg-slate-50 dark:!bg-zinc-800/50 !border-t !border-slate-200 dark:!border-zinc-600 !rounded-b-2xl !p-6' },
      closeButton: { class: '!text-slate-500 hover:!text-slate-700 dark:!text-slate-400 dark:hover:!text-slate-200' }
    }"
  >
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
        <Icon name="lucide:alert-triangle" class="text-red-600 dark:text-red-400" size="1.5em" />
      </div>
      <div class="flex-1">
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-1">Apakah Anda yakin?</h3>
        <p class="text-sm text-slate-600 dark:text-slate-400">
          Data ini akan dihapus secara permanen dan tidak dapat dikembalikan.
        </p>
      </div>
    </div>
    
    <template #footer>
      <div class="flex gap-3">
        <Button 
          @click="showDeleteConfirm = false"
          class="flex-1 px-4 py-3 bg-gradient-to-r from-slate-500 to-gray-600 hover:from-slate-600 hover:to-gray-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium flex items-center justify-center gap-2"
        >
          <Icon name="lucide:x" size="0.9em" />
          Batal
        </Button>
        <Button 
          @click="confirmDelete"
          class="flex-1 px-4 py-3 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium flex items-center justify-center gap-2"
        >
          <Icon name="lucide:trash-2" size="0.9em" />
          Hapus
        </Button>
      </div>
    </template>
  </Dialog>
  
  <Toast />
</template>

<script lang="ts" setup>
definePageMeta({ title: 'Data Produk' })
const route = useRoute()
const confirm = useConfirm()
const toast = useToast()
const visible = ref(false)
const page = ref(route.query.page ? Number(route.query.page) : 1);
const nameFilter = ref('')
const phoneFilter = ref('')
const client = useSanctumClient()
const activeActionMenu = ref<number | null>(null)
const modalData = ref({} as any)
const modalKomponen = ref('')
const isLoading = ref([] as any)
const user = useSanctumUser() as any
const showDeleteConfirm = ref(false)
const deleteItemId = ref<number | null>(null)

const { data, error, refresh } = await useAsyncData('datas', fetchDatas);

function fetchDatas() {  
  return client(`/metas?page=${page.value}`);
}

const onRefreshData = ( message : string) => {
  refresh()
  visible.value = false
  toast.add({ severity: 'success', summary: 'Sukses', detail: message, life: 3000 });
}

const onError = ( error : string) => {
  toast.add({ severity: 'error', summary: 'Error', detail: error, life: 3000 });
}

const onAddData = (response: any) => {
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Tambah data berhasil', life: 3000 });
  visible.value = false
  refresh()
}

const onUpdateData = (response : any) => {
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Update data berhasil', life: 3000 });
  visible.value = false
  modalData.value = response
  data.value.data = data.value.data.map((item: any) => item.id === modalData.value.id ? modalData.value : item);
}
const openDialog = (data: any, komponen: string, title: string, user: any) => {
  // Pastikan modalData.value adalah objek
  modalData.value = (typeof data === 'object' && data !== null) ? data : {};

  // Tambahkan properti title
  modalData.value.title = title
  modalData.value.user = user
  modalKomponen.value = komponen
  visible.value = true;
}

const filterData = () => {
  if (nameFilter.value.length > 2 || phoneFilter.value.length > 2) {
    refresh();
  }
}

const resetFilter = () => {
  nameFilter.value = '';
  phoneFilter.value = '';
  refresh();
}

const deleteData = async (id: number) => {
  deleteItemId.value = id;
  showDeleteConfirm.value = true;
};

const confirmDelete = async () => {
  if (deleteItemId.value === null) return;
  
  try {
    isLoading.value[deleteItemId.value] = true;
    await client(`/metas/${deleteItemId.value}`, { method: 'DELETE' });
    toast.add({ severity: 'success', summary: 'Sukses', detail: 'Data berhasil dihapus!', life: 3000 });
    await refresh();
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal menghapus data', life: 3000 });
    console.error('Delete error:', error);
  } finally {
    if (deleteItemId.value !== null) {
      isLoading.value[deleteItemId.value] = false;
    }
    showDeleteConfirm.value = false;
    deleteItemId.value = null;
  }
};

const onPageChange = (event: { page: number, first: number, rows: number, pageCount: number }) => {
  page.value = event.page + 1; 
  navigateTo(`/data?page=${page.value}`);
  refresh()
}

// Action Menu Functions
const toggleActionMenu = (id: number) => {
  activeActionMenu.value = activeActionMenu.value === id ? null : id
}

const closeActionMenu = () => {
  activeActionMenu.value = null
}

// Function to calculate action menu position
const getActionMenuPosition = (event: any, id: number) => {
  // Find the button element
  const button = document.querySelector(`[value-action-button="${id}"]`) as HTMLElement
  if (!button) return { top: '0px', left: '0px' }

  const rect = button.getBoundingClientRect()
  const menuWidth = 220 // min-w-[220px]
  const menuHeight = 300 // estimated height

  // Calculate position - place to the left of button
  let left = rect.left - menuWidth - 8 // 8px margin
  let top = rect.top

  // Adjust if menu would go off screen
  if (left < 8) {
    left = rect.right + 8 // Place to the right instead
  }

  // Adjust vertical position if needed
  if (top + menuHeight > window.innerHeight) {
    top = window.innerHeight - menuHeight - 8
  }

  if (top < 8) {
    top = 8
  }

  return {
    top: `${top}px`,
    left: `${left}px`
  }
}

// Click outside to close menu
onMounted(() => {
  document.addEventListener('click', (event) => {
    if (activeActionMenu.value) {
      const clickedElement = event.target as HTMLElement
      const isActionButton = clickedElement.closest('[data-action-button]')
      const isMenuContent = clickedElement.closest('.action-menu-content')
      
      if (!isActionButton && !isMenuContent) {
        closeActionMenu()
      }
    }
  })
  
  // Keyboard navigation
  document.addEventListener('keydown', (event) => {
    if (activeActionMenu.value) {
      if (event.key === 'Escape') {
        closeActionMenu()
      } else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
        event.preventDefault()
        const menuItems = document.querySelectorAll('.action-menu-item')
        const currentIndex = Array.from(menuItems).findIndex(item => item === document.activeElement)
        
        if (event.key === 'ArrowDown') {
          const nextIndex = currentIndex < menuItems.length - 1 ? currentIndex + 1 : 0
          ;(menuItems[nextIndex] as HTMLElement)?.focus()
        } else {
          const prevIndex = currentIndex > 0 ? currentIndex - 1 : menuItems.length - 1
          ;(menuItems[prevIndex] as HTMLElement)?.focus()
        }
      }
    }
  })
})

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
  background: linear-gradient(135deg, rgb(248 250 252) 0%, rgb(255 251 235) 100%);
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
  background: linear-gradient(135deg, rgb(248 250 252 / 0.5) 0%, rgb(255 251 235 / 0.3) 100%);
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
  background: linear-gradient(135deg, rgb(255 251 235), rgb(254 243 199));
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
  border-color: rgb(245 158 11);
  box-shadow: 0 0 0 3px rgb(245 158 11 / 0.1);
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

/* Smooth animations */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Action Menu Transitions */
.action-menu-enter-active {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.action-menu-leave-active {
  transition: all 0.15s ease-in;
}

.action-menu-enter-from {
  opacity: 0;
  transform: scale(0.95) translateY(-10px);
}

.action-menu-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(-5px);
}

/* Sticky Action Column */
:deep(.sticky-action-column .p-column-header-content) {
  position: sticky !important;
  right: 0 !important;
  top: 0 !important;
  z-index: 1005 !important;
  background: rgb(248 250 252 / 0.95) !important;
  backdrop-filter: blur(12px) !important;
  border-left: 1px solid rgb(226 232 240 / 0.5) !important;
  box-shadow: -2px 0 8px -2px rgb(0 0 0 / 0.1);
}

:deep(.dark .sticky-action-column .p-column-header-content) {
  background: rgb(39 39 42 / 0.95) !important;
  backdrop-filter: blur(12px) !important;
  border-left: 1px solid rgb(63 63 70 / 0.5) !important;
  box-shadow: -2px 0 8px -2px rgb(0 0 0 / 0.3);
  z-index: 1005 !important;
}

:deep(.sticky-action-column .p-column-header-content::before) {
  content: '';
  position: absolute;
  left: -10px;
  top: 0;
  bottom: 0;
  width: 10px;
  background: linear-gradient(to right, transparent, rgb(248 250 252 / 0.3));
  pointer-events: none;
}

:deep(.dark .sticky-action-column .p-column-header-content::before) {
  background: linear-gradient(to right, transparent, rgb(39 39 42 / 0.3));
}

:deep(.p-datatable-tbody .sticky-action-column) {
  position: sticky !important;
  right: 0 !important;
  z-index: 1000 !important;
  background: rgb(255 255 255 / 0.95) !important;
  backdrop-filter: blur(8px) !important;
  border-left: 1px solid rgb(226 232 240 / 0.3) !important;
}

:deep(.dark .p-datatable-tbody .sticky-action-column) {
  background: rgb(24 24 27 / 0.95) !important;
  border-left: 1px solid rgb(63 63 70 / 0.3) !important;
  z-index: 1000 !important;
}

:deep(.p-datatable-tbody .sticky-action-column::before) {
  content: '';
  position: absolute;
  left: -10px;
  top: 0;
  bottom: 0;
  width: 10px;
  background: linear-gradient(to right, transparent, rgb(255 255 255 / 0.2));
  pointer-events: none;
}

:deep(.dark .p-datatable-tbody .sticky-action-column::before) {
  background: linear-gradient(to right, transparent, rgb(24 24 27 / 0.2));
}

:deep(.p-datatable-tbody tr:hover .sticky-action-column) {
  background: rgb(248 250 252 / 0.98) !important;
}

:deep(.dark .p-datatable-tbody tr:hover .sticky-action-column) {
  background: rgb(39 39 42 / 0.98) !important;
}
</style>