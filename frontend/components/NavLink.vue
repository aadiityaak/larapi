<template>
    <li>
      <NuxtLink 
        :to="to" 
        class="flex ps-4 py-2 mb-1 items-center cursor-pointer rounded text-surface-700 
              hover:bg-zinc-200 dark:text-surface-0 dark:hover:bg-surface-800 
              duration-150 transition-colors p-ripple" 
        exact-active-class="bg-zinc-200 dark:bg-surface-700"
      >
        <slot />
      </NuxtLink>
    </li>
  </template>
  
  <script setup lang="ts">
  
  const props = defineProps({
    to: {
      type: String,
      required: true
    }
  });
  </script>
  
  <style scoped>
  /* Gaya tambahan jika diperlukan */
  </style>