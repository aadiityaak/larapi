<template>
  <div class="space-y-6">
    <!-- Avatar Section -->
    <div class="text-center">
      <div class="relative inline-block mb-4">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full blur-lg opacity-30"></div>
        <Avatar 
          :image="draftDatas.avatar" 
          shape="circle" 
          size="xlarge" 
          v-if="draftDatas.avatar && typeof draftDatas.avatar === 'string'" 
          class="relative ring-4 ring-white dark:ring-zinc-700 shadow-2xl"
          :pt="{
            image: 'object-cover w-24 h-24',
          }"
        />
        <Avatar 
          shape="circle" 
          size="xlarge" 
          v-else 
          class="relative bg-gradient-to-br from-blue-500 to-purple-600 text-white font-bold text-2xl ring-4 ring-white dark:ring-zinc-700 shadow-2xl"
        > 
          {{ draftDatas.name?.charAt(0) || '?' }} 
        </Avatar>
      </div>
      <h2 class="text-xl font-bold text-slate-800 dark:text-white">
        {{ !draftDatas.id ? 'Tambah Karyawan Baru' : 'Edit Karyawan' }}
      </h2>
      <p class="text-slate-600 dark:text-slate-400">{{ !draftDatas.id ? 'Lengkapi informasi karyawan baru' : 'Perbarui informasi karyawan' }}</p>
    </div>

    <!-- Form -->
    <Form 
      v-slot="$form" 
      ref="form"  
      :resolver="resolver" 
      :validateOnValueUpdate="true" 
      :validateOnBlur="true" 
      @submit="handleUpdate" 
      class="space-y-6" 
      enctype="multipart/form-data"
    >
      <!-- Personal Information Section -->
      <div class="backdrop-blur-xl bg-gradient-to-br from-blue-50/80 to-indigo-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-blue-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-3 mb-6">
          <div class="p-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg shadow-lg">
            <Icon name="lucide:user" class="w-5 h-5 text-white" />
          </div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Informasi Personal</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <IftaLabel class="modern-input">
            <InputText 
              id="name" 
              name="name" 
              v-model="draftDatas.name" 
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 dark:focus:border-blue-400" 
            />
            <label for="name" class="text-slate-600 dark:text-slate-400">Nama Lengkap</label>
            <Message v-if="$form.name?.invalid" severity="error" size="small" variant="simple">
              {{ $form.name.error.message }}
            </Message>
          </IftaLabel>

          <IftaLabel class="modern-input">
            <InputText 
              id="email" 
              type="email" 
              name="email" 
              v-model="draftDatas.email" 
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 dark:focus:border-blue-400" 
            />
            <label for="email" class="text-slate-600 dark:text-slate-400">Email</label>
            <Message v-if="$form.email?.invalid" severity="error" size="small" variant="simple">
              {{ $form.email.error.message }}
            </Message>
          </IftaLabel>

          <IftaLabel class="modern-input">
            <InputText 
              id="phone" 
              name="phone" 
              v-model="draftDatas.phone" 
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 dark:focus:border-blue-400" 
            />
            <label for="phone" class="text-slate-600 dark:text-slate-400">Telepon</label>
            <Message v-if="$form.phone?.invalid" severity="error" size="small" variant="simple">
              {{ $form.phone.error.message }}
            </Message>
          </IftaLabel>

          <IftaLabel class="modern-input md:col-span-2">
            <InputText 
              id="address" 
              name="address" 
              v-model="draftDatas.address" 
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 dark:focus:border-blue-400" 
            />
            <label for="address" class="text-slate-600 dark:text-slate-400">Alamat</label>
            <Message v-if="$form.address?.invalid" severity="error" size="small" variant="simple">
              {{ $form.address.error.message }}
            </Message>
          </IftaLabel>
        </div>
      </div>

      <!-- Role & Avatar Section -->
      <div class="backdrop-blur-xl bg-gradient-to-br from-purple-50/80 to-pink-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-purple-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-3 mb-6">
          <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-lg">
            <Icon name="lucide:settings" class="w-5 h-5 text-white" />
          </div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Pengaturan Akun</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <IftaLabel class="modern-select">
            <Select 
              id="role" 
              name="role" 
              v-model="selectedRole" 
              :options="roles" 
              optionLabel="name" 
              optionValue="name" 
              :aria-label="draftDatas.role" 
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600" 
              :disabled="!user.capabilities.includes('role:update')"
            />
            <label for="role" class="text-slate-600 dark:text-slate-400">Jabatan</label>
          </IftaLabel>

          <IftaLabel class="modern-input">
            <InputText 
              type="file" 
              id="avatar" 
              @change="onFileUpload" 
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-purple-500 dark:focus:border-purple-400 pl-4" 
              accept="image/*"
            />
            <label for="avatar" class="text-slate-600 dark:text-slate-400">Avatar</label>
          </IftaLabel>
        </div>
      </div>

      <!-- Password Section (for new users or when updating password) -->
      <div class="backdrop-blur-xl bg-gradient-to-br from-amber-50/80 to-orange-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-amber-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
        <div class="flex items-center gap-3 mb-6">
          <div class="p-2 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-lg">
            <Icon name="lucide:lock" class="w-5 h-5 text-white" />
          </div>
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
            {{ !draftDatas.id ? 'Password' : 'Ubah Password (Opsional)' }}
          </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <IftaLabel class="modern-input">
            <InputText 
              type="password" 
              id="password" 
              name="password" 
              v-model="draftDatas.password" 
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-amber-500 dark:focus:border-amber-400" 
              autocomplete="new-password" 
            />
            <label for="password" class="text-slate-600 dark:text-slate-400">Password{{ !draftDatas.id ? ' *' : '' }}</label>
            <Message v-if="$form.password?.invalid" severity="error" size="small" variant="simple">
              {{ $form.password.error.message }}
            </Message>
          </IftaLabel>

          <IftaLabel class="modern-input">
            <InputText 
              type="password" 
              id="password_confirmation" 
              name="password_confirmation" 
              v-model="draftDatas.password_confirmation" 
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-amber-500 dark:focus:border-amber-400" 
              autocomplete="new-password" 
            />
            <label for="password_confirmation" class="text-slate-600 dark:text-slate-400">Konfirmasi Password{{ !draftDatas.id ? ' *' : '' }}</label>
            <Message v-if="$form.password_confirmation?.invalid" severity="error" size="small" variant="simple">
              {{ $form.password_confirmation.error.message }}
            </Message>
          </IftaLabel>
        </div>

        <div class="mt-4 p-4 bg-amber-100/50 dark:bg-amber-900/20 rounded-lg">
          <p class="text-sm text-amber-700 dark:text-amber-300 flex items-center gap-2">
            <Icon name="lucide:info" class="w-4 h-4" />
            {{ !draftDatas.id ? 'Password harus minimal 8 karakter' : 'Kosongkan jika tidak ingin mengubah password' }}
          </p>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end gap-3 pt-6 border-t border-slate-200/50 dark:border-zinc-700/50" v-if="user.capabilities.includes('user:create') || user.capabilities.includes('user:update')">
        <Button 
          @click="emits('closeDialog')"
          class="px-6 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-slate-700 dark:text-slate-300 rounded-xl transition-all duration-200 flex items-center gap-2"
          size="small"
        >
          <Icon name="lucide:x" class="w-4 h-4" />
          Tutup
        </Button>

        <Button 
          v-if="isLoading" 
          class="px-6 py-2 bg-gradient-to-r from-slate-400 to-slate-500 text-white rounded-xl shadow-lg flex items-center gap-2"
          size="small" 
          type="submit" 
          disabled
        >
          <Icon name="lucide:loader-2" class="w-4 h-4 animate-spin" />
          Memproses...
        </Button>

        <Button 
          v-else 
          class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
          size="small" 
          type="submit"
        >
          <Icon :name="!draftDatas.id ? 'lucide:user-plus' : 'lucide:save'" class="w-4 h-4" />
          {{ !draftDatas.id ? 'Tambah Karyawan' : 'Update Karyawan' }}
        </Button>
      </div>
    </Form>

    <Toast />
  </div>
</template>

<script setup lang="ts">
  const user = useSanctumUser() as any
  const emits = defineEmits(['refreshData', 'error', 'closeDialog', 'addData', 'updateData', 'goToDetail'])
  const { datas } = defineProps(['datas'])
  const draftDatas = ref(JSON.parse(JSON.stringify(datas)))
  const isLoading = ref(false)
  const roles = draftDatas.value.roles
  const client = useSanctumClient()
  const selectedRole = ref<string | null>(
    Array.isArray(draftDatas.value.role)
      ? (draftDatas.value.role[0] ?? 'staff')
      : (draftDatas.value.role ?? 'staff')
  )

  const resolver = () => {
    const errors = {} as any;

    if (!draftDatas.value.name) {
      errors.name = [{ message: 'Nama Lengkap wajib diisi' }];
    }
    if (!draftDatas.value.email) {
      errors.email = [{ message: 'Email wajib diisi' }];
    } else if (!/\S+@\S+\.\S+/.test(draftDatas.value.email)) {
      errors.email = [{ message: 'Email tidak valid' }];
    }
    if (!draftDatas.value.phone) {
      errors.phone = [{ message: 'Telepon wajib diisi' }];
    }
    if (!draftDatas.value.address) {
      errors.address = [{ message: 'Alamat wajib diisi' }];
    }
    if (draftDatas.value.password && draftDatas.value.password.length < 8) {
      errors.password = [{ message: 'Password minimal 8 karakter' }];
    }
    if (draftDatas.value.password && draftDatas.value.password !== draftDatas.value.password_confirmation) {
      errors.password_confirmation = [{ message: 'Password dan konfirmasi tidak cocok' }];
    }
    
    return { errors };
  };

  const handleUpdate = async () => {
    isLoading.value = true
    try {
      const formData = new FormData()
      formData.append('name', draftDatas.value.name)
      formData.append('email', draftDatas.value.email)
      formData.append('phone', draftDatas.value.phone)
      formData.append('address', draftDatas.value.address)
      selectedRole.value = selectedRole.value || 'staff'
      formData.append('role', selectedRole.value)
      if (draftDatas.value.avatar) {
        formData.append('avatar', draftDatas.value.avatar)
      }

      if(!draftDatas.value.id) {
        formData.append('password', draftDatas.value.password)
        formData.append('password_confirmation', draftDatas.value.password_confirmation)

        const addData = await client(`/api/karyawans`, {
          method: 'POST',
          body: formData,
        })
        emits('addData', addData)
        isLoading.value = false
      } else {
        const updateData = await client(`/api/karyawans/${draftDatas.value.id}`, {
          method: 'PUT',
          body: formData,
        })
        emits('updateData', updateData)
        isLoading.value = false
      }
    } catch (error: any) {
      emits('error', error)
      isLoading.value = false
    }
  }

  const onFileUpload = (event: any) => {
    const file = event.target.files[0];
    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (file && validTypes.includes(file.type)) {
      draftDatas.value.avatar = file;
    } else {
      draftDatas.value.avatar = ''; 
    }
  };
</script>

<style scoped>
.modern-input :deep(.p-iftalabel) {
  position: relative;
}

.modern-input :deep(.p-inputtext) {
  backdrop-filter: blur(8px);
  background-color: rgba(255, 255, 255, 0.5);
  border-width: 2px;
  transition: all 0.2s ease;
}

.modern-input :deep(.p-inputtext:focus) {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  transform: scale(1.02);
  backdrop-filter: blur(12px);
}

.modern-input :deep(.p-iftalabel > label) {
  font-weight: 500;
  transition: all 0.2s ease;
}

.modern-select :deep(.p-select) {
  backdrop-filter: blur(8px);
  background-color: rgba(255, 255, 255, 0.5);
  border-width: 2px;
  transition: all 0.2s ease;
}

.modern-select :deep(.p-select:focus) {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  transform: scale(1.02);
  backdrop-filter: blur(12px);
}

.modern-select :deep(.p-iftalabel > label) {
  font-weight: 500;
  transition: all 0.2s ease;
}

/* File input specific styling */
.modern-input :deep(.p-inputtext[type="file"]) {
  padding-left: 1rem;
  cursor: pointer;
}

.modern-input :deep(.p-inputtext[type="file"]::-webkit-file-upload-button) {
  margin-right: 1rem;
  padding: 0.25rem 0.75rem;
  background: linear-gradient(45deg, #8b5cf6, #ec4899);
  color: white;
  border: none;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.modern-input :deep(.p-inputtext[type="file"]::-webkit-file-upload-button:hover) {
  background: linear-gradient(45deg, #7c3aed, #db2777);
  transform: scale(1.05);
}

/* Button hover effects */
:deep(.p-button) {
  transition: all 0.2s ease;
}

:deep(.p-button:hover) {
  transform: scale(1.05);
}

/* Avatar gradient ring animation */
.relative::before {
  content: '';
  position: absolute;
  inset: -4px;
  padding: 4px;
  background: linear-gradient(45deg, #3b82f6, #8b5cf6, #ec4899, #f59e0b);
  border-radius: 50%;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.relative:hover::before {
  opacity: 0.5;
  animation: rotate 2s linear infinite;
}

@keyframes rotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

</style>
