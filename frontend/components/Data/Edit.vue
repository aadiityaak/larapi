<template>
  <div class="max-w-2xl mx-auto">
    <!-- Header Section -->
    <div class="mb-6 text-center">
      <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg">
        <Icon :name="!draftDatas.id ? 'lucide:database' : 'lucide:database'" class="w-8 h-8 text-white" />
      </div>
      <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">
        {{ !draftDatas.id ? 'Tambah Data Baru' : 'Edit Data' }}
      </h2>
      <p class="text-slate-600 dark:text-slate-400">
        {{ !draftDatas.id ? 'Lengkapi informasi untuk menambah data baru' : 'Perbarui informasi data yang dipilih' }}
      </p>
    </div>

    <!-- Form Section -->
    <Form v-slot="$form" ref="form" :resolver :validateOnValueUpdate="true" :validateOnBlur="true" @submit="handleSubmit" 
          class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      
      <!-- Nama Data -->
      <div class="space-y-4">
        <div class="relative">
          <IftaLabel class="group">
            <InputText 
              id="name" 
              name="name" 
              v-model="draftDatas.name" 
              class="w-full px-4 py-3 text-slate-700 dark:text-white bg-slate-50/50 dark:bg-zinc-800/50 border border-slate-300/50 dark:border-zinc-600/50 rounded-xl focus:border-emerald-500 dark:focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/10 transition-all duration-200" 
            />
            <label for="name" class="text-slate-600 dark:text-slate-300 font-medium">Nama Data</label>
          </IftaLabel>
          <div class="absolute top-3 right-3">
            <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
          </div>
          <Message v-if="$form.name?.invalid" severity="error" size="small" class="mt-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <Icon name="lucide:alert-circle" class="w-4 h-4 mr-2" />
            {{ $form.name.error.message }}
          </Message>
        </div>

        <!-- Type Data -->
        <div class="relative">
          <label for="type" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-3">
            <Icon name="lucide:type" class="w-4 h-4 inline mr-2" />
            Tipe Data
          </label>
          <Select 
            id="type" 
            name="type" 
            v-model="draftDatas.type" 
            :options="ListType" 
            filter 
            optionLabel="name" 
            optionValue="id" 
            placeholder="Pilih Tipe Data"
            class="w-full select-modern"
          >
            <template #value="slotProps">
              <div v-if="slotProps.value" class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full" :class="{
                  'bg-blue-500': slotProps.value === 'text',
                  'bg-green-500': slotProps.value === 'number',
                  'bg-purple-500': slotProps.value === 'date',
                  'bg-yellow-500': slotProps.value === 'currency'
                }"></div>
                <span>{{ ListType.find((type: any) => type.id === slotProps.value)?.name }}</span>
              </div>
              <span v-else class="text-slate-400">Pilih Tipe Data</span>
            </template>
            <template #option="slotProps">
              <div class="flex items-center gap-3 py-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" :class="{
                  'bg-blue-100 dark:bg-blue-900/30': slotProps.option.id === 'text',
                  'bg-green-100 dark:bg-green-900/30': slotProps.option.id === 'number',
                  'bg-purple-100 dark:bg-purple-900/30': slotProps.option.id === 'date',
                  'bg-yellow-100 dark:bg-yellow-900/30': slotProps.option.id === 'currency'
                }">
                  <Icon :name="getIconForType(slotProps.option.id)" class="w-4 h-4" :class="{
                    'text-blue-600 dark:text-blue-400': slotProps.option.id === 'text',
                    'text-green-600 dark:text-green-400': slotProps.option.id === 'number',
                    'text-purple-600 dark:text-purple-400': slotProps.option.id === 'date',
                    'text-yellow-600 dark:text-yellow-400': slotProps.option.id === 'currency'
                  }" />
                </div>
                <div class="flex-1">
                  <div class="font-medium text-slate-800 dark:text-white">{{ slotProps.option.name }}</div>
                  <div class="text-xs text-slate-500 dark:text-slate-400">
                    {{ getDescriptionForType(slotProps.option.id) }}
                  </div>
                </div>
              </div>
            </template>
          </Select>
          <div class="absolute top-8 right-3">
            <div class="w-2 h-2 bg-teal-500 rounded-full animate-pulse"></div>
          </div>
          <Message v-if="$form.type?.invalid" severity="error" size="small" class="mt-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <Icon name="lucide:alert-circle" class="w-4 h-4 mr-2" />
            {{ $form.type.error.message }}
          </Message>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-wrap gap-3 justify-end pt-6 border-t border-slate-200/50 dark:border-zinc-700/50 mt-6">
        <Button 
          v-if="isLoading" 
          size="large" 
          type="submit" 
          class="px-6 py-3 bg-gradient-to-r from-slate-400 to-slate-500 hover:from-slate-500 hover:to-slate-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 cursor-not-allowed"
          disabled
        >
          <Icon name="lucide:loader-2" class="w-4 h-4 mr-2 animate-spin" />
          <span>Memproses...</span>
        </Button>
        <Button 
          v-else 
          size="large" 
          type="submit" 
          class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105"
        >
          <Icon :name="!draftDatas.id ? 'lucide:database-plus' : 'lucide:save'" class="w-4 h-4 mr-2" />
          {{ !draftDatas.id ? 'Tambah Data' : 'Update Data' }}
        </Button>
        
        <Button 
          size="large" 
          @click="emits('closeDialog')"
          class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105"
        >
          <Icon name="lucide:x" class="w-4 h-4 mr-2" />
          Tutup
        </Button>
      </div>
    </Form>
  </div>
</template>

<script setup lang="ts">
const client = useSanctumClient()
const emits = defineEmits(['error', 'closeDialog', 'addData', 'updateData'])
const { datas } = defineProps(['datas'])
const draftDatas = ref(JSON.parse(JSON.stringify(datas)))
const isLoading = ref(false)
const ListType = ref([
  { id: 'text', name: 'Text' },
  { id: 'number', name: 'Number' },
  { id: 'date', name: 'Date' },
  { id: 'currency', name: 'Currency' },
])

// Helper functions
const getIconForType = (type: string) => {
  const icons = {
    'text': 'lucide:type',
    'number': 'lucide:hash',
    'date': 'lucide:calendar',
    'currency': 'lucide:dollar-sign'
  }
  return icons[type as keyof typeof icons] || 'lucide:help-circle'
}

const getDescriptionForType = (type: string) => {
  const descriptions = {
    'text': 'Teks dan karakter',
    'number': 'Angka dan numerik',
    'date': 'Tanggal dan waktu',
    'currency': 'Mata uang dan harga'
  }
  return descriptions[type as keyof typeof descriptions] || 'Tipe tidak dikenal'
}

const resolver = () => {
  const errors = {} as any;
  if (!draftDatas.value.name) {
      errors.name = [{ message: 'Nama wajib diisi' }];
  }
  if (!draftDatas.value.type) {
      errors.type = [{ message: 'Type wajib diisi' }];
  }

  return { errors };
}

const handleSubmit = async ({ valid }: { valid: boolean }) => {
  if(!valid) return
  isLoading.value = true
  if(!draftDatas.value.id) {
      try {
          const responseAdd = await client('/metas', {
              method: 'POST',
              body: draftDatas.value
          })
          emits('addData', responseAdd)
          isLoading.value = false
      } catch (error : any) {
        emits('error', error.response._data.message)
        isLoading.value = false
      }
  } else {
      try {
          const responseUpdate = await client(`/metas/${draftDatas.value.id}`, {
              method: 'PUT',
              body: draftDatas.value
          })
          emits('updateData', responseUpdate)
          isLoading.value = false
      } catch (error) {
          emits('error', 'Update Data gagal!')
          isLoading.value = false
      }
  }
}
</script>

<style scoped>
/* Modern Select Styling */
:deep(.select-modern .p-select) {
  background: rgb(248 250 252 / 0.5);
  border-color: rgb(203 213 225 / 0.5);
  border-radius: 0.75rem;
  padding: 0.75rem 1rem;
}

:deep(.dark .select-modern .p-select) {
  background: rgb(39 39 42 / 0.5);
  border-color: rgb(82 82 91 / 0.5);
}

:deep(.select-modern .p-select:focus) {
  border-color: rgb(16 185 129);
  box-shadow: 0 0 0 4px rgb(16 185 129 / 0.1);
}

:deep(.dark .select-modern .p-select:focus) {
  border-color: rgb(52 211 153);
}

:deep(.select-modern .p-select-overlay) {
  backdrop-filter: blur(12px);
  background: rgb(255 255 255 / 0.95);
  border: 1px solid rgb(226 232 240 / 0.5);
  border-radius: 0.75rem;
  box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
}

:deep(.dark .select-modern .p-select-overlay) {
  background: rgb(24 24 27 / 0.95);
  border-color: rgb(63 63 70 / 0.5);
}

:deep(.select-modern .p-select-option) {
  transition: background-color 0.2s ease;
  border-radius: 0.5rem;
  margin: 0.125rem;
}

:deep(.select-modern .p-select-option:hover) {
  background: rgb(240 253 244);
}

:deep(.dark .select-modern .p-select-option:hover) {
  background: rgb(6 78 59 / 0.2);
}

:deep(.select-modern .p-select-option.p-selected) {
  background: rgb(209 250 229);
  color: rgb(6 78 59);
}

:deep(.dark .select-modern .p-select-option.p-selected) {
  background: rgb(6 78 59 / 0.3);
  color: rgb(110 231 183);
}

/* Form Input Enhancements */
:deep(.p-inputtext) {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

:deep(.p-inputtext:focus) {
  transform: translateY(-1px);
  box-shadow: 0 10px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}

/* Button Animations */
.transform:hover {
  transform: translateY(-2px) scale(1.02);
}

/* Loading State */
.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}

/* Option hover effects */
:deep(.p-select-option) {
  border-radius: 0.5rem;
  margin: 0.125rem 0.25rem;
}
</style>
