<template>
  <SettingsLayout>
    <div class="space-y-6">
      <!-- Modern Header Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
        <div class="flex items-center gap-4">
          <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
            <Icon name="lucide:building-2" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Pengaturan Bank</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola daftar bank untuk sistem pembayaran</p>
          </div>
        </div>

        <!-- Breadcrumb Navigation -->
        <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 mt-4 pt-4 border-t border-slate-200/50 dark:border-zinc-700/50">
          <NuxtLink to="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1">
            <Icon name="lucide:home" class="w-4 h-4" />
            <span class="hidden sm:inline">Dashboard</span>
          </NuxtLink>
          <Icon name="lucide:chevron-right" class="w-3 h-3" />
          <NuxtLink to="/settings" class="hover:text-slate-900 dark:hover:text-white transition-colors">Settings</NuxtLink>
          <Icon name="lucide:chevron-right" class="w-3 h-3" />
          <span class="text-slate-900 dark:text-white font-medium">Bank</span>
        </div>
      </div>

      <!-- Modern Form Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 overflow-hidden">
        <Form @submit="handleSubmit" ref="form" class="p-6">
          <!-- Bank List Section -->
          <div class="backdrop-blur-xl bg-gradient-to-br from-green-50/80 to-emerald-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-green-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-6">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg shadow-lg">
                  <Icon name="lucide:credit-card" class="w-5 h-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Daftar Bank</h3>
              </div>
              
              <Button 
                @click="addBank" 
                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 text-sm font-medium"
              >
                <Icon name="lucide:plus" class="w-4 h-4" />
                Tambah Bank
              </Button>
            </div>

            <div class="space-y-4">
              <div v-for="(bank, index) in banks" :key="index" class="group">
                <div class="flex gap-3 p-4 bg-white/80 dark:bg-zinc-800/80 rounded-xl border border-green-200/50 dark:border-zinc-600/50 backdrop-blur-sm hover:shadow-md transition-all duration-200">
                  <div class="flex-1">
                    <label :for="'bank_name_' + index" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                      Bank {{ index + 1 }}
                    </label>
                    <InputText 
                      v-model="bank.name" 
                      :id="'bank_name_' + index"
                      class="w-full rounded-xl border-green-300 dark:border-green-600/50 focus:border-green-500 dark:focus:border-green-400 px-4 py-3 text-sm bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm" 
                      placeholder="Masukkan nama bank"
                      required 
                    />
                  </div>
                  
                  <div class="flex items-end">
                    <Button 
                      @click="removeBank(index)" 
                      class="px-3 py-3 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
                      :disabled="banks.length === 1"
                    >
                      <Icon name="lucide:trash-2" class="w-4 h-4" />
                    </Button>
                  </div>
                </div>
              </div>

              <div v-if="banks.length === 0" class="text-center py-8">
                <div class="p-4 bg-slate-100/80 dark:bg-zinc-800/80 rounded-xl backdrop-blur-sm">
                  <Icon name="lucide:building-2" class="w-12 h-12 text-slate-400 dark:text-slate-500 mx-auto mb-3" />
                  <p class="text-slate-500 dark:text-slate-400 text-sm">Belum ada bank yang ditambahkan</p>
                  <p class="text-slate-400 dark:text-slate-500 text-xs mt-1">Klik tombol "Tambah Bank" untuk memulai</p>
                </div>
              </div>
            </div>

            <div class="mt-6 p-4 bg-green-100/50 dark:bg-green-900/20 rounded-lg">
              <div class="flex items-start gap-3">
                <Icon name="lucide:info" class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" />
                <div class="text-sm text-green-700 dark:text-green-300">
                  <p class="font-medium mb-1">Informasi Bank:</p>
                  <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Tambahkan semua bank klien</li>
                    <li>Nama bank akan ditampilkan dalam pilihan jenis bank</li>
                    <li>Bank yang kosong akan otomatis diabaikan saat penyimpanan</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-slate-200/50 dark:border-zinc-700/50">
            <Button 
              type="submit" 
              class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 font-medium"
            >
              <Icon name="lucide:save" class="w-4 h-4" />
              Simpan Bank
            </Button>
          </div>
        </Form>
      </div>
    </div>
    
    <Toast />
  </SettingsLayout>
</template>

<script setup lang="ts">
definePageMeta({ title: 'Setting Bank' })
const router = useRouter()
const toast = useToast()
const client = useSanctumClient()
const banks = ref([{ name: '' }]) // Awalnya satu field bank

const fetchBanks = async () => {
  try {
    const response = await client('/settings/banks')
    banks.value = response.map((bank: any) => ({ name: bank.name })) // Sesuaikan struktur data
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal memuat data bank', life: 3000 })
  }
}

const addBank = () => {
  banks.value.push({ name: '' }) // Menambah field baru untuk bank
}

const removeBank = (index: number) => {
  banks.value.splice(index, 1); // Menghapus field bank berdasarkan index
};

const handleSubmit = async () => {
  try {
    const bankData = banks.value.filter(bank => bank.name); // Hanya ambil bank yang memiliki nama
    await client('/settings/banks', {
      method: 'POST',
      body: JSON.stringify({ banks: bankData }), // Tambahkan kunci 'banks'
      headers: { 'Content-Type': 'application/json' }
    })
    toast.add({ severity: 'success', summary: 'Success', detail: 'Update data berhasil!', life: 3000 })
    await fetchBanks()
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal menambahkan bank', life: 3000 })
  }
}


onMounted(fetchBanks)
</script>

<style scoped>
/* Add any specific styles here */
</style>
