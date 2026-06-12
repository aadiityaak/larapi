export const useLogoStore = defineStore('logo', () => {
  const logo = ref<string>('');

  function setLogo(url: string) {
    logo.value = url;
  }

  return { logo, setLogo };
});
