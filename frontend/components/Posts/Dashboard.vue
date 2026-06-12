<template>
  <div class="bg-white/30 dark:bg-zinc-800/30 backdrop-blur-xl rounded-2xl p-6 border border-slate-200/50 dark:border-zinc-700/50 shadow-xl hover:shadow-2xl transition-all duration-300">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="p-2 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl shadow-lg">
          <Icon name="lucide:newspaper" class="text-lg text-white" />
        </div>
        <div>
          <h3 class="text-lg font-bold text-slate-800 dark:text-zinc-200">Informasi Terbaru</h3>
          <p class="text-sm text-slate-500 dark:text-zinc-400">Berita dan update terkini</p>
        </div>
      </div>
      <div class="flex gap-2">
        <button 
          @click="scrollPrev" 
          :disabled="currentIndex === 0"
          class="p-2 bg-white/60 dark:bg-zinc-700/60 backdrop-blur-sm rounded-lg border border-slate-200/30 dark:border-zinc-600/30 hover:bg-white/80 dark:hover:bg-zinc-700/80 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <Icon name="lucide:chevron-left" class="text-slate-600 dark:text-zinc-400" />
        </button>
        <button 
          @click="scrollNext" 
          :disabled="currentIndex >= posts.length - visibleCards"
          class="p-2 bg-white/60 dark:bg-zinc-700/60 backdrop-blur-sm rounded-lg border border-slate-200/30 dark:border-zinc-600/30 hover:bg-white/80 dark:hover:bg-zinc-700/80 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <Icon name="lucide:chevron-right" class="text-slate-600 dark:text-zinc-400" />
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center h-40">
      <div class="flex items-center gap-3">
        <Icon name="lucide:loader" class="animate-spin text-emerald-500 text-2xl" />
        <span class="text-slate-600 dark:text-zinc-400">Memuat informasi...</span>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="text-center py-12">
      <Icon name="lucide:alert-circle" class="text-4xl text-red-500 mx-auto mb-3" />
      <p class="text-red-500 font-medium">Gagal memuat data post</p>
      <p class="text-slate-500 dark:text-zinc-400 text-sm mt-1">Silakan coba lagi nanti</p>
    </div>

    <!-- Carousel Container -->
    <div v-else-if="posts.length > 0" class="relative overflow-hidden">
      <div 
        class="flex transition-transform duration-500 ease-in-out gap-4"
        :style="{ transform: `translateX(-${currentIndex * (100 / visibleCards)}%)` }"
      >
        <div 
          v-for="post in posts" 
          :key="post.id" 
          class="flex-shrink-0 bg-white/60 dark:bg-zinc-700/60 backdrop-blur-sm rounded-xl overflow-hidden border border-slate-200/30 dark:border-zinc-600/30 hover:shadow-lg transition-all duration-300 cursor-pointer group"
          :style="{ width: `calc(${100 / visibleCards}% - ${(visibleCards - 1) * 16 / visibleCards}px)` }"
          @click="showModal(post)"
        >
          <!-- Category Badge -->
          <div class="absolute top-3 right-3 z-10">
            <span 
              v-if="post.category" 
              class="bg-black/20 backdrop-blur-sm text-white text-xs px-2 py-1 rounded-full font-medium"
            >
              {{ post.category.name }}
            </span>
          </div>

          <!-- Featured Image Container -->
          <div class="relative h-40 overflow-hidden">
            <!-- Valid Image -->
            <img
              v-if="post.featured_image && !imageErrors[post.id]"
              :src="post.featured_image"
              :alt="post.title"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              @error="handleImageError(post.id)"
            />
            
            <!-- Fallback Gradient Background with Icon -->
            <div 
              v-else
              class="w-full h-full bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-600 flex items-center justify-center relative overflow-hidden"
            >
              <!-- Background Pattern -->
              <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-transparent via-white/10 to-transparent"></div>
              </div>
              
              <!-- Main Icon -->
              <div class="relative z-10 text-center">
                <Icon name="lucide:image" class="text-4xl text-white/90 mb-2" />
                <p class="text-white/80 text-xs font-medium">No Image</p>
              </div>
              
              <!-- Decorative Elements -->
              <div class="absolute -top-2 -right-2 w-16 h-16 bg-white/10 rounded-full"></div>
              <div class="absolute -bottom-3 -left-3 w-12 h-12 bg-white/10 rounded-full"></div>
            </div>
          </div>

          <!-- Content -->
          <div class="p-4">
            <!-- Title -->
            <h4 class="text-sm font-semibold text-slate-800 dark:text-zinc-200 line-clamp-2 mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-300">
              {{ post.title }}
            </h4>

            <!-- Excerpt -->
            <p class="text-xs text-slate-600 dark:text-zinc-400 line-clamp-2 leading-relaxed">
              {{ getPlainTextSnippet(post.content, 80) }}
            </p>

            <!-- Read More Button -->
            <div class="mt-3 flex items-center text-emerald-600 dark:text-emerald-400 text-xs font-medium">
              <span>Baca selengkapnya</span>
              <Icon name="lucide:arrow-right" class="ml-1 text-xs group-hover:translate-x-1 transition-transform duration-300" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-12">
      <Icon name="lucide:newspaper" class="text-4xl text-slate-400 dark:text-zinc-500 mx-auto mb-3" />
      <p class="text-slate-600 dark:text-zinc-400">Belum ada informasi tersedia</p>
    </div>
  </div>
  <Dialog v-model:visible="visible" modal :header="modalData.title" :style="{ width: '90vw', 'max-width' : '50rem' }" >
      <PostsDetail :datas="modalData" @closeDialog="visible = false" />
  </Dialog>
</template>

<script setup lang="ts">
// State untuk data, loading, dan error
const posts = ref([]) as any;
const loading = ref(true);
const error = ref(false);
const visible = ref(false);
const modalData = ref({});

// Carousel state
const currentIndex = ref(0);
const imageErrors = ref<Record<string, boolean>>({});
const visibleCards = ref(3); // Default for desktop

// Responsive visible cards
const updateVisibleCards = () => {
  if (typeof window !== 'undefined') {
    const width = window.innerWidth;
    if (width < 768) {
      visibleCards.value = 1; // Mobile
    } else if (width < 1024) {
      visibleCards.value = 2; // Tablet
    } else {
      visibleCards.value = 3; // Desktop
    }
  }
};

// Carousel navigation
const scrollNext = () => {
  if (currentIndex.value < posts.value.length - visibleCards.value) {
    currentIndex.value++;
  }
};

const scrollPrev = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--;
  }
};

// Handle image errors
const handleImageError = (postId: string) => {
  imageErrors.value[postId] = true;
};

// Fetch Data Post
const fetchData = async () => {
  try {
    const client = useSanctumClient();
    const response = await client('/api/posts');
    posts.value = response.data; // Simpan data post
  } catch (err) {
    console.error('Error fetching posts:', err);
    error.value = true;
  } finally {
    loading.value = false; // Set loading menjadi false setelah selesai
  }
};

const showModal = (post: any) => {
  modalData.value = post;
  visible.value = true;
};

function getPlainTextSnippet(html: string, maxLength: number) {
  if (!html) return '';
  const tempElement = document.createElement('div');
  tempElement.innerHTML = html;
  let plainText = tempElement.textContent || tempElement.innerText || ""; // Menangani perbedaan browser
  return plainText.substring(0, maxLength) + (plainText.length > maxLength ? '...' : '');
}

// Initialize component
onMounted(() => {
  updateVisibleCards();
  window.addEventListener('resize', updateVisibleCards);
});

onUnmounted(() => {
  if (typeof window !== 'undefined') {
    window.removeEventListener('resize', updateVisibleCards);
  }
});

// Panggil fetchData saat komponen dimuat
fetchData();
</script>

<style scoped>
/* Custom styles for better carousel experience */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Smooth scrolling for carousel */
@media (prefers-reduced-motion: no-preference) {
  .carousel-transition {
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  }
}
</style>
