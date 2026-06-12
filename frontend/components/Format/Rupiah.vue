<template>
  {{ formattedRupiah }}
</template>

<script setup lang="ts">
const props = defineProps({
  value: {
    type: [String, Number],
    default: 0,
    required: false,
  },
})

const formattedRupiah = computed(() => {
  if (props.value === null || props.value === undefined || props.value === '') {
    return 'Rp 0'
  }
  
  try {
    // Convert to number, handle string numbers
    let numericValue: number
    
    if (typeof props.value === 'string') {
      // If it's already formatted currency (contains "Rp"), return as is
      if (props.value.includes('Rp') && !props.value.match(/^\d/)) {
        return props.value
      }
      
      // Clean string and extract numbers
      const cleanString = props.value.replace(/[^\d.-]/g, '')
      numericValue = parseFloat(cleanString)
    } else {
      numericValue = Number(props.value)
    }
    
    // Validate the number
    if (isNaN(numericValue) || !isFinite(numericValue)) {
      return 'Rp 0'
    }
    
    return new Intl.NumberFormat('id-ID', { 
      style: 'currency', 
      currency: 'IDR',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(numericValue)
    
  } catch (error) {
    console.warn('Currency formatting error:', error, 'for value:', props.value)
    return 'Rp 0'
  }
})
</script>