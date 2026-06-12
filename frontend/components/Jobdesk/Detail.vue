<template>
  <!-- Header Section with Jobdesk Info -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-start gap-6">
      <!-- Jobdesk Icon -->
      <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg flex-shrink-0">
        <Icon name="lucide:briefcase" class="text-white" size="1.5em" />
      </div>
      
      <!-- Jobdesk Details -->
      <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between mb-4">
          <div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Jobdesk #{{ datas.id }}</h2>
            <div class="flex items-center gap-2 mb-2">
              <Icon name="lucide:file-text" class="text-slate-500 dark:text-slate-400" size="0.9em" />
              <span class="text-slate-600 dark:text-slate-300">{{ datas.description || 'Deskripsi tidak tersedia' }}</span>
            </div>
            <div class="flex items-center gap-2 mb-2">
              <Icon name="lucide:user" class="text-slate-500 dark:text-slate-400" size="0.9em" />
              <span class="text-slate-600 dark:text-slate-300">{{ datas.user?.name || 'Belum ditugaskan' }}</span>
              <Badge 
                v-if="datas.user?.position"
                :value="datas.user.position" 
                class="px-2 py-1 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 text-blue-700 dark:text-blue-300 rounded-full text-xs font-medium"
              />
            </div>
            <Badge 
              :value="getStatusBadge()" 
              :class="getStatusClass()"
              class="px-3 py-1 rounded-full text-sm font-medium"
            />
          </div>
          
          <!-- Quick Stats -->
          <div class="text-right">
            <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">Status</div>
            <div class="text-slate-800 dark:text-white font-semibold">
              {{ datas.status || 'Masuk' }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Customer Information -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 flex items-center justify-center">
        <Icon name="lucide:users" class="text-green-600 dark:text-green-400" size="0.9em" />
      </div>
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Konsumen</h3>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="space-y-4">
        <div class="flex items-center gap-3">
          <Icon name="lucide:user-circle" class="text-slate-500 dark:text-slate-400" size="1em" />
          <div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Nama Konsumen</div>
            <div class="font-semibold text-slate-800 dark:text-white">{{ datas.order?.customer?.name || '-' }}</div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <Icon name="lucide:map-pin" class="text-slate-500 dark:text-slate-400" size="1em" />
          <div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Alamat</div>
            <div class="font-semibold text-slate-800 dark:text-white">{{ datas.order?.customer?.address || '-' }}</div>
          </div>
        </div>
      </div>
      <div class="space-y-4">
        <div class="flex items-center gap-3">
          <Icon name="lucide:phone" class="text-slate-500 dark:text-slate-400" size="1em" />
          <div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Telepon</div>
            <div class="font-semibold text-slate-800 dark:text-white">{{ datas.order?.customer?.phone || '-' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Order Information -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 flex items-center justify-center">
        <Icon name="lucide:package" class="text-purple-600 dark:text-purple-400" size="0.9em" />
      </div>
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Order</h3>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="space-y-4">
        <div class="flex items-center gap-3">
          <Icon name="lucide:hash" class="text-slate-500 dark:text-slate-400" size="1em" />
          <div>
            <div class="text-sm text-slate-500 dark:text-slate-400">No. Order</div>
            <div class="font-semibold text-slate-800 dark:text-white">#{{ datas.order?.no_order || '-' }}</div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <Icon name="lucide:box" class="text-slate-500 dark:text-slate-400" size="1em" />
          <div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Produk</div>
            <div class="font-semibold text-slate-800 dark:text-white">{{ datas.order?.product?.name || '-' }}</div>
          </div>
        </div>
      </div>
      <div class="space-y-4">
        <div class="flex items-center gap-3">
          <Icon name="lucide:calendar" class="text-slate-500 dark:text-slate-400" size="1em" />
          <div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Tanggal Order</div>
            <div class="font-semibold text-slate-800 dark:text-white">{{ formatDate(datas.order?.order_date) || '-' }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Timeline Information -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-100 to-red-100 dark:from-orange-900/30 dark:to-red-900/30 flex items-center justify-center">
        <Icon name="lucide:clock" class="text-orange-600 dark:text-orange-400" size="0.9em" />
      </div>
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Timeline Pengerjaan</h3>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="space-y-4">
        <div class="flex items-center gap-3">
          <div class="w-3 h-3 rounded-full" :class="datas.tanggal_pengerjaan ? 'bg-green-500' : 'bg-gray-300'"></div>
          <div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Tanggal Mulai</div>
            <div class="font-semibold text-slate-800 dark:text-white">
              {{ datas.tanggal_pengerjaan ? formatDate(datas.tanggal_pengerjaan) : 'Belum dimulai' }}
            </div>
          </div>
        </div>
      </div>
      <div class="space-y-4">
        <div class="flex items-center gap-3">
          <div class="w-3 h-3 rounded-full" :class="datas.tanggal_selesai ? 'bg-green-500' : 'bg-gray-300'"></div>
          <div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Tanggal Selesai</div>
            <div class="font-semibold text-slate-800 dark:text-white">
              {{ datas.tanggal_selesai ? formatDate(datas.tanggal_selesai) : 'Belum selesai' }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Aksi</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">Kelola jobdesk atau tutup dialog</p>
      </div>
    </div>
    
    <div class="flex flex-wrap gap-3 justify-end">
      <Button 
        @click="emits('goToEdit', datas)"
        class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:edit" size="1em" />
        <span>Edit Jobdesk</span>
      </Button>
      
      <Button 
        @click="emits('closeDialog')"
        class="flex items-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-500 to-gray-600 hover:from-slate-600 hover:to-gray-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:x" size="1em" />
        <span>Tutup</span>
      </Button>
    </div>
  </div>
</template>

<script setup lang="ts">
const emits = defineEmits(['closeDialog', 'error', 'goToEdit'])
const { datas } = defineProps(['datas'])
const client = useSanctumClient()

onMounted(() => {
  datas.order ??= {} as any;
  datas.user ??= {} as any;
})

// Helper functions for status
const getStatusBadge = () => {
  const status = datas.status || 'Masuk'
  switch (status) {
    case 'Masuk':
      return 'Belum Dikerjakan'
    case 'Progress':
      return 'Sedang Dikerjakan'
    case 'Selesai':
      return 'Sudah Selesai'
    default:
      return status
  }
}

const getStatusClass = () => {
  const status = datas.status || 'Masuk'
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

const sendNotification = async (id: number) => {
  try {
    const data = {
      jobdesk_id: id,
      description: 'Pemberitahuan Order Baru',
      title: 'Order Baru',
    }
    await client(`/api/jobdesk-reminder`, {
        method: 'POST',
        body: data,
    })
    emits('closeDialog')
  } catch (error: any) {
    emits('error', error.response._data.message)
  }
}

const formatDate = (date: string) => {
  if (!date) return '-';
  return new Date(date).toLocaleString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
}
</script>