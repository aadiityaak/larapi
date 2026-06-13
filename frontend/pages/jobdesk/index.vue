<template>
  <!-- Header Section with Modern Styling -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-3 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex justify-between items-center gap-4">
      <div class="flex items-center gap-4">
        <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg">
          <Icon name="lucide:briefcase" class="w-6 h-6 text-white" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Jobdesk</h1>
          <p class="text-sm text-slate-500 dark:text-slate-400">Kelola tugas pekerjaan</p>
        </div>
      </div>
      
      <div class="flex items-center gap-3">
        <Button 
          @click="openDialog('', 'JobdeskEdit', 'Tambah Jobdesk', user)" 
          class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 h-10"
        >
          <Icon name="lucide:plus" size="1.1em" />
          <span class="hidden sm:inline font-medium">Tambah Jobdesk</span>
        </Button>
        
        <Button 
          @click="columnsVisible = true" 
          class="relative px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-all duration-200 flex items-center justify-center h-10 min-w-[44px]"
        >
          <Icon name="lucide:columns" size="1.1em" class="text-slate-100 dark:text-slate-300" />
          <span v-if="hasHiddenColumns" class="absolute -top-1 -right-1 flex size-3">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex size-3 rounded-full bg-emerald-500"></span>
          </span>
        </Button>
        
        <Button 
          @click="filterVisible = true" 
          class="relative px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-all duration-200 flex items-center justify-center h-10 min-w-[44px]"
        >
          <Icon name="lucide:filter" size="1.1em" class="text-slate-100 dark:text-slate-300" />
          <span v-if="isFilterActive" class="absolute -top-1 -right-1 flex size-3">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-purple-400 opacity-75"></span>
            <span class="relative inline-flex size-3 rounded-full bg-purple-500"></span>
          </span>
        </Button>
      </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
      <div class="bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 p-3 rounded-xl border border-yellow-200/50 dark:border-yellow-800/50">
        <div class="flex items-center gap-2">
          <Icon name="lucide:inbox" class="text-yellow-600 dark:text-yellow-400" size="0.9em" />
          <span class="text-xs font-medium text-yellow-700 dark:text-yellow-300">Masuk</span>
        </div>
        <div class="text-lg font-bold text-yellow-800 dark:text-yellow-200 mt-1">{{ statusCounts.masuk }}</div>
      </div>
      
      <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 p-3 rounded-xl border border-blue-200/50 dark:border-blue-800/50">
        <div class="flex items-center gap-2">
          <Icon name="lucide:loader" class="text-blue-600 dark:text-blue-400" size="0.9em" />
          <span class="text-xs font-medium text-blue-700 dark:text-blue-300">Progress</span>
        </div>
        <div class="text-lg font-bold text-blue-800 dark:text-blue-200 mt-1">{{ statusCounts.progress }}</div>
      </div>
      
      <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-3 rounded-xl border border-green-200/50 dark:border-green-800/50">
        <div class="flex items-center gap-2">
          <Icon name="lucide:check-circle" class="text-green-600 dark:text-green-400" size="0.9em" />
          <span class="text-xs font-medium text-green-700 dark:text-green-300">Selesai</span>
        </div>
        <div class="text-lg font-bold text-green-800 dark:text-green-200 mt-1">{{ statusCounts.selesai }}</div>
      </div>
      
      <div class="bg-gradient-to-br from-slate-50 to-gray-50 dark:from-slate-900/20 dark:to-gray-900/20 p-3 rounded-xl border border-slate-200/50 dark:border-slate-800/50">
        <div class="flex items-center gap-2">
          <Icon name="lucide:briefcase" class="text-slate-600 dark:text-slate-400" size="0.9em" />
          <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Total</span>
        </div>
        <div class="text-lg font-bold text-slate-800 dark:text-slate-200 mt-1">{{ statusCounts.total }}</div>
      </div>
    </div>
  </div>

  <!-- Status Filter Controls -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-4 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <!-- Status Filter Tabs -->
    <div class="flex gap-2 bg-slate-50 dark:bg-zinc-800/50 p-1 rounded-xl">
      <Button 
        :class="[
          'relative flex-1 px-4 py-2 rounded-lg transition-all duration-200 flex items-center justify-center gap-2',
          status === 'Masuk' 
            ? '!bg-gradient-to-r !from-yellow-500 !to-orange-600 !text-white !shadow-lg !transform !ring-2 !ring-yellow-300/50 dark:!ring-yellow-500/30 !border-transparent' 
            : 'text-slate-600 dark:text-slate-300 hover:bg-white/50 dark:hover:bg-zinc-700/50 bg-white/30 dark:bg-zinc-800/30'
        ]"
        @click="filterStatus('Masuk')"
        :style="status === 'Masuk' ? 'background: linear-gradient(to right, rgb(234 179 8), rgb(234 88 12)) !important; border: 1px solid transparent !important;' : ''"
      >
        <Icon name="lucide:inbox" size="0.9em" />
        <span class="font-medium">Masuk</span>
        <!-- Active indicator -->
        <div v-if="status === 'Masuk'" class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-3 h-1 bg-yellow-300 rounded-full"></div>
      </Button>
      
      <Button 
        :class="[
          'relative flex-1 px-4 py-2 rounded-lg transition-all duration-200 flex items-center justify-center gap-2',
          status === 'Progress' 
            ? '!bg-gradient-to-r !from-blue-500 !to-indigo-600 !text-white !shadow-lg !transform !ring-2 !ring-blue-300/50 dark:!ring-blue-500/30 !border-transparent' 
            : 'text-slate-600 dark:text-slate-300 hover:bg-white/50 dark:hover:bg-zinc-700/50 bg-white/30 dark:bg-zinc-800/30'
        ]"
        @click="filterStatus('Progress')"
        :style="status === 'Progress' ? 'background: linear-gradient(to right, rgb(59 130 246), rgb(99 102 241)) !important; border: 1px solid transparent !important;' : ''"
      >
        <Icon name="lucide:loader" size="0.9em" />
        <span class="font-medium">Progress</span>
        <!-- Active indicator -->
        <div v-if="status === 'Progress'" class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-3 h-1 bg-blue-300 rounded-full"></div>
      </Button>
      
      <Button 
        :class="[
          'relative flex-1 px-4 py-2 rounded-lg transition-all duration-200 flex items-center justify-center gap-2',
          status === 'Selesai' 
            ? '!bg-gradient-to-r !from-green-500 !to-emerald-600 !text-white !shadow-lg !transform !ring-2 !ring-green-300/50 dark:!ring-green-500/30 !border-transparent' 
            : 'text-slate-600 dark:text-slate-300 hover:bg-white/50 dark:hover:bg-zinc-700/50 bg-white/30 dark:bg-zinc-800/30'
        ]"
        @click="filterStatus('Selesai')"
        :style="status === 'Selesai' ? 'background: linear-gradient(to right, rgb(34 197 94), rgb(16 185 129)) !important; border: 1px solid transparent !important;' : ''"
      >
        <Icon name="lucide:check-circle" size="0.9em" />
        <span class="font-medium">Selesai</span>
        <!-- Active indicator -->
        <div v-if="status === 'Selesai'" class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-3 h-1 bg-green-300 rounded-full"></div>
      </Button>
      
      <Button 
        v-if="status" 
        @click="resetStatus"
        class="px-3 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center"
      >
        <Icon name="lucide:x" size="0.9em" />
      </Button>
    </div>
  </div>

  <!-- Data Table -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
    <DataTable 
      v-if="data && data.data && data.data.length > 0"
      :value="data.data" 
      stripedRows 
      class="!border-none"
    >
      <!-- Table columns remain the same as original -->
      <Column v-if="isColVisible('id')" field="id" header="#" class="w-20">
        <template #body="slotProps">
          <div class="flex items-center justify-center text-slate-800 dark:text-white font-semibold text-xs">
            {{ slotProps.data.id }}
          </div>
        </template>
      </Column>
      
      <Column v-if="isColVisible('order')" header="Order" class="min-w-[140px]">
        <template #body="slotProps">
          <div 
            class="space-y-1 cursor-pointer p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors"
            @click="goToOrderDetail(slotProps.data)"
          >
            <div class="font-semibold text-slate-800 dark:text-white text-sm">
              {{ getOrderNumber(slotProps.data) }}
            </div>
            <div class="text-xs text-slate-500 dark:text-slate-400">
              {{ formatDate(slotProps.data.order?.order_date) }}
            </div>
          </div>
        </template>
      </Column>

      <Column v-if="isColVisible('detail')" header="Detail Jobdesk" class="min-w-[250px]">
        <template #body="slotProps">
          <div class="space-y-2">
            <div class="font-semibold text-slate-800 dark:text-white">{{ getProductName(slotProps.data) }}</div>
            <div class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2">
              {{ slotProps.data.description || '-' }}
            </div>
          </div>
        </template>
      </Column>

      <Column v-if="isColVisible('customer')" header="Konsumen" class="min-w-[180px]">
        <template #body="slotProps">
          <div v-if="getCustomerName(slotProps.data) && getCustomerName(slotProps.data) !== 'Tidak diketahui'" class="flex items-center gap-3">
            <div>
              <div class="font-medium text-slate-800 dark:text-white">{{ getCustomerName(slotProps.data) }}</div>
              <div class="text-sm text-slate-500 dark:text-slate-400">{{ getCustomerPhone(slotProps.data) }}</div>
            </div>
          </div>
          <span v-else class="text-slate-400 dark:text-slate-500 text-sm italic">Belum ditugaskan</span>
        </template>
      </Column>

      <Column v-if="isColVisible('petugas')" header="Petugas" class="min-w-[140px]">
        <template #body="slotProps">
          <div v-if="getAssignedTo(slotProps.data) && getAssignedTo(slotProps.data) !== '-'" class="flex items-center gap-2">
            <div>
              <div class="font-medium text-slate-800 dark:text-white text-sm">{{ getAssignedTo(slotProps.data) }}</div>
            </div>
          </div>
          <span v-else class="text-slate-400 dark:text-slate-500 text-sm italic">Belum ditugaskan</span>
        </template>
      </Column>
      
      <Column v-if="isColVisible('maker')" header="Maker" class="min-w-[140px]">
        <template #body="slotProps">
          <div class="font-medium text-slate-800 dark:text-white text-sm">
            {{ getMakerName(slotProps.data) || '-' }}
          </div>
        </template>
      </Column>
      
      <Column v-if="isColVisible('pic')" header="PIC" class="min-w-[140px]">
        <template #body="slotProps">
          <div class="font-medium text-slate-800 dark:text-white text-sm">
            {{ getPicName(slotProps.data) || '-' }}
          </div>
        </template>
      </Column>

      <Column v-if="isColVisible('timeline')" header="Timeline" class="min-w-[120px]">
        <template #body="slotProps">
          <div class="space-y-1">
            <div class="flex items-center gap-2 text-xs">
              <span class="text-slate-600 dark:text-slate-400">{{ formatDate(slotProps.data.tanggal_pengerjaan || slotProps.data.tanggal_mulai || slotProps.data.created_at) }}</span>
            </div>
            <div class="flex items-center gap-2 text-xs">
              <span class="text-slate-600 dark:text-slate-400">{{ formatDate(slotProps.data.tanggal_selesai || slotProps.data.deadline || slotProps.data.tanggal_target) }}</span>
            </div>
          </div>
        </template>
      </Column>

      <Column v-if="isColVisible('status')" header="Status" class="w-32">
        <template #body="slotProps">
          <Badge 
            :value="getStatusText(slotProps.data.status, slotProps.data.status_text)" 
            :class="getStatusClass(slotProps.data.status, slotProps.data.status_class)"
            class="px-3 py-1 rounded-full text-xs font-medium truncate"
          />
        </template>
      </Column>

      <Column header="" class="w-[80px] sticky-action-column">
        <template #body="slotProps">
          <div class="relative">
            <button
              @click.stop="toggleActionMenu(slotProps.data.id)"
              :data-action-button="slotProps.data.id"
              :class="[
                'p-2 rounded-lg transition-colors relative focus:outline-none focus:ring-2 focus:ring-purple-500',
                activeActionMenu === slotProps.data.id
                  ? 'bg-purple-100 hover:bg-purple-200 dark:bg-purple-900/30 dark:hover:bg-purple-900/50'
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
                    ? 'text-purple-600 dark:text-purple-400'
                    : 'text-slate-600 dark:text-slate-300'
                ]"
              />
              <!-- Active indicator -->
              <div 
                v-if="activeActionMenu === slotProps.data.id"
                class="absolute -top-1 -right-1 w-2 h-2 bg-purple-500 rounded-full animate-pulse shadow-lg"
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
                  :style="getFixedActionMenuPosition(slotProps.data.id)"
                  class="fixed z-[2000] min-w-[220px] backdrop-blur-xl bg-white/95 dark:bg-zinc-900/95 border border-slate-200/50 dark:border-zinc-700/50 rounded-xl shadow-xl shadow-slate-900/10 dark:shadow-zinc-900/20 overflow-hidden action-menu-content"
                  @click.stop
                >
                <!-- Header Info -->
                <div class="p-3 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-zinc-800 dark:to-zinc-700 border-b border-slate-200/50 dark:border-zinc-600/50">
                  <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                      <Icon name="lucide:briefcase" class="w-3 h-3 text-white" />
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="font-medium text-slate-800 dark:text-white text-sm truncate">Jobdesk #{{ slotProps.data.id }}</div>
                      <div class="text-xs text-slate-500 dark:text-slate-400">{{ getCustomerName(slotProps.data) }}</div>
                    </div>
                  </div>
                </div>
                
                <!-- Action Menu Items -->
                <div class="p-1">
                  <button
                    @click="() => { openDialog(slotProps.data, 'JobdeskDetail', `Jobdesk #${slotProps.data.id}`, user); closeActionMenu(); }"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors group action-menu-item"
                    @keydown.enter="() => { openDialog(slotProps.data, 'JobdeskDetail', `Jobdesk #${slotProps.data.id}`, user); closeActionMenu(); }"
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
                    @click="() => { openDialog(slotProps.data, 'JobdeskEdit', `Edit Jobdesk #${slotProps.data.id}`, user); closeActionMenu(); }"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors group action-menu-item"
                    @keydown.enter="() => { openDialog(slotProps.data, 'JobdeskEdit', `Edit Jobdesk #${slotProps.data.id}`, user); closeActionMenu(); }"
                    tabindex="0"
                  >
                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors">
                      <Icon name="lucide:edit" size="0.9em" class="text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="flex-1">
                      <div class="font-medium text-slate-800 dark:text-white text-sm">Edit Jobdesk</div>
                      <div class="text-xs text-slate-500 dark:text-slate-400">Ubah data jobdesk</div>
                    </div>
                  </button>
                  
                  <div v-if="user.is_admin == '1' || user.is_admin === true || user.is_admin === 1" class="my-1 h-px bg-slate-200 dark:bg-zinc-700"></div>
                  
                  <button
                    v-if="isLoading[slotProps.data.id] && (user.is_admin == '1' || user.is_admin === true || user.is_admin === 1)"
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
                    v-else-if="user.is_admin == '1' || user.is_admin === true || user.is_admin === 1"
                    @click.stop="() => { deleteData(slotProps.data.id); closeActionMenu(); }"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors group action-menu-item"
                    @keydown.enter="() => { deleteData(slotProps.data.id); closeActionMenu(); }"
                    tabindex="0"
                  >
                    <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center group-hover:bg-red-200 dark:group-hover:bg-red-900/50 transition-colors">
                      <Icon name="lucide:trash" size="0.9em" class="text-red-600 dark:text-red-400" />
                    </div>
                    <div class="flex-1">
                      <div class="font-medium text-slate-800 dark:text-white text-sm">Hapus Jobdesk</div>
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

    <!-- Empty State -->
    <div v-else class="flex flex-col items-center justify-center py-16 px-4">
      <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-zinc-800 dark:to-zinc-700 flex items-center justify-center mb-4">
        <Icon name="lucide:search-x" class="text-slate-400 dark:text-slate-500" size="1.5em" />
      </div>
      <h3 class="text-lg font-semibold text-slate-600 dark:text-slate-400 mb-2">Tidak ada data ditemukan</h3>
      <p class="text-sm text-slate-500 dark:text-slate-500 text-center">Coba periksa filter status atau ubah kriteria pencarian</p>
    </div>
  </div>

  <!-- Pagination -->
  <div class="mt-6">
    <Pagination :value="data" :page="page" @pageChange="onPageChange"/>
  </div>

  <!-- Filter Drawer -->
  <Drawer v-model:visible="filterVisible" position="right" header="Filter Jobdesk" class="!w-full md:!w-80 lg:!w-[24rem]">
    <template #header>
      <div class="flex items-center gap-3 p-4 border-b border-slate-200 dark:border-zinc-700">
        <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-lg">
          <Icon name="lucide:filter" class="w-5 h-5 text-white" />
        </div>
        <div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Filter Jobdesk</h3>
          <p class="text-sm text-slate-500 dark:text-slate-400">Atur kriteria pencarian</p>
        </div>
      </div>
    </template>
    
    <div class="p-4 space-y-6">
      <!-- Search Filter -->
      <div class="space-y-3">
        <div class="flex items-center gap-2 mb-2">
          <div class="p-1.5 bg-blue-100 dark:bg-blue-900/30 rounded-md">
            <Icon name="lucide:search" size="0.9em" class="text-blue-600 dark:text-blue-400" />
          </div>
          <label for="name_filter" class="text-sm font-semibold text-slate-700 dark:text-slate-300">
            Pencarian
          </label>
        </div>
        <InputText 
          id="name_filter" 
          v-model="filter.name" 
          class="w-full !rounded-xl !border-slate-300 dark:!border-zinc-600 !shadow-sm focus:!ring-2 focus:!ring-purple-500/20 focus:!border-purple-500" 
          placeholder="Cari berdasarkan nama atau deskripsi..."
        />
        <p class="text-xs text-slate-500 dark:text-slate-400">Masukkan minimal 3 karakter untuk pencarian</p>
      </div>
      
      <!-- Date Range Filter -->
      <div class="space-y-4">
        <div class="flex items-center gap-2 mb-3">
          <div class="p-1.5 bg-green-100 dark:bg-green-900/30 rounded-md">
            <Icon name="lucide:calendar" size="0.9em" class="text-green-600 dark:text-green-400" />
          </div>
          <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
            Rentang Tanggal
          </label>
        </div>
        
        <div class="space-y-3">
          <div>
            <label for="date_dari" class="text-xs font-medium text-slate-600 dark:text-slate-400 mb-1 block">
              Tanggal Mulai
            </label>
            <DatePicker 
              id="date_dari" 
              v-model="filter.dari" 
              class="w-full !rounded-xl !border-slate-300 dark:!border-zinc-600 !shadow-sm focus:!ring-2 focus:!ring-purple-500/20 focus:!border-purple-500" 
              placeholder="Pilih tanggal mulai..."
              dateFormat="dd/mm/yy"
              :showIcon="true"
              iconDisplay="input"
            />
          </div>
          
          <div>
            <label for="date_sampai" class="text-xs font-medium text-slate-600 dark:text-slate-400 mb-1 block">
              Tanggal Selesai
            </label>
            <DatePicker 
              id="date_sampai" 
              v-model="filter.sampai" 
              class="w-full !rounded-xl !border-slate-300 dark:!border-zinc-600 !shadow-sm focus:!ring-2 focus:!ring-purple-500/20 focus:!border-purple-500" 
              placeholder="Pilih tanggal selesai..."
              dateFormat="dd/mm/yy"
              :showIcon="true"
              iconDisplay="input"
            />
          </div>
        </div>
      </div>
      
      <!-- Maker & PIC Filter -->
      <div class="space-y-4">
        <div class="flex items-center gap-2 mb-3">
          <div class="p-1.5 bg-purple-100 dark:bg-purple-900/30 rounded-md">
            <Icon name="lucide:user-cog" size="0.9em" class="text-purple-600 dark:text-purple-400" />
          </div>
          <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">
            Filter Petugas Order
          </label>
        </div>
        <div class="space-y-3">
          <div>
            <label class="text-xs font-medium text-slate-600 dark:text-slate-400 mb-1 block">
              Maker (mengolah surat)
            </label>
            <Select
              v-model="filter.maker_id"
              :options="makers"
              optionLabel="name"
              optionValue="id"
              showClear
              filter
              placeholder="Pilih maker..."
              class="w-full !rounded-xl !border-slate-300 dark:!border-zinc-600"
            />
          </div>
          <div>
            <label class="text-xs font-medium text-slate-600 dark:text-slate-400 mb-1 block">
              PIC (kontak dengan klien)
            </label>
            <Select
              v-model="filter.pic_id"
              :options="pics"
              optionLabel="name"
              optionValue="id"
              showClear
              filter
              placeholder="Pilih PIC..."
              class="w-full !rounded-xl !border-slate-300 dark:!border-zinc-600"
            />
          </div>
        </div>
      </div>
      
      <!-- Current Status Display -->
      <div v-if="status" class="p-3 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border border-purple-200 dark:border-purple-800 rounded-xl">
        <div class="flex items-center gap-2 mb-1">
          <Icon name="lucide:filter-check" size="0.9em" class="text-purple-600 dark:text-purple-400" />
          <span class="text-sm font-medium text-purple-700 dark:text-purple-300">Status Aktif</span>
        </div>
        <div class="flex items-center justify-between">
          <Badge 
            :value="String(status)" 
            :class="getStatusClass(String(status))"
            class="px-2 py-1 rounded-lg text-xs font-medium"
          />
          <Button 
            @click="resetStatus"
            size="small"
            class="!p-1 !text-xs text-purple-600 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-900/30 !border-none !shadow-none"
            text
          >
            <Icon name="lucide:x" size="0.8em" />
          </Button>
        </div>
      </div>
      
      <!-- Filter Summary -->
      <div v-if="isFilterActive" class="p-3 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
        <div class="flex items-center gap-2 mb-2">
          <Icon name="lucide:info" size="0.9em" class="text-blue-600 dark:text-blue-400" />
          <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Filter Aktif</span>
        </div>
        <div class="space-y-1 text-xs">
          <div v-if="filter.name.length > 2" class="flex items-center gap-2 text-blue-600 dark:text-blue-400">
            <Icon name="lucide:search" size="0.7em" />
            <span>Pencarian: "{{ filter.name }}"</span>
          </div>
          <div v-if="filter.dari" class="flex items-center gap-2 text-blue-600 dark:text-blue-400">
            <Icon name="lucide:calendar-plus" size="0.7em" />
            <span>Dari: {{ formatDate(filter.dari.toISOString()) }}</span>
          </div>
          <div v-if="filter.sampai" class="flex items-center gap-2 text-blue-600 dark:text-blue-400">
            <Icon name="lucide:calendar-check" size="0.7em" />
            <span>Sampai: {{ formatDate(filter.sampai.toISOString()) }}</span>
          </div>
          <div v-if="status" class="flex items-center gap-2 text-blue-600 dark:text-blue-400">
            <Icon name="lucide:tag" size="0.7em" />
            <span>Status: {{ status }}</span>
          </div>
          <div v-if="filter.maker_id" class="flex items-center gap-2 text-blue-600 dark:text-blue-400">
            <Icon name="lucide:user-cog" size="0.7em" />
            <span>Maker: {{ makers.find(m => m.id === filter.maker_id)?.name }}</span>
          </div>
          <div v-if="filter.pic_id" class="flex items-center gap-2 text-blue-600 dark:text-blue-400">
            <Icon name="lucide:user-check" size="0.7em" />
            <span>PIC: {{ pics.find(p => p.id === filter.pic_id)?.name }}</span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Action Buttons -->
    <template #footer>
      <div class="flex gap-3 p-4 border-t border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800/50">
        <Button 
          @click="resetFilter"
          class="flex-1 px-4 py-3 bg-gradient-to-r from-slate-500 to-gray-600 hover:from-slate-600 hover:to-gray-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium flex items-center justify-center gap-2"
        >
          <Icon name="lucide:refresh-cw" size="0.9em" />
          Reset Filter
        </Button>
        <Button 
          @click="filterVisible = false"
          class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium flex items-center justify-center gap-2"
        >
          <Icon name="lucide:check" size="0.9em" />
          Terapkan
        </Button>
      </div>
    </template>
  </Drawer>

  <!-- Columns Drawer -->
  <Drawer v-model:visible="columnsVisible" position="right" header="Atur Kolom Tabel" class="!w-full md:!w-80 lg:!w-[24rem]">
    <template #header>
      <div class="flex items-center gap-3 p-4 border-b border-slate-200 dark:border-zinc-700">
        <div class="p-2 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg shadow-lg">
          <Icon name="lucide:columns" class="w-5 h-5 text-white" />
        </div>
        <div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Atur Kolom Tabel</h3>
          <p class="text-sm text-slate-500 dark:text-slate-400">Sembunyikan/lihat kolom sesuai kebutuhan</p>
        </div>
      </div>
    </template>
    <div class="p-4 space-y-3">
      <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-zinc-800/50 border border-slate-200/60 dark:border-zinc-700/60">
        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Pilih Semua</span>
        <Checkbox :binary="true" :modelValue="allColumnsSelected" @update:modelValue="toggleAllColumns" />
      </div>
      <div class="space-y-2">
        <div v-for="col in columns" :key="col.key" class="flex items-center justify-between p-3 rounded-xl border border-slate-200/60 dark:border-zinc-700/60 hover:bg-slate-50 dark:hover:bg-zinc-800/40 transition-colors">
          <div class="flex items-center gap-2">
            <Icon :name="getColumnIcon(col.key)" class="w-4 h-4 text-slate-500 dark:text-slate-400" />
            <span class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ col.label }}</span>
            <span v-if="col.locked" class="ml-2 text-[10px] px-2 py-0.5 rounded-full bg-slate-200 dark:bg-zinc-700 text-slate-600 dark:text-slate-300">Tetap</span>
          </div>
          <Checkbox v-model="col.visible" :binary="true" :disabled="col.locked" />
        </div>
      </div>
    </div>
    <template #footer>
      <div class="flex gap-3 p-4 border-t border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800/50">
        <Button @click="resetColumns" class="flex-1 px-4 py-3 bg-gradient-to-r from-slate-500 to-gray-600 hover:from-slate-600 hover:to-gray-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium flex items-center justify-center gap-2">
          <Icon name="lucide:rotate-ccw" size="0.9em" />
          Reset
        </Button>
        <Button @click="columnsVisible = false" class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium flex items-center justify-center gap-2">
          <Icon name="lucide:check" size="0.9em" />
          Selesai
        </Button>
      </div>
    </template>
  </Drawer>

  <!-- Dialog Modal -->
  <Dialog 
    v-model:visible="visible" 
    modal 
    :header="modalData.title" 
    :style="{ width: '90vw', 'max-width': '60rem' }"
    :pt="{
      root: { class: '!rounded-2xl !border-0 !shadow-2xl' },
      header: { class: '!bg-gradient-to-r !from-slate-50 !to-slate-100 dark:!from-zinc-800 dark:!to-zinc-700 !rounded-t-2xl !border-b !border-slate-200 dark:!border-zinc-600' },
      content: { class: '!p-6 !bg-white dark:!bg-zinc-900' },
      closeButton: { class: '!text-slate-500 hover:!text-slate-700 dark:!text-slate-400 dark:hover:!text-slate-200' }
    }"
  >
    <JobdeskDetail 
      v-if="modalKomponen === 'JobdeskDetail'" 
      :datas="modalData" 
      @goToEdit="openDialog(modalData, 'JobdeskEdit', `Edit Jobdesk #${modalData.id}`, user)" 
      @closeDialog="visible = false" 
    />
    <OrderDetail
      v-if="modalKomponen === 'OrderDetail'"
      :datas="modalData"
      @closeDialog="visible = false"
    />
    <JobdeskEdit 
      v-if="modalKomponen === 'JobdeskEdit'" 
      :datas="modalData" 
      @addData="onAddData"
      @updateData="onUpdateData" 
      @closeDialog="visible = false" 
      @error="onError"
    />
  </Dialog>

  <!-- Toast Notifications -->
  <Toast 
    :pt="{
      root: { class: '!rounded-xl !shadow-2xl !border-0' },
      message: { class: '!rounded-xl' }
    }"
  />

  <!-- Confirm Delete Dialog -->
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
          Data jobdesk ini akan dihapus secara permanen dan tidak dapat dikembalikan.
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
</template>

<script lang="ts" setup>
definePageMeta({title: 'List Jobdesk'})

const route = useRoute()
const visible = ref(false)
const showDeleteConfirm = ref(false)
const deleteItemId = ref<number | null>(null)
const user = useSanctumUser() as any
const toast = useToast()
const page = ref(route.query.page ? Number(route.query.page) : 1)
const order_id = ref(route.query.order_id || '')
const modalData = ref({} as any)
const modalKomponen = ref('')
const isLoading = ref<{ [key: number]: boolean }>({})
const client = useSanctumClient()
const filterVisible = ref(false)
const status = ref(route.query.status || null)
const activeActionMenu = ref<number | null>(null)
type ColumnOption = { key: string; label: string; visible: boolean; locked?: boolean }
const columnsVisible = ref(false)
const defaultColumns: ColumnOption[] = [
  { key: 'id', label: '#', visible: true },
  { key: 'order', label: 'Order', visible: true },
  { key: 'detail', label: 'Detail Jobdesk', visible: true },
  { key: 'customer', label: 'Konsumen', visible: true },
  { key: 'petugas', label: 'Petugas', visible: true },
  { key: 'maker', label: 'Maker', visible: true },
  { key: 'pic', label: 'PIC', visible: true },
  { key: 'timeline', label: 'Timeline', visible: true },
  { key: 'status', label: 'Status', visible: true },
  { key: 'actions', label: 'Aksi', visible: true, locked: true }
]
const columns = ref<ColumnOption[]>([])
const filter = ref({
  name: '',
  dari: null as Date | null,
  sampai: null as Date | null,
  maker_id: null as number | null,
  pic_id: null as number | null
})
const makers = ref<any[]>([])
const pics = ref<any[]>([])
const normalizeList = (response: any) => {
  if (Array.isArray(response)) return response
  if (Array.isArray(response?.data)) return response.data
  return []
}

// Computed
const isFilterActive = computed(() =>
  filter.value.name.length > 2 ||
  filter.value.dari ||
  filter.value.sampai ||
  !!status.value ||
  !!filter.value.maker_id ||
  !!filter.value.pic_id
)
const hasHiddenColumns = computed(() => {
  return columns.value.some(c => !c.visible && !c.locked)
})
const allColumnsSelected = computed(() => {
  return columns.value.filter(c => !c.locked).every(c => c.visible)
})

// Get status counts separately
const statsData = ref(null);

const statusCounts = computed(() => {
  // Use statsData if available
  if (statsData.value && typeof statsData.value === 'object') {
    return {
      masuk: statsData.value.masuk || 0,
      progress: statsData.value.progress || 0,
      selesai: statsData.value.selesai || 0,
      total: statsData.value.total || 0
    }
  }
  
  // Fallback to manual counting if stats not available
  const fallbackCounts = {
    masuk: 0,
    progress: 0,
    selesai: 0,
    total: 0
  }
  
  if (data.value && data.value.data) {
    data.value.data.forEach((item: any) => {
      fallbackCounts.total++
      switch(item.status) {
        case 'Masuk':
          fallbackCounts.masuk++
          break
        case 'Progress':
          fallbackCounts.progress++
          break
        case 'Selesai':
          fallbackCounts.selesai++
          break
      }
    })
  }
  
  return fallbackCounts
})

// Watch for route changes
watch(() => route.query.status, (newStatus) => {
  if (newStatus) {
    status.value = newStatus
  }
})

onMounted(async () => {
  if (route.query.order_id && route.query.tambah) {
    order_id.value = route.query.order_id
    openDialog('', 'JobdeskEdit', 'Tambah Jobdesk', user)
  }
  
  // Fetch stats data
  try {
    const response = await client('/jobdesks/stats');
    statsData.value = response;
  } catch (error) {
    console.error('Error fetching stats:', error);
  }
  
  // Load makers & PICs
  try {
    const resUsers = await client('/karyawans?paginate=false')
    makers.value = normalizeList(resUsers)
  } catch (e) {
    console.log('Error load makers', e)
  }
  try {
    const resPICs = await client('/karyawans?paginate=false')
    pics.value = normalizeList(resPICs)
    if (!pics.value?.length) {
      const resPICsMin = await client('/karyawans/min')
      pics.value = normalizeList(resPICsMin)
    }
  } catch (e) {
    try {
      const resPICsMin = await client('/karyawans/min')
      pics.value = normalizeList(resPICsMin)
    } catch (err) {
      console.log('Error load PICs', err)
    }
  }
  
  const saved = localStorage.getItem('jobdesk.columns')
  if (saved) {
    try {
      const parsed = JSON.parse(saved) as ColumnOption[]
      const merged = defaultColumns.map(def => {
        const found = parsed.find(p => p.key === def.key)
        return found ? { ...def, visible: found.visible } : def
      })
      columns.value = merged
    } catch {
      columns.value = [...defaultColumns]
    }
  } else {
    columns.value = [...defaultColumns]
  }
})

const { data, error, refresh } = await useAsyncData('jobdesk-data', fetchJobdesk);


// Helper functions
const getStatusText = (status: string, statusText?: string) => {
  // Use status_text from API if available, otherwise fallback to mapping
  if (statusText) {
    return statusText
  }
  
  switch (status) {
    case 'Masuk':
      return 'Belum Mulai'
    case 'Progress': 
      return 'Sedang Dikerjakan'
    case 'Selesai':
      return 'Selesai'
    default:
      return status
  }
}

const getStatusClass = (status: string, statusClass?: string) => {
  // Use status_class from API if available, otherwise fallback to mapping
  if (statusClass) {
    return statusClass.replace('bg-', 'bg-gradient-to-r from-').replace('text-', 'text-').replace('border-', '')
  }
  
  switch (status) {
    case 'Masuk':
      return 'bg-gradient-to-r from-yellow-100 to-orange-100 dark:from-yellow-900/30 dark:to-orange-900/30 text-yellow-700 dark:text-yellow-300'
    case 'Progress':
      return 'bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 text-blue-700 dark:text-blue-300'
    case 'Selesai':
      return 'bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-700 dark:text-green-300'
    default:
      return 'bg-gradient-to-r from-gray-100 to-slate-100 dark:from-gray-900/30 dark:to-slate-900/30 text-gray-700 dark:text-gray-300'
  }
}

function fetchJobdesk() {
  const query = new URLSearchParams();
  if (filter.value.name.length > 2) query.append('name', filter.value.name)
  if (filter.value.dari) query.append('dari', filter.value.dari.toISOString().slice(0, 10))
  if (filter.value.sampai) query.append('sampai', filter.value.sampai.toISOString().slice(0, 10))
  if (filter.value.maker_id) query.append('maker_id', String(filter.value.maker_id))
  if (filter.value.pic_id) query.append('pic_id', String(filter.value.pic_id))
  if (status.value) {
    query.append('status', String(status.value));
  } else {
    query.delete('status');
  }
  return client(`/jobdesks?page=${page.value}&order_id=${order_id.value}&${query.toString()}`);
}

const onError = (error: string) => {
  toast.add({ severity: 'error', summary: 'Error', detail: error, life: 3000 });
}

const deleteData = async (id: number) => {
  deleteItemId.value = id;
  showDeleteConfirm.value = true;
}

const confirmDelete = async () => {
  if (!deleteItemId.value) return;
  
  const id = deleteItemId.value;
  showDeleteConfirm.value = false;
  isLoading.value[id] = true;
  
  try {
    await client(`/jobdesks/${id}`, {
      method: 'DELETE',
    });
    isLoading.value[id] = false;
    toast.add({ severity: 'success', summary: 'Sukses', detail: 'Jobdesk berhasil dihapus!', life: 3000 });
    refresh();
    const queryDelete = new URLSearchParams();
    queryDelete.append('status', route.query.status?.toString() || 'Masuk');
    queryDelete.append('page', page.value.toString());
    navigateTo(`/jobdesk?${queryDelete.toString()}`);
  } catch (error: any) {
    isLoading.value[id] = false;
    console.error('Delete error:', error);
    toast.add({ 
      severity: 'error', 
      summary: 'Error', 
      detail: error?.data?.message || 'Gagal menghapus jobdesk!', 
      life: 3000 
    });
  }
  
  deleteItemId.value = null;
}

const onAddData = (response: any) => {
  visible.value = false;
  modalData.value = response;
  data.value.data.unshift(modalData.value);
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Tambah data berhasil', life: 3000 });
  openDialog(modalData.value, 'JobdeskDetail', 'Jobdesk #' + modalData.value.id, user)
}

const onUpdateData = (response: any) => {
  visible.value = false;
  modalData.value = response;
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Edit data berhasil', life: 3000 });
  data.value.data = data.value.data.map((item: any) => item.id === modalData.value.id ? modalData.value : item);
  openDialog(modalData.value, 'JobdeskDetail', 'Jobdesk #' + modalData.value.id, user)
}

const openDialog = (data: any, komponen: string, title: string, user: any) => {
  closeActionMenu()
  modalData.value = (typeof data === 'object' && data !== null) ? data : {};
  modalData.value.title = title;
  modalData.value.id = data?.id || '';
  modalKomponen.value = komponen
  modalData.value.user = user
  visible.value = true;
}

const filterStatus = async (newStatus: string) => {
  filter.value.name = '';
  const query_status = new URLSearchParams();

  query_status.set('status', newStatus);
  query_status.set('page', '1');

  if (order_id.value) {
    query_status.set('order_id', String(order_id.value));
  }
  
  status.value = newStatus;
  page.value = 1;
  
  try {
    await navigateTo(`/jobdesk?${query_status.toString()}`);
  } catch (error) {
    console.error('Navigation error:', error);
  }

  await refresh();
}

const resetStatus = async () => {
  const query_status = new URLSearchParams();
  query_status.set('page', '1');
  
  if (order_id.value) {
    query_status.set('order_id', String(order_id.value));
  }
  
  status.value = null;
  page.value = 1;
  
  try {
    await navigateTo(`/jobdesk?${query_status.toString()}`);
  } catch (error) {
    console.error('Navigation error:', error);
  }

  await refresh();
}

const resetFilter = () => {
  filter.value = {
    name: '',
    dari: null as Date | null,
    sampai: null as Date | null,
    maker_id: null as number | null,
    pic_id: null as number | null
  }
  order_id.value = '';
  status.value = null;
  page.value = 1;
  navigateTo(`/jobdesk`);
  filterVisible.value = false
  refresh();
}

const onPageChange = (event: any) => {
  page.value = event.page + 1;
  
  // Build query parameters to maintain current filters
  const queryParams = new URLSearchParams();
  queryParams.append('page', String(page.value));
  
  if (order_id.value) queryParams.append('order_id', String(order_id.value));
  if (status.value) queryParams.append('status', String(status.value));
  if (filter.value.name.length > 2) queryParams.append('name', filter.value.name);
  if (filter.value.dari) queryParams.append('dari', filter.value.dari.toISOString().slice(0, 10));
  if (filter.value.sampai) queryParams.append('sampai', filter.value.sampai.toISOString().slice(0, 10));
  
  navigateTo(`/jobdesk?${queryParams.toString()}`);
  refresh();
}

const formatDate = (date: string) => {
  if (!date) return '-';
  return new Date(date).toLocaleString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
}

// Customer helper functions
const getCustomerName = (data: any) => {
  // Use customer_name from API if available, otherwise fallback to nested structure
  return data.customer_name || data.order?.customer?.name || 'Tidak diketahui'
}

const getCustomerPhone = (data: any) => {
  // Use customer_phone from API if available, otherwise fallback to nested structure
  return data.customer_phone || data.order?.customer?.phone || '-'
}

const getCustomerInitial = (data: any) => {
  const name = getCustomerName(data)
  return name && name !== 'Tidak diketahui' ? name.charAt(0).toUpperCase() : '?'
}

const getProductName = (data: any) => {
  // Use product_name from API if available, otherwise fallback to nested structure
  return data.product_name || data.order?.product?.name || 'Jobdesk'
}

const getAssignedTo = (data: any) => {
  // Use assigned_to from API if available, otherwise fallback to nested structure
  return data.assigned_to || data.user?.name || '-'
}
const getOrderNumber = (data: any) => {
  return data.order?.no_order || (data.order_id ? `#${data.order_id}` : '-')
}
const goToOrderDetail = (row: any) => {
  const id = row?.order?.id || row?.order_id
  if (!id) return
  navigateTo(`/order?order_id=${id}`)
}
const getMakerName = (data: any) => {
  return data.maker_name || data.order?.maker?.name || ''
}
const getPicName = (data: any) => {
  return data.pic_name || data.order?.pic?.name || ''
}
const isColVisible = (key: string) => {
  const f = columns.value.find(c => c.key === key)
  return f ? f.visible : true
}
const toggleAllColumns = (val: boolean) => {
  columns.value = columns.value.map(c => c.locked ? c : { ...c, visible: !!val })
}
const resetColumns = () => {
  columns.value = [...defaultColumns]
  localStorage.removeItem('jobdesk.columns')
}
const getColumnIcon = (key: string) => {
  switch (key) {
    case 'id': return 'lucide:hash'
    case 'order': return 'lucide:receipt'
    case 'detail': return 'lucide:clipboard-list'
    case 'customer': return 'lucide:user'
    case 'petugas': return 'lucide:users'
    case 'maker': return 'lucide:user-cog'
    case 'pic': return 'lucide:user-check'
    case 'timeline': return 'lucide:calendar-range'
    case 'status': return 'lucide:badge-check'
    case 'actions': return 'lucide:settings-2'
    default: return 'lucide:square'
  }
}

// Action menu functions
const toggleActionMenu = (id: number) => {
  activeActionMenu.value = activeActionMenu.value === id ? null : id
}

const closeActionMenu = () => {
  activeActionMenu.value = null
}

// Function to calculate fixed action menu position
const getFixedActionMenuPosition = (id: number) => {
  // Find the button element
  const button = document.querySelector(`[data-action-button="${id}"]`) as HTMLElement
  if (!button) return { top: '0px', left: '0px' }

  const rect = button.getBoundingClientRect()
  const menuWidth = 220 // min-w-[220px]
  const menuHeight = 300 // estimated height

  // Calculate position - place to the left of button
  let left = rect.left - menuWidth - 8 // 8px margin
  let top = rect.top + window.scrollY // Add scroll offset for fixed positioning

  // Adjust if menu would go off screen
  if (left < 8) {
    left = rect.right + 8 // Place to the right instead
  }

  // Adjust vertical position if needed
  if (top + menuHeight > window.innerHeight + window.scrollY) {
    top = window.innerHeight + window.scrollY - menuHeight - 8
  }

  if (top < window.scrollY + 8) {
    top = window.scrollY + 8
  }

  return {
    top: `${top}px`,
    left: `${left}px`
  }
}

// Close action menu when clicking outside
onMounted(() => {
  document.addEventListener('click', (event) => {
    const target = event.target as HTMLElement
    if (!target.closest('[data-action-button]') && !target.closest('.action-menu-content')) {
      closeActionMenu()
    }
  })
})

watch(filter, () => {
  if (isFilterActive.value) {
    page.value = 1; // Reset pagination when filter changes
    refresh();
  }
}, { deep: true })
watch(columns, () => {
  try {
    localStorage.setItem('jobdesk.columns', JSON.stringify(columns.value))
  } catch {}
}, { deep: true })
</script>

<style scoped>
/* Override PrimeVue button styles for status filters */
.status-filter-active {
  background: var(--gradient-bg) !important;
  border: 1px solid transparent !important;
  color: white !important;
}

/* Action Menu Transitions */
.action-menu-enter-active,
.action-menu-leave-active {
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.action-menu-enter-from {
  opacity: 0;
  transform: translateX(10px) scale(0.95);
}

.action-menu-leave-to {
  opacity: 0;
  transform: translateX(10px) scale(0.95);
}

.action-menu-enter-to,
.action-menu-leave-from {
  opacity: 1;
  transform: translateX(0) scale(1);
}

/* Action Menu Item Hover Effects */
.action-menu-item {
  position: relative;
  overflow: hidden;
}

.action-menu-item:hover::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
  transform: translateX(-100%);
  animation: shimmer 0.6s ease-out;
}

@keyframes shimmer {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}

/* Custom scrollbar for action menu */
.action-menu-content::-webkit-scrollbar {
  width: 4px;
}

.action-menu-content::-webkit-scrollbar-track {
  background: transparent;
}

.action-menu-content::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.2);
  border-radius: 2px;
}

.action-menu-content::-webkit-scrollbar-thumb:hover {
  background: rgba(0, 0, 0, 0.3);
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
