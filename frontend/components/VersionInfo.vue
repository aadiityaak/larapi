<template>
  <div class="flex justify-end italic text-slate-500">
    <small class="bg-zinc-100 dark:bg-zinc-800 px-3 py-1 rounded-full text-xs shadow-md" v-if="info">
      Asisten Notaris V2 | Build: {{ info.version }} | {{ buildDateFormated }}
    </small>
    <small class="bg-red-100 dark:bg-red-900 px-3 py-1 rounded-full text-xs text-red-700 dark:text-red-300" v-else>
      Loading version info...
    </small>
  </div>
</template>

<script setup lang="ts">
const info = ref(null);

const buildDateFormated = computed(() => {
  if (!info.value?.buildDate) return '';
  return new Date(info.value.buildDate).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: 'numeric',
    minute: 'numeric',
    second: 'numeric'
  });
});

onMounted(async () => {
  info.value = await useBuildInfo();
});
</script>