<template>
  <div :class="cardClass" class="p-6 rounded-2xl relative overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 h-[140px] flex flex-col justify-between backdrop-blur-sm border border-white/20 dark:border-zinc-700/30">
    <!-- Background decorative icon -->
    <div v-if="icon" class="absolute -bottom-2 -right-2 opacity-4">
      <Icon :name="icon" class="text-6xl text-slate-400 dark:text-zinc-100" />
    </div>
    
    <div class="relative z-10 flex flex-col justify-between h-full">
      <!-- Header -->
      <div class="flex items-center justify-between mb-2">
        <h3 class="text-xs font-semibold text-slate-700 dark:text-zinc-100 uppercase tracking-wider">{{ title }}</h3>
        <div v-if="iconfront" class="p-1.5 rounded-lg from-indigo-900 to-blue-900 dark:from-indigo-500 dark:to-blue-500 backdrop-blur-sm">
          <Icon :name="iconfront" class="text-base text-slate-900 dark:text-zinc-100"></Icon>
        </div>
      </div>
      
      <!-- Value -->
      <div class="font-bold text-slate-600 dark:text-zinc-100 mb-2" :class="classValue">
        <FormatRupiah v-if="format === 'currency'" :value="value" />
        <FormatDate v-else-if="format === 'date'" :value="value" />
        <span v-else>{{ value }}</span>
      </div>
      
      <!-- Subtitle -->
      <span v-if="subtitle" class="text-xs text-slate-300 dark:text-zinc-600 mb-2 block font-medium">
        {{ subtitle }}
      </span>
      
      <!-- Trend indicator -->
      <div v-if="trend" class="flex items-center text-xs mt-auto">
        <div class="flex items-center px-2 py-1 rounded-md bg-slate-500/20 dark:bg-zinc-400/20 backdrop-blur-sm">
          <Icon :name="trend > 0 ? 'lucide:trending-up' : 'lucide:trending-down'" 
                :class="trend > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-700 dark:text-red-100'" 
                class="mr-1 text-xs" />
          <span :class="trend > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-700 dark:text-red-100'" class="font-semibold text-xs">
            {{ Math.abs(trend) }}%
          </span>
          <span class="text-slate-600 dark:text-zinc-600 ml-1 text-xs">vs bulan lalu</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
const props = defineProps({
  title: String,
  value: [String, Number],
  subtitle : String,
  type: {
    type: String,
    default: 'success',
  },
  format: String,
  icon : String,
  iconfront : String,
  trend: Number,
  classValue: {
    type: String,
    default: 'text-2xl',
  }
});

const cardClass = computed(() => {
  switch (props.type) {
    case 'primary':
      return 'bg-gradient-to-r from-rose-300/50 to-pink-300/50 dark:from-rose-400/60 dark:to-pink-400/60';
    case 'success':
      return 'bg-gradient-to-r from-green-300/50 to-emerald-300/50 dark:from-green-400/60 dark:to-emerald-400/60';
    case 'secondary':
      return 'bg-gradient-to-r from-slate-300/50 to-zinc-300/50 dark:from-slate-400/60 dark:to-zinc-400/60';
    case 'info':
      return 'bg-gradient-to-r from-blue-300/50 to-cyan-300/50 dark:from-blue-400/60 dark:to-cyan-400/60';
    case 'error':
      return 'bg-gradient-to-r from-red-300/50 to-rose-300/50 dark:from-red-400/60 dark:to-rose-400/60';
    case 'warning':
      return 'bg-gradient-to-r from-yellow-300/50 to-amber-300/50 dark:from-yellow-400/60 dark:to-amber-400/60';
    default:
      return 'bg-gradient-to-r from-slate-300/50 to-zinc-300/50 dark:from-slate-400/60 dark:to-zinc-400/60';
  }
});
</script>