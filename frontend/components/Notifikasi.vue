<template>
  <div class="relative">
    <button 
      @click="toggleNotif" 
      class="relative p-2 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-all duration-200 group"
    >
      <Icon 
        name="lucide:bell" 
        size="1.1em" 
        class="text-slate-600 dark:text-slate-300 group-hover:text-slate-800 dark:group-hover:text-white transition-colors" 
      />
      <!-- Notification Badge dengan efek pulse yang menarik -->
      <div 
        v-if="notifications.length > 0" 
        class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center px-1 z-20"
        style="transform-origin: center center;"
      >
        <span class="relative z-30 font-bold">{{ notifications.length > 99 ? '99+' : notifications.length }}</span>
        <!-- Ring pulse effects positioning tetap -->
        <div class="absolute inset-0 rounded-full bg-red-500 animate-ping opacity-75 z-10" style="transform-origin: center center;"></div>
        <div class="absolute inset-0 rounded-full bg-red-400 animate-pulse opacity-50 z-10" style="transform-origin: center center;"></div>
        <!-- Pulse ring container -->
        <div class="absolute inset-0 rounded-full animate-pulse-ring z-10" style="transform-origin: center center;"></div>
      </div>
    </button>
    
    <Popover ref="opNotif" class="notification-popover">
      <!-- Header -->
      <div class="px-4 py-3 border-b border-slate-200 dark:border-zinc-700 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-zinc-800 dark:to-zinc-700 rounded-t-xl -m-0">
        <h3 class="font-semibold text-slate-800 dark:text-white">Notifikasi</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400">{{ notifications.length }} notifikasi belum dibaca</p>
      </div>
      
      <!-- Notifications List -->
      <div class="w-[24rem] max-h-64 overflow-y-auto -mx-0">
        <div v-if="notifications.length === 0" class="p-6 text-center">
          <Icon name="lucide:bell-off" size="2em" class="text-slate-400 dark:text-zinc-500 mx-auto mb-3" />
          <p class="text-sm text-slate-500 dark:text-slate-400">Tidak ada notifikasi baru</p>
        </div>
        
        <div v-else>
          <div 
            v-for="notif in notifications" 
            :key="notif.id" 
            class="p-3 border-b border-slate-100 dark:border-zinc-800 hover:bg-slate-50 dark:hover:bg-zinc-800/50 cursor-pointer transition-colors last:border-b-0 -mx-0" 
            @click="readNotifikasi(notif)"
          >
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                <Icon name="lucide:bell" class="text-white" size="0.8em" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-800 dark:text-white truncate">{{ notif.message }}</p>
                <div class="flex items-center gap-1 mt-1 text-xs text-slate-500 dark:text-slate-400">
                  <Icon name="lucide:clock" size="0.7em" />
                  <FormatDateTime :value="notif.time" />
                </div>
              </div>
              <div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-2"></div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Footer -->
      <div v-if="notifications.length > 0" class="px-4 py-3 border-t border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800/50 rounded-b-xl -mx-0 -mb-0">
        <NuxtLink 
          to="/notifications" 
          class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors"
          @click="opNotif.hide()"
        >
          Lihat semua notifikasi
        </NuxtLink>
      </div>
    </Popover>
  </div>
</template>

<script setup>
const client = useSanctumClient();
const notifications = ref([]);
const opNotif = ref(null);
let intervalId;

onMounted(() => {
  fetchNotifications();
  intervalId = setInterval(fetchNotifications, 10000); // Setiap 10 detik
});

onUnmounted(() => {
  if (intervalId) {
    clearInterval(intervalId);
  }
});

const fetchNotifications = async () => {
  try {
    const response = await client('/api/notifications/unread');
    notifications.value = response || [];
  } catch (error) {
    console.error('Error fetching notifications:', error);
    notifications.value = [];
  }
};

const readNotifikasi = async (notif) => {
  try {
    await client(`/api/notifications/read/${notif.id}`, { method: 'PUT' });
    // Remove the read notification from the list
    notifications.value = notifications.value.filter(n => n.id !== notif.id);
    
    // Navigate to the notification URL if available
    if (notif.url) {
      navigateTo(`${notif.url}&refresh=true`);
    }
    opNotif.value.hide();
  } catch (error) {
    console.error('Error marking notification as read:', error);
  }
};

const toggleNotif = (event) => {
  opNotif.value.toggle(event);
};
</script>

<style scoped>
/* Remove default PrimeVue Popover padding and apply seamless styling */
:deep(.p-popover-content) {
  padding: 0 !important;
  margin: 0 !important;
  background: rgba(255, 255, 255, 0.95) !important;
  backdrop-filter: blur(12px) !important;
  border: 1px solid rgba(148, 163, 184, 0.3) !important;
  border-radius: 0.75rem !important;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
  overflow: hidden !important;
  width: 24rem !important;
  max-height: 20rem !important;
}

/* Dark mode styling */
:deep(.dark .p-popover-content) {
  background: rgba(24, 24, 27, 0.95) !important;
  border-color: rgba(113, 113, 122, 0.3) !important;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2) !important;
}

/* Seamless content integration */
.notification-popover :deep(.p-popover-content) {
  padding: 0 !important;
  margin: 0 !important;
}

/* Remove any default gaps or spacing */
.notification-popover :deep(.p-popover-content > *) {
  margin: 0 !important;
}

/* Ensure header and footer align perfectly with container */
.notification-popover :deep(.p-popover-content > div:first-child) {
  border-top-left-radius: 0.75rem !important;
  border-top-right-radius: 0.75rem !important;
  margin-top: 0 !important;
}

.notification-popover :deep(.p-popover-content > div:last-child) {
  border-bottom-left-radius: 0.75rem !important;
  border-bottom-right-radius: 0.75rem !important;
  margin-bottom: 0 !important;
}

/* Custom pulse ring animation untuk badge notifikasi */
@keyframes pulse-ring {
  0% {
    box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
  }
  50% {
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.3);
  }
  100% {
    box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
  }
}

.animate-pulse-ring {
  animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Efek ting-ting dengan multiple rings - hanya untuk hover */
@keyframes ting-ting {
  0%, 20%, 50%, 80%, 100% {
    transform: scale(1) rotate(0deg);
    box-shadow: 
      0 0 0 0 rgba(239, 68, 68, 0.7),
      0 0 0 0 rgba(239, 68, 68, 0.4);
  }
  10% {
    transform: scale(1.05) rotate(-1deg);
    box-shadow: 
      0 0 0 3px rgba(239, 68, 68, 0.5),
      0 0 0 6px rgba(239, 68, 68, 0.2);
  }
  30% {
    transform: scale(1.03) rotate(1deg);
    box-shadow: 
      0 0 0 5px rgba(239, 68, 68, 0.3),
      0 0 0 10px rgba(239, 68, 68, 0.1);
  }
  40% {
    transform: scale(1.01) rotate(-0.5deg);
    box-shadow: 
      0 0 0 2px rgba(239, 68, 68, 0.4),
      0 0 0 8px rgba(239, 68, 68, 0.15);
  }
}

/* Hover effect untuk badge - hanya pada ring container */
.animate-pulse-ring:hover {
  animation: ting-ting 1.5s ease-in-out infinite;
}

/* Gradient shimmer effect - lebih subtle */
@keyframes shimmer {
  0% {
    background-position: -200% center;
  }
  100% {
    background-position: 200% center;
  }
}

/* Shimmer hanya pada badge container, bukan pada positioning */
.animate-pulse-ring::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.3),
    transparent
  );
  background-size: 200% 100%;
  border-radius: inherit;
  animation: shimmer 3s ease-in-out infinite;
  pointer-events: none;
}
</style>
