<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 dark:from-zinc-900 dark:to-zinc-800 p-6">
    <div class="max-w-4xl mx-auto space-y-6">
      <!-- Modern Header Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
        <div class="flex items-center gap-4">
          <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
            <Icon name="lucide:edit" class="w-6 h-6 text-white" />
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ isEdit ? 'Edit' : 'Buat' }} Post</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ isEdit ? 'Perbarui konten post yang sudah ada' : 'Buat post baru untuk website' }}</p>
          </div>
        </div>

        <!-- Breadcrumb Navigation -->
        <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 mt-4 pt-4 border-t border-slate-200/50 dark:border-zinc-700/50">
          <NuxtLink to="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1">
            <Icon name="lucide:home" class="w-4 h-4" />
            <span class="hidden sm:inline">Dashboard</span>
          </NuxtLink>
          <Icon name="lucide:chevron-right" class="w-3 h-3" />
          <NuxtLink to="/posts" class="hover:text-slate-900 dark:hover:text-white transition-colors">Posts</NuxtLink>
          <Icon name="lucide:chevron-right" class="w-3 h-3" />
          <span class="text-slate-900 dark:text-white font-medium">{{ isEdit ? 'Edit' : 'Create' }}</span>
        </div>
      </div>

      <!-- Modern Form Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 overflow-hidden">
        <form @submit.prevent="savePost" class="p-6">
          <div class="space-y-6">
            <!-- Title Section -->
            <div class="backdrop-blur-xl bg-gradient-to-br from-blue-50/80 to-indigo-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-blue-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-lg">
                  <Icon name="lucide:type" class="w-5 h-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Judul Post</h3>
              </div>

              <div class="space-y-2">
                <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Masukkan judul post</label>
                <InputText 
                  id="title"
                  v-model.trim="post.title" 
                  class="w-full rounded-xl border-blue-300 dark:border-blue-600/50 focus:border-blue-500 dark:focus:border-blue-400 px-4 py-3 text-sm bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm" 
                  placeholder="Judul yang menarik untuk post Anda"
                  required 
                />
              </div>
            </div>

            <!-- Content Section -->
            <div class="backdrop-blur-xl bg-gradient-to-br from-green-50/80 to-emerald-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-green-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg shadow-lg">
                  <Icon name="lucide:file-text" class="w-5 h-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Konten Post</h3>
              </div>

              <div class="space-y-2">
                <label for="content" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Tulis konten post</label>
                <div class="bg-white/80 dark:bg-zinc-800/80 rounded-xl border border-green-300 dark:border-green-600/50 backdrop-blur-sm overflow-hidden">
                  <Editor 
                    v-model="post.content" 
                    editorStyle="height: 350px; background: transparent;"
                    :pt="{
                      root: { class: 'w-full' },
                      content: { class: 'bg-transparent border-0' }
                    }"
                  />
                </div>
              </div>
            </div>

            <!-- Category Section -->
            <div class="backdrop-blur-xl bg-gradient-to-br from-purple-50/80 to-pink-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-purple-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-lg">
                  <Icon name="lucide:tag" class="w-5 h-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Kategori</h3>
              </div>

              <div class="space-y-2">
                <label for="category" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Pilih kategori post</label>
                <div class="flex gap-3">
                  <Dropdown 
                    v-model="post.category_id" 
                    :options="categories" 
                    optionLabel="name" 
                    optionValue="id" 
                    class="flex-1"
                    placeholder="Pilih kategori"
                    :pt="{
                      root: { class: 'w-full' },
                      input: { class: 'rounded-xl border-purple-300 dark:border-purple-600/50 focus:border-purple-500 dark:focus:border-purple-400 px-4 py-3 text-sm bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm' }
                    }"
                  />
                  <Button 
                    @click="showCategoryModal = true"
                    class="px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
                  >
                    <Icon name="lucide:plus" class="w-4 h-4" />
                  </Button>
                </div>
              </div>
            </div>

            <!-- Featured Image Section -->
            <div class="backdrop-blur-xl bg-gradient-to-br from-amber-50/80 to-orange-50/80 dark:from-zinc-800/80 dark:to-zinc-700/80 border border-amber-200/50 dark:border-zinc-600/50 rounded-2xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-lg">
                  <Icon name="lucide:image" class="w-5 h-5 text-white" />
                </div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Gambar Unggulan</h3>
              </div>

              <div class="space-y-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Upload gambar untuk post</label>
                <div class="border-2 border-dashed border-amber-300 dark:border-amber-600/50 rounded-xl p-6 bg-amber-50/50 dark:bg-amber-900/10 hover:bg-amber-100/50 dark:hover:bg-amber-900/20 transition-colors">
                  <FileUpload 
                    mode="basic" 
                    accept="image/*" 
                    customUpload 
                    @select="handleImageUpload"
                    :pt="{
                      root: { class: 'w-full' },
                      input: { class: 'w-full px*4 py-3 rounded-xl border-amber-300 dark:border-amber-600 focus:border-amber-500 dark:focus:border-amber-400 bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm text-sm' },
                      chooseButton: { class: 'px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 text-sm font-medium' }
                    }"
                  />
                </div>

                <div v-if="previewImage || post.featured_image" class="mt-4">
                  <div class="bg-white dark:bg-zinc-800 p-4 rounded-xl shadow-lg border border-slate-200 dark:border-zinc-700">
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Preview Gambar:</p>
                    <img :src="previewImage || `${post.featured_image}`" class="w-48 h-32 object-cover rounded-lg shadow-md mx-auto" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-between items-center gap-3 pt-6 mt-6 border-t border-slate-200/50 dark:border-zinc-700/50">
            <NuxtLink 
              to="/posts" 
              class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-2 text-sm font-medium"
            >
              <Icon name="lucide:arrow-left" class="w-4 h-4" />
              Kembali ke Posts
            </NuxtLink>
            
            <Button 
              type="submit" 
              class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 font-medium"
            >
              <Icon name="lucide:save" class="w-4 h-4" />
              {{ isEdit ? 'Update Post' : 'Buat Post' }}
            </Button>
          </div>
        </form>
      </div>
    </div>

    <!-- Category Modal -->
    <Dialog v-model:visible="showCategoryModal" header="Tambah Kategori Baru" :modal="true" :closable="false" class="w-full max-w-md">
      <div class="backdrop-blur-xl bg-white/90 dark:bg-zinc-900/90 rounded-xl p-6">
        <div class="space-y-4">
          <div>
            <label for="newCategory" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nama Kategori</label>
            <InputText 
              id="newCategory"
              v-model.trim="newCategory" 
              class="w-full rounded-xl border-slate-300 dark:border-zinc-600 focus:border-blue-500 dark:focus:border-blue-400 px-4 py-3 text-sm bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm" 
              placeholder="Masukkan nama kategori"
            />
          </div>
          
          <div class="flex justify-end gap-3 pt-4">
            <Button 
              @click="showCategoryModal = false"
              class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors text-sm font-medium"
            >
              Batal
            </Button>
            <Button 
              @click="addCategory"
              class="px-6 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 text-sm font-medium"
            >
              <Icon name="lucide:plus" class="w-4 h-4" />
              Simpan
            </Button>
          </div>
        </div>
      </div>
    </Dialog>
    
    <Toast />
  </div>
</template>

<script setup>
const client = useSanctumClient()
const router = useRouter()
const route = useRoute()
const toast = useToast()

// State untuk form post
const post = ref({
  title: '',
  content: '',
  category_id: null,
  featured_image: '', // Ini bisa berupa string (URL) atau File object
});

// State untuk kategori
const categories = ref([]);
const showCategoryModal = ref(false);
const newCategory = ref('');

// State untuk preview gambar
const previewImage = ref(null);

// Komputasi untuk menentukan apakah ini mode edit
const isEdit = computed(() => !!route.params.id && route.params.id !== 'new');

// Debugging untuk memastikan nilai `isEdit`
console.log('isEdit.value:', isEdit.value);

// Lifecycle hook untuk mengambil data saat komponen dimuat
onMounted(async () => {
  try {
    await fetchCategories(); // Ambil daftar kategori
    if (isEdit.value) {
      await fetchPost(route.params.id); // Jika mode edit, ambil data post berdasarkan ID
    }
  } catch (error) {
    console.error('Error during component initialization:', error);
  }
});

// Fungsi untuk mengambil daftar kategori
const fetchCategories = async () => {
  try {
    const response = await client('/categories');
    categories.value = response; // Pastikan respons adalah array
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
};

// Fungsi untuk mengambil data post berdasarkan ID
const fetchPost = async (id) => {
  try {
    const response = await client(`/posts/${id}`);
    console.log("Response dari API:", response);

    // Pastikan respons adalah objek dengan properti yang sesuai
    post.value = {
      title: response.title || '',
      content: response.content || '',
      category_id: response.category_id || null,
      featured_image: response.featured_image || '', // Pastikan ada fallback jika tidak ada gambar
    };
  } catch (error) {
    console.error('Error fetching post:', error);
  }
};

// Fungsi untuk menyimpan/memperbarui post
const savePost = async () => {
  const formData = new FormData();

  // Tambahkan data ke FormData
  formData.append("title", post.value.title);
  formData.append("content", post.value.content);
  formData.append("category_id", post.value.category_id);

  // Jika ada file gambar, tambahkan ke FormData
  if (post.value.featured_image instanceof File) {
    formData.append("featured_image", post.value.featured_image); // Pastikan nama field sesuai
  }

  // Debugging: Cek isi FormData
  for (let [key, value] of formData.entries()) {
    console.log(`${key}:`, value);
  }

  try {
    if (isEdit.value) {
      await client(`/posts/${route.params.id}`, {
        method: 'PUT',
        body: formData,
      });
      toast.add({ severity: 'success', summary: 'Success', detail: 'Update post berhasil!', life: 3000 });
    } else {
      await client('/posts', {
        method: 'POST',
        body: formData,
      });
      toast.add({ severity: 'success', summary: 'Success', detail: 'Post berhasil disimpan!', life: 3000 });
    }

    router.push('/posts');
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal menyimpan post', life: 3000 });
    console.error('Error saving post:', error);
  }
};

// Fungsi untuk menambahkan kategori baru
const addCategory = async () => {
  if (!newCategory.value.trim()) return;

  try {
    const response = await client('/categories', {
      method: 'POST',
      body: { name: newCategory.value },
    });

    // Tambahkan kategori baru ke daftar kategori
    categories.value.push(response);

    // Set kategori baru sebagai pilihan default di form
    post.value.category_id = response.id;

    // Reset modal dan input
    newCategory.value = '';
    showCategoryModal.value = false;
  } catch (error) {
    console.error('Error adding category:', error);
  }
};

// Fungsi untuk menangani upload gambar
const handleImageUpload = (event) => {
  const file = event.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      previewImage.value = e.target.result; // Simpan URL preview gambar
    };
    reader.readAsDataURL(file); // Baca file sebagai Data URL
    post.value.featured_image = file; // Simpan file ke state post
  } else {
    console.error('No file selected');
  }
};
</script>
