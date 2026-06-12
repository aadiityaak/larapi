export const useSidebarStore = defineStore('sidebar', () => {
  const collapsed = ref<boolean>(JSON.parse(localStorage.getItem('sidebarCollapsed') || 'false'));
  const app_name = ref<string>('');
  const router = useRouter();

  const toggleCollapsed = () => {
    collapsed.value = !collapsed.value;
    localStorage.setItem('sidebarCollapsed', JSON.stringify(collapsed.value));
  };

  // Alias untuk konsistensi
  const toggle = toggleCollapsed;

  const setAppName = (name: string) => {
    app_name.value = name;
    localStorage.setItem('appName', JSON.stringify(app_name.value));
  };

  // Untuk mobile, sidebar akan collapsed secara otomatis
  const updateSidebarStatus = () => {
    if (window.innerWidth < 768) {
      collapsed.value = true;
    }
  };

  // Fungsi untuk collapse sidebar jika rute berubah saat layar mobile
  const collapseSidebarOnRouteChange = () => {
    if (window.innerWidth < 768) {
      collapsed.value = true;
    }
  };

  onMounted(() => {
    window.addEventListener('resize', updateSidebarStatus);
    updateSidebarStatus(); // Panggil saat pertama kali

    // Tambahkan listener untuk perubahan rute
    router.beforeEach((to, from, next) => {
      collapseSidebarOnRouteChange();
      next();
    });
  });

  onBeforeUnmount(() => {
    window.removeEventListener('resize', updateSidebarStatus);
  });

  watch(collapsed, (newValue) => {
    localStorage.setItem('sidebarCollapsed', JSON.stringify(newValue));
  });

  watch(app_name, (newValue) => {
    localStorage.setItem('appName', JSON.stringify(newValue));
  });

  return { collapsed, toggleCollapsed, toggle, app_name, setAppName };
});