<template>
  <!-- Header Section with Form Info -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
        <Icon :name="!draftDatas.id ? 'lucide:user-plus' : 'lucide:user-pen'" class="text-white" size="1.2em" />
      </div>
      <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-1">
          {{ !draftDatas.id ? 'Tambah Konsumen Baru' : 'Edit Data Konsumen' }}
        </h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">
          {{ !draftDatas.id ? 'Lengkapi form untuk menambah konsumen baru' : `Perbarui informasi untuk ${draftDatas.name}` }}
        </p>
      </div>
    </div>
  </div>

  <!-- Form Section -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 mb-6">
    <Form v-slot="$form" ref="form" id="customer-form" :resolver :validateOnValueUpdate="true" :validateOnBlur="true" @submit="handleSubmit" class="space-y-6">
      <!-- Personal Information Section -->
      <div class="space-y-4">
        <div class="flex items-center gap-2 mb-4">
          <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 flex items-center justify-center">
            <Icon name="lucide:user" class="text-blue-600 dark:text-blue-400" size="0.9em" />
          </div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Personal</h3>
        </div>
        
        <!-- Name Field -->
        <div class="space-y-2">
          <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
            <Icon name="lucide:user" class="inline mr-2" size="0.9em" />
            Nama Lengkap *
          </label>
          <InputText 
            id="name" 
            name="name" 
            v-model="draftDatas.name" 
            placeholder="Masukkan nama lengkap konsumen..."
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 focus:ring-blue-500 bg-white dark:bg-zinc-900 px-4 py-3"
            :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': $form.name?.invalid }"
          />
          <Message v-if="$form.name?.invalid" severity="error" size="small" variant="simple" class="text-red-600 dark:text-red-400 text-sm">
            {{ $form.name.error.message }}
          </Message>
        </div>

        <!-- Phone Fields -->
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
              <Icon name="lucide:phone" class="inline mr-2" size="0.9em" />
              Nomor Telepon *
            </label>
            <Button 
              type="button" 
              @click="addPhone" 
              class="p-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200"
              size="small"
            >
              <Icon name="lucide:plus" class="w-4 h-4" />
            </Button>
          </div>

          <!-- Primary Phone -->
          <div class="flex items-center gap-2">
            <div class="flex-1">
              <InputText 
                id="phone" 
                name="phone" 
                v-model="draftDatas.phone" 
                placeholder="Nomor telepon utama"
                class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 focus:ring-blue-500 bg-white dark:bg-zinc-900 px-4 py-3"
                :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': $form.phone?.invalid }"
              />
            </div>
            <Badge class="bg-amber-500 text-white text-xs px-3 py-1 rounded-full">
              Utama
            </Badge>
          </div>
          <Message v-if="$form.phone?.invalid" severity="error" size="small" variant="simple" class="text-red-600 dark:text-red-400 text-sm">
            {{ $form.phone.error.message }}
          </Message>

          <!-- Additional Phones -->
          <div v-for="(phoneItem, index) in additionalPhones" :key="phoneItem.id || index" class="flex items-center gap-2">
            <div class="flex-1">
              <InputText 
                v-model="phoneItem.phone" 
                :placeholder="`Nomor telepon tambahan ${index + 1}`"
                class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 focus:ring-blue-500 bg-white dark:bg-zinc-900 px-4 py-3"
              />
            </div>
            <Badge class="bg-slate-500 text-white text-xs px-3 py-1 rounded-full">
              Tambahan
            </Badge>
            <Button 
              type="button" 
              @click="removePhone(index)" 
              class="p-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200"
              size="small"
            >
              <Icon name="lucide:trash-2" class="w-4 h-4" />
            </Button>
          </div>
        </div>

        <!-- Address Field -->
        <div class="space-y-2">
          <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
            <Icon name="lucide:map-pin" class="inline mr-2" size="0.9em" />
            Alamat Lengkap *
          </label>
          <Textarea 
            id="address" 
            name="address" 
            v-model="draftDatas.address" 
            placeholder="Masukkan alamat lengkap konsumen..."
            rows="3"
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 focus:ring-blue-500 bg-white dark:bg-zinc-900 px-4 py-3 resize-none"
            :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': $form.address?.invalid }"
          />
          <Message v-if="$form.address?.invalid" severity="error" size="small" variant="simple" class="text-red-600 dark:text-red-400 text-sm">
            {{ $form.address.error.message }}
          </Message>
        </div>
      </div>

      <!-- Category Section -->
      <div class="space-y-4 pt-6 border-t border-slate-200/50 dark:border-zinc-700/50">
        <div class="flex items-center gap-2 mb-4">
          <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 flex items-center justify-center">
            <Icon name="lucide:building-2" class="text-green-600 dark:text-green-400" size="0.9em" />
          </div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Kategori Konsumen</h3>
        </div>

        <div class="space-y-2">
          <label for="bank" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
            <Icon name="lucide:tag" class="inline mr-2" size="0.9em" />
            Jenis Konsumen *
          </label>
          <Select 
            id="bank" 
            name="bank" 
            v-model="selectedBank" 
            :options="banks" 
            optionLabel="name" 
            optionValue="name" 
            placeholder="Pilih kategori konsumen..."
            class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 focus:ring-blue-500"
            required
          >
            <template #value="slotProps">
              <div v-if="slotProps.value" class="flex items-center gap-2">
                <Icon name="lucide:tag" size="0.8em" class="text-slate-500 dark:text-slate-400" />
                <span>{{ slotProps.value }}</span>
              </div>
              <span v-else class="text-slate-400">Pilih kategori konsumen...</span>
            </template>
            <template #option="slotProps">
              <div class="flex items-center gap-2 p-2">
                <Icon name="lucide:tag" size="0.8em" class="text-slate-500 dark:text-slate-400" />
                <span>{{ slotProps.option.name }}</span>
              </div>
            </template>
          </Select>
          <small class="text-xs text-slate-500 dark:text-slate-400">
            Pilih kategori sesuai jenis konsumen (Perorangan, Perusahaan, dll)
          </small>
        </div>
      </div>
    </Form>
  </div>

  <!-- Action Buttons -->
  <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Aksi</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400">Simpan perubahan atau batalkan</p>
      </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      <!-- Submit Button -->
      <div class="sm:col-span-1 order-1">
        <Button 
          v-if="isLoading" 
          :disabled="true"
          class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-400 to-slate-500 text-white rounded-xl font-medium cursor-not-allowed"
        >
          <Icon name="lucide:loader-2" class="animate-spin" size="1em" />
          <span>Memproses...</span>
        </Button>
        <Button 
          v-else 
          type="submit"
          form="customer-form"
          class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
        >
          <Icon :name="!draftDatas.id ? 'lucide:user-plus' : 'lucide:save'" size="1em" />
          <span>{{ !draftDatas.id ? 'Tambah Konsumen' : 'Update Data' }}</span>
        </Button>
      </div>

      <!-- Detail Button (if editing) -->
      <div v-if="draftDatas.id" class="sm:col-span-1 order-2">
        <Button 
          @click="emits('goToDetail', draftDatas)"
          class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
        >
          <Icon name="lucide:eye" size="1em" />
          <span class="hidden sm:inline">Detail Konsumen</span>
          <span class="sm:hidden">Detail</span>
        </Button>
      </div>

      <!-- Close Button -->
      <div class="sm:col-span-1 order-3">
        <Button 
          @click="emits('closeDialog')"
          class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-slate-500 to-gray-600 hover:from-slate-600 hover:to-gray-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
        >
          <Icon name="lucide:x" size="1em" />
          <span>Tutup</span>
        </Button>
      </div>
    </div>

    <!-- Additional Info -->
    <div class="mt-4 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200/50 dark:border-blue-800/50">
      <div class="flex items-start gap-2">
        <Icon name="lucide:info" class="text-blue-600 dark:text-blue-400 mt-0.5" size="0.9em" />
        <div class="text-sm text-blue-700 dark:text-blue-300">
          <p class="font-medium mb-1">Catatan:</p>
          <ul class="text-xs space-y-1 text-blue-600 dark:text-blue-400">
            <li>• Semua field yang bertanda (*) wajib diisi</li>
            <li>• Pastikan nomor telepon aktif untuk komunikasi</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Types
interface BankOption {
  name: string
}

const client = useSanctumClient()
const emits = defineEmits(['error', 'closeDialog', 'addData', 'updateData', 'goToDetail'])
const { datas } = defineProps(['datas'])
const selectedBank = ref('')
const banks = ref<BankOption[]>([])
const isLoading = ref(false)
const form = ref(null)

const draftDatas = ref({
  ...datas, // Salin semua properti dari datas
  meta: datas?.meta || [], // Pastikan meta adalah array
  phones: datas?.phones || [], // Pastikan phones adalah array
} as any)

// State untuk nomor telepon tambahan
const additionalPhones = ref<Array<{ id?: number | string; phone: string; is_primary?: boolean }>>([])

watch(() => datas, (newData) => {
  draftDatas.value = {
    ...newData,
    meta: Array.isArray(newData?.meta) ? newData.meta : [],
    phones: Array.isArray(newData?.phones) ? newData.phones : [],
  }
  
  // Inisialisasi additionalPhones dari phones
  if (newData?.phones && Array.isArray(newData.phones)) {
    additionalPhones.value = newData.phones.filter((p: any) => !p.is_primary)
  } else {
    additionalPhones.value = []
  }
}, { immediate: true })

// Tambah nomor telepon baru
const addPhone = () => {
  additionalPhones.value.push({
    id: Date.now().toString(),
    phone: '',
    is_primary: false,
  })
}

// Hapus nomor telepon tambahan
const removePhone = (index: number) => {
  additionalPhones.value.splice(index, 1)
}

// Form resolver for validation
const resolver = () => {
  const errors = {} as any
  
  if (!draftDatas.value.name?.trim()) {
    errors.name = [{ message: 'Nama lengkap wajib diisi' }]
  } else if (draftDatas.value.name.length < 2) {
    errors.name = [{ message: 'Nama minimal 2 karakter' }]
  }
  
  if (!draftDatas.value.phone?.trim()) {
    errors.phone = [{ message: 'Nomor telepon wajib diisi' }]
  } else if (!/^[0-9+\-\s()]+$/.test(draftDatas.value.phone)) {
    errors.phone = [{ message: 'Format nomor telepon tidak valid' }]
  }
  
  if (!draftDatas.value.address?.trim()) {
    errors.address = [{ message: 'Alamat lengkap wajib diisi' }]
  } else if (draftDatas.value.address.length < 10) {
    errors.address = [{ message: 'Alamat minimal 10 karakter' }]
  }

  return { errors }
}

// Load banks data on mount
onMounted(async () => {
  try {
    const responseBank = await client('/api/settings/banks')
    banks.value = [{ name: 'Perorangan' }, ...responseBank]
  } catch (error) {
    console.error('Error loading banks:', error)
  }
})

// Watch for meta changes to set selected bank
watch(() => draftDatas.value.meta, () => {
  if (draftDatas.value.meta) {
    const bank = draftDatas.value.meta.find((meta: any) => meta.meta_key === 'bank')
    if (bank) {
      selectedBank.value = bank.meta_value
    } else {
      selectedBank.value = 'Perorangan'
    }
  }
}, { immediate: true })

// Handle form submission
const handleSubmit = async ({ valid }: { valid: boolean }) => {
  console.log('Form submit triggered, valid:', valid) // Debug log
  
  if (!valid) {
    console.log('Form is not valid, stopping submission') // Debug log
    return
  }
  
  if (!selectedBank.value) {
    console.log('No bank selected') // Debug log
    emits('error', 'Kategori konsumen wajib dipilih!')
    return
  }
  
  console.log('Starting submission process...') // Debug log
  isLoading.value = true

  // Prepare meta data
  draftDatas.value.meta = [
    {
      meta_key: 'bank',
      meta_value: selectedBank.value
    }
  ]

  // Siapkan phones untuk dikirim ke backend
  draftDatas.value.phones = [
    { phone: draftDatas.value.phone, is_primary: true },
    ...additionalPhones.value,
  ]

  try {
    if (!draftDatas.value.id) {
      // Add new customer
      console.log('Adding new customer...') // Debug log
      const responseAdd = await client('/api/customers', {
        method: 'POST',
        body: draftDatas.value,
      })
      console.log('Add response:', responseAdd) // Debug log
      emits('addData', responseAdd.data)
    } else {
      // Update existing customer
      console.log('Updating customer with ID:', draftDatas.value.id) // Debug log
      const responseUpdate = await client(`/api/customers/${draftDatas.value.id}`, {
        method: 'PUT',
        body: draftDatas.value,
      })
      console.log('Update response:', responseUpdate) // Debug log
      emits('updateData', responseUpdate.data)
    }
  } catch (error) {
    console.error('Submit error:', error)
    emits('error', draftDatas.value.id ? 'Update konsumen gagal! Periksa kembali data anda.' : 'Tambah konsumen gagal! Periksa kembali data anda.')
  } finally {
    console.log('Setting loading to false') // Debug log
    isLoading.value = false
  }
}
</script>
