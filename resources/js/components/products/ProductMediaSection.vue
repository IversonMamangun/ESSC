<script setup lang="ts">
import { ref } from 'vue';

const images = defineModel<File[]>('images', {
  required: true,
});

const video = defineModel<File | null>('video', {
  required: true,
});

const imagePreviews = ref<string[]>([]);
const videoPreview = ref<string | null>(null);

const handleImages = (e: Event) => {
  const target = e.target as HTMLInputElement;

  const files = Array.from(target.files ?? []);

  images.value.push(...files);

  imagePreviews.value.push(...files.map((f) => URL.createObjectURL(f)));
};

const handleVideo = (e: Event) => {
  const target = e.target as HTMLInputElement;

  const file = target.files?.[0];

  if (!file) {
    return;
  }
  
  video.value = file;

  videoPreview.value = URL.createObjectURL(file);
};
</script>

<template>
  <div class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-900">
    
    <!-- Images Section -->
    <div>
      <div class="mb-2 flex items-center justify-between">
        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Product Images</label>
        <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ images.length }} / 5</span>
      </div>

      <div class="flex flex-wrap gap-3">
        <!-- Previews -->
        <div 
          v-for="src in imagePreviews" 
          :key="src" 
          class="relative h-20 w-20 rounded border border-zinc-200 dark:border-zinc-700"
        >
          <img :src="src" class="h-full w-full rounded object-cover" />
        </div>

        <label
          class="flex h-20 w-20 cursor-pointer flex-col items-center justify-center rounded border border-dashed border-zinc-300 bg-zinc-50 text-zinc-500 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-700"
        >
          <svg class="mb-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          <span class="text-[10px] font-medium">Add Photo</span>
          <input type="file" multiple accept="image/*" class="hidden" @change="handleImages" />
        </label>
      </div>
    </div>

    <!-- Video Section -->
    <div>
      <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Product Video</label>

      <div class="flex flex-wrap gap-3">
        <!-- Preview -->
        <div v-if="videoPreview" class="h-20 w-20 rounded border border-zinc-200 bg-black dark:border-zinc-700">
          <video :src="videoPreview" controls class="h-full w-full rounded object-cover"></video>
        </div>

        <label
          v-else
          class="flex h-20 w-20 cursor-pointer flex-col items-center justify-center rounded border border-dashed border-zinc-300 bg-zinc-50 text-zinc-500 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-700"
        >
          <svg class="mb-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
          </svg>
          <span class="text-[10px] font-medium">Add Video</span>
          <input type="file" accept="video/*" class="hidden" @change="handleVideo" />
        </label>
      </div>
    </div>

  </div>
</template>