<script setup lang="ts">
import { computed } from 'vue';
import { Star, StarHalf } from 'lucide-vue-next';

const props = withDefaults(
  defineProps<{
    rating: number;      // The raw average rating score (e.g., 3.74)
    reviewCount?: number; // Optional review count tracking tag
    iconSize?: string;   // Optional custom utility sizing string
  }>(),
  {
    reviewCount: 0,
    iconSize: 'h-4 w-4',
  }
);

// Compute star array categorization patterns
const starState = computed(() => {
  // Round to the nearest half star
  const rounded = Math.round(props.rating * 2) / 2;
  const fullStars = Math.floor(rounded);
  const hasHalfStar = rounded % 1 !== 0;
  const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

  return { fullStars, hasHalfStar, emptyStars };
});
</script>

<template>
  <div class="flex items-center gap-1.5">
    <div class="flex items-center gap-0.5 text-amber-400">
      
      <Star 
        v-for="i in starState.fullStars" 
        :key="`full-${i}`" 
        :class="[iconSize, 'fill-amber-400']" 
      />

      <StarHalf 
        v-if="starState.hasHalfStar" 
        :class="[iconSize, 'fill-amber-400']" 
      />

      <Star 
        v-for="i in starState.emptyStars" 
        :key="`empty-${i}`" 
        :class="[iconSize, 'text-zinc-300 dark:text-zinc-700']" 
      />
    </div>

    <div class="ml-1 flex items-center gap-1 text-xs font-bold text-zinc-500 dark:text-zinc-400">
      <span class="text-zinc-800 dark:text-zinc-200">{{ rating.toFixed(1) }}</span>
      <span v-if="reviewCount > 0">({{ reviewCount }} reviews)</span>
    </div>
  </div>
</template>