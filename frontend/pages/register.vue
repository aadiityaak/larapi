<template>
  <form @submit.prevent="handleRegister" ref="form" class="max-w-[400px] w-full mx-auto rounded-lg border border-surface-200 bg-surface-50 dark:border-surface-700 dark:bg-surface-800 p-4" >
      <FloatLabel variant="on" class="mb-4">
          <InputText id="name" v-model="state.name" class="w-full" />
          <label for="name">Nama</label>
      </FloatLabel>
      <FloatLabel variant="on" class="mb-4">
          <InputText id="email" v-model="state.email" class="w-full" />
          <label for="email">Email</label>
      </FloatLabel>
      <FloatLabel variant="on" class="mb-4">
          <InputText type="password" id="password" v-model="state.password" class="w-full" />
          <label for="password">Password</label>
      </FloatLabel>
      <FloatLabel variant="on" class="mb-4">
          <InputText type="password" id="password_confirmation" v-model="state.password_confirmation" class="w-full" />
          <label for="password_confirmation">Konfirmasi Password</label>
      </FloatLabel>
      <Message severity="error" class="mb-4 max-w-[400px] w-full mx-auto" v-if="message">
        {{ message }}
      </Message>
      <Button label="Register" type="submit" class="mt-4"><Icon name="lucide:user-plus"/> Register</Button>
  </form>
</template>

<script setup lang="ts">
  definePageMeta({
    title: 'Register',
    // sanctum: {
    //     excluded: true,
    // }
  })
  const sanctumFetch = useSanctumClient()
  const { refreshIdentity } = useSanctumAuth()

  const form = ref<HTMLFormElement>()
  const state = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  })

  const message = ref('')

  async function handleRegister() {
    try {
      const response = await sanctumFetch('/register', {
        method: 'POST',
        body: state.value
      })
      await refreshIdentity()
      navigateTo('/')
    } catch (err: any) {
      message.value = err.response.data.message
    }
  }
</script>
