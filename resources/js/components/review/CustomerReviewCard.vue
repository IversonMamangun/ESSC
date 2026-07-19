<script setup lang="ts">
import { CheckCircle2Icon, StarIcon } from 'lucide-vue-next';
import { Card, CardContent } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';
import type { ProductReview } from '@/types';

const props = defineProps<{
  review: ProductReview;
}>();

function formatDate(date: string | null) {
  if (!date) return '';
  return new Date(date).toLocaleString('en-PH', {
    dateStyle: 'medium',
    timeStyle: 'short',
  });
}

function initials(name: string) {
  return name
    .split(' ')
    .map((part) => part[0])
    .filter(Boolean)
    .slice(0, 2)
    .join('')
    .toUpperCase();
}

function getStars(rating: number, max = 5) {
  return Array.from({ length: max }, (_, i) => i < rating);
}
</script>

<template>
  <Card>
    <CardContent class="px-6">
      <!-- Product / rating row -->
      <div
        class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
      >
        <!-- Reviewer -->
        <div class="flex items-center gap-2">
          <Avatar class="h-8 w-8">
            <AvatarImage
              v-if="review.user.avatar"
              :src="review.user.avatar"
              :alt="review.user.name"
            />
            <AvatarFallback>{{ initials(review.user.name) }}</AvatarFallback>
          </Avatar>
          <div>
            <p class="text-sm font-medium">{{ review.user.name }}</p>
            <p class="flex items-center gap-1 text-xs text-muted-foreground">
              <CheckCircle2Icon class="h-3 w-3" />
              Verified purchase
            </p>
          </div>
        </div>

        <div class="flex shrink-0 flex-col items-start gap-1 sm:items-end">
          <div
            class="flex items-center gap-0.5"
            :aria-label="`${review.rating} out of 5 stars`"
            role="img"
          >
            <StarIcon
              v-for="(filled, i) in getStars(review.rating)"
              :key="i"
              :class="[
                'h-4 w-4',
                filled
                  ? 'fill-amber-400 text-amber-400'
                  : 'fill-none text-muted-foreground/30',
              ]"
            />
          </div>
          <span class="text-xs text-muted-foreground">{{
            formatDate(review.created_at)
          }}</span>
        </div>
      </div>

      <Separator class="my-4" />

      <!-- Comment -->
      <p v-if="review.comment" class="text-sm leading-relaxed">
        {{ review.comment }}
      </p>
      <p v-else class="text-sm text-muted-foreground italic">
        No written feedback provided.
      </p>

      <!-- Media -->
      <div
        v-if="review.images.length || review.video"
        class="mt-4 flex flex-wrap gap-2"
      >
        <a
          v-for="image in review.images"
          :key="image.id"
          :href="image.url"
          target="_blank"
          rel="noopener noreferrer"
          class="block overflow-hidden rounded-md border"
        >
          <img
            :src="image.url"
            class="h-24 w-24 cursor-zoom-in object-cover transition hover:scale-105 hover:opacity-90"
          />
        </a>
        <video
          v-if="review.video"
          :src="review.video"
          controls
          preload="metadata"
          class="h-24 rounded-md border"
        />
      </div>
    </CardContent>
  </Card>
</template>
