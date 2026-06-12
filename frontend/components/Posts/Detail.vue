<template>
  <div class="space-y-6">
    <!-- Featured Image Container -->
    <div class="relative overflow-hidden rounded-2xl">
      <!-- Valid Image -->
      <img 
        v-if="datas.featured_image && !imageError"
        :src="datas.featured_image" 
        :alt="datas.title"
        class="w-full h-64 sm:h-80 object-cover"
        @error="handleImageError"
      />
      
      <!-- Fallback Gradient Background with Icon -->
      <div 
        v-else
        class="w-full h-64 sm:h-80 bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-600 flex items-center justify-center relative overflow-hidden"
      >
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20">
          <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-transparent via-white/10 to-transparent"></div>
        </div>
        
        <!-- Main Icon -->
        <div class="relative z-10 text-center">
          <Icon name="lucide:image" class="text-6xl text-white/90 mb-3" />
          <p class="text-white/80 text-sm font-medium">Image not available</p>
        </div>
        
        <!-- Decorative Elements -->
        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full"></div>
        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/10 rounded-full"></div>
      </div>
      
      <!-- Category Badge -->
      <div v-if="datas.category" class="absolute top-4 right-4">
        <span class="bg-black/30 backdrop-blur-sm text-white text-sm px-3 py-1.5 rounded-full font-medium">
          {{ datas.category.name }}
        </span>
      </div>
    </div>

    <!-- Content Container -->
    <div class="bg-white/30 dark:bg-zinc-800/30 backdrop-blur-sm rounded-2xl p-6 border border-slate-200/30 dark:border-zinc-600/30">
      <!-- Article Header -->
      <div class="mb-6 pb-4 border-b border-slate-200/50 dark:border-zinc-700/50">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 dark:text-zinc-200 mb-2 leading-tight">
          {{ datas.title }}
        </h1>
        
        <!-- Meta Information -->
        <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-zinc-400">
          <div class="flex items-center gap-2">
            <Icon name="lucide:calendar" class="text-xs" />
            <span>{{ formatDate(datas.created_at) }}</span>
          </div>
          <div v-if="datas.author" class="flex items-center gap-2">
            <Icon name="lucide:user" class="text-xs" />
            <span>{{ datas.author }}</span>
          </div>
        </div>
      </div>

      <!-- Article Content -->
      <div 
        v-html="datas.content" 
        class="prose prose-slate dark:prose-invert max-w-none prose-headings:text-slate-800 dark:prose-headings:text-zinc-200 prose-p:text-slate-600 dark:prose-p:text-zinc-300 prose-a:text-emerald-600 dark:prose-a:text-emerald-400 prose-strong:text-slate-800 dark:prose-strong:text-zinc-200"
      ></div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between">
      <button 
        @click="$emit('closeDialog')"
        class="flex items-center gap-2 px-4 py-2 bg-white/60 dark:bg-zinc-700/60 backdrop-blur-sm rounded-lg border border-slate-200/30 dark:border-zinc-600/30 hover:bg-white/80 dark:hover:bg-zinc-700/80 transition-all duration-300 text-slate-600 dark:text-zinc-400"
      >
        <Icon name="lucide:arrow-left" class="text-sm" />
        <span class="text-sm font-medium">Kembali</span>
      </button>
      
      <div class="flex items-center gap-2">
        <button class="p-2 bg-white/60 dark:bg-zinc-700/60 backdrop-blur-sm rounded-lg border border-slate-200/30 dark:border-zinc-600/30 hover:bg-white/80 dark:hover:bg-zinc-700/80 transition-all duration-300 text-slate-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400">
          <Icon name="lucide:share-2" class="text-sm" />
        </button>
        <button class="p-2 bg-white/60 dark:bg-zinc-700/60 backdrop-blur-sm rounded-lg border border-slate-200/30 dark:border-zinc-600/30 hover:bg-white/80 dark:hover:bg-zinc-700/80 transition-all duration-300 text-slate-600 dark:text-zinc-400 hover:text-red-500 dark:hover:text-red-400">
          <Icon name="lucide:heart" class="text-sm" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Props
const { datas } = defineProps<{
  datas: {
    id: string;
    title: string;
    content: string;
    featured_image?: string;
    category?: {
      name: string;
    };
    author?: string;
    created_at: string;
  }
}>();

// Emits
defineEmits<{
  closeDialog: [];
}>();

// State
const imageError = ref(false);

// Methods
const handleImageError = () => {
  imageError.value = true;
};

const formatDate = (dateString: string) => {
  if (!dateString) return '';
  
  try {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  } catch (error) {
    return dateString;
  }
};
</script>

<style scoped>
/* Enhanced prose styling for article content */
:deep(.prose) {
  font-size: 1rem;
  line-height: 1.75;
}

:deep(.prose h1) {
  font-size: 1.875rem;
  font-weight: 700;
  margin-top: 2rem;
  margin-bottom: 1rem;
  line-height: 1.2;
}

:deep(.prose h2) {
  font-size: 1.5rem;
  font-weight: 600;
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
  line-height: 1.3;
}

:deep(.prose h3) {
  font-size: 1.25rem;
  font-weight: 600;
  margin-top: 1.25rem;
  margin-bottom: 0.5rem;
  line-height: 1.4;
}

:deep(.prose p) {
  margin-bottom: 1.25rem;
  text-align: justify;
}

:deep(.prose img) {
  border-radius: 0.75rem;
  margin: 1.5rem 0;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

:deep(.prose ul),
:deep(.prose ol) {
  margin: 1.25rem 0;
  padding-left: 1.5rem;
}

:deep(.prose li) {
  margin-bottom: 0.5rem;
}

:deep(.prose a) {
  font-weight: 500;
  text-decoration: none;
  border-bottom: 1px solid transparent;
  transition: all 0.2s ease;
}

:deep(.prose a:hover) {
  border-bottom-color: currentColor;
}

:deep(.prose blockquote) {
  border-left: 4px solid #10b981;
  padding-left: 1rem;
  margin: 1.5rem 0;
  font-style: italic;
  background: rgba(16, 185, 129, 0.05);
  padding: 1rem;
  border-radius: 0.5rem;
}

:deep(.prose code) {
  background: rgba(100, 116, 139, 0.1);
  padding: 0.125rem 0.25rem;
  border-radius: 0.25rem;
  font-size: 0.875em;
  font-weight: 500;
}

:deep(.prose pre) {
  background: rgba(15, 23, 42, 0.95);
  border-radius: 0.75rem;
  padding: 1.25rem;
  margin: 1.5rem 0;
  overflow-x: auto;
}

:deep(.prose pre code) {
  background: transparent;
  padding: 0;
  color: #e2e8f0;
}

:deep(.prose table) {
  width: 100%;
  border-collapse: collapse;
  margin: 1.5rem 0;
  border-radius: 0.5rem;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

:deep(.prose th),
:deep(.prose td) {
  padding: 0.75rem;
  border-bottom: 1px solid rgba(148, 163, 184, 0.2);
}

:deep(.prose th) {
  background: rgba(148, 163, 184, 0.1);
  font-weight: 600;
}

/* Dark mode specific adjustments */
@media (prefers-color-scheme: dark) {
  :deep(.prose blockquote) {
    background: rgba(16, 185, 129, 0.1);
  }
  
  :deep(.prose code) {
    background: rgba(148, 163, 184, 0.2);
  }
  
  :deep(.prose th) {
    background: rgba(63, 63, 70, 0.5);
  }
}

/* Responsive image handling */
@media (max-width: 640px) {
  :deep(.prose img) {
    margin: 1rem 0;
  }
}
</style>