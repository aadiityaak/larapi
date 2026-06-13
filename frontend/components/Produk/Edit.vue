<template>
  <div class="max-w-2xl mx-auto">
    <!-- Header Section -->
    <div class="mb-6 text-center">
      <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-lg">
        <Icon :name="!draftDatas.id ? 'lucide:plus-circle' : 'lucide:edit-3'" class="w-8 h-8 text-white" />
      </div>
      <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">
        {{ !draftDatas.id ? 'Tambah Produk Baru' : 'Edit Produk' }}
      </h2>
      <p class="text-slate-600 dark:text-slate-400">
        {{ !draftDatas.id ? 'Lengkapi informasi untuk menambah produk baru' : 'Perbarui informasi produk yang dipilih' }}
      </p>
    </div>

    <!-- Form Section -->
    <Form v-slot="$form" ref="form" :resolver :validateOnValueUpdate="true" :validateOnBlur="true" @submit="handleSubmit" 
          class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
      
      <!-- Nama Produk -->
      <div class="space-y-4">
        <div class="relative">
          <IftaLabel class="group">
            <InputText 
              id="name" 
              name="name" 
              v-model="draftDatas.name" 
              class="w-full px-4 py-3 text-slate-700 dark:text-white bg-slate-50/50 dark:bg-zinc-800/50 border border-slate-300/50 dark:border-zinc-600/50 rounded-xl focus:border-blue-500 dark:focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200" 
            />
            <label for="name" class="text-slate-600 dark:text-slate-300 font-medium">Nama Produk</label>
          </IftaLabel>
          <div class="absolute top-3 right-3">
            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
          </div>
          <Message v-if="$form.name?.invalid" severity="error" size="small" class="mt-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <Icon name="lucide:alert-circle" class="w-4 h-4 mr-2" />
            {{ $form.name.error.message }}
          </Message>
        </div>

        <!-- Deskripsi Produk -->
        <div class="relative">
          <IftaLabel class="group">
            <Textarea 
              id="description" 
              name="description" 
              v-model="draftDatas.description" 
              rows="4"
              class="w-full px-4 py-3 text-slate-700 dark:text-white bg-slate-50/50 dark:bg-zinc-800/50 border border-slate-300/50 dark:border-zinc-600/50 rounded-xl focus:border-blue-500 dark:focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 resize-none" 
            />
            <label for="description" class="text-slate-600 dark:text-slate-300 font-medium">Deskripsi Produk</label>
          </IftaLabel>
          <div class="absolute top-3 right-3">
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
          </div>
          <Message v-if="$form.description?.invalid" severity="error" size="small" class="mt-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <Icon name="lucide:alert-circle" class="w-4 h-4 mr-2" />
            {{ $form.description.error.message }}
          </Message>
        </div>

        <!-- Meta Products -->
        <div class="relative">
          <label for="meta_products" class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-3">
            <Icon name="lucide:tags" class="w-4 h-4 inline mr-2" />
            Data Produk
          </label>
          <MultiSelect 
            v-model="draftDatas.meta_products" 
            display="chip" 
            :options="selectMeta" 
            filter 
            optionLabel="name" 
            optionValue="id" 
            placeholder="Pilih Data Produk" 
            class="w-full multiselect-modern"
          >
            <template #chip="slotProps">
              <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200/50 dark:border-blue-700/50">
                <Icon name="lucide:tag" class="w-3 h-3 mr-1" />
                {{ selectMeta.find((m: any) => m.id === slotProps.value)?.name || slotProps.value }}
              </div>
            </template>
          </MultiSelect>
          <div class="absolute top-8 right-3">
            <div class="w-2 h-2 bg-purple-500 rounded-full animate-pulse"></div>
          </div>
          <Message v-if="$form.meta_products?.invalid" severity="error" size="small" class="mt-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <Icon name="lucide:alert-circle" class="w-4 h-4 mr-2" />
            {{ $form.meta_products.error.message }}
          </Message>

          <div v-if="Array.isArray(draftDatas.meta_products) && draftDatas.meta_products.length" class="mt-4 p-4 rounded-xl border border-slate-200/60 dark:border-zinc-700/60 bg-slate-50/40 dark:bg-zinc-800/30">
            <div class="flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-200 mb-3">
              <Icon name="lucide:printer" class="w-4 h-4 text-slate-500 dark:text-slate-400" />
              Tampilkan di Print (Jaminan / Agunan / Objek)
            </div>
            <div class="space-y-2">
              <div
                v-for="metaId in draftDatas.meta_products"
                :key="metaId"
                class="flex items-center justify-between gap-3 p-3 rounded-xl border border-slate-200/60 dark:border-zinc-700/60 bg-white/60 dark:bg-zinc-900/30"
              >
                <div class="text-sm text-slate-700 dark:text-slate-200 font-medium">
                  {{ selectMeta.find((m: any) => m.id === metaId)?.name || metaId }}
                </div>
                <Checkbox
                  :binary="true"
                  :modelValue="Array.isArray(draftDatas.meta_print_ids) && draftDatas.meta_print_ids.includes(metaId)"
                  @update:modelValue="(v: boolean) => toggleMetaPrint(metaId, v)"
                />
              </div>
            </div>
          </div>
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
          class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105"
        >
          <Icon :name="!draftDatas.id ? 'lucide:plus-circle' : 'lucide:save'" class="w-4 h-4 mr-2" />
          {{ !draftDatas.id ? 'Tambah Produk' : 'Update Produk' }}
        </Button>
        
        <Button 
          v-if="draftDatas.id" 
          size="large" 
          @click="emits('goToDetail')"
          class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105"
        >
          <Icon name="lucide:eye" class="w-4 h-4 mr-2" />
          Detail Produk
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
const emits = defineEmits(['error', 'closeDialog', 'goToDetail', 'addData', 'updateData'])
const { datas } = defineProps(['datas']);
const draftDatas = ref(JSON.parse(JSON.stringify(datas))) as any
const selectMeta = ref([])
const isLoading = ref(false)

const normalizeCategory = (value: any) => {
  if (value == null) return '';
  if (Array.isArray(value)) return normalizeCategory(value[0]);
  if (typeof value === 'object') {
    const v: any = value;
    return normalizeCategory(v.id ?? v.value ?? v.key ?? v.name);
  }
  return String(value);
};

onMounted(async () => {
  const rawMetaProducts = Array.isArray((draftDatas.value as any).meta_products)
    ? (draftDatas.value as any).meta_products
    : [];
  const rawMetaPrintIds = Array.isArray((draftDatas.value as any).meta_print_ids)
    ? (draftDatas.value as any).meta_print_ids
    : rawMetaProducts
      .filter((v: any) => typeof v === 'object' && v?.show_in_print)
      .map((v: any) => v?.id ?? v?.meta_id ?? v?.meta?.id)
      .filter((v: any) => v != null);

  draftDatas.value.meta_print_ids = rawMetaPrintIds
    .map((v: any) => Number(v))
    .filter((v: any) => !isNaN(v));

  const raw = Array.isArray(draftDatas.value.meta_products)
    ? draftDatas.value.meta_products
    : Array.isArray(draftDatas.value.meta)
      ? draftDatas.value.meta
      : [];
  draftDatas.value.meta_products = raw
    .map((v: any) => {
      if (v == null) return null;
      if (typeof v === 'number') return v;
      if (typeof v === 'string' && v.trim()) return Number(v);
      if (typeof v === 'object') return (v as any).meta_id ?? (v as any).id ?? (v as any).meta?.id;
      return null;
    })
    .map((v: any) => Number(v))
    .filter((v: any) => !isNaN(v));

  const normalizedCategory = normalizeCategory(draftDatas.value.category);
  draftDatas.value.category = normalizedCategory || 'perorangan';

  if (Array.isArray(draftDatas.value.meta_print_ids)) {
    draftDatas.value.meta_print_ids = draftDatas.value.meta_print_ids.filter((id: number) =>
      draftDatas.value.meta_products.includes(id),
    );
  } else {
    draftDatas.value.meta_print_ids = [];
  }

  try {
    const response = await client('/metas?paginate=false')
    selectMeta.value = response
  } catch (error) {
    console.log(error)
  }
})

watch(
  () => draftDatas.value.meta_products,
  (newValue) => {
    if (!Array.isArray(newValue)) {
      draftDatas.value.meta_products = [];
      return;
    }
    if (!Array.isArray(draftDatas.value.meta_print_ids)) {
      draftDatas.value.meta_print_ids = [];
    }
    draftDatas.value.meta_print_ids = draftDatas.value.meta_print_ids.filter((id: number) =>
      newValue.includes(id),
    );
  },
  { deep: true },
)

const toggleMetaPrint = (metaId: number, enabled: boolean) => {
  if (!Array.isArray(draftDatas.value.meta_print_ids)) {
    draftDatas.value.meta_print_ids = [];
  }
  const ids = draftDatas.value.meta_print_ids as number[];
  const exists = ids.includes(metaId);
  if (enabled && !exists) ids.push(metaId);
  if (!enabled && exists) draftDatas.value.meta_print_ids = ids.filter((id) => id !== metaId);
}


const resolver = () => {
  const errors = {} as any;
  if (!draftDatas.value.name) {
    errors.name = [{ message: 'Nama Produk wajib diisi' }];
  }
  return { errors };
}

const handleSubmit = async ({ valid }: { valid: boolean }) => {
  if(!valid) return
  isLoading.value = true
  const category = normalizeCategory(draftDatas.value.category) || 'perorangan';
  const payload = {
    ...draftDatas.value,
    category,
  }
  if(!draftDatas.value.id) {
      try {
        const responseadd = await client('/produk', {
          method: 'POST',
          body: payload
        })
        emits('addData', responseadd)
        isLoading.value = false
      } catch (error) {
        emits('error', 'Tambah Produk gagal! Periksa kembali data anda!')
        isLoading.value = false
      }
  } else {
      try {
        const responseUpdate = await client(`/produk/${draftDatas.value.id}`, {
          method: 'PUT',
          body: payload
        })
        emits('updateData', responseUpdate)
        isLoading.value = false
      } catch (error) {
        emits('error', 'Update Produk gagal!')
        isLoading.value = false
      }
  }
}
</script>

<style scoped>
/* Modern MultiSelect Styling */
:deep(.multiselect-modern .p-multiselect) {
  background: rgb(248 250 252 / 0.5);
  border-color: rgb(203 213 225 / 0.5);
  border-radius: 0.75rem;
}

:deep(.dark .multiselect-modern .p-multiselect) {
  background: rgb(39 39 42 / 0.5);
  border-color: rgb(82 82 91 / 0.5);
}

:deep(.multiselect-modern .p-multiselect:focus) {
  border-color: rgb(59 130 246);
  box-shadow: 0 0 0 4px rgb(59 130 246 / 0.1);
}

:deep(.dark .multiselect-modern .p-multiselect:focus) {
  border-color: rgb(96 165 250);
}

:deep(.multiselect-modern .p-multiselect-panel) {
  backdrop-filter: blur(12px);
  background: rgb(255 255 255 / 0.95);
  border: 1px solid rgb(226 232 240 / 0.5);
  border-radius: 0.75rem;
  box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
}

:deep(.dark .multiselect-modern .p-multiselect-panel) {
  background: rgb(24 24 27 / 0.95);
  border-color: rgb(63 63 70 / 0.5);
}

:deep(.multiselect-modern .p-multiselect-item) {
  transition: background-color 0.2s ease;
}

:deep(.multiselect-modern .p-multiselect-item:hover) {
  background: rgb(239 246 255);
}

:deep(.dark .multiselect-modern .p-multiselect-item:hover) {
  background: rgb(30 58 138 / 0.2);
}

:deep(.multiselect-modern .p-multiselect-item.p-highlight) {
  background: rgb(219 234 254);
  color: rgb(30 64 175);
}

:deep(.dark .multiselect-modern .p-multiselect-item.p-highlight) {
  background: rgb(30 58 138 / 0.3);
  color: rgb(147 197 253);
}

/* Form Input Enhancements */
:deep(.p-inputtext), :deep(.p-textarea) {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

:deep(.p-inputtext:focus), :deep(.p-textarea:focus) {
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
</style>
