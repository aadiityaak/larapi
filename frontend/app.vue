<template>
  <NuxtLoadingIndicator />
  <NuxtLayout :name="layoutCondition">
    <NuxtPage />
  </NuxtLayout>
</template>

<script setup lang="ts">
  const { isAuthenticated, init } = useSanctumAuth();
  const userStore = useUserStore();
  const sanctumUser = useSanctumUser();
  
  const layoutCondition = computed(() => (isAuthenticated.value ? 'dashboard' : 'default'));
  
  // Initialize dark mode on app startup
  const { initializeDarkMode } = useDarkMode();
  
  onMounted(async () => {
    initializeDarkMode();
    
    // Initialize sanctum authentication
    await init();
    
    // Sync user data jika sudah authenticated
    if (isAuthenticated.value && sanctumUser.value) {
      userStore.setUser({
        name: (sanctumUser.value as any)?.name || '',
        avatar: (sanctumUser.value as any)?.avatar || '',
        email: (sanctumUser.value as any)?.email || '',
        phone: (sanctumUser.value as any)?.phone || '',
        address: (sanctumUser.value as any)?.address || ''
      });
    }
  });
  
  // Watch sanctum user changes dan sync ke store
  watch(sanctumUser, (newUser) => {
    if (newUser && isAuthenticated.value) {
      userStore.setUser({
        name: (newUser as any)?.name || '',
        avatar: (newUser as any)?.avatar || '',
        email: (newUser as any)?.email || '',
        phone: (newUser as any)?.phone || '',
        address: (newUser as any)?.address || ''
      });
    }
  }, { immediate: true, deep: true });
</script>

<style>
html.dark {
  color: #cfcfcf;
}
</style>