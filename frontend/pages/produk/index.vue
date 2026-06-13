<template>
  <div class="space-y-6">
    <!-- Modern Header Section with Glass Morphism -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <div class="flex justify-between items-center gap-4">
        <div class="flex items-center gap-4">
          <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
            <Icon name="lucide:package" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">List Produk</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola produk dan data meta produk</p>
          </div>
        </div>
        
        <div class="flex items-center gap-3">
          <Button 
            @click="openDialog('', 'ProdukEdit', 'Tambah Produk', user)" 
            class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 h-10"
          >
            <Icon name="lucide:plus" size="1.1em" />
            <span class="hidden sm:inline font-medium">Tambah Produk</span>
          </Button>
        </div>
      </div>

      <!-- Breadcrumb Navigation -->
      <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 mt-4 pt-4 border-t border-slate-200/50 dark:border-zinc-700/50">
        <NuxtLink to="/" class="hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-1">
          <Icon name="lucide:home" class="w-4 h-4" />
          <span class="hidden sm:inline">Dashboard</span>
        </NuxtLink>
        <Icon name="lucide:chevron-right" class="w-3 h-3" />
        <span class="text-slate-900 dark:text-white font-medium">Produk</span>
      </div>
    </div>

    <!-- Modern Search Section -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-4 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <div class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
          <InputText 
            id="name_filter" 
            size="small" 
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-green-500 dark:focus:border-green-400" 
            v-model="nameFilter"
            @input="filterData"
            placeholder="Cari berdasarkan nama produk..."
          />
        </div>
        <div class="flex gap-2">
          <Button 
            @click="resetFilter" 
            v-if="nameFilter" 
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
            header: 'bg-gradient-to-r from-slate-50 to-green-50 dark:from-zinc-800 dark:to-zinc-700 border-b border-slate-200/50 dark:border-zinc-600/50',
            bodyRow: 'hover:bg-slate-50/50 dark:hover:bg-zinc-700/30 transition-all duration-200',
            column: { 
              headerContent: 'font-semibold text-slate-700 dark:text-slate-200 text-sm uppercase tracking-wide',
              bodyCell: 'px-6 py-4 border-b border-slate-100/50 dark:border-zinc-700/50'
            }
          }"
        >
          <Column header="Produk" class="min-w-[250px]">
            <template #body="slotProps">
              <div 
                class="cursor-pointer group p-2 rounded-lg hover:bg-green-50/50 dark:hover:bg-green-900/20 transition-all duration-200" 
                @click="openDialog(slotProps.data, 'ProdukDetail', 'Detail Produk', user)"
              >
                <div class="flex items-center gap-3">
                  <div class="relative group-avatar">
                    <Avatar 
                      shape="circle" 
                      size="large"
                      class="bg-gradient-to-br from-green-500 to-emerald-600 text-white font-semibold ring-2 ring-white dark:ring-zinc-700 shadow-lg transition-transform duration-200"
                    >
                      {{ slotProps.data.name.charAt(0) }}
                    </Avatar>
                  </div>
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="font-semibold text-slate-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                        {{ slotProps.data.name }}
                      </span>
                    </div>
                    <div class="text-sm text-slate-600 dark:text-slate-400 flex items-center gap-1 mt-1">
                      <Icon name="lucide:shopping-cart" class="w-3 h-3" />
                      {{ slotProps.data.order_count || 0 }} terjual
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </Column>

          <Column header="Data Meta" class="min-w-[300px]">
            <template #body="slotProps">
              <div class="flex flex-wrap gap-1 max-w-md">
                <Badge 
                  v-for="metaId in getMetaIds(slotProps.data).slice(0, 3)" 
                  :key="metaId"
                  class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-xs px-2 py-1 rounded-full shadow-sm"
                >
                  {{ selectMeta.find((meta: any) => meta.id === metaId)?.name || 'Unknown' }}
                </Badge>
                <Badge 
                  v-if="getMetaIds(slotProps.data).length > 3"
                  class="bg-gradient-to-r from-slate-500 to-gray-600 text-white text-xs px-2 py-1 rounded-full shadow-sm"
                >
                  +{{ getMetaIds(slotProps.data).length - 3 }} lainnya
                </Badge>
                <Badge 
                  v-if="!getMetaIds(slotProps.data).length"
                  class="bg-gradient-to-r from-gray-400 to-slate-500 text-white text-xs px-2 py-1 rounded-full shadow-sm"
                >
                  Tidak ada meta data
                </Badge>
              </div>
            </template>
          </Column>

          <Column header="Statistik" class="w-40">
            <template #body="slotProps">
              <div class="space-y-2">
                <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
                  <span class="font-medium">Terjual</span>
                  <span class="font-semibold">{{ slotProps.data.order_count || 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-600 dark:text-slate-400">
                  <span class="font-medium">Meta</span>
                  <span class="font-semibold">{{ getMetaIds(slotProps.data).length }}</span>
                </div>
              </div>
            </template>
          </Column>

          <Column header="" class="w-[80px] sticky-action-column">
            <template #body="slotProps">
              <div class="relative">
                <button
                  @click.stop="toggleActionMenu(slotProps.data.id)"
                  :value-action-button="slotProps.data.id"
                  :class="[
                    'p-2 rounded-lg transition-colors relative focus:outline-none focus:ring-2 focus:ring-green-500',
                    activeActionMenu === slotProps.data.id
                      ? 'bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50'
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
                        ? 'text-green-600 dark:text-green-400'
                        : 'text-slate-600 dark:text-slate-300'
                    ]"
                  />
                  <!-- Active indicator -->
                  <div 
                    v-if="activeActionMenu === slotProps.data.id"
                    class="absolute -top-1 -right-1 w-2 h-2 bg-green-500 rounded-full animate-pulse shadow-lg"
                  ></div>
                  
                  <!-- Badge indicator for active menu -->
                  <div 
                    v-if="activeActionMenu === slotProps.data.id"
                    class="absolute -top-2 -left-2 w-1 h-1 bg-emerald-400 rounded-full animate-ping"
                  ></div>
                </button>
                
                <!-- Sticky Action Menu -->
                <Transition name="action-menu">
                  <div 
                    v-if="activeActionMenu === slotProps.data.id"
                    class="absolute right-full top-0 mr-2 z-[999999] min-w-[220px] backdrop-blur-xl bg-white/95 dark:bg-zinc-900/95 border border-slate-200/50 dark:border-zinc-700/50 rounded-xl shadow-xl shadow-slate-900/10 dark:shadow-zinc-900/20 overflow-hidden action-menu-content"
                    @click.stop
                  >
                    <!-- Header Info -->
                    <div class="p-3 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-zinc-800 dark:to-zinc-700 border-b border-slate-200/50 dark:border-zinc-600/50">
                      <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                          <span class="text-white font-bold text-xs">{{ slotProps.data.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                          <div class="font-medium text-slate-800 dark:text-white text-sm truncate">{{ slotProps.data.name }}</div>
                          <div class="text-xs text-slate-500 dark:text-slate-400">{{ slotProps.data.order_count || 0 }} terjual</div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Action Menu Items -->
                    <div class="p-1">
                      <button
                        @click="() => { openDialog(slotProps.data, 'ProdukDetail', 'Detail Produk', user); closeActionMenu(); }"
                        class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors group action-menu-item"
                        @keydown.enter="() => { openDialog(slotProps.data, 'ProdukDetail', 'Detail Produk', user); closeActionMenu(); }"
                        tabindex="0"
                      >
                        <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition-colors">
                          <Icon name="lucide:eye" size="0.9em" class="text-green-600 dark:text-green-400" />
                        </div>
                        <div class="flex-1">
                          <div class="font-medium text-slate-800 dark:text-white text-sm">Lihat Detail</div>
                          <div class="text-xs text-slate-500 dark:text-slate-400">Tampilkan informasi lengkap</div>
                        </div>
                      </button>
                      
                      <button
                        @click="() => { openDialog(slotProps.data, 'ProdukEdit', 'Edit Produk', user); closeActionMenu(); }"
                        class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors group action-menu-item"
                        @keydown.enter="() => { openDialog(slotProps.data, 'ProdukEdit', 'Edit Produk', user); closeActionMenu(); }"
                        tabindex="0"
                      >
                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors">
                          <Icon name="lucide:pencil" size="0.9em" class="text-blue-600 dark:text-blue-400" />
                        </div>
                        <div class="flex-1">
                          <div class="font-medium text-slate-800 dark:text-white text-sm">Edit Produk</div>
                          <div class="text-xs text-slate-500 dark:text-slate-400">Ubah data produk</div>
                        </div>
                      </button>
                      
                      <div class="my-1 h-px bg-slate-200 dark:bg-zinc-700"></div>
                      
                      <button
                        @click="() => { deleteProduk(slotProps.data.id); closeActionMenu(); }"
                        class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors group action-menu-item"
                        @keydown.enter="() => { deleteProduk(slotProps.data.id); closeActionMenu(); }"
                        tabindex="0"
                      >
                        <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center group-hover:bg-red-200 dark:group-hover:bg-red-900/50 transition-colors">
                          <Icon name="lucide:trash" size="0.9em" class="text-red-600 dark:text-red-400" />
                        </div>
                        <div class="flex-1">
                          <div class="font-medium text-slate-800 dark:text-white text-sm">Hapus Produk</div>
                          <div class="text-xs text-slate-500 dark:text-slate-400">Menghapus secara permanen</div>
                        </div>
                      </button>
                    </div>
                  </div>
                </Transition>
              </div>
            </template>
          </Column>
        </DataTable>
      </div>
      
      <!-- Empty State -->
      <div v-else class="p-12 text-center">
        <div class="p-6 bg-gradient-to-br from-slate-100 to-green-50 dark:from-zinc-800 dark:to-zinc-700 rounded-2xl inline-block mb-4">
          <Icon name="lucide:package-x" class="text-6xl text-slate-400 dark:text-slate-500" />
        </div>
        <h3 class="text-xl font-semibold text-slate-600 dark:text-slate-400 mb-2">Tidak Ada Produk</h3>
        <p class="text-slate-500 dark:text-slate-500 mb-6">Belum ada produk yang tersedia</p>
        <Button 
          @click="openDialog('', 'ProdukEdit', 'Tambah Produk', user)"
          class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200"
        >
          <Icon name="lucide:plus" class="mr-2" />
          Tambah Produk Pertama
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
    :header="modalData.title || 'Detail Produk'" 
    class="modern-dialog"
    :style="{ width: '95vw', 'max-width': '50rem' }"
  >
    <ProdukDetail 
      v-if="modalKomponen === 'ProdukDetail'" 
      :datas="modalData" 
      @goToEdit="openDialog(modalData, 'ProdukEdit', 'Edit Produk', user)" 
      @closeDialog="visible = false" 
    />
    <ProdukEdit 
      v-if="modalKomponen === 'ProdukEdit'" 
      :datas="modalData" 
      @addData="onAddData" 
      @updateData="onUpdateData" 
      @goToDetail="openDialog(modalData, 'ProdukDetail', 'Detail Produk', user)" 
      @error="onError" 
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
          Produk ini akan dihapus secara permanen dan tidak dapat dikembalikan.
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
definePageMeta({title: 'List Produk'})
const route = useRoute()
const confirm = useConfirm()
const toast = useToast()
const visible = ref(false)
const page = ref(route.query.page ? Number(route.query.page) : 1);
const nameFilter = ref('')
const client = useSanctumClient()
const activeActionMenu = ref<number | null>(null)
const modalData = ref({} as any)
const modalKomponen = ref('')
const user = useSanctumUser()
const selectMeta = ref([] as any)
const showDeleteConfirm = ref(false)
const deleteItemId = ref<number | null>(null)
const { data, error, refresh } = await useAsyncData('products', fetchProduct);

const getMetaIds = (product: any) => {
  const raw = Array.isArray(product?.meta_products)
    ? product.meta_products
    : Array.isArray(product?.meta)
      ? product.meta
      : [];

  const ids = raw
    .map((v: any) => {
      if (v == null) return null;
      if (typeof v === 'number') return v;
      if (typeof v === 'string' && v.trim()) return Number(v);
      if (typeof v === 'object') return (v as any).meta_id ?? (v as any).id ?? (v as any).meta?.id;
      return null;
    })
    .map((v: any) => Number(v))
    .filter((v: any) => !isNaN(v));

  return Array.from(new Set(ids));
};

// Action Menu Functions
const toggleActionMenu = (id: number) => {
  activeActionMenu.value = activeActionMenu.value === id ? null : id
}

const closeActionMenu = () => {
  activeActionMenu.value = null
}

onMounted(async () => {
  try {
    const response = await client('/metas?paginate=false')
    selectMeta.value = response
  } catch (error) {
    console.log(error)
  }
  page.value = route.query.page ? Number(route.query.page) : 1;
  
  // Add event listeners for action menu
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
});

function fetchProduct() {
  const query = new URLSearchParams();
  if (nameFilter.value.length > 2) {
    query.append('name', nameFilter.value);
  }
  return client(`/produk?page=${page.value}&${query.toString()}`);
}

const onError = ( error : string) => {
  toast.add({ severity: 'error', summary: 'Error', detail: error, life: 3000 });
}

const onAddData = ( response : any) => {
  visible.value = false
  modalData.value = response
  data.value.data.unshift(modalData.value)
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Tambah data berhasil', life: 3000 });
  openDialog(modalData.value, 'ProdukDetail', 'Detail Produk', user)
}

const onUpdateData = ( response : any) => {
  visible.value = false
  modalData.value = response
  data.value.data = data.value.data.map((item: any) => item.id === modalData.value.id ? modalData.value : item);
  refresh()
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Update data berhasil', life: 3000 });
  openDialog(modalData.value, 'ProdukDetail', 'Detail Produk', user)
}
const openDialog = (data: any, komponen: string, title: string, user: any) => {
  modalData.value = (typeof data === 'object' && data !== null) ? data : {};
  modalData.value.title = title
  modalKomponen.value = komponen
  visible.value = true;
}
const filterData = () => {
  if (nameFilter.value.length > 2 ) {
    refresh();
  }
}

const resetFilter = () => {
  nameFilter.value = '';
  refresh();
}

const deleteProduk = async (id: number) => {
  deleteItemId.value = id;
  showDeleteConfirm.value = true;
};

const confirmDelete = async () => {
  if (deleteItemId.value === null) return;
  
  try {
    await client(`/produk/${deleteItemId.value}`, { method: 'DELETE' });
    toast.add({ severity: 'success', summary: 'Sukses', detail: 'Produk berhasil dihapus!', life: 3000 });
    await refresh();
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal menghapus produk', life: 3000 });
    console.error('Delete error:', error);
  } finally {
    showDeleteConfirm.value = false;
    deleteItemId.value = null;
  }
};

const onPageChange = (event: { page: number, first: number, rows: number, pageCount: number }) => {
  page.value = event.page + 1; 
  navigateTo(`/produk?page=${page.value}`);
  refresh()
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
  background: linear-gradient(135deg, rgb(248 250 252) 0%, rgb(236 253 245) 100%);
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
  background: linear-gradient(135deg, rgb(248 250 252 / 0.5) 0%, rgb(236 253 245 / 0.3) 100%);
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
  background: linear-gradient(135deg, rgb(236 253 245), rgb(240 253 244));
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
  border-color: rgb(34 197 94);
  box-shadow: 0 0 0 3px rgb(34 197 94 / 0.1);
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
