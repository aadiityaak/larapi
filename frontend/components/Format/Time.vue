<template>
  {{ formattedTime }}
</template>

<script setup lang="ts">
const props = defineProps({
  value: {
    type: String || Date,
    required: true,
  },
})

const formattedTime = computed(() => {
  const options: Intl.DateTimeFormatOptions = {
    hour: 'numeric',
    minute: 'numeric',
  }
  const dateObject = new Date(props.value)
  return isNaN(dateObject.getTime()) ? 'Invalid Date' : dateObject.toLocaleTimeString('id-ID', options)
})
</script>