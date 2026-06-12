<template>
  <div class="max-sm:flex-wrap flex gap-4 mb-4">
      <StatCard @onclick="navigateTo('/jobdesk?status=Progress')" class="grow cursor-pointer" title="Jobdesk Aktif" type="warning" :value="datas.jobdesk_on_progress + ' Jobdesk' || 0" classValue="text-2xl cursor-pointer" icon="lucide:clock"/>
      <StatCard @onclick="navigateTo('/jobdesk?status=selesai')" class="grow cursor-pointer" title="Jobdesk Selesai" type="success" :value="datas.jobdesk_selesai + ' Jobdesk' || 0" classValue="text-2xl cursor-pointer" icon="lucide:check"/>
      <StatCard @onclick="navigateTo('/jobdesk?status=Masuk')" class="grow" title="Total Jobdesk" type="secondary" :value="datas.total_jobdesk + ' Jobdesk' || 0" icon="lucide:box" />
  </div>
</template>

<script lang="ts" setup>
  const { datas } = defineProps(['datas'])
</script>