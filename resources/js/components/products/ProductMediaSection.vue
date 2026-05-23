<script setup lang="ts">
import { ref, computed } from 'vue';
import { XIcon, VideoIcon } from 'lucide-vue-next';
import type { ExistingProductImage } from '@/types';

// edit passing props
const props = withDefaults(
  defineProps<{
    existingImages?: ExistingProductImage[];
    existingVideo?: string | null;
    errors?: Record<string, string>;
  }>(),
  {
    existingImages: () => [],
    existingVideo: null,
  },
);

// new uploads
const images = defineModel<File[]>('images', { required: true });
const video = defineModel<File | null>('video', { required: true });

// deletion tracking
const deletedImageIds = defineModel<number[]>('deletedImageIds', {
  default: () => [],
});
const deleteVideo = defineModel<boolean>('deleteVideo', { default: false });

// local previews
const newImagePreviews = ref<string[]>([]);
const newVideoPreview = ref<string | null>(null);

// Existing images not yet marked for deletion
const visibleExistingImages = computed(() =>
  props.existingImages.filter((img) => !deletedImageIds.value.includes(img.id)),
);

const markImageDeleted = (id: number) => {
  if (!deletedImageIds.value.includes(id)) {
    deletedImageIds.value.push(id);
  }
};

const handleImages = (e: Event) => {
  const files = Array.from((e.target as HTMLInputElement).files ?? []);
  images.value.push(...files);
  newImagePreviews.value.push(...files.map((f) => URL.createObjectURL(f)));
};

const removeNewImage = (index: number) => {
  images.value.splice(index, 1);
  URL.revokeObjectURL(newImagePreviews.value[index]);
  newImagePreviews.value.splice(index, 1);
};

const handleVideo = (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0];
  if (!file) return;
  video.value = file;
  newVideoPreview.value = URL.createObjectURL(file);
  deleteVideo.value = false;
};

const handleDeleteVideo = () => {
  deleteVideo.value = true;
  video.value = null;
  newVideoPreview.value = null;
};

const handleReplaceVideo = () => {
  deleteVideo.value = false;
};

// What to show in the video slot
const showExistingVideo = computed(
  () => props.existingVideo && !deleteVideo.value && !newVideoPreview.value,
);
</script>

<template>
  <div
    class="space-y-6 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-900"
  >
    <!-- Images -->
    <div>
      <label
        class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300"
      >
        Product Images
      </label>

      <div class="flex flex-wrap gap-3">
        <!-- Existing images (edit mode) -->
        <div
          v-for="img in visibleExistingImages"
          :key="`existing-${img.id}`"
          class="group relative h-20 w-20 rounded border border-zinc-200 dark:border-zinc-700"
        >
          <img :src="img.url" class="h-full w-full rounded object-cover" />
          <button
            type="button"
            class="absolute -top-1.5 -right-1.5 hidden rounded-full bg-red-500 p-0.5 text-white group-hover:flex"
            @click="markImageDeleted(img.id)"
          >
            <XIcon class="h-3 w-3" />
          </button>
        </div>

        <!-- New image previews -->
        <div
          v-for="(src, i) in newImagePreviews"
          :key="`new-${src}`"
          class="group relative h-20 w-20 rounded border border-zinc-200 dark:border-zinc-700"
        >
          <img :src="src" class="h-full w-full rounded object-cover" />
          <button
            type="button"
            class="absolute -top-1.5 -right-1.5 hidden rounded-full bg-red-500 p-0.5 text-white group-hover:flex"
            @click="removeNewImage(i)"
          >
            <XIcon class="h-3 w-3" />
          </button>
        </div>

        <!-- Upload button -->
        <label
          class="flex h-20 w-20 cursor-pointer flex-col items-center justify-center rounded border border-dashed border-zinc-300 bg-zinc-50 text-zinc-500 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-700"
        >
          <svg
            class="mb-1 h-5 w-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 6v6m0 0v6m0-6h6m-6 0H6"
            />
          </svg>
          <span class="text-[10px] font-medium">Add Photo</span>
          <input
            type="file"
            multiple
            accept="image/*"
            class="hidden"
            @change="handleImages"
          />
        </label>
      </div>
    </div>

    <!-- Video -->
    <div>
      <label
        class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300"
      >
        Product Video
      </label>

      <div class="flex flex-wrap gap-3">
        <!-- Existing video preview (edit mode) -->
        <div
          v-if="showExistingVideo"
          class="relative h-20 w-20 rounded border border-zinc-200 bg-black dark:border-zinc-700"
        >
          <video
            :src="existingVideo!"
            controls
            class="h-full w-full rounded object-cover"
          />
          <div class="absolute -top-1.5 -right-1.5 flex gap-1">
            <label
              class="cursor-pointer rounded-full bg-zinc-700 p-0.5 text-white"
              title="Replace video"
              @click="handleReplaceVideo"
            >
              <VideoIcon class="h-3 w-3" />
              <input
                type="file"
                accept="video/*"
                class="hidden"
                @change="handleVideo"
              />
            </label>
            <button
              type="button"
              class="rounded-full bg-red-500 p-0.5 text-white"
              title="Remove video"
              @click="handleDeleteVideo"
            >
              <XIcon class="h-3 w-3" />
            </button>
          </div>
        </div>

        <!-- New video preview -->
        <div
          v-else-if="newVideoPreview"
          class="relative h-20 w-20 rounded border border-zinc-200 bg-black dark:border-zinc-700"
        >
          <video
            :src="newVideoPreview"
            controls
            class="h-full w-full rounded object-cover"
          />
          <button
            type="button"
            class="absolute -top-1.5 -right-1.5 rounded-full bg-red-500 p-0.5 text-white"
            @click="
              () => {
                video = null;
                newVideoPreview = null;
              }
            "
          >
            <XIcon class="h-3 w-3" />
          </button>
        </div>

        <!-- Upload button (shown when no video exists / video was deleted) -->
        <label
          v-else
          class="flex h-20 w-20 cursor-pointer flex-col items-center justify-center rounded border border-dashed border-zinc-300 bg-zinc-50 text-zinc-500 hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-700"
        >
          <svg
            class="mb-1 h-5 w-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"
            />
          </svg>
          <span class="text-[10px] font-medium">Add Video</span>
          <input
            type="file"
            accept="video/*"
            class="hidden"
            @change="handleVideo"
          />
        </label>
      </div>
    </div>
  </div>
</template>
