<template>
  <SettingsLayout>
    <div class="space-y-6">
      <!-- Modern Header Section -->
      <div class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl p-6 shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg">
              <Icon name="lucide:file-text" class="w-6 h-6 text-white" />
            </div>
            <div>
              <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Kelola Posts</h1>
              <p class="text-sm text-slate-500 dark:text-slate-400">Buat dan kelola konten blog atau artikel</p>
            </div>
          </div>

          <NuxtLink 
            to="/posts/new" 
            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2 font-medium"
          >
            <Icon name="lucide:plus" class="w-4 h-4" />
            <span class="hidden sm:inline">Tambah Post</span>
          </NuxtLink>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 pt-6 border-t border-slate-200/50 dark:border-zinc-700/50">
          <div class="text-center p-3 bg-blue-50/80 dark:bg-blue-900/20 rounded-xl border border-blue-200/50 dark:border-blue-700/50">
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ data?.data?.length || 0 }}</div>
            <div class="text-xs text-blue-600/70 dark:text-blue-400/70 font-medium">Total Posts</div>
          </div>
          <div class="text-center p-3 bg-green-50/80 dark:bg-green-900/20 rounded-xl border border-green-200/50 dark:border-green-700/50">
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ publishedCount }}</div>
            <div class="text-xs text-green-600/70 dark:text-green-400/70 font-medium">Published</div>
          </div>
          <div class="text-center p-3 bg-amber-50/80 dark:bg-amber-900/20 rounded-xl border border-amber-200/50 dark:border-amber-700/50">
            <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ categoriesCount }}</div>
            <div class="text-xs text-amber-600/70 dark:text-amber-400/70 font-medium">Categories</div>
          </div>
        </div>
      </div>

      <!-- Posts Grid -->
      <div v-if="data?.data?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="post in data.data" 
          :key="post.id" 
          class="group backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
        >
          <!-- Featured Image -->
          <div class="relative h-48 overflow-hidden">
            <img
              :src="post.featured_image || '/placeholder.jpg'"
              :alt="post.title"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
            
            <!-- Overlay Actions -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <div class="absolute bottom-4 left-4 right-4 flex items-center gap-2">
                <NuxtLink
                  :to="`/posts/${post.id}`"
                  class="flex-1 px-3 py-2 bg-blue-500/90 hover:bg-blue-600/90 text-white rounded-lg shadow-lg backdrop-blur-sm transition-all duration-200 flex items-center justify-center gap-2 text-sm font-medium"
                >
                  <Icon name="lucide:edit" class="w-4 h-4" />
                  Edit
                </NuxtLink>
                <button
                  @click="deletePost(post.id)"
                  class="px-3 py-2 bg-red-500/90 hover:bg-red-600/90 text-white rounded-lg shadow-lg backdrop-blur-sm transition-all duration-200 flex items-center justify-center"
                >
                  <Icon name="lucide:trash-2" class="w-4 h-4" />
                </button>
              </div>
            </div>

            <!-- Category Badge -->
            <div class="absolute top-4 left-4">
              <div class="px-3 py-1 bg-purple-500/90 text-white rounded-full text-xs font-medium backdrop-blur-sm shadow-lg">
                {{ post.category?.name || 'Uncategorized' }}
              </div>
            </div>
          </div>

          <!-- Post Content -->
          <div class="p-6">
            <div class="space-y-3">
              <h3 class="text-lg font-semibold text-slate-800 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                {{ post.title }}
              </h3>
              
              <div class="text-sm text-slate-600 dark:text-slate-400 line-clamp-3" v-html="post.excerpt || post.content"></div>
              
              <!-- Meta Information -->
              <div class="flex items-center justify-between pt-3 border-t border-slate-200/50 dark:border-zinc-700/50">
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                  <Icon name="lucide:calendar" class="w-3 h-3" />
                  <span>{{ formatDate(post.created_at) }}</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                  <Icon name="lucide:eye" class="w-3 h-3" />
                  <span>{{ post.views || 0 }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="backdrop-blur-xl bg-white/80 dark:bg-zinc-900/80 border border-slate-200/50 dark:border-zinc-700/50 rounded-2xl shadow-lg shadow-slate-900/5 dark:shadow-zinc-900/20 p-12">
        <div class="text-center space-y-4">
          <div class="p-4 bg-slate-100/80 dark:bg-zinc-800/80 rounded-full w-24 h-24 mx-auto flex items-center justify-center">
            <Icon name="lucide:file-text" class="w-12 h-12 text-slate-400 dark:text-slate-500" />
          </div>
          <div>
            <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-2">Belum Ada Posts</h3>
            <p class="text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto">
              Mulai membuat konten dengan menambahkan post pertama Anda. Klik tombol "Tambah Post" untuk memulai.
            </p>
          </div>
          <NuxtLink 
            to="/posts/new" 
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 font-medium"
          >
            <Icon name="lucide:plus" class="w-4 h-4" />
            Buat Post Pertama
          </NuxtLink>
        </div>
      </div>
    </div>
  </SettingsLayout>
</template>

<script setup lang="ts">
const client = useSanctumClient();
const posts = ref([]);

// Fetch Data Post
const { data, refresh } = await useAsyncData('posts', fetchPost);

function fetchPost() {
  return client('/posts');
}

// Computed properties for stats
const publishedCount = computed(() => {
  return data.value?.data?.filter((post: any) => post.status === 'published')?.length || data.value?.data?.length || 0;
});

const categoriesCount = computed(() => {
  const categories = new Set();
  data.value?.data?.forEach((post: any) => {
    if (post.category?.name) {
      categories.add(post.category.name);
    }
  });
  return categories.size;
});

// Utility function for date formatting
const formatDate = (dateString: string) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

// Fungsi untuk Menghapus Post
const deletePost = async (id: number) => {
  try {
    await client(`/posts/${id}`, { method: 'DELETE' });
    await refresh(); // Refresh data setelah menghapus
  } catch (error) {
    console.error('Error deleting post:', error);
  }
};
</script>
