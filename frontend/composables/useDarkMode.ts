export const useDarkMode = () => {
  const isDark = ref(false);

  const initializeDarkMode = () => {
    if (process.client) {
      const savedMode = localStorage.getItem('darkMode') === 'true';
      isDark.value = savedMode;
      document.documentElement.classList.toggle('dark', savedMode);
    }
  };

  const toggleDarkMode = () => {
    isDark.value = !isDark.value;
    if (process.client) {
      document.documentElement.classList.toggle('dark', isDark.value);
      localStorage.setItem('darkMode', isDark.value ? 'true' : 'false');
    }
  };

  const setDarkMode = (value: boolean) => {
    isDark.value = value;
    if (process.client) {
      document.documentElement.classList.toggle('dark', value);
      localStorage.setItem('darkMode', value ? 'true' : 'false');
    }
  };

  // Initialize on first use
  if (process.client && !isDark.value) {
    initializeDarkMode();
  }

  return {
    isDark: readonly(isDark),
    toggleDarkMode,
    setDarkMode,
    initializeDarkMode
  };
};
