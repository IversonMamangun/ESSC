<script setup lang="ts">
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import {
  Store,
  Star,
  MapPin,
  ShoppingCart,
  Zap,
  ChevronRight,
  ShieldCheck,
  Plus,
  Minus,
  Video,
} from 'lucide-vue-next';
import { ref, computed, onMounted } from 'vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import shop from '@/routes/shop';
import type { ProductShow } from '@/types';

// Sample product data for testing when no real data is passed
const sampleProduct = {
  id: 1,
  title: 'Wireless Headphones',
  name: 'Wireless Headphones',
  price: 99.99,
  image: '/assets/store/products/headphones.jpg',
  description: 'High-quality wireless headphones with noise cancellation',
  rating: 4.5,
  is_top_deal: true,
  stock: 100,
  sold_count: 45,
  store: {
    id: 1,
    name: 'Tech Store',
    city: 'New York',
  },
};

const props = defineProps<{
  product: ProductShow;
}>();

const page = usePage();
const user = computed(() => page.props.auth?.user || null);

const quantity = ref(1);

const increaseQuantity = () => {
  //
};

const decreaseQuantity = () => {
  //
};

const handleAddToCart = () => {
  //
};

const handleBuyNow = () => {
  //
};

onMounted(() => {
  document.documentElement.classList.remove('dark');
});
</script>

<template>
  <Head :title="`${product.name} - Store`" />

  <div class="flex min-h-screen flex-col transition-colors duration-300">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
      <Navbar />
    </div>

    <main
      class="mx-auto mb-20 w-full max-w-7xl flex-grow px-4 py-8 sm:px-6 md:py-12 lg:px-8"
    >
      <!-- Breadcrumbs -->
      <nav
        class="mb-8 flex items-center gap-2 text-sm font-medium text-zinc-500 dark:text-zinc-400"
      >
        <Link :href="" class="transition-colors hover:text-[#009933]"
          >Home</Link
        >
        <ChevronRight class="h-4 w-4" />
        <Link :href="" class="transition-colors hover:text-[#009933]"
          >Online Store</Link
        >
        <ChevronRight class="h-4 w-4" />
        <span
          class="max-w-[200px] truncate text-zinc-900 md:max-w-none dark:text-white"
          >{{ productData.title }}</span
        >
      </nav>

      <div
        class="overflow-hidden rounded-[2rem] border border-zinc-200 bg-white shadow-sm transition-colors dark:border-zinc-800 dark:bg-zinc-900"
      >
        <div class="flex flex-col lg:flex-row">
          <!-- Left Column: Media (Video & Image) -->
          <div
            class="space-y-4 border-b border-zinc-200 p-6 md:p-8 lg:w-1/2 lg:border-r lg:border-b-0 dark:border-zinc-800"
          >
            <!-- Main Image -->
            <div
              class="group relative aspect-square overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800"
            >
              <img
                :src="productData.image"
                :alt="productData.title"
                class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
              />

              <!-- Sale Badge -->
              <div
                class="absolute top-4 right-4 rounded-xl bg-red-600 px-3 py-1.5 text-sm font-black tracking-wider text-white shadow-lg"
              >
                -1% OFF
              </div>
            </div>
          </div>

          <!-- Right Column: Details -->
          <div class="flex flex-col p-6 md:p-10 lg:w-1/2">
            <div class="mb-6">
              <span
                v-if="productData.is_top_deal"
                class="mr-2 mb-4 inline-block rounded-full border border-red-200 bg-red-100 px-3 py-1 text-[10px] font-black tracking-widest text-red-600 uppercase dark:border-red-800/50 dark:bg-red-900/30 dark:text-red-400"
              >
                🔥 Top Deal
              </span>
              <span
                class="mb-4 inline-block rounded-full border border-green-200 bg-green-100 px-3 py-1 text-[10px] font-black tracking-widest text-[#009933] uppercase dark:border-green-800/50 dark:bg-green-900/30 dark:text-green-400"
              >
                Official Product
              </span>
              <h1
                class="text-2xl leading-tight font-black text-zinc-900 md:text-4xl dark:text-white"
              >
                {{ productData.title }}
              </h1>

              <div class="mt-4 flex flex-wrap items-center gap-4 text-sm">
                <div class="flex items-center gap-1 text-amber-400">
                  <Star v-for="i in 5" :key="i" class="h-4 w-4 fill-current" />
                  <span class="ml-1 font-black text-zinc-900 dark:text-white">{{
                    productData.rating || '5.0'
                  }}</span>
                </div>
                <div class="h-3 w-px bg-zinc-300 dark:bg-zinc-700"></div>
                <span class="font-bold text-zinc-500 dark:text-zinc-400"
                  >{{ productData.sold_count || '0' }} Sold</span
                >
              </div>
            </div>

            <!-- Price Block -->
            <div
              class="mb-8 rounded-3xl border border-zinc-200 bg-zinc-50 p-6 transition-colors dark:border-zinc-800 dark:bg-zinc-800/50"
            >
              <div class="flex flex-wrap items-end gap-3">
                <span
                  class="text-4xl font-black tracking-tighter text-[#009933] md:text-5xl"
                >
                  ₱{{ currentPrice }}
                </span>
              </div>

              <div
                class="mt-4 flex items-center gap-2 text-xs font-bold text-[#009933]"
              >
                <ShieldCheck class="h-4 w-4" />
                100% Authentic Guarantee
              </div>
            </div>

            <!-- Select Quantity -->
            <div class="mb-10 space-y-4">
              <label
                class="ml-1 text-xs font-bold tracking-widest text-zinc-500 uppercase dark:text-zinc-400"
                >Select Quantity</label
              >
              <div class="flex items-center gap-6">
                <div
                  class="flex items-center rounded-2xl border border-zinc-200 bg-zinc-100 p-1 shadow-inner transition-colors dark:border-zinc-700 dark:bg-zinc-800"
                >
                  <button
                    @click="decreaseQuantity"
                    class="flex h-10 w-10 items-center justify-center rounded-xl text-zinc-600 transition-all hover:bg-white active:scale-90 dark:text-zinc-300 dark:hover:bg-zinc-700"
                  >
                    <Minus class="h-5 w-5" />
                  </button>
                  <div
                    class="w-14 text-center font-black text-zinc-900 dark:text-white"
                  >
                    {{ quantity }}
                  </div>
                  <button
                    @click="increaseQuantity"
                    class="flex h-10 w-10 items-center justify-center rounded-xl text-zinc-600 transition-all hover:bg-white active:scale-90 dark:text-zinc-300 dark:hover:bg-zinc-700"
                  >
                    <Plus class="h-5 w-5" />
                  </button>
                </div>
                <span class="text-xs font-bold text-zinc-400 dark:text-zinc-500"
                  >{{ productData.stock || 100 }} pieces available</span
                >
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-auto flex flex-col gap-4 sm:flex-row">
              <button
                @click="handleAddToCart"
                :disabled="isComingSoon"
                class="flex flex-1 items-center justify-center gap-3 rounded-2xl border-2 border-[#009933] py-4 font-black tracking-widest text-[#009933] uppercase shadow-sm transition-all"
                :class="
                  isComingSoon
                    ? 'cursor-not-allowed border-zinc-300 text-zinc-400 opacity-50'
                    : 'hover:bg-green-50 active:scale-95 dark:hover:bg-green-900/10'
                "
              >
                <ShoppingCart class="h-5 w-5" /> Add To Cart
              </button>
              <button
                @click="handleBuyNow"
                :disabled="isComingSoon"
                class="flex flex-1 items-center justify-center gap-3 rounded-2xl py-4 font-black tracking-widest text-white uppercase transition-all"
                :class="
                  isComingSoon
                    ? 'cursor-not-allowed bg-zinc-300'
                    : 'bg-[#009933] shadow-lg shadow-green-900/20 hover:bg-green-700 active:scale-95'
                "
              >
                <Zap class="h-5 w-5 fill-current" /> Buy Now
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Description -->
      <div
        class="mt-8 rounded-[2rem] border border-zinc-200 bg-white p-8 shadow-sm transition-colors md:p-12 dark:border-zinc-800 dark:bg-zinc-900"
      >
        <div class="mb-8 flex items-center gap-4">
          <div class="h-8 w-1.5 rounded-full bg-[#009933]"></div>
          <h2
            class="text-2xl font-black tracking-widest text-zinc-900 uppercase dark:text-white"
          >
            Description
          </h2>
        </div>
        <div
          class="max-w-4xl text-lg leading-relaxed font-medium whitespace-pre-wrap text-zinc-600 dark:text-zinc-300"
        >
          {{ productData.description }}
        </div>
      </div>
    </main>

    <Footer />
  </div>
</template>
