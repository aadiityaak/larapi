<template>
  <!-- Header Section with Customer Info -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-start gap-6">
      <!-- Customer Avatar -->
      <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg flex-shrink-0">
        <span class="text-white font-bold text-2xl">{{ datas.name.charAt(0).toUpperCase() }}</span>
      </div>
      
      <!-- Customer Details -->
      <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between mb-4">
          <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">{{ datas.name }}</h2>
            <div class="flex items-center gap-2 mb-2">
              <Icon name="lucide:map-pin" class="text-slate-500 dark:text-slate-400" size="0.9em" />
              <span class="text-slate-600 dark:text-slate-300">{{ datas.address || 'Alamat tidak tersedia' }}</span>
            </div>
            <!-- All Phones -->
            <div v-if="datas.phones && datas.phones.length > 0" class="space-y-1 mb-2">
              <div v-for="phoneItem in datas.phones" :key="phoneItem.id" class="flex items-center gap-2">
                <Icon name="lucide:phone" class="text-slate-500 dark:text-slate-400" size="0.9em" />
                <span class="text-slate-600 dark:text-slate-300">{{ phoneItem.phone }}</span>
                <Badge v-if="phoneItem.is_primary" class="bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded-full">
                  Utama
                </Badge>
              </div>
            </div>
            <div v-else class="flex items-center gap-2 mb-2">
              <Icon name="lucide:phone" class="text-slate-500 dark:text-slate-400" size="0.9em" />
              <span class="text-slate-600 dark:text-slate-300">{{ datas.phone || 'Telepon tidak tersedia' }}</span>
            </div>
            <Badge 
              :value="getBankValue(datas.meta)" 
              class="px-3 py-1 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm font-medium"
            />
          </div>
          
          <!-- Quick Stats -->
          <div class="text-right">
            <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">Member sejak</div>
            <div class="text-slate-800 dark:text-white font-semibold">
              <FormatDateTime :value="datas.created_at" format="date" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistics Grid -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Order Statistics -->
    <div 
      @click="navigateTo(`/order?konsumen_id=${datas.id}`)" 
      class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 cursor-pointer hover:scale-105 transition-all duration-200 group"
    >
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
          <Icon name="lucide:shopping-cart" class="text-white" size="1.2em" />
        </div>
        <div class="flex-1">
          <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Order</div>
          <div class="text-2xl font-bold text-slate-800 dark:text-white">{{ datas.order_count || 0 }}</div>
          <div class="text-xs text-green-600 dark:text-green-400">Klik untuk lihat detail</div>
        </div>
      </div>
    </div>

    <!-- Total Bills -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
          <Icon name="lucide:credit-card" class="text-white" size="1.2em" />
        </div>
        <div class="flex-1">
          <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Tagihan</div>
          <div class="text-2xl font-bold text-slate-800 dark:text-white">
            <span v-if="user.capabilities.includes('access:keuangan')">
              <FormatRupiah :value="price" />
            </span>
            <span v-else class="text-slate-400">***</span>
          </div>
          <div class="text-xs text-slate-500 dark:text-slate-400">Keseluruhan tagihan</div>
        </div>
      </div>
    </div>

    <!-- Total Paid -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg">
          <Icon name="lucide:check-circle" class="text-white" size="1.2em" />
        </div>
        <div class="flex-1">
          <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Dibayar</div>
          <div class="text-2xl font-bold text-slate-800 dark:text-white">
            <span v-if="user.capabilities.includes('access:keuangan')">
              <FormatRupiah :value="paid" />
            </span>
            <span v-else class="text-slate-400">***</span>
          </div>
          <div class="text-xs text-emerald-600 dark:text-emerald-400">Sudah terbayar</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Outstanding Balance -->
  <div v-if="user.capabilities.includes('access:keuangan') && (price - paid) > 0" class="backdrop-blur-xl bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 border border-orange-200/50 dark:border-orange-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center shadow-lg">
        <Icon name="lucide:alert-circle" class="text-white" size="1.2em" />
      </div>
      <div class="flex-1">
        <div class="text-sm font-medium text-orange-700 dark:text-orange-300 mb-1">Sisa Tagihan</div>
        <div class="text-2xl font-bold text-orange-800 dark:text-orange-200">
          <FormatRupiah :value="price - paid" />
        </div>
        <div class="text-xs text-orange-600 dark:text-orange-400">Belum terbayar</div>
      </div>
      <div class="text-right">
        <div class="text-xs text-orange-600 dark:text-orange-400 mb-1">Persentase Lunas</div>
        <div class="text-lg font-semibold text-orange-700 dark:text-orange-300">
          {{ Math.round((paid / price) * 100) }}%
        </div>
      </div>
    </div>
  </div>

  <!-- Success Message for Fully Paid -->
  <div v-else-if="user.capabilities.includes('access:keuangan') && price > 0 && (price - paid) <= 0" class="backdrop-blur-xl bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200/50 dark:border-green-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg">
        <Icon name="lucide:check-circle-2" class="text-white" size="1.2em" />
      </div>
      <div class="flex-1">
        <div class="text-sm font-medium text-green-700 dark:text-green-300 mb-1">Status Pembayaran</div>
        <div class="text-2xl font-bold text-green-800 dark:text-green-200">Lunas</div>
        <div class="text-xs text-green-600 dark:text-green-400">Semua tagihan telah dibayar</div>
      </div>
      <div class="flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30">
        <Icon name="lucide:trophy" class="text-green-600 dark:text-green-400" size="1.5em" />
      </div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Aksi Tersedia</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">Kelola data konsumen dan order</p>
      </div>
    </div>
    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
      <Button 
        @click="navigateTo(`/order?konsumen_id=${datas.id}&tambah=1`)" 
        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:plus" size="1em" />
        <span class="hidden sm:inline">Tambah Order</span>
        <span class="sm:hidden">Tambah</span>
      </Button>
      
      <Button 
        @click="navigateTo(`/order?konsumen_id=${datas.id}`)" 
        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:list" size="1em" />
        <span class="hidden sm:inline">{{ datas.orders?.length || 0 }} Order</span>
        <span class="sm:hidden">Order</span>
      </Button>
      
      <Button 
        @click="emits('goToEdit', datas)" 
        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:edit" size="1em" />
        <span class="hidden sm:inline">Edit Data</span>
        <span class="sm:hidden">Edit</span>
      </Button>
      
      <Button 
        @click="emits('closeDialog')" 
        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-500 to-gray-600 hover:from-slate-600 hover:to-gray-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:x" size="1em" />
        <span class="hidden sm:inline">Tutup</span>
        <span class="sm:hidden">Tutup</span>
      </Button>
    </div>
  </div>
</template>

<script lang="ts" setup>
const emits = defineEmits(['closeDialog', 'goToEdit'])
const { datas } = defineProps(['datas'])
const user = useSanctumUser() as any
const price = ref(0)
const paid = ref(0)

// Type for meta data
interface MetaItem {
  meta_key: string
  meta_value: string
}

// Helper function to find bank meta with proper typing
const getBankValue = (metaData: MetaItem[] | undefined): string => {
  return metaData?.find((meta: MetaItem) => meta.meta_key === 'bank')?.meta_value || 'Perorangan'
}

onMounted(() => {
  datas.orders?.forEach((item: any) => {
    price.value += Number(item.price)
    paid.value += Number(item.paid)
  })
})
</script>