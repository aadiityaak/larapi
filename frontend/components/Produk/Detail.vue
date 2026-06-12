<template>
  <div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8 text-center">
      <div class="flex items-center justify-center w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl shadow-lg">
        <Icon name="lucide:package" class="w-10 h-10 text-white" />
      </div>
      <h2 class="text-3xl font-bold text-slate-800 dark:text-white mb-2">Detail Produk</h2>
      <p class="text-slate-600 dark:text-slate-400">Informasi lengkap tentang produk yang dipilih</p>
    </div>

    <!-- Main Content -->
    <div class="space-y-6">
      <!-- Product Overview -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
        <div class="flex items-start gap-6">
          <div class="flex-shrink-0">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
              <Icon name="lucide:package-2" class="w-8 h-8 text-white" />
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">{{ datas.name }}</h3>
            <div class="flex items-center gap-2 mb-4">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200/50 dark:border-green-700/50">
                <Icon name="lucide:tag" class="w-3 h-3 mr-1" />
                {{ datas.category || 'Tidak ada kategori' }}
              </span>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200/50 dark:border-blue-700/50">
                <Icon name="lucide:shopping-cart" class="w-3 h-3 mr-1" />
                {{ datas.order_count || 0 }} Terjual
              </span>
            </div>
            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
              {{ datas.description || 'Tidak ada deskripsi tersedia' }}
            </p>
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
              <Icon name="lucide:package" class="w-6 h-6 text-white" />
            </div>
            <div>
              <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Produk</p>
              <p class="text-2xl font-bold text-slate-800 dark:text-white">1</p>
            </div>
          </div>
        </div>

        <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
              <Icon name="lucide:shopping-cart" class="w-6 h-6 text-white" />
            </div>
            <div>
              <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Terjual</p>
              <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ datas.order_count || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
              <Icon name="lucide:tags" class="w-6 h-6 text-white" />
            </div>
            <div>
              <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Meta Data</p>
              <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ (datas.meta_products || []).length }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Meta Products Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
            <Icon name="lucide:database" class="w-5 h-5 text-white" />
          </div>
          <h3 class="text-xl font-bold text-slate-800 dark:text-white">Data Produk</h3>
        </div>

        <div v-if="datas.meta_products && datas.meta_products.length > 0" class="space-y-3">
          <div 
            v-for="(item, index) in metaIds" 
            :key="index"
            class="flex items-center justify-between p-4 bg-slate-50/50 dark:bg-zinc-800/50 rounded-xl border border-slate-200/30 dark:border-zinc-700/30 hover:bg-slate-50 dark:hover:bg-zinc-800/70 transition-colors duration-200"
          >
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">{{ index + 1 }}</span>
              </div>
              <div>
                <p class="font-medium text-slate-800 dark:text-white">
                  {{ selectMeta.find((meta: any) => meta.id === item)?.name || 'Data tidak ditemukan' }}
                </p>
                <p class="text-sm text-slate-500 dark:text-slate-400">ID: {{ item }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200/50 dark:border-blue-700/50">
                <Icon name="lucide:tag" class="w-3 h-3 mr-1" />
                {{ selectMeta.find((meta: any) => meta.id === item)?.type || 'Unknown' }}
              </span>
            </div>
          </div>
        </div>
        
        <div v-else class="text-center py-8">
          <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-800 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <Icon name="lucide:inbox" class="w-8 h-8 text-slate-400" />
          </div>
          <p class="text-slate-500 dark:text-slate-400">Tidak ada data produk yang tersedia</p>
        </div>
      </div>
    </div>
    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3 justify-end pt-6 border-t border-slate-200/50 dark:border-zinc-700/50 mt-8">
      <Button 
        @click="emits('goToEdit')" 
        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105"
      >
        <Icon name="lucide:edit-3" class="w-4 h-4 mr-2" />
        Edit Produk
      </Button>
      
      <Button 
        @click="emits('closeDialog')"
        class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105"
      >
        <Icon name="lucide:x" class="w-4 h-4 mr-2" />
        Tutup
      </Button>
    </div>
  </div>
</template>

<script setup lang="ts">
const client = useSanctumClient()
const emits = defineEmits(['refreshData', 'error', 'closeDialog', 'goToEdit'])
const { datas } = defineProps(['datas'])
const selectMeta = ref([] as any)
const metaIds = computed(() => {
  const raw = Array.isArray((datas as any)?.meta_products)
    ? (datas as any).meta_products
    : Array.isArray((datas as any)?.meta)
      ? (datas as any).meta
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
})

onMounted(async () => {
  try {
    const response = await client('/api/metas?paginate=false')
    selectMeta.value = response
  } catch (error) {
    console.log(error)
  }
})
</script>

<style scoped>
/* Button Animations */
.transform:hover {
  transform: translateY(-2px) scale(1.02);
}

/* Card Hover Effects */
.backdrop-blur-xl:hover {
  transform: translateY(-1px);
  box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Stats Animation */
@keyframes countUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.text-2xl {
  animation: countUp 0.6s ease-out;
}

/* Badge Animations */
.inline-flex {
  transition: all 0.2s ease;
}

.inline-flex:hover {
  transform: scale(1.05);
}

/* Meta Products List Animation */
.space-y-3 > * {
  animation: fadeInUp 0.4s ease-out;
  animation-fill-mode: both;
}

.space-y-3 > *:nth-child(1) { animation-delay: 0.1s; }
.space-y-3 > *:nth-child(2) { animation-delay: 0.2s; }
.space-y-3 > *:nth-child(3) { animation-delay: 0.3s; }
.space-y-3 > *:nth-child(4) { animation-delay: 0.4s; }
.space-y-3 > *:nth-child(5) { animation-delay: 0.5s; }

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
