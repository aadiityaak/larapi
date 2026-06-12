<template>
  <!-- Header Section with Order Info -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-start gap-6">
      <!-- Order Icon -->
      <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg flex-shrink-0">
        <Icon name="lucide:shopping-cart" class="text-white" size="1.5em" />
      </div>
      
      <!-- Order Details -->
      <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between mb-4">
          <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Order #{{ datas.no_order || datas.id }}</h2>
            <div class="flex items-center gap-2 mb-2">
              <Icon name="lucide:package" class="text-slate-500 dark:text-slate-400" size="0.9em" />
              <span class="text-slate-600 dark:text-slate-300">{{ getProductNames() }}</span>
            </div>
            <div class="flex items-center gap-2 mb-2">
              <Icon name="lucide:calendar" class="text-slate-500 dark:text-slate-400" size="0.9em" />
              <span class="text-slate-600 dark:text-slate-300">
                <FormatDate :value="datas.order_date" />
              </span>
            </div>
            <Badge 
              :value="getOrderStatus()" 
              :class="getStatusBadgeClass()"
              class="px-3 py-1 rounded-full text-sm font-medium"
            />
          </div>
          
          <!-- Quick Stats -->
          <div class="text-right">
            <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">Dibuat</div>
            <div class="text-slate-800 dark:text-white font-semibold">
              <FormatDate :value="datas.created_at" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Customer Info Section -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 flex items-center justify-center">
        <Icon name="lucide:user" class="text-blue-600 dark:text-blue-400" size="0.9em" />
      </div>
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Konsumen</h3>
    </div>
    
    <div class="flex items-start gap-4">
      <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg flex-shrink-0">
        <span class="text-white font-bold text-lg">{{ datas.customer?.name?.charAt(0).toUpperCase() }}</span>
      </div>
      <div class="flex-1 min-w-0">
        <h4 class="text-xl font-bold text-slate-800 dark:text-white mb-2">{{ datas.customer?.name }}</h4>
        <div class="space-y-2">
          <div class="flex items-center gap-2">
            <Icon name="lucide:map-pin" class="text-slate-500 dark:text-slate-400" size="0.8em" />
            <span class="text-slate-600 dark:text-slate-300 text-sm">{{ datas.customer?.address || 'Alamat tidak tersedia' }}</span>
          </div>
          <!-- All Phones -->
          <div v-if="datas.customer?.phones && datas.customer.phones.length > 0" class="space-y-1">
            <div v-for="phoneItem in datas.customer.phones" :key="phoneItem.id" class="flex items-center gap-2">
              <Icon name="lucide:phone" class="text-slate-500 dark:text-slate-400" size="0.8em" />
              <span class="text-slate-600 dark:text-slate-300 text-sm">{{ phoneItem.phone }}</span>
              <Badge v-if="phoneItem.is_primary" class="bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded-full">
                Utama
              </Badge>
            </div>
          </div>
          <div v-else class="flex items-center gap-2">
            <Icon name="lucide:phone" class="text-slate-500 dark:text-slate-400" size="0.8em" />
            <span class="text-slate-600 dark:text-slate-300 text-sm">{{ datas.customer?.phone || 'Telepon tidak tersedia' }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Related Order Section -->
  <div v-if="datas.related_order || (datas.related_orders && datas.related_orders.length > 0)" 
       class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-100 to-red-100 dark:from-orange-900/30 dark:to-red-900/30 flex items-center justify-center">
        <Icon name="lucide:link-2" class="text-orange-600 dark:text-orange-400" size="0.9em" />
      </div>
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Relasi Order</h3>
    </div>
    
    <div class="space-y-4">
      <!-- Related Order (Parent) -->
      <div v-if="datas.related_order" class="p-4 bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-xl border border-orange-200/50 dark:border-orange-800/50">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center shadow-lg flex-shrink-0">
            <Icon name="lucide:arrow-left" class="text-white" size="0.9em" />
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
              <Badge class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full">
                {{ getRelationTypeLabel(datas.relation_type) }}
              </Badge>
              <span class="font-semibold text-slate-800 dark:text-white">
                Order Terkait: {{ datas.related_order.no_order }}
              </span>
            </div>
            <div class="text-sm text-slate-600 dark:text-slate-300">
              Konsumen: {{ datas.related_order.customer?.name }}
            </div>
          </div>
          <Button 
            size="small" 
            class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white"
            @click="goToOrder(datas.related_order.id)"
          >
            <Icon name="lucide:eye" size="0.9em" />
            <span>Lihat</span>
          </Button>
        </div>
      </div>
      
      <!-- Related Orders (Children) -->
      <div v-if="datas.related_orders && datas.related_orders.length > 0" class="space-y-3">
        <div v-for="relatedOrder in datas.related_orders" :key="relatedOrder.id" 
             class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200/50 dark:border-blue-800/50">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg flex-shrink-0">
              <Icon name="lucide:arrow-right" class="text-white" size="0.9em" />
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1">
                <Badge class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                  {{ getRelationTypeLabel(relatedOrder.relation_type) }}
                </Badge>
                <span class="font-semibold text-slate-800 dark:text-white">
                  {{ relatedOrder.no_order }}
                </span>
              </div>
              <div class="text-sm text-slate-600 dark:text-slate-300">
                Konsumen: {{ relatedOrder.customer?.name }}
              </div>
            </div>
            <Button 
              size="small" 
              class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white"
              @click="goToOrder(relatedOrder.id)"
            >
              <Icon name="lucide:eye" size="0.9em" />
              <span>Lihat</span>
            </Button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Financial Statistics Grid -->
  <div v-if="user.capabilities.includes('access:keuangan')" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Cost -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg">
          <Icon name="lucide:dollar-sign" class="text-white" size="1.2em" />
        </div>
        <div class="flex-1">
          <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Biaya</div>
          <div class="text-2xl font-bold text-slate-800 dark:text-white">
            <FormatRupiah :value="datas.price || 0" />
          </div>
          <div class="text-xs text-slate-500 dark:text-slate-400">Harga jasa notaris</div>
        </div>
      </div>
    </div>

    <!-- Amount Paid -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
          <Icon name="lucide:credit-card" class="text-white" size="1.2em" />
        </div>
        <div class="flex-1">
          <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Dibayar</div>
          <div class="text-2xl font-bold text-slate-800 dark:text-white">
            <FormatRupiah :value="datas.paid || 0" />
          </div>
          <div class="text-xs text-blue-600 dark:text-blue-400">Metode: {{ datas.payment_method || 'Belum ditentukan' }}</div>
        </div>
      </div>
    </div>

    <!-- Outstanding Balance -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg">
          <Icon name="lucide:alert-circle" class="text-white" size="1.2em" />
        </div>
        <div class="flex-1">
          <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Kekurangan</div>
          <div class="text-2xl font-bold text-slate-800 dark:text-white">
            <FormatRupiah :value="(datas.price || 0) - (datas.paid || 0)" />
          </div>
          <div class="text-xs text-amber-600 dark:text-amber-400">
            {{ (datas.price || 0) - (datas.paid || 0) > 0 ? 'Belum lunas' : 'Lunas' }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Product Details Section -->
  <div v-if="productsForDetail.length > 0" class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-center gap-3 mb-6">
      <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 flex items-center justify-center">
        <Icon name="lucide:settings" class="text-purple-600 dark:text-purple-400" size="0.9em" />
      </div>
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Detail Produk</h3>
    </div>

    <div class="space-y-6">
      <div
        v-for="product in productsForDetail"
        :key="product.id"
        class="p-5 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-zinc-800 dark:to-zinc-700 rounded-2xl border border-slate-200/50 dark:border-zinc-600/50"
      >
        <div class="flex items-center gap-3 mb-4">
          <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg">
            <Icon name="lucide:package" class="text-white" size="1em" />
          </div>
          <div class="min-w-0">
            <div class="text-sm text-slate-500 dark:text-slate-400">Produk</div>
            <div class="font-semibold text-slate-800 dark:text-white truncate">{{ product.name }}</div>
          </div>
        </div>

        <div v-if="product.meta_products && product.meta_products.length > 0" class="space-y-3">
          <div v-for="item in product.meta_products" :key="item.id">
            <div
              v-if="getMetaValue(product.id, item.id) !== null && getMetaValue(product.id, item.id) !== undefined && getMetaValue(product.id, item.id) !== ''"
              class="flex items-center justify-between gap-4 p-4 bg-white/70 dark:bg-zinc-900/30 rounded-xl border border-slate-200/50 dark:border-zinc-700/50"
            >
              <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-slate-100 to-slate-200 dark:from-zinc-700 dark:to-zinc-600 flex items-center justify-center">
                  <Icon :name="getFieldIcon(item.type)" class="text-slate-600 dark:text-slate-300" size="0.8em" />
                </div>
                <span class="font-medium text-slate-800 dark:text-white truncate">{{ item.name }}</span>
              </div>
              <div class="text-slate-600 dark:text-slate-300 font-medium text-right">
                <template v-if="item.type === 'date' || (item.type === '' && isDateField(item.name))">
                  <FormatDate :value="getMetaValue(product.id, item.id)" />
                </template>
                <template v-else-if="item.type === 'currency' || (item.type === '' && isCurrencyField(item.name))">
                  <FormatRupiah :value="getMetaValue(product.id, item.id)" />
                </template>
                <template v-else>
                  {{ getMetaValue(product.id, item.id) ?? '-' }}
                </template>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="text-sm text-slate-600 dark:text-slate-300">
          Tidak ada detail untuk produk ini.
        </div>
      </div>
    </div>
  </div>

  <!-- Document Preview Section -->
  <div v-if="datas.lampiran" class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-center gap-3 mb-6">
      <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-100 to-blue-100 dark:from-indigo-900/30 dark:to-blue-900/30 flex items-center justify-center">
        <Icon name="lucide:file-text" class="text-indigo-600 dark:text-indigo-400" size="0.9em" />
      </div>
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Dokumen Order</h3>
    </div>
    
    <OrderPreview :datas="datas" />
  </div>
  <!-- Action Buttons -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Aksi</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">Kelola order dan jobdesk</p>
      </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
      <!-- Add Jobdesk Button -->
      <Button 
        as="router-link" 
        :to="`/jobdesk?order_id=${datas.id}&tambah=1`"
        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:plus" size="1em" />
        <span class="hidden sm:inline">Jobdesk</span>
        <span class="sm:hidden">Jobdesk</span>
      </Button>

      <!-- View Jobdesk Button -->
      <Button 
        as="router-link" 
        :to="`/jobdesk?order_id=${datas.id}`"
        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:list" size="1em" />
        <span class="hidden sm:inline">
          {{ datas.jobdesks?.filter((jobdesk: any) => jobdesk.status === 'Selesai').length }} / {{ datas.jobdesks?.length }} Jobdesk
        </span>
        <span class="sm:hidden">List</span>
      </Button>

      <!-- Edit Order Button -->
      <Button 
        @click="emits('goToEdit', datas)"
        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:pencil" size="1em" />
        <span class="hidden sm:inline">Edit Order</span>
        <span class="sm:hidden">Edit</span>
      </Button>

      <!-- Close Button -->
      <Button 
        @click="emits('closeDialog')"
        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-500 to-gray-600 hover:from-slate-600 hover:to-gray-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:x" size="1em" />
        <span>Tutup</span>
      </Button>
    </div>

    <!-- Additional Info -->
    <div class="mt-4 p-3 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200/50 dark:border-green-800/50">
      <div class="flex items-start gap-2">
        <Icon name="lucide:info" class="text-green-600 dark:text-green-400 mt-0.5" size="0.9em" />
        <div class="text-sm text-green-700 dark:text-green-300">
          <p class="font-medium mb-1">Status Order:</p>
          <div class="text-xs space-y-1 text-green-600 dark:text-green-400">
            <div class="flex items-center gap-2">
              <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>
              <span>{{ getJobdeskProgress() }}</span>
            </div>
            <div class="flex items-center gap-2">
              <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
              <span>Dibuat: <FormatDate :value="datas.created_at" /></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Import format components explicitly
import FormatDate from '~/components/Format/Date.vue'
import FormatRupiah from '~/components/Format/Rupiah.vue'

const emits = defineEmits(['closeDialog', 'goToEdit'])
const { datas } = defineProps(['datas'])
const user = useSanctumUser() as any

const productsForDetail = computed(() => {
  const list = (datas as any)?.products
  if (Array.isArray(list) && list.length) return list
  const single = (datas as any)?.product
  return single ? [single] : []
})

const getProductNames = () => {
  if (productsForDetail.value.length) {
    return productsForDetail.value.map((p: any) => p?.name).filter(Boolean).join(', ')
  }
  return (datas as any)?.product?.name || 'Produk tidak tersedia'
}

const getMetaValue = (productId: number, metaId: number) => {
  const meta = ((datas as any)?.meta ?? {}) as any
  const byProduct = meta?.meta_by_product
  if (byProduct && typeof byProduct === 'object') {
    const bucket = byProduct[String(productId)] ?? byProduct[productId]
    if (bucket && typeof bucket === 'object') {
      return bucket[String(metaId)] ?? bucket[metaId]
    }
  }
  return meta[String(metaId)] ?? meta[metaId]
}

// Helper function to get order status
const getOrderStatus = () => {
  if (!datas.jobdesks || datas.jobdesks.length === 0) {
    return 'Belum ada jobdesk'
  }
  
  const completedJobs = datas.jobdesks.filter((job: any) => job.status === 'Selesai')
  const totalJobs = datas.jobdesks.length
  
  if (completedJobs.length === totalJobs) {
    return 'Selesai'
  } else if (completedJobs.length > 0) {
    return 'Dalam Progress'
  } else {
    return 'Menunggu'
  }
}

// Helper function to get status badge class
const getStatusBadgeClass = () => {
  const status = getOrderStatus()
  switch (status) {
    case 'Selesai':
      return 'bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-700 dark:text-green-300'
    case 'Dalam Progress':
      return 'bg-gradient-to-r from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 text-blue-700 dark:text-blue-300'
    case 'Menunggu':
      return 'bg-gradient-to-r from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 text-amber-700 dark:text-amber-300'
    default:
      return 'bg-gradient-to-r from-slate-100 to-gray-100 dark:from-slate-900/30 dark:to-gray-900/30 text-slate-700 dark:text-slate-300'
  }
}

// Helper function to get field icon
const getFieldIcon = (type: string) => {
  switch (type) {
    case 'text': return 'lucide:type'
    case 'number': return 'lucide:hash'
    case 'currency': return 'lucide:dollar-sign'
    case 'date': return 'lucide:calendar'
    default: return 'lucide:settings'
  }
}

// Helper function to get jobdesk progress text
const getJobdeskProgress = () => {
  if (!datas.jobdesks || datas.jobdesks.length === 0) {
    return 'Belum ada jobdesk'
  }
  
  const completedJobs = datas.jobdesks.filter((job: any) => job.status === 'Selesai')
  const totalJobs = datas.jobdesks.length
  
  return `${completedJobs.length} dari ${totalJobs} jobdesk selesai`
}

// Helper function to detect date fields based on name (fallback when type is empty)
const isDateField = (fieldName: string) => {
  const dateKeywords = ['tanggal', 'date', 'tgl', 'waktu', 'time']
  return dateKeywords.some(keyword => 
    fieldName.toLowerCase().includes(keyword)
  )
}

// Helper function to detect currency fields based on name (fallback when type is empty)  
const isCurrencyField = (fieldName: string) => {
  const fieldLower = fieldName.toLowerCase()
  
  // Exclude non-currency fields that might contain currency keywords
  const excludePatterns = [
    'bukti', 'kepemilikan', 'sertifikat', 'nomor', 'no', 'nama', 'pemilik', 'alamat'
  ]
  
  if (excludePatterns.some(pattern => fieldLower.includes(pattern))) {
    return false
  }
  
  const currencyKeywords = [
    'harga', 'biaya', 'jumlah', 'nominal', 'price', 'cost', 'amount', 
    'pinjaman', 'rupiah', 'rp', 'nilai', 'value', 'penjaminan',
    'kredit', 'dana', 'uang', 'pembayaran', 'tagihan', 'hutang', 'piutang'
  ]
  
  return currencyKeywords.some(keyword => fieldLower.includes(keyword))
}

// Helper function for relation type labels
const getRelationTypeLabel = (type: string) => {
  if (!type) return 'Relasi'
  // Capitalize first letter if it's not already
  return type.charAt(0).toUpperCase() + type.slice(1)
}

// Navigate to related order
const router = useRouter()
const goToOrder = (orderId: number) => {
  emits('closeDialog')
  router.push(`/order?order_id=${orderId}`)
}

onMounted(() => {
  datas.meta ??= {}
})
</script>
