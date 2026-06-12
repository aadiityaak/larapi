<template>
  <div>
   <embed :src="pdfUrl" type="application/pdf" width="100%" height="600px" />
  </div>
</template>

<script setup lang="ts">
const { datas } = defineProps(['datas']);

// Tambahkan parameter query ke URL PDF
const pdfUrl = computed(() => {
 const url = new URL(datas.lampiran, window.location.origin);
 url.searchParams.set('toolbar', '0'); // Menonaktifkan toolbar
 url.searchParams.set('navpanes', '0'); // Menutup sidebar
 return url.toString();
});
</script>