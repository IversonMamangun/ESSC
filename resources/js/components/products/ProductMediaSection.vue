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

  if (!file) return;

  video.value = file;

  videoPreview.value = URL.createObjectURL(file);
};
</script>

<template>
  <div class="space-y-6 rounded-2xl border p-6">
    <div>
      <input type="file" multiple accept="image/*" @change="handleImages" />

      <div class="mt-4 flex flex-wrap gap-4">
        <img
          v-for="src in imagePreviews"
          :key="src"
          :src="src"
          class="h-24 w-24 rounded-lg border object-cover"
        />
      </div>
    </div>

    <div>
      <input type="file" accept="video/*" @change="handleVideo" />

      <video
        v-if="videoPreview"
        :src="videoPreview"
        controls
        class="mt-4 h-64 rounded-xl"
      />
    </div>
  </div>
</template>
