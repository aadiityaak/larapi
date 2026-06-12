<template>
  <Paginator
    v-if="data && data.total && Number(data.total) > Number(data.per_page || 25)"
    :rows="data.per_page"
    :totalRecords="data.total"
    @page="onPageChange"
    aria-label="page"
    :pt="{
      root: (event) => {
        const itemForPage = data.per_page;
        const currentPage = page - 1;
        event.state.d_first = itemForPage * currentPage;
      },
    }"
  >
    <template #start="slotProps">
      Menampilkan <b>{{ data.from}} - {{ data.to}}</b> dari <b>{{ data.total }}</b> Data
    </template>
  </Paginator>
</template>

<script lang="ts" setup>
const props = defineProps(['value', 'page'])

// Alias value to data for easier access
const data = computed(() => props.value)
const emit = defineEmits(['pageChange'])


const onPageChange = (event) => {
  emit('pageChange', event)
}
</script>