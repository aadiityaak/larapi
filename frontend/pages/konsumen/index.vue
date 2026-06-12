<template>
  <!-- Header Section with Modern Styling -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-3 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex justify-between items-center gap-4">
      <div class="flex items-center gap-4">
        <div class="p-3 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
          <Icon name="lucide:users" class="w-6 h-6 text-white" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Konsumen</h1>
          <p class="text-sm text-slate-500 dark:text-slate-400">Kelola data konsumen</p>
        </div>
      </div>
      
      <div class="flex items-center gap-3">
        <Button 
          @click="openDialog('', 'KonsumenEdit', 'Tambah Konsumen', user)" 
          class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 h-10"
        >
          <Icon name="lucide:plus" size="1.1em" />
          <span class="hidden sm:inline font-medium">Tambah Konsumen</span>
        </Button>
        
        <Button 
          @click="filterVisible = true" 
          class="relative px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-all duration-200 flex items-center justify-center h-10 min-w-[44px]"
        >
          <Icon name="lucide:filter" size="1.1em" class="text-slate-100 dark:text-slate-300" />
          <span v-if="isFilterActive" class="absolute -top-1 -right-1 flex size-3">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-75"></span>
            <span class="relative inline-flex size-3 rounded-full bg-blue-500"></span>
          </span>
        </Button>
      </div>
    </div>
  </div>

  <!-- Modern Filter Drawer -->
  <Drawer v-model:visible="filterVisible" position="right" class="modern-drawer">
    <template #header>
      <div class="flex items-center gap-3">
        <div class="p-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg shadow-lg">
          <Icon name="lucide:filter" class="w-5 h-5 text-white" />
        </div>
        <div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Filter Konsumen</h3>
          <p class="text-sm text-slate-500 dark:text-slate-400">Cari konsumen berdasarkan kriteria</p>
        </div>
      </div>
    </template>

    <div class="space-y-6 p-1">
      <!-- Search Section -->
      <div class="space-y-4">
        <div class="p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-700 rounded-xl border border-blue-200/50 dark:border-zinc-600/50">
          <h4 class="text-sm font-semibold text-blue-700 dark:text-blue-300 mb-3 flex items-center gap-2">
            <Icon name="lucide:search" size="0.9em" />
            Pencarian
          </h4>
          
          <div class="space-y-3">
            <div>
              <label for="name_filter" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                <Icon name="lucide:user" class="inline mr-2" size="0.9em" />
                Nama Konsumen
              </label>
              <InputText 
                id="name_filter" 
                class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 focus:ring-blue-500 bg-white dark:bg-zinc-900" 
                v-model="filter.name" 
                placeholder="Minimal 3 karakter..."
              />
              <small class="text-xs text-slate-500 dark:text-slate-400 mt-1 block">
                {{ filter.name.length }}/3 karakter minimum
              </small>
            </div>
            
            <div>
              <label for="phone_filter" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                <Icon name="lucide:phone" class="inline mr-2" size="0.9em" />
                Nomor HP
              </label>
              <InputText 
                id="phone_filter" 
                class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 focus:ring-blue-500 bg-white dark:bg-zinc-900" 
                v-model="filter.phone" 
                placeholder="Minimal 5 digit..."
              />
              <small class="text-xs text-slate-500 dark:text-slate-400 mt-1 block">
                {{ filter.phone.length }}/5 digit minimum
              </small>
            </div>
          </div>
        </div>
        
        <!-- Category Section -->
        <div class="p-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-zinc-800 dark:to-zinc-700 rounded-xl border border-green-200/50 dark:border-zinc-600/50">
          <h4 class="text-sm font-semibold text-green-700 dark:text-green-300 mb-3 flex items-center gap-2">
            <Icon name="lucide:building-2" size="0.9em" />
            Kategori
          </h4>
          
          <div>
            <label for="bank" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
              Jenis Konsumen
            </label>
            <Select 
              id="bank" 
              name="bank" 
              class="w-full modern-select" 
              v-model="filter.bank" 
              :options="banks" 
              optionLabel="name" 
              optionValue="name" 
              placeholder="Semua kategori..."
              showClear
            >
              <template #value="slotProps">
                <div v-if="slotProps.value" class="flex items-center gap-2">
                  <Icon name="lucide:tag" size="0.8em" class="text-slate-500 dark:text-slate-400" />
                  <span>{{ slotProps.value }}</span>
                </div>
                <span v-else class="text-slate-500">Semua kategori...</span>
              </template>
              <template #option="slotProps">
                <div class="flex items-center gap-2 p-2">
                  <Icon name="lucide:tag" size="0.8em" class="text-slate-500 dark:text-slate-400" />
                  <span>{{ slotProps.option.name }}</span>
                </div>
              </template>
            </Select>
          </div>
        </div>
        
        <!-- Date Range Section -->
        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-zinc-800 dark:to-zinc-700 rounded-xl border border-purple-200/50 dark:border-zinc-600/50">
          <h4 class="text-sm font-semibold text-purple-700 dark:text-purple-300 mb-3 flex items-center gap-2">
            <Icon name="lucide:calendar-range" size="0.9em" />
            Rentang Tanggal Daftar
          </h4>
          
          <div class="grid grid-cols-1 gap-3">
            <div>
              <label for="date_dari" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                <Icon name="lucide:calendar-days" class="inline mr-2" size="0.9em" />
                Dari Tanggal
              </label>
              <DatePicker 
                id="date_dari" 
                class="w-full modern-datepicker" 
                v-model="filter.dari" 
                placeholder="Pilih tanggal mulai..."
                showIcon
                dateFormat="dd/mm/yy"
              />
            </div>
            <div>
              <label for="date_sampai" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                <Icon name="lucide:calendar-check" class="inline mr-2" size="0.9em" />
                Sampai Tanggal
              </label>
              <DatePicker 
                id="date_sampai" 
                class="w-full modern-datepicker" 
                v-model="filter.sampai" 
                placeholder="Pilih tanggal akhir..."
                showIcon
                dateFormat="dd/mm/yy"
              />
            </div>
          </div>
          
          <div v-if="filter.dari || filter.sampai" class="mt-3 p-2 bg-white/60 dark:bg-zinc-700/60 rounded-lg">
            <div class="text-xs text-slate-600 dark:text-slate-400">
              <Icon name="lucide:info" size="0.8em" class="inline mr-1" />
              Rentang: 
              {{ filter.dari ? filter.dari.toLocaleDateString('id-ID') : '...' }} - 
              {{ filter.sampai ? filter.sampai.toLocaleDateString('id-ID') : '...' }}
            </div>
          </div>
        </div>
      </div>
      
      <!-- Action Section -->
      <div class="sticky bottom-0 pt-4 border-t border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 -mx-1 px-1">
        <div class="space-y-3">
          <!-- Filter Status -->
          <div v-if="isFilterActive" class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200/50 dark:border-blue-800/50">
            <div class="flex items-center gap-2 text-sm text-blue-700 dark:text-blue-300">
              <Icon name="lucide:filter-check" size="0.9em" />
              <span class="font-medium">Filter Aktif</span>
            </div>
            <div class="mt-2 space-y-1 text-xs text-blue-600 dark:text-blue-400">
              <div v-if="filter.name.length > 2" class="flex items-center gap-1">
                <Icon name="lucide:user" size="0.7em" />
                Nama: "{{ filter.name }}"
              </div>
              <div v-if="filter.phone.length > 4" class="flex items-center gap-1">
                <Icon name="lucide:phone" size="0.7em" />
                HP: "{{ filter.phone }}"
              </div>
              <div v-if="filter.bank" class="flex items-center gap-1">
                <Icon name="lucide:building-2" size="0.7em" />
                Kategori: {{ filter.bank }}
              </div>
              <div v-if="filter.dari || filter.sampai" class="flex items-center gap-1">
                <Icon name="lucide:calendar" size="0.7em" />
                Tanggal: {{ filter.dari ? filter.dari.toLocaleDateString('id-ID') : '...' }} - {{ filter.sampai ? filter.sampai.toLocaleDateString('id-ID') : '...' }}
              </div>
            </div>
          </div>
          
          <!-- Action Buttons -->
          <div class="grid grid-cols-2 gap-3">
            <Button 
              @click="resetFilter" 
              :disabled="!isFilterActive"
              class="px-4 py-3 bg-gradient-to-r from-slate-500 to-slate-600 hover:from-slate-600 hover:to-slate-700 disabled:from-slate-300 disabled:to-slate-400 text-white rounded-xl transition-all duration-200 flex items-center justify-center gap-2 font-medium"
            >
              <Icon name="lucide:x" size="1em" />
              Reset
            </Button>
            
            <Button 
              @click="filterVisible = false"
              class="px-4 py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl transition-all duration-200 flex items-center justify-center gap-2 font-medium"
            >
              <Icon name="lucide:check" size="1em" />
              Terapkan
            </Button>
          </div>
        </div>
      </div>
    </div>
  </Drawer>

  <!-- Modern Data Table -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 overflow-hidden">
    <div v-if="error" class="p-6 text-center">
      <Icon name="lucide:alert-circle" class="text-6xl text-red-500 mx-auto mb-4" />
      <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Error Loading Data</h3>
      <p class="text-slate-600 dark:text-slate-400">{{ error }}</p>
    </div>

    <div v-else-if="data?.data && data.data.length > 0">
      <DataTable :value="data.data" class="modern-table">
        <Column field="name" header="Konsumen" class="min-w-[150px]">
          <template #body="slotProps">
            <div 
              class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors" 
              @click="openDialog(slotProps.data, 'KonsumenDetail', 'Detail Konsumen', user)"
            >
              <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                <span class="text-white font-bold text-sm">{{ slotProps.data.name.charAt(0).toUpperCase() }}</span>
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-semibold text-slate-800 dark:text-white truncate" v-tooltip="slotProps.data.name">
                  {{ slotProps.data.name }}
                </div>
                <div class="flex items-center gap-2 mt-1">
                  <Badge 
                    :value="slotProps.data.meta?.find((meta: any) => meta.meta_key === 'bank')?.meta_value || 'Perorangan'" 
                    class="text-xs px-2 py-1 bg-slate-100 dark:bg-zinc-700 text-slate-600 dark:text-slate-300 rounded-full"
                  />
                </div>
              </div>
            </div>
          </template>
        </Column>
        
        <Column field="address" header="Alamat" class="min-w-[200px]">
          <template #body="slotProps">
            <div class="max-w-48 truncate text-slate-600 dark:text-slate-300" v-tooltip="slotProps.data.address">
              <Icon name="lucide:map-pin" class="inline mr-2" size="0.9em" />
              {{ slotProps.data.address || '-' }}
            </div>
          </template>
        </Column>
        
        <Column field="phone" header="Telepon" class="min-w-[180px]">
          <template #body="slotProps">
            <div class="space-y-1">
              <div class="flex items-center gap-1 flex-wrap">
                <Icon name="lucide:phone" class="text-slate-500 dark:text-slate-400" size="0.9em" />
                
                <!-- Dapatkan semua nomor telepon -->
                <template v-for="(phoneItem, idx) in getCustomerPhones(slotProps.data)" :key="idx">
                  <Badge 
                    :class="phoneItem.is_primary 
                      ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white' 
                      : 'bg-slate-100 dark:bg-zinc-700 text-slate-600 dark:text-slate-300'"
                    class="text-xs px-2 py-1 rounded-full mr-1 mb-1"
                  >
                    {{ phoneItem.phone }}
                    <Icon v-if="phoneItem.is_primary" name="lucide:star" size="0.7em" class="ml-1" />
                  </Badge>
                </template>
                
                <span v-if="getCustomerPhones(slotProps.data).length === 0" class="text-slate-500 dark:text-slate-400">-</span>
              </div>
            </div>
          </template>
        </Column>
        
        <Column field="order_count" header="Order" class="min-w-[120px]">
          <template #body="slotProps">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <Icon name="lucide:shopping-cart" size="0.9em" class="text-blue-600 dark:text-blue-400" />
              </div>
              <span class="font-semibold text-slate-800 dark:text-white">{{ slotProps.data.order_count || 0 }}</span>
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
                  'p-2 rounded-lg transition-colors relative focus:outline-none focus:ring-2 focus:ring-blue-500',
                  activeActionMenu === slotProps.data.id
                    ? 'bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50'
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
                      ? 'text-blue-600 dark:text-blue-400'
                      : 'text-slate-600 dark:text-slate-300'
                  ]"
                />
                <!-- Active indicator -->
                <div 
                  v-if="activeActionMenu === slotProps.data.id"
                  class="absolute -top-1 -right-1 w-2 h-2 bg-blue-500 rounded-full animate-pulse shadow-lg"
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
                    class="fixed z-[999999] min-w-[220px] backdrop-blur-xl bg-white/95 dark:bg-zinc-900/95 border border-slate-200/50 dark:border-zinc-700/50 rounded-xl shadow-xl shadow-slate-900/10 dark:shadow-zinc-900/20 overflow-hidden action-menu-content"
                    @click.stop
                  >
                  <!-- Header Info -->
                  <div class="p-3 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-zinc-800 dark:to-zinc-700 border-b border-slate-200/50 dark:border-zinc-600/50">
                    <div class="flex items-center gap-2">
                      <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <span class="text-white font-bold text-xs">{{ slotProps.data.name.charAt(0).toUpperCase() }}</span>
                      </div>
                      <div class="flex-1 min-w-0">
                        <div class="font-medium text-slate-800 dark:text-white text-sm truncate">{{ slotProps.data.name }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">ID: {{ slotProps.data.id }}</div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Action Menu Items -->
                  <div class="p-1">
                    <button
                      @click="() => { openDialog(slotProps.data, 'KonsumenDetail', 'Detail Konsumen', user); closeActionMenu(); }"
                      class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors group action-menu-item"
                      @keydown.enter="() => { openDialog(slotProps.data, 'KonsumenDetail', 'Detail Konsumen', user); closeActionMenu(); }"
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
                      @click="() => { openDialog(slotProps.data, 'KonsumenEdit', 'Edit Konsumen', user); closeActionMenu(); }"
                      class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors group action-menu-item"
                      @keydown.enter="() => { openDialog(slotProps.data, 'KonsumenEdit', 'Edit Konsumen', user); closeActionMenu(); }"
                      tabindex="0"
                    >
                      <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors">
                        <Icon name="lucide:pencil" size="0.9em" class="text-blue-600 dark:text-blue-400" />
                      </div>
                      <div class="flex-1">
                        <div class="font-medium text-slate-800 dark:text-white text-sm">Edit Konsumen</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Ubah data konsumen</div>
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
                        <div class="font-medium text-slate-800 dark:text-white text-sm">Hapus Konsumen</div>
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
    
    <div v-else class="p-12 text-center">
      <Icon name="lucide:users-x" class="text-6xl text-slate-400 dark:text-slate-500 mx-auto mb-4" />
      <h3 class="text-xl font-semibold text-slate-600 dark:text-slate-400 mb-2">Tidak Ada Konsumen</h3>
      <p class="text-slate-500 dark:text-slate-500 mb-6">Belum ada data konsumen yang tersedia</p>
      <Button 
        @click="openDialog('', 'KonsumenEdit', 'Tambah Konsumen', user)"
        class="px-6 py-2 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl shadow-lg transition-all duration-200"
      >
        <Icon name="lucide:plus" class="mr-2" />
        Tambah Konsumen Pertama
      </Button>
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
    <KonsumenDetail 
      v-if="modalKomponen === 'KonsumenDetail'" 
      :datas="modalData" 
      @goToEdit="openDialog(modalData, 'KonsumenEdit', 'Edit Konsumen', user)" 
      @closeDialog="visible = false" 
    />
    <KonsumenEdit 
      v-if="modalKomponen === 'KonsumenEdit'" 
      :datas="modalData" 
      @error="onError" 
      @goToDetail="openDialog(modalData, 'KonsumenDetail', 'Detail Konsumen', user)" 
      @updateData="onUpdateData" 
      @addData="onAddData" 
      @closeDialog="visible = false"
    />
  </Dialog>

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
          Data konsumen ini akan dihapus secara permanen dan tidak dapat dikembalikan.
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
  
  <!-- Modern Pagination -->
  <div class="mt-6">
    <Pagination :value="data" :page="page" @pageChange="onPageChange" />
  </div>
</template>


<script setup lang="ts">
definePageMeta({ title: 'List Konsumen' })

const route = useRoute()
const client = useSanctumClient()
const user = useSanctumUser()
const toast = useToast()

const page = ref(route.query.page ? Number(route.query.page) : 1)
const filterVisible = ref(false)
const visible = ref(false)
const showDeleteConfirm = ref(false)
const deleteItemId = ref<number | null>(null)
const modalKomponen = ref('')
const modalData = ref<any>({})
const isLoading = ref<any>({})
const banks = ref<any[]>([])
const activeActionMenu = ref<number | null>(null)

const filter = ref({
  name: '',
  phone: '',
  bank: '',
  dari: null as Date | null,
  sampai: null as Date | null
})

const isFilterActive = computed(() =>
  filter.value.name.length > 2 ||
  filter.value.phone.length > 4 ||
  filter.value.bank ||
  filter.value.dari ||
  filter.value.sampai
)

// Helper function to get all customer phones
const getCustomerPhones = (customer: any) => {
  const phones = [];
  
  // Add primary phone
  if (customer.phone) {
    phones.push({
      phone: customer.phone,
      is_primary: true,
    });
  }
  
  // Add phones from customer.phones if available
  if (customer.phones && Array.isArray(customer.phones)) {
    customer.phones.forEach((p: any) => {
      // Avoid duplicate primary
      if (!p.is_primary) {
        phones.push(p);
      }
    });
  } else {
    // Fallback to customer.meta for additional phones
    if (customer.meta && Array.isArray(customer.meta)) {
      customer.meta.forEach((meta: any) => {
        if (meta.meta_key && meta.meta_key.startsWith('phone_') && meta.meta_value) {
          phones.push({
            phone: meta.meta_value,
            is_primary: false,
          });
        }
      });
    }
  }
  
  return phones;
}

async function fetchCustomers() {
  const query = new URLSearchParams()
  if (filter.value.name.length > 2) query.append('name', filter.value.name)
  if (filter.value.phone.length > 4) query.append('phone', filter.value.phone)
  if (filter.value.bank) query.append('bank', filter.value.bank)
  if (filter.value.dari) query.append('dari', filter.value.dari.toISOString().slice(0, 10))
  if (filter.value.sampai) query.append('sampai', filter.value.sampai.toISOString().slice(0, 10))

  return client(`/api/customers?page=${page.value}&${query.toString()}`)
}

const { data, error, refresh } = await useAsyncData('customers', fetchCustomers)

onMounted(async () => {
  const responseBank = await client('/api/settings/banks')
  banks.value = [{ name: 'Perorangan' }, ...responseBank]
})

watch(filter, () => {
  if (isFilterActive.value) refresh()
}, { deep: true })

// Debug watcher for action menu
watch(activeActionMenu, (newValue, oldValue) => {
  console.log('Action menu changed:', { from: oldValue, to: newValue })
})

const openDialog = (data: any, komponen: string, title: string, user: any) => {
  modalData.value = { ...(typeof data === 'object' ? data : {}), title, user }
  modalKomponen.value = komponen
  visible.value = true
}

// Action menu functions
const toggleActionMenu = (id: number) => {
  console.log('Toggle action menu for ID:', id) // Debug log
  
  if (activeActionMenu.value === id) {
    activeActionMenu.value = null
  } else {
    activeActionMenu.value = id
  }
}

const closeActionMenu = () => {
  console.log('Closing action menu') // Debug log
  activeActionMenu.value = null
}

// Function to calculate fixed action menu position
const getFixedActionMenuPosition = (id: number) => {
  // Find the button element
  const button = document.querySelector(`[value-action-button="${id}"]`) as HTMLElement
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

// Handle keyboard navigation
const handleKeydown = (event: KeyboardEvent) => {
  if (event.key === 'Escape') {
    closeActionMenu()
  }
}

// Close action menu when clicking outside
const handleDocumentClick = (event: Event) => {
  const target = event.target as HTMLElement
  // Don't close if clicking on action button or menu content
  if (!target.closest('[data-action-button]') && !target.closest('.action-menu-content')) {
    closeActionMenu()
  }
}

onMounted(() => {
  document.addEventListener('click', handleDocumentClick)
  document.addEventListener('keydown', handleKeydown)
  // Also close when scrolling
  document.addEventListener('scroll', closeActionMenu, true)
})

onUnmounted(() => {
  document.removeEventListener('click', handleDocumentClick)
  document.removeEventListener('keydown', handleKeydown)
  document.removeEventListener('scroll', closeActionMenu, true)
})

const onError = (error: string) => {
  toast.add({ severity: 'error', summary: 'Error', detail: error, life: 3000 })
}

const onAddData = (newData: any) => {
  visible.value = false
  modalData.value = newData
  if (!data.value) data.value = { data: [] }
  data.value.data.unshift(newData)
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Tambah data berhasil', life: 3000 })
  openDialog(newData, 'KonsumenDetail', 'Detail Konsumen', user)
}

const onUpdateData = (updated: any) => {
  visible.value = false
  modalData.value = updated
  data.value.data = data.value.data.map((item: any) => item.id === updated.id ? updated : item)
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Edit data berhasil', life: 3000 })
  openDialog(updated, 'KonsumenDetail', 'Detail Konsumen', user)
}

const resetFilter = () => {
  filter.value = {
    name: '',
    phone: '',
    bank: '',
    dari: null,
    sampai: null
  }
  filterVisible.value = false
  refresh()
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
    await client(`/api/customers/${id}`, { method: 'DELETE' });
    isLoading.value[id] = false;
    toast.add({ severity: 'success', summary: 'Sukses', detail: 'Konsumen berhasil dihapus!', life: 3000 });
    refresh();
  } catch (error: any) {
    isLoading.value[id] = false;
    console.error('Delete error:', error);
    toast.add({ 
      severity: 'error', 
      summary: 'Error', 
      detail: error?.data?.message || 'Gagal menghapus konsumen!', 
      life: 3000 
    });
  }
  
  deleteItemId.value = null;
}

const onPageChange = (event: { page: number }) => {
  page.value = event.page + 1
  navigateTo(`/konsumen?page=${page.value}`)
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
  overflow: visible !important;
  position: relative !important;
}

:deep(.modern-table .p-datatable-table) {
  overflow: visible !important;
  position: relative !important;
}

:deep(.modern-table .p-datatable-tbody) {
  overflow: visible !important;
}

:deep(.modern-table .p-datatable-tbody > tr) {
  overflow: visible !important;
}

:deep(.modern-table .p-datatable-tbody > tr > td) {
  overflow: visible !important;
}

:deep(.modern-table .p-datatable-thead > tr > th) {
  background: rgb(248 250 252 / 1);
  color: rgb(51 65 85 / 1);
  font-weight: 600;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border-bottom: 1px solid rgb(226 232 240 / 1);
  padding: 1rem 1.5rem;
}

:deep(.dark .modern-table .p-datatable-thead > tr > th) {
  background: rgb(39 39 42 / 0.5);
  color: rgb(203 213 225 / 1);
  border-bottom: 1px solid rgb(63 63 70 / 1);
}

:deep(.modern-table .p-datatable-tbody > tr > td) {
  border-bottom: 1px solid rgb(241 245 249 / 0.2);
  padding: 0.5rem;
  color: rgb(71 85 105 / 1);
}

:deep(.dark .modern-table .p-datatable-tbody > tr > td) {
  border-bottom: 1px solid rgb(39 39 42 / 1);
  color: rgb(203 213 225 / 1);
}

:deep(.modern-table .p-datatable-tbody > tr:hover) {
  background: rgb(248 250 252 / 0.5);
}

:deep(.dark .modern-table .p-datatable-tbody > tr:hover) {
  background: rgb(39 39 42 / 0.3);
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
  background: linear-gradient(to right, rgb(239 246 255), rgb(250 245 255));
  border-bottom: 1px solid rgb(226 232 240 / 0.5);
  border-radius: 1rem 1rem 0 0;
}

:deep(.dark .modern-dialog .p-dialog-header) {
  background: linear-gradient(to right, rgb(39 39 42), rgb(63 63 70));
  border-bottom: 1px solid rgb(63 63 70 / 0.5);
}

:deep(.modern-dialog .p-dialog-title) {
  color: rgb(30 41 59);
  font-weight: 600;
}

:deep(.dark .modern-dialog .p-dialog-title) {
  color: rgb(248 250 252);
}

/* Modern Drawer Styling */
:deep(.modern-drawer .p-drawer) {
  width: 100% !important;
  max-width: 420px !important;
  backdrop-filter: blur(16px);
  background: rgb(255 255 255 / 0.95);
  border-left: 1px solid rgb(226 232 240 / 0.5);
  box-shadow: -10px 0 25px -5px rgb(0 0 0 / 0.1);
}

:deep(.dark .modern-drawer .p-drawer) {
  background: rgb(24 24 27 / 0.95);
  border-left: 1px solid rgb(63 63 70 / 0.5);
}

:deep(.modern-drawer .p-drawer-header) {
  background: linear-gradient(135deg, rgb(239 246 255), rgb(250 245 255));
  border-bottom: 1px solid rgb(226 232 240 / 0.5);
  padding: 1.5rem;
}

:deep(.dark .modern-drawer .p-drawer-header) {
  background: linear-gradient(135deg, rgb(39 39 42), rgb(63 63 70));
  border-bottom: 1px solid rgb(63 63 70 / 0.5);
}

:deep(.modern-drawer .p-drawer-content) {
  padding: 0;
  background: transparent;
}

/* Modern Select Styling */
:deep(.modern-select .p-select) {
  border-radius: 0.75rem;
  border: 1px solid rgb(203 213 225);
  background: white;
}

:deep(.dark .modern-select .p-select) {
  border: 1px solid rgb(63 63 70);
  background: rgb(24 24 27);
}

:deep(.modern-select .p-select:focus) {
  border-color: rgb(59 130 246);
  box-shadow: 0 0 0 3px rgb(59 130 246 / 0.1);
}

/* Modern DatePicker Styling */
:deep(.modern-datepicker .p-datepicker-input) {
  border-radius: 0.75rem;
  border: 1px solid rgb(203 213 225);
  background: white;
  padding: 0.75rem 1rem;
}

:deep(.dark .modern-datepicker .p-datepicker-input) {
  border: 1px solid rgb(63 63 70);
  background: rgb(24 24 27);
  color: rgb(248 250 252);
}

:deep(.modern-datepicker .p-datepicker-input:focus) {
  border-color: rgb(59 130 246);
  box-shadow: 0 0 0 3px rgb(59 130 246 / 0.1);
}

/* Filter Progress Indicator */
.filter-progress {
  height: 2px;
  background: linear-gradient(90deg, rgb(59 130 246), rgb(147 51 234));
  border-radius: 1px;
  transition: width 0.3s ease;
}

/* Action Menu Animation and Positioning */
:deep(.action-menu-enter-active),
:deep(.action-menu-leave-active) {
  transition: all 0.15s ease;
}

:deep(.action-menu-enter-from),
:deep(.action-menu-leave-to) {
  opacity: 0;
  transform: translateY(-8px) scale(0.95);
}

/* Ensure action menu is always visible */
.action-menu-content {
  position: absolute !important;
  z-index: 999999 !important;
  right: 100% !important;
  margin-right: 8px !important;
  background: white !important;
  border: 1px solid #e1e5e9 !important;
  border-radius: 12px !important;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
  min-width: 220px !important;
}

.dark .action-menu-content {
  background: rgb(24 24 27 / 0.95) !important;
  border: 1px solid rgb(63 63 70 / 0.5) !important;
}

.action-menu-container {
  position: relative !important;
  z-index: 1000000 !important;
}

/* Responsive positioning for action menu */
@media (max-width: 768px) {
  .action-menu-content {
    right: 0 !important;
    top: 100% !important;
    margin-right: 0 !important;
    margin-top: 8px !important;
    min-width: 200px !important;
  }
}

/* Ensure menu doesn't go outside viewport */
.action-menu-content {
  max-width: calc(100vw - 20px);
}
.action-menu-content::-webkit-scrollbar {
  width: 4px;
}

.action-menu-content::-webkit-scrollbar-track {
  background: transparent;
}

.action-menu-content::-webkit-scrollbar-thumb {
  background: rgb(203 213 225 / 0.5);
  border-radius: 2px;
}

.dark .action-menu-content::-webkit-scrollbar-thumb {
  background: rgb(63 63 70 / 0.5);
}

/* Hover effect for action menu items */
.action-menu-item {
  transition: all 0.15s ease;
}

.action-menu-item:hover {
  transform: translateX(2px);
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
