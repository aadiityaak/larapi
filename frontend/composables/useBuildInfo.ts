export const useBuildInfo = async () => {
  const { data, error } = await useFetch('/build-version.json')
  if (error.value) {
    console.warn('⚠️ Failed to load build info:', error.value)
  }
  return data.value ?? {
    version: 'dev',
    buildDate: new Date().toISOString()
  }
}
