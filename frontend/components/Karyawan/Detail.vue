<template>
  <div class="space-y-6">
    <!-- Profile Header -->
    <div class="text-center">
      <div class="relative inline-block mb-4">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full blur-lg opacity-30"></div>
        <Avatar 
          v-if="datas.avatar !== null" 
          :image="datas.avatar" 
          shape="circle" 
          size="xlarge"
          class="relative ring-4 ring-white dark:ring-zinc-700 shadow-2xl"
          :pt="{
            image: 'object-cover w-24 h-24',
          }"
        />
        <Avatar 
          v-else 
          shape="circle" 
          size="xlarge" 
          class="relative bg-gradient-to-br from-blue-500 to-purple-600 text-white font-bold text-2xl ring-4 ring-white dark:ring-zinc-700 shadow-2xl"
        > 
          {{ datas.name.charAt(0) }} 
        </Avatar>
      </div>
      
      <div class="space-y-2">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">{{ datas.name }}</h2>
        <div v-if="datas.is_admin === '1'" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-full shadow-lg">
          <Icon name="lucide:verified" class="w-4 h-4" /> 
          <span class="font-semibold">Administrator</span>
        </div>
        <p class="text-slate-600 dark:text-slate-400">{{ datas.position }}</p>
      </div>
    </div>

    <!-- Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Personal Info Card -->
      <div class="backdrop-blur-xl bg-gradient-to-br from-blue-50/80 to-indigo-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-blue-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-3 mb-4">
          <div class="p-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg shadow-lg">
            <Icon name="lucide:user" class="w-5 h-5 text-white" />
          </div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Personal</h3>
        </div>
        
        <div class="space-y-4">
          <div class="flex items-start gap-3">
            <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
              <Icon name="lucide:mail" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
            </div>
            <div>
              <p class="text-sm text-slate-600 dark:text-slate-400">Email</p>
              <p class="font-semibold text-slate-800 dark:text-white">{{ datas.email }}</p>
            </div>
          </div>
          
          <div class="flex items-start gap-3">
            <div class="p-2 bg-green-100 dark:bg-green-900/50 rounded-lg">
              <Icon name="lucide:phone" class="w-4 h-4 text-green-600 dark:text-green-400" />
            </div>
            <div>
              <p class="text-sm text-slate-600 dark:text-slate-400">Telepon</p>
              <p class="font-semibold text-slate-800 dark:text-white">{{ datas.phone }}</p>
            </div>
          </div>
          
          <div class="flex items-start gap-3">
            <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg">
              <Icon name="lucide:map-pin" class="w-4 h-4 text-purple-600 dark:text-purple-400" />
            </div>
            <div>
              <p class="text-sm text-slate-600 dark:text-slate-400">Alamat</p>
              <p class="font-semibold text-slate-800 dark:text-white">{{ datas.address || 'Belum diisi' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Statistics Card -->
      <div class="backdrop-blur-xl bg-gradient-to-br from-green-50/80 to-emerald-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-green-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-3 mb-4">
          <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg shadow-lg">
            <Icon name="lucide:bar-chart-3" class="w-5 h-5 text-white" />
          </div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Statistik Kerja</h3>
        </div>
        
        <div class="space-y-4">
          <!-- Progress Overview -->
          <div class="p-4 bg-white/60 dark:bg-zinc-700/60 rounded-xl">
            <div class="flex justify-between items-center mb-2">
              <span class="text-sm text-slate-600 dark:text-slate-400">Progress Jobdesk</span>
              <span class="font-bold text-green-600 dark:text-green-400">
                {{ Math.round((datas.jobdesk_selesai / datas.total_jobdesk) * 100) }}%
              </span>
            </div>
            <div class="w-full bg-slate-200 dark:bg-zinc-600 rounded-full h-3 overflow-hidden">
              <div 
                class="h-full bg-gradient-to-r from-green-500 to-emerald-600 rounded-full transition-all duration-500"
                :style="{ width: Math.round((datas.jobdesk_selesai / datas.total_jobdesk) * 100) + '%' }"
              ></div>
            </div>
            <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400 mt-1">
              <span>{{ datas.jobdesk_selesai }} selesai</span>
              <span>{{ datas.total_jobdesk }} total</span>
            </div>
          </div>

          <!-- Stats Grid -->
          <div class="grid grid-cols-2 gap-4">
            <div class="text-center p-4 bg-white/60 dark:bg-zinc-700/60 rounded-xl">
              <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ datas.jobdesk_selesai }}</div>
              <div class="text-xs text-slate-600 dark:text-slate-400">Selesai</div>
            </div>
            <div class="text-center p-4 bg-white/60 dark:bg-zinc-700/60 rounded-xl">
              <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ datas.total_jobdesk }}</div>
              <div class="text-xs text-slate-600 dark:text-slate-400">Total</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Role Information -->
    <div class="backdrop-blur-xl bg-gradient-to-br from-purple-50/80 to-pink-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-purple-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
      <div class="flex items-center gap-3 mb-4">
        <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-lg">
          <Icon name="lucide:shield-check" class="w-5 h-5 text-white" />
        </div>
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Jabatan</h3>
      </div>
      
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Posisi</p>
          <p class="text-xl font-bold text-slate-800 dark:text-white">{{ datas.position }}</p>
        </div>
        <div class="flex items-center gap-2">
          <Badge 
            class="px-4 py-2 text-sm font-medium"
            :class="{
              'bg-gradient-to-r from-red-500 to-pink-600 text-white': datas.is_admin === '1',
              'bg-gradient-to-r from-slate-500 to-gray-600 text-white': datas.is_admin !== '1'
            }"
          >
            {{ datas.is_admin === '1' ? 'Administrator' : 'Staff' }}
          </Badge>
        </div>
      </div>
    </div>

    <!-- Login History -->
    <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg">
      <div class="flex items-center gap-3 mb-4">
        <div class="p-2 bg-gradient-to-br from-slate-500 to-gray-600 rounded-lg shadow-lg">
          <Icon name="lucide:history" class="w-5 h-5 text-white" />
        </div>
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Riwayat Login</h3>
      </div>

      <div v-if="loginHistories.length === 0" class="text-sm text-slate-600 dark:text-slate-400">
        Belum ada riwayat login
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left text-slate-600 dark:text-slate-300 border-b border-slate-200/50 dark:border-zinc-700/50">
              <th class="py-2 pr-4 font-semibold">Waktu</th>
              <th class="py-2 pr-4 font-semibold">IP</th>
              <th class="py-2 font-semibold">User Agent</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in loginHistories"
              :key="item.id"
              class="border-b border-slate-100/60 dark:border-zinc-800/60"
            >
              <td class="py-2 pr-4 whitespace-nowrap text-slate-800 dark:text-slate-200">
                <FormatDateTime :value="item.created_at" />
              </td>
              <td class="py-2 pr-4 whitespace-nowrap text-slate-700 dark:text-slate-300">
                {{ item.ip_address || '-' }}
              </td>
              <td class="py-2 text-slate-700 dark:text-slate-300 break-words">
                {{ item.user_agent || '-' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end gap-3 pt-4 border-t border-slate-200/50 dark:border-zinc-700/50">
      <Button 
        @click="emits('closeDialog')"
        class="px-6 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-slate-700 dark:text-slate-300 rounded-xl transition-all duration-200 flex items-center gap-2"
        size="small"
      >
        <Icon name="lucide:x" class="w-4 h-4" />
        Tutup
      </Button>
    </div>
  </div>
</template>

<script lang="ts" setup>
    import FormatDateTime from '~/components/Format/DateTime.vue'

    const emits = defineEmits(['closeDialog'])
    const { datas } = defineProps(['datas'])
    const loginHistories = computed(() => Array.isArray((datas as any)?.login_histories) ? (datas as any).login_histories : [])
</script>
