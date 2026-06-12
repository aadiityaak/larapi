<template>
  <div class="bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-800 dark:to-purple-800 rounded-2xl p-8 text-white relative overflow-hidden mb-6">
    <!-- Background decorative elements -->
    <div class="absolute top-0 right-0 opacity-10">
      <Icon name="lucide:sparkles" class="text-8xl" />
    </div>
    <div class="absolute bottom-0 left-0 opacity-5">
      <Icon name="lucide:target" class="text-6xl" />
    </div>
    
    <div class="relative z-10">
      <div class="flex items-start justify-between">
        <div class="flex-1">
          <div class="flex items-center mb-3">
            <div class="p-2 bg-white/20 rounded-lg mr-3">
              <Icon :name="timeIcon" class="text-2xl text-white" />
            </div>
            <div>
              <h1 class="text-3xl font-bold mb-1">
                Selamat {{ waktu }}, {{ user.name }}! <span class="wave">👋</span>
              </h1>
              <p class="text-blue-100 dark:text-purple-100 text-lg">
                {{ currentDate }}
              </p>
            </div>
          </div>
          
          <div class="flex items-center gap-4 mt-4">
            <div class="flex items-center bg-white/20 rounded-lg px-3 py-2">
              <Icon name="lucide:briefcase" class="mr-2 text-lg" />
              <span class="font-semibold">{{ user.role[0] }}</span>
            </div>
            <div class="flex items-center bg-white/20 rounded-lg px-3 py-2">
              <Icon name="lucide:map-pin" class="mr-2 text-lg" />
              <span class="font-semibold">{{ user.position || 'Staff' }}</span>
            </div>
          </div>
        </div>
        
        <div class="hidden md:flex items-center">
          <div class="text-right">
            <div class="text-sm text-blue-100 dark:text-purple-100 mb-1">Status Hari Ini</div>
            <div class="flex items-center">
              <div class="w-3 h-3 bg-green-400 rounded-full mr-2 animate-pulse"></div>
              <span class="font-semibold">Online</span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Business info section dengan glass morphism subtle -->
      <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/15 transition-all duration-300">
          <div class="flex items-center">
            <div class="p-2 bg-white/20 rounded-lg mr-3">
              <Icon name="lucide:target" class="text-lg text-white" />
            </div>
            <div>
              <div class="text-sm text-blue-100 dark:text-purple-100 font-medium">Order Bulan Ini</div>
              <div class="text-xl font-bold text-white">{{ dashboardData?.order_bulan_ini || 0 }}</div>
            </div>
          </div>
        </div>
        
        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/15 transition-all duration-300">
          <div class="flex items-center">
            <div class="p-2 bg-white/20 rounded-lg mr-3">
              <Icon name="lucide:clock" class="text-lg text-white" />
            </div>
            <div>
              <div class="text-sm text-blue-100 dark:text-purple-100 font-medium">Waktu</div>
              <div class="text-xl font-bold text-white">{{ currentTime }}</div>
            </div>
          </div>
        </div>
        
        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/15 transition-all duration-300">
          <div class="flex items-center">
            <div class="p-2 bg-white/20 rounded-lg mr-3">
              <Icon name="lucide:history" class="text-lg text-white" />
            </div>
            <div>
              <div class="text-sm text-blue-100 dark:text-purple-100 font-medium">Terakhir Login</div>
              <div class="text-xl font-bold text-white">
                <FormatDateTime v-if="dashboardData?.last_login_at" :value="dashboardData.last_login_at" />
                <span v-else>-</span>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/15 transition-all duration-300">
          <div class="flex items-center">
            <div class="p-2 bg-white/20 rounded-lg mr-3">
              <Icon name="lucide:users" class="text-lg text-white" />
            </div>
            <div>
              <div class="text-sm text-blue-100 dark:text-purple-100 font-medium">Tim Aktif</div>
              <div class="text-xl font-bold text-white">{{ dashboardData?.total_karyawan || 0 }} Orang</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import FormatDateTime from '~/components/Format/DateTime.vue'

// Props untuk menerima data dashboard
interface Props {
  dashboardData?: any
}

const props = withDefaults(defineProps<Props>(), {
  dashboardData: null
})

const user = useSanctumUser() as any
const waktu = ref('pagi')
const currentTime = ref('')

const timeIcon = computed(() => {
  const hours = new Date().getHours()
  if (hours >= 5 && hours < 12) return 'lucide:sunrise'
  if (hours >= 12 && hours < 17) return 'lucide:sun'
  if (hours >= 17 && hours < 20) return 'lucide:sunset'
  return 'lucide:moon'
})

const currentDate = computed(() => {
  return new Date().toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

const updateTime = () => {
  const now = new Date()
  const hours = now.getHours()
  
  // Update greeting
  waktu.value = hours >= 5 && hours < 12 ? 'pagi' : 
                hours >= 12 && hours < 17 ? 'sore' : 
                hours >= 17 && hours < 20 ? 'sore' : 'malam'
  
  // Update current time
  currentTime.value = now.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  updateTime()
  // Update time every minute
  setInterval(updateTime, 60000)
})
</script>

<style scoped>
.wave {
  display: inline-block;
  animation: wave 2s infinite;
  transform-origin: 70% 70%;
}

@keyframes wave {
  0% { transform: rotate(0deg); }
  10% { transform: rotate(14deg); } 
  20% { transform: rotate(-8deg); }
  30% { transform: rotate(14deg); }
  40% { transform: rotate(-4deg); }
  50% { transform: rotate(10deg); }
  60% { transform: rotate(0deg); }
  100% { transform: rotate(0deg); }
}

/* Hover effect untuk interaksi */
.wave:hover {
  animation: wave-fast 0.6s infinite;
}

@keyframes wave-fast {
  0% { transform: rotate(0deg); }
  25% { transform: rotate(20deg); } 
  50% { transform: rotate(-10deg); }
  75% { transform: rotate(20deg); }
  100% { transform: rotate(0deg); }
}
</style>
