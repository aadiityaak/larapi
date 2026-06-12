export const useUserStore = defineStore('user', () => {
  // Initialize dari localStorage jika ada
  const savedUser = process.client ? localStorage.getItem('user') : null;
  const initialUser = savedUser ? JSON.parse(savedUser) : { 
    name: '', 
    avatar: '', 
    email: '', 
    phone: '', 
    address: '' 
  };
  
  const user = ref({
    name: initialUser.name || '',
    avatar: initialUser.avatar || '',
    email: initialUser.email || '',
    phone: initialUser.phone || '',
    address: initialUser.address || '',
  });

  function setUser(data: { 
    name: string; 
    avatar?: string; 
    email?: string; 
    phone?: string; 
    address?: string; 
  }) {
    user.value.name = data.name;
    if (data.avatar !== undefined) {
      user.value.avatar = data.avatar;
    }
    if (data.email !== undefined) {
      user.value.email = data.email;
    }
    if (data.phone !== undefined) {
      user.value.phone = data.phone;
    }
    if (data.address !== undefined) {
      user.value.address = data.address;
    }
    
    // Persist ke localStorage
    if (process.client) {
      localStorage.setItem('user', JSON.stringify(user.value));
    }
  }

  // Watch untuk auto-save ke localStorage
  watch(user, (newUser) => {
    if (process.client) {
      localStorage.setItem('user', JSON.stringify(newUser));
    }
  }, { deep: true });

  return { user, setUser };
});