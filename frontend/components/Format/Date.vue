<template>
  {{ formattedDate }}
</template>

<script setup lang="ts">
const props = defineProps({
  value: {
    type: [String, Date],
    required: false,
    default: '',
  },
})

const formattedDate = computed(() => {
  if (!props.value || props.value === '') return '-'
  
  const options: Intl.DateTimeFormatOptions = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  }
  
  // Handle different date formats
  let dateObject: Date
  
  try {
    // If it's already a Date object
    if (props.value instanceof Date) {
      dateObject = props.value
    } else {
      // If it's a string, try to parse it
      const dateString = String(props.value).trim()
      
      // If it's already formatted (not ISO), return as is if it looks like formatted date
      if (dateString.match(/^\d{1,2}\s+\w+\s+\d{4}$/)) {
        return dateString
      }
      
      // Handle ISO string format (YYYY-MM-DDTHH:mm:ss.sssZ or similar)
      if (dateString.includes('T')) {
        dateObject = new Date(dateString)
      }
      // Handle date-only format (YYYY-MM-DD)
      else if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
        const [year, month, day] = dateString.split('-')
        dateObject = new Date(parseInt(year), parseInt(month) - 1, parseInt(day))
      }
      // Handle other formats
      else {
        dateObject = new Date(dateString)
      }
    }
    
    // Validate the date
    if (isNaN(dateObject.getTime())) {
      return 'Invalid Date'
    }
    
    return dateObject.toLocaleDateString('id-ID', options)
    
  } catch (error) {
    console.warn('Date formatting error:', error, 'for value:', props.value)
    return 'Invalid Date'
  }
})
</script>