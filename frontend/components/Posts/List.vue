<template>
  <div>
    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center h-40">
      <Icon name="lucide:loader" class="animate-spin text-blue-500 text-4xl" />
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="text-red-500 text-center">
      Gagal memuat data post. Silakan coba lagi.
    </div>

    <!-- Post List -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="post in posts" :key="post.id" class="bg-white shadow-md rounded-lg overflow-hidden relative">
        <!-- Featured Image -->
        <img
          :src="post.featured_image || '/placeholder.jpg'"
          alt="Featured Image"
          class="w-full h-48 object-cover"
        />

        <!-- Konten Post -->
        <div class="p-4">
          <!-- Kategori -->
          <div class="text-sm text-gray-500 mb-2">
            <span v-if="post.category" class="bg-gray-200 px-2 py-1 rounded">{{ post.category.name }}</span>
          </div>

          <!-- Judul -->
          <h3 class="text-lg font-semibold text-gray-800">{{ post.title }}</h3>

          <!-- Excerpt -->
          <p class="text-gray-600 mt-2 line-clamp-3">{{ post.excerpt }}</p>

          <!-- Aksi -->
          <div class="mt-4 flex justify-between items-center absolute top-0">
            <NuxtLink
              :to="`/posts/${post.id}`"
              class="text-blue-500 hover:text-blue-600 flex items-center"
            >
              <Icon name="lucide:edit" class="mr-1" /> Edit
            </NuxtLink>
            <button
              @click="deletePost(post.id)"
              class="text-red-500 hover:text-red-600 flex items-center"
            >
              <Icon name="lucide:trash-2" class="mr-1" /> Hapus
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

// State untuk data, loading, dan error
const posts = ref([]) as any;
const loading = ref(true);
const error = ref(false);

// Fetch Data Post
const fetchData = async () => {
  try {
    const client = useSanctumClient();
    const response = await client('/posts');
    posts.value = response.data; // Simpan data post
  } catch (err) {
    console.error('Error fetching posts:', err);
    error.value = true;
  } finally {
    loading.value = false; // Set loading menjadi false setelah selesai
  }
};

// Fungsi untuk Menghapus Post
const deletePost = async (id: number) => {
  try {
    const client = useSanctumClient();
    await client(`/posts/${id}`, { method: 'DELETE' });
    posts.value = posts.value.filter((post) => post.id !== id); // Hapus post dari daftar
  } catch (err) {
    console.error('Error deleting post:', err);
  }
};

// Panggil fetchData saat komponen dimuat
fetchData();
</script>

<style scoped>
/* Placeholder image jika featured_image tidak tersedia */
.placeholder {
  background-color: #f3f4f6;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: #9ca3af;
}
</style>
