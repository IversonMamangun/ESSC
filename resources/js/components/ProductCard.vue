<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { StarIcon, MapPinIcon } from 'lucide-vue-next';
import type { ProductCard } from '@/types';
import shop from '@/routes/shop';

const props = defineProps<{
  product: ProductCard;
}>();
</script>

<template>
  <Link
    :href="shop.products.show(product.slug)"
    class="group relative block h-full"
  >
    <div
      class="relative flex h-full flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white transition-all duration-300 hover:border-[#009933]/50 hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900"
    >
      <div
        class="relative w-full overflow-hidden border-b border-zinc-100 bg-zinc-50 pt-[100%] dark:border-zinc-800 dark:bg-zinc-800"
      >
        <img
          :src="product.image"
          :alt="product.name"
          class="absolute top-0 left-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
        />
      </div>

      <div class="flex grow flex-col p-4">
        <h3
          class="line-clamp-2 grow text-sm leading-tight font-bold text-zinc-900 transition-colors group-hover:text-[#009933] dark:text-zinc-100"
        >
          {{ product.name }}
        </h3>

        <div class="mt-3 flex flex-wrap items-end gap-2">
          <!-- DISPLAY LOGIC FOR PRICE OR COMING SOON -->
          <template>
            <p class="text-lg font-black tracking-tight text-[#009933]">
              ₱{{ product.price }}
            </p>

            <p
              v-if="product.compare_price"
              class="pb-1 text-xs font-semibold text-zinc-400 line-through dark:text-zinc-500"
            >
              ₱{{ product.compare_price }}
            </p>
          </template>
        </div>

        <div
          class="mt-2 flex items-center space-x-2 text-xs font-medium text-zinc-500 dark:text-zinc-400"
        >
          <div class="flex items-center text-amber-400">
            <StarIcon class="h-3.5 w-3.5 fill-current" />
            <span class="ml-1 font-bold text-zinc-700 dark:text-zinc-300"
              >5</span
            >
          </div>
          <span class="text-zinc-300 dark:text-zinc-600">|</span>
          <span>123 sold</span>
        </div>

        <div
          class="mt-3 flex items-center justify-between border-t border-zinc-100 pt-3 text-[11px] font-bold tracking-wider text-zinc-500 uppercase dark:border-zinc-800 dark:text-zinc-400"
        >
          <span class="text-zinc-600 dark:text-zinc-300">
            {{ `${product.stock} available` }}
          </span>

          <div class="ml-2 flex items-center">
            <MapPinIcon class="h-4 w-4 shrink-0" />
          </div>
        </div>
      </div>
    </div>
  </Link>
</template>
