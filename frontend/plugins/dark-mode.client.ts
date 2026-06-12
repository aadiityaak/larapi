export default defineNuxtPlugin(() => {
  if (process.client) {
    // Initialize dark mode as early as possible to prevent flash
    const savedMode = localStorage.getItem('darkMode') === 'true';
    if (savedMode) {
      document.documentElement.classList.add('dark');
    }
  }
});
