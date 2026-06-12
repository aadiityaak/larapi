<template>
  <!-- Header Section with Order Info -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-center gap-4 mb-6">
      <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
        <Icon name="lucide:upload" class="text-white" size="1.2em" />
      </div>
      <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-1">Upload Dokumen Order</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Upload dokumen untuk order #{{ datas.no_order || datas.id }}</p>
      </div>
    </div>

    <!-- Order Summary Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Customer Info -->
      <div class="space-y-4">
        <div class="flex items-center gap-2 mb-3">
          <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 flex items-center justify-center">
            <Icon name="lucide:user" class="text-blue-600 dark:text-blue-400" size="0.8em" />
          </div>
          <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Konsumen</h3>
        </div>
        
        <div class="flex items-start gap-3 p-4 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-xl border border-blue-200/50 dark:border-blue-800/50">
          <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg flex-shrink-0">
            <span class="text-white font-bold text-sm">{{ datas.customer?.name?.charAt(0).toUpperCase() }}</span>
          </div>
          <div class="flex-1 min-w-0">
            <h4 class="font-semibold text-slate-800 dark:text-white mb-1">{{ datas.customer?.name }}</h4>
            <div class="space-y-1 text-xs text-slate-600 dark:text-slate-400">
              <div class="flex items-center gap-1">
                <Icon name="lucide:map-pin" size="0.7em" />
                <span class="truncate">{{ datas.customer?.address }}</span>
              </div>
              <div class="flex items-center gap-1">
                <Icon name="lucide:phone" size="0.7em" />
                <span>{{ datas.customer?.phone }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Info -->
      <div class="space-y-4">
        <div class="flex items-center gap-2 mb-3">
          <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 flex items-center justify-center">
            <Icon name="lucide:package" class="text-green-600 dark:text-green-400" size="0.8em" />
          </div>
          <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Detail Order</h3>
        </div>

        <div class="space-y-3">
          <div class="p-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200/50 dark:border-green-800/50">
            <div class="flex items-center gap-2 mb-2">
              <Icon name="lucide:package" class="text-green-600 dark:text-green-400" size="0.8em" />
              <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Produk</span>
            </div>
            <div class="font-semibold text-slate-800 dark:text-white">{{ datas.product?.name || 'Produk tidak tersedia' }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ datas.product?.category || 'Kategori tidak tersedia' }}</div>
          </div>

          <div class="p-4 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl border border-amber-200/50 dark:border-amber-800/50">
            <div class="flex items-center gap-2 mb-2">
              <Icon name="lucide:calendar" class="text-amber-600 dark:text-amber-400" size="0.8em" />
              <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Tanggal Order</span>
            </div>
            <div class="font-semibold text-slate-800 dark:text-white">
              <FormatDate :value="datas.order_date" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Current Document Preview -->
  <div v-if="datas.lampiran && typeof datas.lampiran === 'string'" class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 flex items-center justify-center">
        <Icon name="lucide:file-text" class="text-blue-600 dark:text-blue-400" size="0.9em" />
      </div>
      <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Dokumen Saat Ini</h3>
    </div>
    
    <OrderPreview :datas="datas" />
  </div>

  <!-- Upload Document Section -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <Form 
      ref="form"  
      @submit="handleUpdate" 
      class="space-y-6" 
      enctype="multipart/form-data"
    >
      <!-- Upload Section Header -->
      <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 flex items-center justify-center">
          <Icon name="lucide:upload-cloud" class="text-purple-600 dark:text-purple-400" size="0.9em" />
        </div>
        <div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Upload Dokumen Baru</h3>
          <p class="text-sm text-slate-500 dark:text-slate-400">Upload file PDF maksimal 15MB</p>
        </div>
      </div>

      <!-- File Upload Area -->
      <div class="space-y-4">
        <div class="p-6 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-zinc-800 dark:to-zinc-700 rounded-xl border-2 border-dashed border-slate-300 dark:border-zinc-600 hover:border-purple-400 dark:hover:border-purple-500 transition-colors">
          <div class="text-center">
            <div class="w-12 h-12 mx-auto mb-4 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg">
              <Icon name="lucide:file-plus" class="text-white" size="1.2em" />
            </div>
            
            <FileUpload 
              mode="basic" 
              name="lampiran" 
              accept=".pdf" 
              :maxFileSize="15000000" 
              @select="onFileUpload" 
              :auto="false" 
              chooseLabel="Pilih File PDF" 
              class="custom-file-upload"
            />
            
            <div class="mt-4 text-sm text-slate-600 dark:text-slate-400">
              <div class="flex items-center justify-center gap-2 mb-2">
                <Icon name="lucide:info" size="0.8em" />
                <span>Format yang didukung: PDF</span>
              </div>
              <div class="flex items-center justify-center gap-2">
                <Icon name="lucide:hard-drive" size="0.8em" />
                <span>Ukuran maksimal: 15MB</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Selected File Info -->
        <div v-if="selectedFile" class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border border-green-200/50 dark:border-green-800/50">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg">
              <Icon name="lucide:file-check" class="text-white" size="0.9em" />
            </div>
            <div class="flex-1">
              <div class="font-medium text-green-800 dark:text-green-200">File Terpilih</div>
              <div class="text-sm text-green-600 dark:text-green-400">{{ selectedFile.name || 'File PDF' }}</div>
              <div class="text-xs text-green-500 dark:text-green-500">
                {{ formatFileSize(selectedFile.size || 0) }}
              </div>
            </div>
            <Button
              @click="clearFile"
              class="px-3 py-2 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm"
            >
              <Icon name="lucide:x" size="0.8em" />
              <span>Hapus</span>
            </Button>
          </div>
        </div>
      </div>
    </Form>
  </div>

  <!-- Action Buttons -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Aksi</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">Upload dokumen atau kelola jobdesk</p>
      </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
      <!-- Upload Button -->
      <Button 
        v-if="isLoading"
        :disabled="true"
        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-400 to-slate-500 text-white rounded-xl font-medium cursor-not-allowed"
      >
        <Icon name="lucide:loader-2" class="animate-spin" size="1em" />
        <span>Mengupload...</span>
      </Button>
      <Button 
        v-else
        @click="handleUpdate"
        :disabled="!datas.lampiran"
        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 disabled:from-slate-300 disabled:to-slate-400 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:upload" size="1em" />
        <span>Upload Dokumen</span>
      </Button>

      <!-- View Jobdesk Button -->
      <Button 
        as="router-link" 
        :to="`/jobdesk?order_id=${datas.id}`"
        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:list" size="1em" />
        <span class="hidden sm:inline">{{ completedJobdeskCount }} / {{ totalJobdeskCount }} Jobdesk</span>
        <span class="sm:hidden">Jobdesk</span>
      </Button>

      <!-- Close Button -->
      <Button 
        @click="emits('closeDialog')"
        class="flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-500 to-gray-600 hover:from-slate-600 hover:to-gray-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
      >
        <Icon name="lucide:x" size="1em" />
        <span>Tutup</span>
      </Button>
    </div>

    <!-- Additional Info -->
    <div class="mt-4 p-3 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl border border-purple-200/50 dark:border-purple-800/50">
      <div class="flex items-start gap-2">
        <Icon name="lucide:info" class="text-purple-600 dark:text-purple-400 mt-0.5" size="0.9em" />
        <div class="text-sm text-purple-700 dark:text-purple-300">
          <p class="font-medium mb-1">Catatan Upload:</p>
          <ul class="text-xs space-y-1 text-purple-600 dark:text-purple-400">
            <li>• Hanya file PDF yang diperbolehkan</li>
            <li>• Ukuran maksimal file adalah 15MB</li>
            <li>• Dokumen akan menggantikan file sebelumnya</li>
            <li>• Pastikan dokumen sudah final sebelum upload</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

</template>

<script setup lang="ts">
const emits = defineEmits(['error', 'closeDialog', 'updateData'])
const client = useSanctumClient()
const datas = ref({ 
  customer: null, 
  service: '', 
  order_date: '', 
  id: 0, 
  jobdesks: [], 
  lampiran: null,
  product: null,
  no_order: ''
}) as any
const isLoading = ref(false)
const selectedFile = ref(null) as any
const props = defineProps(['datas'])

watch(
  () => props.datas,
  (newValue) => {
    if (newValue) {
      datas.value = { ...newValue }
    }
  },
  { immediate: true }
)

// Handle file upload
const onFileUpload = (event: any) => {
  const file = event.files[0] // PrimeVue menyimpan file dalam 'event.files'
  const validTypes = ['application/pdf']

  if (file && validTypes.includes(file.type)) {
    selectedFile.value = file
    datas.value.lampiran = file
  } else {
    selectedFile.value = null
    datas.value.lampiran = null
    emits('error', 'File harus berformat PDF')
  }
}

// Clear selected file
const clearFile = () => {
  selectedFile.value = null
  datas.value.lampiran = null
}

// Format file size
const formatFileSize = (bytes: number) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

// Handle document update
const handleUpdate = async () => {
  if (!selectedFile.value) {
    emits('error', 'Pilih file PDF terlebih dahulu')
    return
  }

  try {
    isLoading.value = true
    const formData = new FormData()

    if (selectedFile.value) {
      formData.append('lampiran', selectedFile.value)
    }
    
    const response = await client(`/api/orders/${datas.value.id}`, {
      method: 'PUT',
      body: formData,
    })

    datas.value = response
    selectedFile.value = null // Clear selected file after successful upload
    emits('updateData', response)
  } catch (error: any) {
    console.error('Upload error:', error)
    emits('error', error.response?._data?.message || 'Upload dokumen gagal! Periksa kembali file anda.')
  } finally {
    isLoading.value = false
  }
}

// Computed properties
const totalJobdeskCount = computed(() => datas.value.jobdesks?.length || 0)
const completedJobdeskCount = computed(() => 
  datas.value.jobdesks?.filter((jobdesk: any) => jobdesk.status === 'Selesai').length || 0
)
</script>

<style scoped>
/* Custom File Upload Styling */
:deep(.custom-file-upload .p-fileupload-choose) {
  background: linear-gradient(to right, rgb(147 51 234), rgb(219 39 119)) !important;
  border: none !important;
  border-radius: 0.75rem !important;
  padding: 0.75rem 1.5rem !important;
  font-weight: 600 !important;
  transition: all 0.2s ease !important;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
}

:deep(.custom-file-upload .p-fileupload-choose:hover) {
  background: linear-gradient(to right, rgb(126 34 206), rgb(190 24 93)) !important;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
  transform: translateY(-1px) !important;
}

:deep(.custom-file-upload .p-fileupload-choose:focus) {
  outline: none !important;
  box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.1) !important;
}

/* File Upload Icon */
:deep(.custom-file-upload .p-fileupload-choose .p-button-icon) {
  display: none !important;
}
</style>