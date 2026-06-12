<template>
  <div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-6 pb-4 border-b border-slate-200 dark:border-zinc-700">
      <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Notifikasi</h2>
      <p class="text-sm text-slate-600 dark:text-slate-400">
        {{ data?.length || 0 }} notifikasi ditemukan
      </p>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
      <div v-if="!data || data.length === 0" class="text-center py-12">
        <Icon name="lucide:bell-off" size="3em" class="text-slate-300 dark:text-zinc-600 mx-auto mb-4" />
        <h3 class="text-lg font-medium text-slate-600 dark:text-slate-400 mb-2">Tidak ada notifikasi</h3>
        <p class="text-sm text-slate-500 dark:text-slate-500">Semua notifikasi akan muncul di sini</p>
      </div>

      <div v-else>
        <div 
          v-for="item in data" 
          :key="item.id"
          class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-200"
          :class="{ 'ring-2 ring-blue-500/20 bg-blue-50/50 dark:bg-blue-900/10': item.read_at === null }"
        >
          <div class="flex items-start justify-between gap-4">
            <!-- Notification Content -->
            <div 
              class="flex-1 cursor-pointer" 
              @click="openNotifikasi(item)"
            >
              <div class="flex items-start gap-3">
                <!-- Icon -->
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center flex-shrink-0 mt-1">
                  <Icon name="lucide:bell" class="text-white" size="1em" />
                </div>
                
                <!-- Content -->
                <div class="flex-1 min-w-0">
                  <h3 
                    class="font-semibold text-base mb-2 transition-colors"
                    :class="item.read_at === null ? 'text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-300'"
                  >
                    {{ item.data.message }}
                  </h3>
                  
                  <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                    <Icon name="lucide:clock" size="0.8em" />
                    <span>{{ dateFormat(item.created_at) }}</span>
                    
                    <!-- Unread indicator -->
                    <div v-if="item.read_at === null" class="flex items-center gap-1 ml-2">
                      <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                      <span class="text-xs font-medium text-blue-600 dark:text-blue-400">Baru</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Action Button -->
            <div class="flex-shrink-0">
              <button
                @click="readNotifikasi(item.id)"
                class="p-2 rounded-lg transition-all duration-200 group relative"
                :class="item.read_at === null 
                  ? 'bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50' 
                  : 'bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700'"
                :title="item.read_at === null ? 'Tandai sudah dibaca' : 'Sudah dibaca'"
              >
                <Icon 
                  :name="item.read_at === null ? 'lucide:mail' : 'lucide:mail-open'" 
                  size="1.1em"
                  :class="item.read_at === null 
                    ? 'text-blue-600 dark:text-blue-400' 
                    : 'text-slate-600 dark:text-slate-400'"
                />
                
                <!-- Unread dot indicator -->
                <div 
                  v-if="item.read_at === null" 
                  class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 border-2 border-white dark:border-zinc-900 rounded-full"
                ></div>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  datas: {
    type: Object,
    default: () => ({})
  }
})

const client = useSanctumClient()

const { data, error, refresh } = await useAsyncData('notifikasi', fetchNotifikasi);

function fetchNotifikasi() {
  return client(`/api/notifications`);
}

const openNotifikasi = async (item) => {
  try {
    // Mark as read
    await client(`/api/notifications/${item.id}`, {
      method: 'PUT',
    });
    
    // Navigate to the notification target
    if (item.data?.notifiable?.id) {
      await navigateTo(`/order?order_id=${item.data.notifiable.id}`)
    }
    
    // Refresh the list
    refresh();
  } catch (error) {
    console.error('Error opening notification:', error)
  }
}

const readNotifikasi = async ($id) => {
  try {
    await client(`/api/notifications/${$id}`, {
      method: 'PUT',
    });
    refresh();
  } catch (error) {
    console.error('Error marking notification as read:', error);
  }
}

const dateFormat = (date) => {
  const dateObj = new Date(date);
  return dateObj.toLocaleString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}
</script>