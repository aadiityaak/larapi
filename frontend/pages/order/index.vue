<template>
  <!-- Header Section with Modern Styling -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-3 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex justify-between items-center gap-4">
      <div class="flex items-center gap-4">
        <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
          <Icon name="lucide:shopping-cart" class="w-6 h-6 text-white" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Order</h1>
          <p class="text-sm text-slate-500 dark:text-slate-400">Kelola data pesanan</p>
        </div>
      </div>
      
      <div class="flex items-center gap-3">
        <Button 
          @click="openDialog({}, 'OrderEdit', 'Tambah Order Baru', user)" 
          class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 h-10"
        >
          <Icon name="lucide:plus" size="1.1em" />
          <span class="hidden sm:inline font-medium">Tambah Order</span>
        </Button>
        
        <Button 
          @click="exportToExcel" 
          class="px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 h-10"
        >
          <Icon name="lucide:file-text" size="1.1em" />
          <span class="hidden sm:inline font-medium">Export Excel</span>
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
    
    <!-- Status Filter Tabs -->
    <div v-if="!filter.name" class="mt-4 pt-4 border-t border-slate-200/50 dark:border-zinc-700/50">
      <div class="flex gap-2 bg-slate-50 dark:bg-zinc-800/50 p-1 rounded-xl mx-6">
        <Button 
          :class="[
            'relative flex-1 px-4 py-2 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 status-tab-button',
            route.query.status === 'Masuk' 
              ? 'status-tab-active status-tab-masuk' 
              : 'status-tab-inactive'
          ]"
          @click="filterArchive('Masuk')"
        >
          <Icon 
            name="lucide:clock" 
            size="1em" 
            :class="route.query.status === 'Masuk' ? 'animate-pulse' : ''"
          />
          <span class="font-medium">Masuk</span>
          <!-- Active indicator -->
          <div 
            v-if="route.query.status === 'Masuk'"
            class="absolute top-1 right-1 w-2 h-2 bg-white rounded-full shadow-md animate-bounce"
          ></div>
        </Button>

        <Button 
          :class="[
            'relative flex-1 px-4 py-2 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 status-tab-button',
            route.query.status === 'Arsip' 
              ? 'status-tab-active status-tab-arsip' 
              : 'status-tab-inactive'
          ]"
          @click="filterArchive('Arsip')"
        >
          <Icon 
            name="lucide:loader-circle" 
            size="1em" 
            :class="route.query.status === 'Arsip' ? 'animate-spin' : ''"
          />
          <span class="font-medium">Arsip</span>
          <!-- Active indicator -->
          <div 
            v-if="route.query.status === 'Arsip'"
            class="absolute top-1 right-1 w-2 h-2 bg-white rounded-full shadow-md animate-bounce"
          ></div>
        </Button>

        <Button 
          :class="[
            'relative flex-1 px-4 py-2 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 status-tab-button',
            route.query.status === 'Selesai' 
              ? 'status-tab-active status-tab-selesai' 
              : 'status-tab-inactive'
          ]"
          @click="filterArchive('Selesai')"
        >
          <Icon 
            name="lucide:check" 
            size="1em" 
            :class="route.query.status === 'Selesai' ? 'animate-pulse' : ''"
          />
          <span class="font-medium">Selesai</span>
          <!-- Active indicator -->
          <div 
            v-if="route.query.status === 'Selesai'"
            class="absolute top-1 right-1 w-2 h-2 bg-white rounded-full shadow-md animate-bounce"
          ></div>
        </Button>
      </div>
      
      <!-- Active Tab Info with Reset Button -->
      <div class="mt-3 flex items-center justify-between">
        <div class="flex-1 flex justify-center">
          <div v-if="route.query.status" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/70 dark:bg-zinc-700/70 rounded-full backdrop-blur-sm border border-slate-200/30 dark:border-zinc-600/30">
            <div class="flex items-center gap-1">
              <div 
                :class="[
                  'w-2 h-2 rounded-full animate-pulse',
                  route.query.status === 'Masuk' ? 'bg-gradient-to-r from-green-500 to-emerald-600' :
                  route.query.status === 'Arsip' ? 'bg-gradient-to-r from-amber-500 to-orange-600' :
                  route.query.status === 'Selesai' ? 'bg-gradient-to-r from-blue-500 to-purple-600' :
                  'bg-gradient-to-r from-slate-500 to-slate-600'
                ]"
              ></div>
              <span class="text-xs font-medium text-slate-600 dark:text-slate-300">
                Status aktif: 
                <span class="font-semibold text-slate-800 dark:text-white">
                  {{ route.query.status === 'Masuk' ? 'Masuk' :
                     route.query.status === 'Arsip' ? 'Arsip' : 
                     route.query.status === 'Selesai' ? 'Selesai' : route.query.status }}
                </span>
              </span>
            </div>
          </div>
        </div>
        
        <!-- Reset Filter Button -->
        <div v-if="route.query.status" class="flex items-center">
          <Button 
            @click="resetStatusFilter"
            class="px-3 py-1.5 bg-gradient-to-r from-slate-500 to-slate-600 hover:from-slate-600 hover:to-slate-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-xs"
            size="small"
          >
            <Icon name="lucide:x" size="0.8em" />
            <span class="font-medium">Reset</span>
          </Button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modern Filter Drawer -->
  <Drawer v-model:visible="filterVisible" position="right" class="modern-drawer">
    <template #header>
      <div class="flex items-center gap-3">
        <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg shadow-lg">
          <Icon name="lucide:filter" class="w-5 h-5 text-white" />
        </div>
        <div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Filter Order</h3>
          <p class="text-sm text-slate-500 dark:text-slate-400">Cari order berdasarkan kriteria</p>
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
              <label for="product_filter" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                <Icon name="lucide:package" class="inline mr-2" size="0.9em" />
                Nama Produk
              </label>
              <InputText 
                id="product_filter" 
                class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 focus:ring-blue-500 bg-white dark:bg-zinc-900" 
                v-model="filter.product" 
                placeholder="Minimal 3 karakter..."
              />
              <small class="text-xs text-slate-500 dark:text-slate-400 mt-1 block">
                {{ filter.product.length }}/3 karakter minimum
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
            />
          </div>
        </div>
        
        <!-- Date Range Section -->
        <div class="p-4 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-zinc-800 dark:to-zinc-700 rounded-xl border border-purple-200/50 dark:border-zinc-600/50">
          <h4 class="text-sm font-semibold text-purple-700 dark:text-purple-300 mb-3 flex items-center gap-2">
            <Icon name="lucide:calendar-range" size="0.9em" />
            Rentang Tanggal Order
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
      
      <!-- Filter Status -->
      <div v-if="isFilterActive" class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200/50 dark:border-blue-800/50">
        <div class="flex items-center gap-2 text-sm text-blue-700 dark:text-blue-300">
          <Icon name="lucide:filter-check" size="0.9em" />
          <span class="font-medium">Filter Aktif</span>
        </div>
        <div class="mt-2 space-y-1 text-xs text-blue-600 dark:text-blue-400">
          <div v-if="filter.name.length > 2" class="flex items-center gap-1">
            <Icon name="lucide:user" size="0.7em" />
            Konsumen: "{{ filter.name }}"
          </div>
          <div v-if="filter.product.length > 2" class="flex items-center gap-1">
            <Icon name="lucide:package" size="0.7em" />
            Produk: "{{ filter.product }}"
          </div>
          <div v-if="filter.bank" class="flex items-center gap-1">
            <Icon name="lucide:building-2" size="0.7em" />
            Kategori: {{ filter.bank }}
          </div>
          <div v-if="filter.dari || filter.sampai" class="flex items-center gap-1">
            <Icon name="lucide:calendar" size="0.7em" />
            Periode order
          </div>
        </div>
      </div>
      
      <!-- Action Buttons -->
      <div class="sticky bottom-0 pt-4 border-t border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 -mx-1 px-1">
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
            class="px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl transition-all duration-200 flex items-center justify-center gap-2 font-medium"
          >
            <Icon name="lucide:check" size="1em" />
            Terapkan
          </Button>
        </div>
      </div>
    </div>
  </Drawer>
  <!-- Modern Data Table -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 overflow-hidden relative">
    <div v-if="data?.data && data.data.length > 0">
      <DataTable :value="data.data" class="modern-table" :scrollable="true" scrollHeight="calc(100vh - 300px)">
        <Column header="Order ID" class="min-w-[120px]">
          <template #body="slotProps">
            <div 
              class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors" 
              @click="openDialog(slotProps.data, 'OrderDetail', 'Detail Order', user)"
            >
              <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg">
                <Icon name="lucide:hash" class="w-5 h-5 text-white" />
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-semibold text-slate-800 dark:text-white">
                  #{{ slotProps.data.no_order }}
                </div>
                <div class="text-sm text-slate-500 dark:text-slate-400">
                  <FormatDate :value="slotProps.data.created_at" />
                </div>
              </div>
            </div>
          </template>
        </Column>
        
        <Column header="Konsumen" class="w-full max-w-[150px]">
          <template #body="slotProps">
            <div
              v-if="slotProps.data.customer"
              class="flex items-center gap-3 p-2 cursor-pointer rounded-lg hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors"
              @click="openCustomerDialog(slotProps.data.customer)"
            >
              <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                <span class="text-white font-bold text-sm">{{ slotProps.data.customer.name?.charAt(0)?.toUpperCase() || '?' }}</span>
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-semibold text-slate-800 dark:text-white truncate hover:text-blue-600 dark:hover:text-blue-400 transition-colors" v-tooltip="slotProps.data.customer.name || 'Unknown Customer'">
                  {{ slotProps.data.customer.name || 'Unknown Customer' }}
                </div>
                <div class="flex items-center gap-2 mt-1">
                  <Badge
                    :value="slotProps.data.customer.meta?.find((meta: any) => meta.meta_key === 'bank')?.meta_value || 'Perorangan'"
                    class="text-xs px-2 py-1 bg-slate-100 dark:bg-zinc-700 text-slate-600 dark:text-slate-300 rounded-full"
                  />
                </div>
              </div>
            </div>
            <div v-else class="p-2 text-slate-500 dark:text-slate-400">
              No customer data
            </div>
          </template>
        </Column>
        
        <Column header="Relasi" class="w-full max-w-[200px]">
          <template #body="slotProps">
            <div class="p-2">
              <!-- Parent related order -->
              <div v-if="slotProps.data.related_order" class="mb-2">
                <div class="flex items-center gap-2">
                  <Icon name="lucide:arrow-left" class="text-orange-500" size="0.8em" />
                  <div class="flex-1 min-w-0">
                    <div class="font-semibold text-slate-800 dark:text-white text-sm truncate">
                      #{{ slotProps.data.related_order.no_order }}
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 truncate">
                      {{ slotProps.data.related_order.customer?.name }}
                    </div>
                  </div>
                  <Badge class="bg-orange-500 text-white text-[10px] px-2 py-0.5 rounded-full">
                    {{ getRelationTypeLabel(slotProps.data.relation_type) }}
                  </Badge>
                </div>
              </div>
              
              <!-- Child related orders -->
              <div v-if="slotProps.data.related_orders && slotProps.data.related_orders.length > 0" class="space-y-1">
                <div v-for="relatedOrder in slotProps.data.related_orders" :key="relatedOrder.id" class="flex items-center gap-2">
                  <Icon name="lucide:arrow-right" class="text-blue-500" size="0.8em" />
                  <div class="flex-1 min-w-0">
                    <div class="font-semibold text-slate-800 dark:text-white text-sm truncate">
                      #{{ relatedOrder.no_order }}
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 truncate">
                      {{ relatedOrder.customer?.name }}
                    </div>
                  </div>
                  <Badge class="bg-blue-500 text-white text-[10px] px-2 py-0.5 rounded-full">
                    {{ getRelationTypeLabel(relatedOrder.relation_type) }}
                  </Badge>
                </div>
              </div>
              
              <!-- No relation -->
              <div v-if="!slotProps.data.related_order && (!slotProps.data.related_orders || slotProps.data.related_orders.length === 0)" class="text-sm text-slate-500 dark:text-slate-400">
                -
              </div>
            </div>
          </template>
        </Column>
        
        <Column header="Produk" class="w-full max-w-[180px]">
          <template #body="slotProps">
            <div class="flex items-center gap-3 p-2">
              <div
                class="font-medium text-slate-800 dark:text-white truncate"
                v-tooltip="getOrderProductNames(slotProps.data) || 'Unknown Product'"
              >
                {{ getOrderProductNames(slotProps.data) || "Unknown Product" }}
              </div>
            </div>
          </template>
        </Column>
        
        <Column header="Jobdesk" class="w-full max-w-[220px]">
          <template #body="{ data }">
            <div
              class="flex items-center gap-3 cursor-pointer p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors"
              @click="goToJobdesk(data.id, getLastJobdesk(data).status || '')"
            >
              <div class="flex-1 min-w-0">
                <div class="font-medium text-slate-800 dark:text-white truncate">
                  {{ getLastJobdesk(data).description }}
                </div>
                <div class="flex items-center gap-2 mt-1">
                  <Badge
                    v-if="getLastJobdesk(data).status"
                    :severity="getBadgeSeverity(getLastJobdesk(data).status || '')"
                    size="small"
                    class="text-xs"
                  >
                    {{ getLastJobdesk(data).status }}
                  </Badge>
                </div>
              </div>
            </div>
          </template>
        </Column>
        
        <Column v-if="user.capabilities.includes('access:keuangan')" header="Harga" class="w-full max-w-[140px]">
          <template #body="slotProps">
            <div class="flex items-center gap-2 p-2">
              <div class="font-semibold text-slate-800 dark:text-white">
                <FormatRupiah :value="slotProps.data.price" />
              </div>
            </div>
          </template>
        </Column>
        
        <Column header="Progress" class="w-full max-w-[200px]">
          <template #body="slotProps">
            <div class="p-2">
              <div v-if="slotProps.data?.jobdesks.length > 0" class="space-y-2">
                <div class="flex items-center justify-between text-sm">
                  <span class="text-slate-600 dark:text-slate-400">Selesai</span>
                  <span class="font-medium text-slate-800 dark:text-white">
                    {{ slotProps.data.jobdesks.filter((jobdesk: any) => jobdesk.status === 'Selesai').length }}/{{ slotProps.data.jobdesks.length }}
                  </span>
                </div>
                <ProgressBar 
                  :value="Math.round((slotProps.data.jobdesks.filter((jobdesk: any) => jobdesk.status === 'Selesai').length / slotProps.data.jobdesks.length) * 100)"
                  class="h-2"
                />
              </div>
              <div v-else class="text-sm text-slate-500 dark:text-slate-400">
                Belum ada jobdesk
              </div>
            </div>
          </template>
        </Column>
        
        <Column header="" class="w-[80px] sticky-action-column">
          <template #body="slotProps">
            <div class="relative">
              <button
                @click.stop="toggleActionMenu(slotProps.data.id)"
                :data-action-button="slotProps.data.id"
                :ref="`actionButton-${slotProps.data.id}`"
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
                      <div class="w-6 h-6 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                        <Icon name="lucide:hash" class="w-3 h-3 text-white" />
                      </div>
                      <div class="flex-1 min-w-0">
                        <div class="font-medium text-slate-800 dark:text-white text-sm truncate">#{{ slotProps.data.no_order }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ slotProps.data.customer.name }}</div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- Action Menu Items -->
                  <div class="p-1">
                    <button
                      @click="() => { openDialog(slotProps.data, 'OrderDetail', '#' + slotProps.data.no_order, user); closeActionMenu(); }"
                      class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors group action-menu-item"
                      @keydown.enter="() => { openDialog(slotProps.data, 'OrderDetail', '#' + slotProps.data.no_order, user); closeActionMenu(); }"
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
                      @click="() => { printOrderBackend(slotProps.data); closeActionMenu(); }"
                      class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors group action-menu-item"
                      @keydown.enter="() => { printOrderBackend(slotProps.data); closeActionMenu(); }"
                      tabindex="0"
                    >
                      <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center group-hover:bg-amber-200 dark:group-hover:bg-amber-900/50 transition-colors">
                        <Icon name="lucide:printer" size="0.9em" class="text-amber-600 dark:text-amber-400" />
                      </div>
                      <div class="flex-1">
                        <div class="font-medium text-slate-800 dark:text-white text-sm" @click.stop="() => { printOrderBackend(slotProps.data); closeActionMenu(); }">Cetak</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Cetak lembar order</div>
                      </div>
                    </button>

                    <button
                      v-if="slotProps.data.jobdesks.filter((jobdesk: any) => jobdesk.status === 'Selesai').length !== slotProps.data.jobdesks.length || slotProps.data.jobdesks.length === 0"
                      @click="() => { openDialog(slotProps.data, 'OrderEdit', 'Edit Order', user); closeActionMenu(); }"
                      class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors group action-menu-item"
                      @keydown.enter="() => { openDialog(slotProps.data, 'OrderEdit', 'Edit Order', user); closeActionMenu(); }"
                      tabindex="0"
                    >
                      <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors">
                        <Icon name="lucide:pencil" size="0.9em" class="text-blue-600 dark:text-blue-400" />
                      </div>
                      <div class="flex-1">
                        <div class="font-medium text-slate-800 dark:text-white text-sm">Edit Order</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Ubah data order</div>
                      </div>
                    </button>
                    
                    <button
                      v-else-if="(!slotProps.data.lampiran || slotProps.data.lampiran.length === 0 || Number(user.is_admin) === 1) && slotProps.data.jobdesks.filter((jobdesk: any) => jobdesk.status === 'Selesai').length === slotProps.data.jobdesks.length && slotProps.data.jobdesks.length > 0"
                      @click="() => { openDialog(slotProps.data, 'EditDokumen', 'Edit Dokumen', user); closeActionMenu(); }"
                      class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-colors group action-menu-item"
                      @keydown.enter="() => { openDialog(slotProps.data, 'EditDokumen', 'Edit Dokumen', user); closeActionMenu(); }"
                      tabindex="0"
                    >
                      <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center group-hover:bg-purple-200 dark:group-hover:bg-purple-900/50 transition-colors">
                        <Icon name="lucide:upload" size="0.9em" class="text-purple-600 dark:text-purple-400" />
                      </div>
                      <div class="flex-1">
                        <div class="font-medium text-slate-800 dark:text-white text-sm">Upload Dokumen</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Upload dokumen order</div>
                      </div>
                    </button>
                    
                    <div v-if="Number(user.is_admin) === 1" class="my-1 h-px bg-slate-200 dark:bg-zinc-700"></div>
                    
                    <button
                      v-if="isLoading[slotProps.data.id] && Number(user.is_admin) === 1"
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
                      v-else-if="Number(user.is_admin) === 1"
                      @click="() => { deleteData(slotProps.data.id); closeActionMenu(); }"
                      class="w-full flex items-center gap-3 px-3 py-2.5 text-left hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors group action-menu-item"
                      @keydown.enter="() => { deleteData(slotProps.data.id); closeActionMenu(); }"
                      tabindex="0"
                    >
                      <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center group-hover:bg-red-200 dark:group-hover:bg-red-900/50 transition-colors">
                        <Icon name="lucide:trash" size="0.9em" class="text-red-600 dark:text-red-400" />
                      </div>
                      <div class="flex-1">
                        <div class="font-medium text-slate-800 dark:text-white text-sm">Hapus Order</div>
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
      <Icon name="lucide:shopping-cart-x" class="text-6xl text-slate-400 dark:text-slate-500 mx-auto mb-4" />
      <h3 class="text-xl font-semibold text-slate-600 dark:text-slate-400 mb-2">Tidak Ada Order</h3>
      <p class="text-slate-500 dark:text-slate-500 mb-6">Belum ada data order yang tersedia</p>
      <Button 
        @click="openDialog({}, 'OrderEdit', 'Tambah Order Baru', user)"
        class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg transition-all duration-200"
      >
        <Icon name="lucide:plus" class="mr-2" />
        Tambah Order Pertama
      </Button>
    </div>
  </div>

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
          Data order ini akan dihapus secara permanen dan tidak dapat dikembalikan.
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

  <!-- Modern Dialog -->
  <Dialog 
    v-model:visible="visible" 
    modal 
    :header="modalData.title" 
    class="modern-dialog"
    :style="{ width: '95vw', 'max-width': '50rem' }"
  >
    <OrderDetail 
      v-if="modalKomponen === 'OrderDetail'" 
      :datas="modalData" 
      @goToEdit="openDialog(modalData, 'OrderEdit', 'Edit Order', user)" 
      @closeDialog="visible = false" 
    />
    <OrderEdit 
      v-if="modalKomponen === 'OrderEdit'" 
      :datas="modalData" 
      @error="onError" 
      @goToDetail="openDialog(modalData, 'OrderDetail', '#' + modalData.no_order, user)" 
      @addData="onAddData" 
      @updateData="onUpdateData" 
      @closeDialog="visible = false" 
    />
    <EditDokumen 
      v-if="modalKomponen === 'EditDokumen'" 
      :datas="modalData" 
      @error="onError" 
      @closeDialog="visible = false" 
      @goToDetail="openDialog(modalData, 'OrderDetail', '#' + modalData.no_order, user)" 
      @updateData="onUpdateData" 
    />
    <KonsumenDetail 
      v-if="modalKomponen === 'KonsumenDetail'" 
      :datas="modalData" 
      @closeDialog="visible = false" 
      @goToDetail="visible = false" 
    />
  </Dialog>

  <Toast />
  
  <!-- Modern Pagination -->
  <div class="mt-6">
    <Pagination :value="data" :page="page" @pageChange="onPageChange" />
  </div>
</template>

<script lang="ts" setup>
import { useActionMenu } from '~/composables/useActionMenu'

definePageMeta({ title: 'List Order' });
const route = useRoute();
const router = useRouter();
const confirm = useConfirm();
const toast = useToast();
const page = ref(route.query.page ? Number(route.query.page) : 1);
const visible = ref(false);
const modalKomponen = ref('');
const modalData = ref({ title: '', user: {} }) as any;
const isLoading = ref([] as any);
const konsumenId = ref(route.query.konsumen_id || '');
const client = useSanctumClient();
const debounceTimeout = ref(null);
const user = useSanctumUser() as any;
const banks = ref([] as any);
const showDeleteConfirm = ref(false);
const deleteItemId = ref<number | null>(null);
const filterVisible = ref(false);
const settingsAll = ref<any>({});
const filter = ref({
  name: '',
  product: '',
  bank: '',
  dari: null as Date | null,
  sampai: null as Date | null
})

// Action Menu Composable
const { activeActionMenu, toggleActionMenu, closeActionMenu, setupEventListeners, removeEventListeners } = useActionMenu()

const isFilterActive = computed(() =>
  filter.value.name.length > 2 ||
  filter.value.product.length > 2 ||
  filter.value.bank ||
  filter.value.dari ||
  filter.value.sampai
)

// Helper function for relation type labels
const getRelationTypeLabel = (type: string) => {
  if (!type) return 'Relasi'
  // Capitalize first letter if it's not already
  return type.charAt(0).toUpperCase() + type.slice(1)
}

const { data, error, refresh } = await useAsyncData('orders', fetchOrders);

watch(data, (newValue) => {
  if (newValue && newValue.data && newValue.data.length > 0) {
    modalData.value = { title: 'Tambah Order', user: user.value || {}};
  }
});

watch(() => route.query.konsumen_id, async (newQuery, oldQuery) => {
  if (newQuery === undefined) return;
  konsumenId.value = String(newQuery || '');
  await refresh();
}, { deep: true, immediate: true });

onMounted(async () => {
  setupEventListeners()
  
  if (route.query.order_id) {
    try {
      const detail = await client(`/orders/${route.query.order_id}`)
      openDialog(detail, 'OrderDetail', 'Detail Order', user);
    } catch (e) {
      // fallback: try find in current list
      const order = data.value?.data?.find((o: any) => o.id === Number(route.query.order_id));
      openDialog(order || {}, 'OrderDetail', 'Detail Order', user);
    }
  }
  if (route.query.konsumen_id && route.query.tambah) {
    openDialog({}, 'OrderEdit', 'Order Baru', user);
    navigateTo({ query: { ...route.query, tambah: undefined } });
  }

  try {
    const responseBank = await client('/settings/banks')
    banks.value = [{ name: 'Perorangan' }, ...responseBank]
  } catch (error) {
    console.log(error)
  }

  try {
    settingsAll.value = await client('/settings')
  } catch (e) {}

});

onUnmounted(() => {
  removeEventListeners()
})

const exportToExcel = async () => {
  const XLSX = await import('xlsx');

  const query = new URLSearchParams();
  query.append('paginate', 'false');
  if (filter.value.name.length > 2) query.append('name', filter.value.name);
  if (filter.value.product.length > 2) query.append('product', filter.value.product);
  if (filter.value.bank) query.append('bank', filter.value.bank);
  if (filter.value.dari) query.append('dari', filter.value.dari.toISOString().slice(0, 10));
  if (filter.value.sampai) query.append('sampai', filter.value.sampai.toISOString().slice(0, 10));
  if (konsumenId.value) query.append('customer_id', String(konsumenId.value));
  if (route.query.status) query.append('status', route.query.status as string);

  try {
    const response = await client(`/orders?${query.toString()}`);

    if (!response || !response.length) {
      toast.add({ severity: 'warn', summary: 'Tidak ada data', detail: 'Tidak ada pesanan untuk diekspor.', life: 3000 });
      return;
    }

    // Helper function to get document name with fallbacks for bank orders
    const getDocumentName = (order: any) => {
      const docName = order.meta?.[1];
      if (!docName || docName === '-') {
        // Fallback to document type name for bank orders that don't have meta[1]
        const productId = order.product?.product_id;
        if (productId === 4) {
          return 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA';
        } else if (productId === 3) {
          return 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA';
        } else if (productId === 2) {
          return 'PERJANJIAN PENGIKATAN JAMINAN FIDUSIA';
        } else if (productId === 1) {
          return 'Akta Jual Beli';
        } else {
          return 'Dokumen Notarial';
        }
      }
      return docName;
    };

    // Helper function to format date consistently (YYYY-MM-DD)
    const formatDate = (dateString: string | undefined) => {
      if (!dateString) return '-';

      // Handle ISO format with time (2025-11-13T17:00:00.000Z)
      if (dateString.includes('T')) {
        return dateString.split('T')[0];
      }

      // Handle date strings that are already in correct format
      if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
        return dateString;
      }

      return dateString;
    };

    // Helper function to format number as Indonesian Rupiah
    const formatRupiah = (amount: number) => {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(amount);
    };

    const worksheetData = response.map((order: any, index: number) => {
      // Get bank name from customer meta with null checks
      const bankMeta = order.customer?.meta?.find((meta: any) => meta.meta_key === 'bank');
      const bankName = bankMeta?.meta_value || 'Perorangan';

      const nilaiPengikatan = parseInt((order.meta?.[5] || '0').toString()) || 0;
      const biayaNotaris = order.price || 0;

      // Get order status based on jobdesk progress
      let orderStatus = '';
      if (!order.jobdesks || order.jobdesks.length === 0) {
        orderStatus = 'Belum ada jobdesk';
      } else {
        const completedJobdesks = order.jobdesks.filter((jobdesk: any) => jobdesk.status === 'Selesai').length;
        const totalJobdesks = order.jobdesks.length;

        if (completedJobdesks === totalJobdesks) {
          orderStatus = 'Selesai';
        } else {
          orderStatus = `${completedJobdesks}/${totalJobdesks} (On Progress)`;
        }
      }

      return {
        'No.': index + 1,
        'Tanggal Akad': formatDate(order.meta?.[3]) || formatDate(order.order_date) || '-',
        'Nama Debitur/Klien': order.customer?.name || '-',
        'Bank/Pribadi': bankName,
        'Agunan/Obyek': order.meta?.[8] || order.meta?.[17] || '-',
        'Nilai Pengikatan': formatRupiah(nilaiPengikatan),
        'Biaya Notaris': formatRupiah(biayaNotaris),
        'Keterangan': getOrderProductNames(order) || '-',
        'Warkah Minuta Notariil': getDocumentName(order),
        'Warkah Minuta Legalisasi': order.no_order || '-',
        'Status': orderStatus,
      };
    });

    const worksheet = XLSX.utils.json_to_sheet(worksheetData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Orders');
    XLSX.writeFile(workbook, `LAPORAN FIDUSIA.xlsx`);
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal mengekspor data.', life: 3000 });
    console.error(error);
  }
};

function getLastJobdesk(data: any = {}) {
  const priority = ['Progress', 'Masuk', 'Selesai'];
  for (const status of priority) {
    const last = data.jobdesks?.filter((j: any) => j.status === status).at(-1);
    if (last) {
      return {
        status,
        description: last.description || '-',
      };
    }
  }
  return {
    status: null,
    description: '-',
  };
}

function getBadgeSeverity(status = '') {
  switch (status) {
    case 'Progress': return 'warn';
    case 'Masuk': return 'danger';
    case 'Selesai': return 'success';
    default: return 'info';
  }
}

function getOrderProductNames(order: any) {
  const names = Array.isArray(order?.products)
    ? order.products.map((p: any) => p?.name).filter(Boolean)
    : [];
  if (names.length) return names.join(', ');
  return order?.product?.name || '';
}

async function fetchOrders() {
  const query = new URLSearchParams();
  query.append('page', String(page.value));
  if (konsumenId.value) query.append('customer_id', String(konsumenId.value));
  if (route.query.status) query.append('status', String(route.query.status));
  if (filter.value.name.length > 2) query.append('name', filter.value.name);
  if (filter.value.product.length > 2) query.append('product', filter.value.product);
  if (filter.value.bank) query.append('bank', filter.value.bank);
  if (filter.value.dari) query.append('dari', filter.value.dari.toISOString().slice(0, 10));
  if (filter.value.sampai) query.append('sampai', filter.value.sampai.toISOString().slice(0, 10));
  return client(`/orders?${query.toString()}`);
}

const openDialog = async (data: any, komponen: string, title: string, user: any) => {
  try {
    let baseData = (typeof data === 'object' && data !== null) ? data : {};
    if ((komponen === 'OrderEdit' || komponen === 'OrderDetail') && baseData?.id) {
      // Ambil detail order terbaru agar field tambahan (mis. billing_notes) tidak kosong
      const detail = await client(`/orders/${baseData.id}`);
      baseData = detail || baseData;
    }
    modalData.value = baseData;
    modalData.value.title = title;
    modalKomponen.value = komponen;
    modalData.value.user = user;
    visible.value = true;
  } catch (e) {
    // fallback ke data existing jika fetch gagal
    modalData.value = (typeof data === 'object' && data !== null) ? data : {};
    modalData.value.title = title;
    modalKomponen.value = komponen;
    modalData.value.user = user;
    visible.value = true;
  }
};

const openCustomerDialog = async (customerData: any) => {
  try {
    // Fetch detailed customer data with orders
    const detailCustomer = await client(`/customers/${customerData.id}`);
    modalData.value = detailCustomer;
    modalData.value.title = `Detail Konsumen - ${customerData.name}`;
    modalKomponen.value = 'KonsumenDetail';
    visible.value = true;
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal mengambil detail konsumen', life: 3000 });
    console.error('Error fetching customer detail:', error);
  }
};

const goToJobdesk = (id: number, status: string) => {
  console.log('id', id);
  navigateTo(`/jobdesk/?status=${status}&order_id=${id}`);
}

async function printOrderBackend(order: any) {
  try {
    const blob = await client(`/orders/${order.id}/print`, {
      method: 'GET',
      responseType: 'blob',
      headers: {
        'Accept': 'application/pdf'
      }
    })
    
    const blobUrl = URL.createObjectURL(blob)
    window.open(blobUrl, '_blank', 'noopener,noreferrer,width=900,height=1200')
  } catch (e: any) {
    console.error('Print error:', e)
    if (e.status === 401) {
      toast.add({ severity: 'warn', summary: 'Sesi berakhir', detail: 'Silakan login ulang', life: 3000 })
      navigateTo('/login')
      return
    }
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal mengunduh PDF', life: 3000 })
  }
}

const onError = (error: string) => {
  toast.add({ severity: 'error', summary: 'Error', detail: error, life: 3000 });
};

const onUpdateData = (response: any) => {
  visible.value = false;
  modalData.value = response;
  data.value.data = data.value.data.map((item: any) => item.id === modalData.value.id ? modalData.value : item);
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Edit data berhasil', life: 3000 });
  openDialog(modalData.value, 'OrderDetail', '#' + modalData.value.no_order, user);
};

function buildPrintHTML(order: any) {
  const appName = settingsAll.value?.app_name || 'KANTOR NOTARIS';
  const officeDesc = settingsAll.value?.app_description || '';
  const address = settingsAll.value?.address || '';
  const phone = settingsAll.value?.phone || '';
  const orderDate = (order.order_date || order.created_at || '').toString().split('T')[0] || '';
  const customerName = order.customer?.name || '';
  const contactPerson = order.customer?.phone || '';
  const jenisOrder = getOrderProductNames(order) || '';
  const catatan = Array.isArray(order.meta) ? (order.meta?.[1] || '') : '';
  const agunan = Array.isArray(order.meta) ? (order.meta?.[8] || order.meta?.[17] || '') : '';
  const pic = '';
  const maker = '';
  const noOrder = order.no_order || '';
  return `
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Cetak Order ${noOrder}</title>
<style>
  *{box-sizing:border-box} body{font-family:Arial,Helvetica,sans-serif;margin:0;padding:24px;color:#000}
  .sheet{width:800px;margin:0 auto;border:2px solid #000;padding:16px}
  .header{text-align:center;margin-bottom:8px}
  .header .title{font-size:20px;font-weight:700}
  .header .subtitle{font-size:12px;margin-top:4px}
  .header .contact{font-size:11px;margin-top:2px}
  .grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;border:1px solid #000}
  .row{display:grid;grid-template-columns:1.2fr 1fr;min-height:64px;border-top:1px solid #000}
  .cell{border-left:1px solid #000;padding:8px;display:flex;align-items:center;justify-content:center;font-weight:700}
  .cell:first-child{border-left:0}
  .noorder{grid-column:1/2;grid-row:1/3;background:#3b82f6;color:#fff;font-size:36px;letter-spacing:1px}
  .label{font-size:12px;font-weight:600;color:#111}
  .value{font-size:18px;font-weight:700}
  .block{min-height:120px}
  .footer{display:grid;grid-template-columns:1fr 1fr;border-top:1px solid #000}
  .footer .fcell{border-left:1px solid #000;padding:16px;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800}
  .footer .fcell:first-child{border-left:0}
  .section-title{font-size:12px;color:#111;margin-bottom:4px}
  @media print { body{padding:0} .sheet{border-width:2px;box-shadow:none} }
</style>
</head>
<body onload="setTimeout(function(){window.print()},200)">
  <div class="sheet">
    <div class="header">
      <div class="title">${appName}</div>
      <div class="subtitle">${officeDesc}</div>
      <div class="contact">${address}</div>
      <div class="contact">${phone}</div>
    </div>
    <div class="grid">
      <div class="noorder cell value">${noOrder}</div>
      <div class="cell">
        <div>
          <div class="label">Tanggal Order</div>
          <div class="value">${orderDate}</div>
        </div>
      </div>
      <div class="cell">
        <div>
          <div class="label">Pemberi Order</div>
          <div class="value">${customerName}</div>
        </div>
      </div>
      <div class="cell">
        <div>
          <div class="label">Contact Person</div>
          <div class="value">${contactPerson}</div>
        </div>
      </div>
      <div class="cell">
        <div>
          <div class="label">Jenis Order</div>
          <div class="value">${jenisOrder}</div>
        </div>
      </div>
      <div class="cell" style="grid-column:1/3">
        <div style="width:100%">
          <div class="section-title">Klien</div>
          <div class="block" style="border:1px solid #000;height:100px"></div>
        </div>
      </div>
      <div class="cell" style="grid-column:1/3">
        <div style="width:100%">
          <div class="section-title">Jaminan / Agunan / Objek</div>
          <div class="block" style="border:1px solid #000;height:80px;display:flex;align-items:center;padding:8px">${agunan || ''}</div>
        </div>
      </div>
      <div class="cell" style="grid-column:1/3">
        <div style="width:100%">
          <div class="section-title">Catatan & Keterangan</div>
          <div class="block" style="border:1px solid #000;height:120px;display:flex;align-items:center;padding:8px">${catatan || ''}</div>
        </div>
      </div>
      <div class="cell" style="grid-column:1/3">
        <div style="width:100%">
          <div class="section-title">Catatan & Keterangan Tagihan</div>
          <div class="block" style="border:1px solid #000;height:80px"></div>
        </div>
      </div>
      <div class="footer" style="grid-column:1/3">
        <div class="fcell">PIC<br>${pic}</div>
        <div class="fcell">MAKER<br>${maker}</div>
      </div>
    </div>
  </div>
</body>
</html>
`
}

function printOrder(order: any) {
  const w = window.open('', '_blank', 'noopener,noreferrer,width=900,height=1200')
  if (!w) return
  const html = buildPrintHTML(order)
  w.document.open()
  w.document.write(html)
  w.document.close()
}

const onAddData = (response: any) => {
  visible.value = false;
  modalData.value = response;  
  data.value.data.unshift(modalData.value);
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Tambah data berhasil', life: 3000 });
  openDialog(modalData.value, 'OrderDetail', '#' + modalData.value.no_order, user);
};

const resetFilter = async () => {
  filter.value = {
    name: '',
    product: '',
    bank: '',
    dari: null,
    sampai: null
  }

  refresh()
};

const resetStatusFilter = async () => {
  try {
    // Reset halaman ke 1 dan hapus status filter
    page.value = 1;
    
    // Panggil API untuk mendapatkan semua data tanpa filter status
    const queryArchive = new URLSearchParams();
    queryArchive.append('page', '1');

    const response = await client(`/orders?customer_id=${konsumenId.value}&${queryArchive.toString()}`);
    data.value = response;

    // Navigasi ke halaman tanpa query parameter status
    const newQuery = { ...route.query };
    delete newQuery.status;
    router.push({ path: '/order', query: newQuery });
  } catch (err) {
    console.error("Gagal mereset filter status:", err);
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal mereset filter. Silakan coba lagi.', life: 3000 });
  }
};

const deleteData = async (id: number) => {
  deleteItemId.value = id;
  showDeleteConfirm.value = true;
};

const confirmDelete = async () => {
  if (deleteItemId.value === null) return;
  
  try {
    isLoading.value[deleteItemId.value] = true;
    await client(`/orders/${deleteItemId.value}`, { method: 'DELETE' });
    toast.add({ severity: 'success', summary: 'Success', detail: 'Delete order berhasil!', life: 3000 });
    await refresh();
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal menghapus order', life: 3000 });
    console.error('Delete error:', error);
  } finally {
    if (deleteItemId.value !== null) {
      isLoading.value[deleteItemId.value] = false;
    }
    showDeleteConfirm.value = false;
    deleteItemId.value = null;
  }
};

const filterArchive = async (status: string) => {
  try {
    // Buat query string untuk API
    const queryArchive = new URLSearchParams();
    if (status) {
      queryArchive.append('status', status);
    }
    queryArchive.append('page', '1');

    // Panggil API untuk mendapatkan data
    const response = await client(`/orders?customer_id=${konsumenId.value}&${queryArchive.toString()}`);
    data.value = response;
    page.value = 1;

    // Navigasi ke halaman baru dengan query parameter yang diperbarui
    const newQuery: Record<string, string> = { page: '1', konsumen_id: String(konsumenId.value) };
    if (status) {
      newQuery.status = status;
    }
    router.push({ path: '/order', query: newQuery });
  } catch (err) {
    console.error("Gagal memuat data berdasarkan status:", err);
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal memuat data. Silakan coba lagi.', life: 3000 });
  }
};

const onPageChange = (event: { page: number }) => {
  page.value = event.page + 1;
  navigateTo(`/order?page=${page.value}&konsumen_id=${konsumenId.value}`);
  refresh();
};

const getActionMenuPosition = (index: number, totalRows: number) => {
  // If it's one of the last 3 rows, position the menu above
  if (index >= totalRows - 3) {
    return 'right-full bottom-0 mr-2';
  }
  // Default position for other rows
  return 'right-full top-0 mr-2';
};

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
};

watch(filter, () => {
  if (isFilterActive.value) refresh()
}, { deep: true })
</script>

<style scoped>
/* Modern Table Styling */
:deep(.modern-table) {
  background: transparent;
  position: relative;
}

:deep(.modern-table .p-datatable-wrapper) {
  overflow-x: auto !important;
  overflow-y: auto !important;
  position: relative !important;
  max-height: calc(100vh - 300px) !important;
  /* Ensure proper stacking context */
  contain: layout style paint !important;
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

/* Sticky Table Header - Comprehensive Fix */
:deep(.modern-table) {
  position: relative !important;
}

:deep(.modern-table .p-datatable-table) {
  border-collapse: separate !important;
  border-spacing: 0 !important;
}

:deep(.modern-table thead),
:deep(.modern-table .p-datatable-thead) {
  position: sticky !important;
  top: 0 !important;
  z-index: 1010 !important;
  background: rgb(248 250 252 / 0.95) !important;
  backdrop-filter: blur(12px) !important;
}

:deep(.dark .modern-table thead),
:deep(.dark .modern-table .p-datatable-thead) {
  background: rgb(39 39 42 / 0.95) !important;
  backdrop-filter: blur(12px) !important;
  z-index: 1010 !important;
}

:deep(.modern-table thead th),
:deep(.modern-table .p-datatable-thead th),
:deep(.modern-table .p-datatable-thead > tr > th) {
  position: sticky !important;
  top: 0 !important;
  z-index: 1010 !important;
  background: rgb(248 250 252 / 0.95) !important;
  backdrop-filter: blur(12px) !important;
  color: rgb(51 65 85 / 1) !important;
  font-weight: 600 !important;
  font-size: 0.875rem !important;
  text-transform: uppercase !important;
  letter-spacing: 0.05em !important;
  border-bottom: 1px solid rgb(226 232 240 / 1) !important;
  padding: 1rem 1.5rem !important;
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1) !important;
}

:deep(.dark .modern-table thead th),
:deep(.dark .modern-table .p-datatable-thead th),
:deep(.dark .modern-table .p-datatable-thead > tr > th) {
  background: rgb(39 39 42 / 0.95) !important;
  backdrop-filter: blur(12px) !important;
  color: rgb(203 213 225 / 1) !important;
  border-bottom: 1px solid rgb(63 63 70 / 1) !important;
  box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.3), 0 1px 2px -1px rgb(0 0 0 / 0.2) !important;
  z-index: 1010 !important;
}

:deep(.modern-table .p-datatable-tbody > tr > td) {
  border-bottom: 1px solid rgb(241 245 249 / 0.3);
  padding: 0.5rem;
  color: rgb(71 85 105 / 1);
}

:deep(.dark .modern-table .p-datatable-tbody > tr > td) {
  border-bottom: 1px solid rgb(39 39 42 / 0.3);
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

/* Progress Bar Modern Styling */
:deep(.p-progressbar) {
  border-radius: 0.5rem;
  background: rgb(241 245 249);
  overflow: hidden;
}

:deep(.dark .p-progressbar) {
  background: rgb(39 39 42);
}

:deep(.p-progressbar .p-progressbar-value) {
  background: linear-gradient(90deg, rgb(34 197 94), rgb(22 163 74));
  border-radius: 0.5rem;
  transition: width 0.3s ease;
}

/* Status Badge Colors */
:deep(.p-badge) {
  border-radius: 0.5rem;
  font-weight: 500;
  font-size: 0.75rem;
}

/* Status Tab Button Overrides */
.status-tab-button.p-button {
  border: none !important;
  outline: none !important;
  box-shadow: none !important;
}

.status-tab-inactive.p-button {
  background: transparent !important;
  color: rgb(71 85 105) !important;
  border: none !important;
}

.status-tab-inactive.p-button:hover {
  background: rgba(255, 255, 255, 0.5) !important;
  color: rgb(30 41 59) !important;
}

:deep(.dark) .status-tab-inactive.p-button {
  color: rgb(148 163 184) !important;
}

:deep(.dark) .status-tab-inactive.p-button:hover {
  background: rgba(63, 63, 70, 0.5) !important;
  color: rgb(226 232 240) !important;
}

.status-tab-active.p-button {
  font-weight: 600 !important;
  transform: scale(1.05) !important;
  color: white !important;
  border: none !important;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
}

.status-tab-masuk.p-button {
  background: linear-gradient(to right, rgb(34, 197, 94), rgb(5, 150, 105)) !important;
}

.status-tab-arsip.p-button {
  background: linear-gradient(to right, rgb(245, 158, 11), rgb(217, 119, 6)) !important;
}

.status-tab-selesai.p-button {
  background: linear-gradient(to right, rgb(59, 130, 246), rgb(147, 51, 234)) !important;
}

.status-tab-active.p-button:hover {
  transform: scale(1.05) !important;
}

.status-tab-masuk.p-button:hover {
  background: linear-gradient(to right, rgb(22, 163, 74), rgb(4, 120, 87)) !important;
}

.status-tab-arsip.p-button:hover {
  background: linear-gradient(to right, rgb(217, 119, 6), rgb(180, 83, 9)) !important;
}

.status-tab-selesai.p-button:hover {
  background: linear-gradient(to right, rgb(37, 99, 235), rgb(124, 58, 237)) !important;
}

/* Action Menu Transitions */
.action-menu-enter-active,
.action-menu-leave-active {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
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

/* Action Menu Item Animations */
.action-menu-item {
  transition: all 0.15s ease;
}

.action-menu-item:hover {
  transform: translateX(-2px);
}

.action-menu-item:focus {
  outline: none;
  background: rgb(248 250 252);
  transform: translateX(-2px);
}

.dark .action-menu-item:focus {
  background: rgb(39 39 42);
}

/* Action Menu Z-index Fix */
:deep(.p-column-header-content) {
  position: relative;
  z-index: 1;
}

:deep(.p-datatable-tbody > tr > td) {
  position: relative !important;
  z-index: 1 !important;
}

:deep(.p-datatable-tbody > tr > td:last-child) {
  z-index: 1000 !important;
  overflow: visible !important;
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

/* Action Menu Positioning Improvements */
.action-menu-content {
  will-change: transform;
}
</style>
