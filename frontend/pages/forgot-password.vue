<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-700 flex items-center justify-center p-4">
    <!-- Background Pattern -->
    <div class="absolute inset-0 overflow-hidden z-0">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-indigo-600/20 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-purple-400/20 to-pink-600/20 rounded-full blur-3xl"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-br from-green-400/10 to-blue-600/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Forgot Password Card -->
    <div class="relative z-10 w-full max-w-md">
      <div class="backdrop-blur-md bg-white/20 dark:bg-zinc-900/20 border border-slate-200/30 dark:border-zinc-700/30 rounded-2xl p-8 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
        
        <!-- Header -->
        <div class="text-center mb-8">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg mb-4">
            <Icon name="lucide:help-circle" class="w-8 h-8 text-white" />
          </div>
          <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Lupa Password?</h2>
          <p class="text-sm text-slate-500 dark:text-slate-400">Butuh bantuan untuk mengakses akun Anda?</p>
        </div>

        <!-- Information Card -->
        <div class="space-y-6">
          <!-- Main Message -->
          <div class="p-6 bg-gradient-to-br from-blue-50/80 to-indigo-50/80 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200/50 dark:border-blue-800/50 rounded-xl">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                  <Icon name="lucide:info" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                </div>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-2">Bantuan Reset Password</h3>
                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                  Untuk keamanan sistem, reset password hanya dapat dilakukan oleh administrator. 
                  Silahkan hubungi developer aplikasi untuk mendapat bantuan lebih lanjut.
                </p>
              </div>
            </div>
          </div>

          <!-- Contact Information -->
          <div class="p-6 bg-gradient-to-br from-emerald-50/80 to-teal-50/80 dark:from-emerald-900/20 dark:to-teal-900/20 border border-emerald-200/50 dark:border-emerald-800/50 rounded-xl">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                  <Icon name="lucide:phone" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                </div>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-2">Kontak Developer</h3>
                <p class="text-sm text-slate-600 dark:text-slate-300 mb-3">
                  Hubungi tim developer untuk bantuan reset password:
                </p>
                <div class="space-y-2">
                  <div class="flex items-center gap-2 text-sm">
                    <Icon name="lucide:mail" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                    <span class="text-slate-700 dark:text-slate-300">admin@sucitek.com</span>
                  </div>
                  <div class="flex items-center gap-2 text-sm">
                    <Icon name="lucide:globe" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                    <span class="text-slate-700 dark:text-slate-300">sucitek.com</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- What to Include -->
          <div class="p-6 bg-gradient-to-br from-amber-50/80 to-orange-50/80 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200/50 dark:border-amber-800/50 rounded-xl">
            <div class="flex items-start gap-4">
              <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/50 rounded-lg flex items-center justify-center">
                  <Icon name="lucide:clipboard-list" class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                </div>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-2">Informasi yang Diperlukan</h3>
                <p class="text-sm text-slate-600 dark:text-slate-300 mb-3">
                  Sertakan informasi berikut saat menghubungi developer:
                </p>
                <ul class="text-sm text-slate-600 dark:text-slate-300 space-y-1">
                  <li class="flex items-center gap-2">
                    <Icon name="lucide:check" class="w-3 h-3 text-amber-600 dark:text-amber-400" />
                    Email akun yang terdaftar
                  </li>
                  <li class="flex items-center gap-2">
                    <Icon name="lucide:check" class="w-3 h-3 text-amber-600 dark:text-amber-400" />
                    Nama lengkap sesuai akun
                  </li>
                  <li class="flex items-center gap-2">
                    <Icon name="lucide:check" class="w-3 h-3 text-amber-600 dark:text-amber-400" />
                    Jabatan/posisi di kantor
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Back to Login Button -->
        <div class="mt-8 flex justify-center">
          <NuxtLink 
            to="/login"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
          >
            <Icon name="lucide:arrow-left" class="w-4 h-4" />
            Kembali ke Login
          </NuxtLink>
        </div>
      </div>

      <!-- Footer -->
      <div class="text-center mt-6">
        <p class="text-xs text-slate-500 dark:text-slate-400">
          © 2025 Asisten Notaris by sucitek.com. All rights reserved.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  definePageMeta({
    title: 'Lupa Password',
    sanctum: {
        excluded: true,
    }
  })
</script>