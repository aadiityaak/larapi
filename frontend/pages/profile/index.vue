<template>
  <div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
      <div class="flex items-center gap-4 mb-6">
        <div class="p-3 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-lg">
          <Icon name="lucide:user" class="w-6 h-6 text-white" />
        </div>
        <div>
          <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Edit Profile</h1>
          <p class="text-slate-600 dark:text-slate-300">Kelola informasi profil dan akun Anda</p>
        </div>
      </div>
    </div>

    <!-- Profile Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Avatar Section -->
      <div class="lg:col-span-1">
        <div class="bg-white/50 dark:bg-zinc-800/50 backdrop-blur-sm rounded-2xl border border-white/20 dark:border-zinc-700/50 shadow-xl p-6">
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Foto Profil</h3>
          
          <div class="flex flex-col items-center text-center">
            <!-- Current Avatar -->
            <div class="relative mb-4">
              <Avatar 
                :image="userStore.user.avatar" 
                shape="circle" 
                size="xlarge" 
                v-if="userStore.user && userStore.user.avatar" 
                class="w-32 h-32 border-4 border-white dark:border-zinc-700 shadow-lg"
                :pt="{
                  image: 'object-cover w-full h-full',
                }"
              />
              <Avatar 
                shape="circle" 
                size="xlarge" 
                v-else 
                class="w-32 h-32 bg-gradient-to-br from-blue-500 to-purple-600 border-4 border-white dark:border-zinc-700 shadow-lg text-white text-3xl font-bold flex items-center justify-center"
              > 
                {{ userStore.user?.name?.charAt(0) || 'U' }} 
              </Avatar>
              
              <!-- Status Badge -->
              <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 border-4 border-white dark:border-zinc-700 rounded-full flex items-center justify-center">
                <Icon name="lucide:check" class="w-4 h-4 text-white" />
              </div>
            </div>
            
            <h4 class="font-semibold text-slate-800 dark:text-white mb-1">{{ userStore.user?.name || 'User' }}</h4>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ userStore.user?.email || 'No email' }}</p>
            
            <!-- Upload Button -->
            <label for="avatar" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 transition-colors cursor-pointer shadow-lg">
              <Icon name="lucide:camera" class="w-4 h-4" />
              <span class="text-sm font-medium">Ganti Foto</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Form Section -->
      <div class="lg:col-span-2">
        <div class="bg-white/50 dark:bg-zinc-800/50 backdrop-blur-sm rounded-2xl border border-white/20 dark:border-zinc-700/50 shadow-xl p-6">
          <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-6">Informasi Pribadi</h3>
          
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
            <!-- Personal Information Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Name Field -->
              <div class="space-y-2">
                <label for="name" class="text-sm font-medium text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                <div class="relative">
                  <InputText 
                    id="name" 
                    name="name" 
                    v-model="state.name" 
                    class="w-full pl-12 pr-4 py-3 bg-white/50 dark:bg-zinc-700/50 border border-slate-200 dark:border-zinc-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                    placeholder="Masukkan nama lengkap"
                  />
                </div>
                <Message v-if="$form.name?.invalid" severity="error" size="small" variant="simple">{{ $form.name.error.message }}</Message>
              </div>

              <!-- Email Field -->
              <div class="space-y-2">
                <label for="email" class="text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                <div class="relative">
                  <InputText 
                    id="email" 
                    type="email" 
                    name="email" 
                    v-model="state.email" 
                    class="w-full pl-12 pr-4 py-3 bg-white/50 dark:bg-zinc-700/50 border border-slate-200 dark:border-zinc-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                    placeholder="nama@email.com"
                  />
                </div>
                <Message v-if="$form.email?.invalid" severity="error" size="small" variant="simple">{{ $form.email.error.message }}</Message>
              </div>

              <!-- Phone Field -->
              <div class="space-y-2">
                <label for="phone" class="text-sm font-medium text-slate-700 dark:text-slate-300">Telepon</label>
                <div class="relative">
                  <InputText 
                    id="phone" 
                    name="phone" 
                    v-model="state.phone" 
                    class="w-full pl-12 pr-4 py-3 bg-white/50 dark:bg-zinc-700/50 border border-slate-200 dark:border-zinc-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                    placeholder="08xx-xxxx-xxxx"
                  />
                </div>
                <Message v-if="$form.phone?.invalid" severity="error" size="small" variant="simple">{{ $form.phone.error.message }}</Message>
              </div>

              <!-- Address Field -->
              <div class="space-y-2">
                <label for="address" class="text-sm font-medium text-slate-700 dark:text-slate-300">Alamat</label>
                <div class="relative">
                  <InputText 
                    id="address" 
                    name="address" 
                    v-model="state.address" 
                    class="w-full pl-12 pr-4 py-3 bg-white/50 dark:bg-zinc-700/50 border border-slate-200 dark:border-zinc-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                    placeholder="Alamat lengkap"
                  />
                </div>
                <Message v-if="$form.address?.invalid" severity="error" size="small" variant="simple">{{ $form.address.error.message }}</Message>
              </div>
            </div>

            <!-- Avatar Upload (Hidden) -->
            <input 
              type="file" 
              id="avatar" 
              @change="onFileUpload" 
              class="hidden" 
              accept="image/jpeg,image/png,image/gif"
            />

            <!-- Notification Settings Section -->
            <div class="border-t border-slate-200 dark:border-zinc-700 pt-6 mt-6">
              <h4 class="text-md font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                <Icon name="lucide:bell" class="w-4 h-4" />
                Pengaturan Notifikasi
              </h4>
              
              <div class="space-y-4">
                <!-- Email Notifications Toggle -->
                <div class="flex items-center justify-between p-4 bg-slate-50/80 dark:bg-zinc-700/50 rounded-xl border border-slate-200 dark:border-zinc-600">
                  <div class="flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg">
                      <Icon name="lucide:mail" class="w-4 h-4 text-white" />
                    </div>
                    <div>
                      <h5 class="font-medium text-slate-800 dark:text-white">Notifikasi Email</h5>
                      <p class="text-sm text-slate-500 dark:text-slate-400">Terima notifikasi melalui email untuk mendapatkan notifikasi terbaru</p>
                    </div>
                  </div>
                  <ToggleSwitch 
                    v-model="state.email_notifications" 
                    class="ml-4"
                    :pt="{
                      slider: {
                        class: 'bg-gradient-to-r from-blue-500 to-purple-600 data-[p-toggleswitch-checked]:from-green-500 data-[p-toggleswitch-checked]:to-emerald-600'
                      }
                    }"
                  />
                </div>
              </div>
            </div>

            <!-- Password Section -->
            <div class="border-t border-slate-200 dark:border-zinc-700 pt-6 mt-6">
              <h4 class="text-md font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                Ubah Password
              </h4>
              <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Kosongkan jika tidak ingin mengubah password</p>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Password Field -->
                <div class="space-y-2">
                  <label for="password" class="text-sm font-medium text-slate-700 dark:text-slate-300">Password Baru</label>
                  <div class="relative">
                    <InputText 
                      type="password" 
                      id="password" 
                      name="password" 
                      v-model="state.password" 
                      class="w-full pl-12 pr-4 py-3 bg-white/50 dark:bg-zinc-700/50 border border-slate-200 dark:border-zinc-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                      placeholder="Minimal 8 karakter"
                      autocomplete="new-password" 
                    />
                  </div>
                  <Message v-if="$form.password?.invalid" severity="error" size="small" variant="simple">{{ $form.password.error.message }}</Message>
                </div>

                <!-- Password Confirmation Field -->
                <div class="space-y-2">
                  <label for="password_confirmation" class="text-sm font-medium text-slate-700 dark:text-slate-300">Konfirmasi Password</label>
                  <div class="relative">
                    <InputText 
                      type="password" 
                      id="password_confirmation" 
                      name="password_confirmation" 
                      v-model="state.password_confirmation" 
                      class="w-full pl-12 pr-4 py-3 bg-white/50 dark:bg-zinc-700/50 border border-slate-200 dark:border-zinc-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                      placeholder="Ulangi password baru"
                      autocomplete="new-password" 
                    />
                  </div>
                  <Message v-if="$form.password_confirmation?.invalid" severity="error" size="small" variant="simple">{{ $form.password_confirmation.error.message }}</Message>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-slate-200 dark:border-zinc-700">
              <Button 
                label="Simpan Perubahan" 
                type="submit" 
                class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 border-0 px-6 py-3 rounded-xl font-medium shadow-lg transition-all duration-200 transform hover:scale-[1.02]"
              >
                <template #icon>
                  <Icon name="lucide:save" class="w-4 h-4" />
                </template>
              </Button>
              
              <Button 
                label="Reset" 
                type="button" 
                severity="secondary"
                @click="resetForm"
                class="sm:w-auto bg-slate-100 dark:bg-zinc-700 hover:bg-slate-200 dark:hover:bg-zinc-600 border border-slate-200 dark:border-zinc-600 px-6 py-3 rounded-xl font-medium transition-colors"
              >
                <template #icon>
                  <Icon name="lucide:rotate-ccw" class="w-4 h-4" />
                </template>
              </Button>
            </div>
          </Form>
        </div>
      </div>
    </div>

    <!-- Toast Notifications -->
    <Toast />
  </div>
</template>

<script setup lang="ts">
definePageMeta({ title: 'Profile' });

const userStore = useUserStore();
const client = useSanctumClient();
const user = useSanctumUser() as any;
const toast = useToast();

interface ProfileState {
  email: string;
  name: string;
  avatar: string | File;
  phone: string;
  address: string;
  password: string;
  password_confirmation: string;
  email_notifications: boolean;
}

const state = ref<ProfileState>({
  email: '',
  name: '',
  avatar: '',
  phone: '',
  address: '',
  password: '',
  password_confirmation: '',
  email_notifications: true,
});

const originalState = ref<ProfileState>({
  email: '',
  name: '',
  avatar: '',
  phone: '',
  address: '',
  password: '',
  password_confirmation: '',
  email_notifications: true,
});

const resolver = () => {
  const errors = {} as any;

  if (!state.value.name) {
    errors.name = [{ message: 'Nama Lengkap wajib diisi' }];
  }
  if (!state.value.email) {
    errors.email = [{ message: 'Email wajib diisi' }];
  } else if (!/\S+@\S+\.\S+/.test(state.value.email)) {
    errors.email = [{ message: 'Email tidak valid' }];
  }
  if (!state.value.phone) {
    errors.phone = [{ message: 'Telepon wajib diisi' }];
  }
  if (!state.value.address) {
    errors.address = [{ message: 'Alamat wajib diisi' }];
  }
  if (state.value.password && state.value.password.length < 8) {
    errors.password = [{ message: 'Password minimal 8 karakter' }];
  }
  if (state.value.password && state.value.password !== state.value.password_confirmation) {
    errors.password_confirmation = [{ message: 'Password dan konfirmasi tidak cocok' }];
  }
  
  return { errors };
};

const handleUpdate = async ({ valid }: { valid: boolean }) => {
  if (!valid) {
    toast.add({ 
      severity: 'error', 
      summary: 'Validasi Error', 
      detail: 'Pastikan semua kolom telah diisi dengan benar!', 
      life: 3000 
    });
    return;
  }

  try {
    const formData = new FormData();
    
    // Add form fields to FormData
    formData.append('name', state.value.name);
    formData.append('email', state.value.email);
    formData.append('phone', state.value.phone);
    formData.append('address', state.value.address);
    formData.append('email_notifications', state.value.email_notifications ? '1' : '0');
    
    if (state.value.password) {
      formData.append('password', state.value.password);
      formData.append('password_confirmation', state.value.password_confirmation);
    }
    
    if (state.value.avatar instanceof File) {
      formData.append('avatar', state.value.avatar);
    }

    const response = await client(`/api/profile`, {
      method: 'PUT',
      body: formData
    });

    // Update user store with new data
    userStore.setUser({
      name: state.value.name,
      email: state.value.email,
      phone: state.value.phone,
      address: state.value.address,
      avatar: response.user.avatar,
      email_notifications: state.value.email_notifications,
    });

    // Also update the sanctum user ref to sync with header
    if (user.value) {
      user.value.name = state.value.name;
      user.value.email = state.value.email;
      user.value.phone = state.value.phone;
      user.value.address = state.value.address;
      user.value.avatar = response.user.avatar;
      user.value.email_notifications = state.value.email_notifications;
    }

    // Reset password fields after successful update
    state.value.password = '';
    state.value.password_confirmation = '';

    toast.add({ 
      severity: 'success', 
      summary: 'Berhasil!', 
      detail: 'Profil berhasil diperbarui', 
      life: 3000 
    });
  } catch (error: any) {
    console.error('Profile update error:', error);
    toast.add({ 
      severity: 'error', 
      summary: 'Error!', 
      detail: error?.data?.message || 'Terjadi kesalahan saat memperbarui profil', 
      life: 3000 
    });
  }
};

const resetForm = () => {
  state.value = { ...originalState.value };
  state.value.password = '';
  state.value.password_confirmation = '';
  
  toast.add({ 
    severity: 'info', 
    summary: 'Form Reset', 
    detail: 'Form telah dikembalikan ke data asli', 
    life: 2000 
  });
};

const onFileUpload = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
  const maxSize = 2 * 1024 * 1024; // 2MB

  if (file) {
    if (!validTypes.includes(file.type)) {
      toast.add({ 
        severity: 'error', 
        summary: 'Format File Salah', 
        detail: 'Harap upload gambar dengan format JPEG, PNG, atau GIF', 
        life: 3000 
      });
      target.value = '';
      return;
    }

    if (file.size > maxSize) {
      toast.add({ 
        severity: 'error', 
        summary: 'File Terlalu Besar', 
        detail: 'Ukuran file maksimal 2MB', 
        life: 3000 
      });
      target.value = '';
      return;
    }

    state.value.avatar = file;
    toast.add({ 
      severity: 'success', 
      summary: 'File Dipilih', 
      detail: `File ${file.name} siap untuk diupload`, 
      life: 2000 
    });
  }
};

onMounted(() => {
  if (user.value) {
    const userData = {
      email: user.value.email || '',
      name: user.value.name || '',
      phone: user.value.phone || '',
      address: user.value.address || '',
      avatar: user.value.avatar || '',
      password: '',
      password_confirmation: '',
      email_notifications: user.value.email_notifications ?? true
    };
    
    state.value = { ...userData };
    originalState.value = { ...userData };
  }
});
</script>