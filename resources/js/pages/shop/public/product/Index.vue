<script setup lang="ts">
import { Head, router, usePage, Link } from '@inertiajs/vue3';
import { ref, onMounted, nextTick, computed } from 'vue';
import { SearchIcon, StoreIcon } from 'lucide-vue-next';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Pagination from '@/components/Pagination.vue';
import ProductCard from '@/components/ProductCard.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import shop from '@/routes/shop';
import type { PaginatedProducts, ProductFilters } from '@/types';

const props = defineProps<{
  products: PaginatedProducts;
  filters: ProductFilters;
}>();

const showProduct = (slug: string) => {
  router.visit(shop.products.show(slug).url + '?ref=catalog');
};

const pageTitle = computed(() => {
  return props.filters.type === 'top-deals' ? 'Top Deals' : 'Discover';
});
const pageDescription = computed(() => {
  return props.filters.type === 'top-deals'
    ? 'Grab the best discounts and limited-time offers on our hottest products.'
    : 'Explore our latest arrivals, trending items, and full catalog.';
});

const breadcrumbs = computed(() => [
  { title: 'Home', href: shop.home() },
  { title: pageTitle.value, href: '#' },
]);

// search logic
const search = ref(props.filters.search ?? '');
let debounceTimer: ReturnType<typeof setTimeout> | undefined;
const runSearch = (value: string) => {
  router.get(
    shop.products.index().url,
    {
      type: props.filters.type,
      search: value || undefined,
    },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
      only: ['products', 'filters'],
    },
  );
};
const onSearchInput = (event: Event) => {
  const value = (event.target as HTMLInputElement).value;
  search.value = value;

  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => runSearch(value), 350);
};
</script>

<template>
  <Head title="Store" />

  <div class="flex min-h-screen flex-col transition-colors duration-300">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
      <Navbar />
    </div>

    <main class="flex-grow pb-20">
      <section class="flex w-full justify-center p-4">
        <div
          class="group relative flex w-full max-w-7xl items-center justify-center overflow-hidden rounded-3xl border border-zinc-200 shadow-sm"
        >
          <img
            src="/assets/store/online-store.jpg"
            alt="Store Background"
            class="h-40 w-full object-cover brightness-75 transition-all duration-500 group-hover:brightness-90 md:h-52"
          />

          <div
            class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center"
          >
            <h1
              class="absolute mb-4 text-5xl font-black tracking-normal text-[#009933] drop-shadow-2xl transition-all [text-shadow:2px_2px_0_#fff,-1px_-1px_0_#fff,1px_-1px_0_#fff,-1px_1px_0_#fff,1px_1px_0_#fff] md:text-7xl"
            >
              ONLINE STORE
            </h1>
          </div>
        </div>
      </section>

      <div class="mx-auto mt-4 max-w-7xl px-4">
        <Breadcrumbs :breadcrumbs="breadcrumbs" />
      </div>

      <div class="mx-auto mt-8 max-w-7xl px-4">
        <div class="relative">
          <SearchIcon class="absolute top-3.5 left-4 h-5 w-5 text-zinc-400" />
          <input
            type="text"
            v-model="search"
            @input="onSearchInput"
            :placeholder="`Search Product in ${pageTitle}`"
            class="w-full rounded-xl border border-zinc-200 bg-white py-3.5 pr-4 pl-12 text-sm text-zinc-900 shadow-sm transition-all outline-none focus:border-[#009933] focus:ring-2 focus:ring-[#009933]/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
          />
        </div>
      </div>

      <section class="mt-4 flex w-full justify-center px-4">
        <div
          class="w-full max-w-7xl rounded-3xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm transition-colors md:p-8 dark:border-zinc-800 dark:bg-zinc-900"
        >
          <div class="mb-8 border-b border-zinc-200 pb-4 dark:border-zinc-800">
            <h2 class="text-2xl font-black text-zinc-900 dark:text-white">
              {{ pageTitle }}
            </h2>
            <p class="mt-1 text-zinc-500 dark:text-zinc-400">
              {{ pageDescription }}
            </p>
          </div>

          <!-- Products grid - Clickable -->
          <div
            v-if="products.data.length > 0"
            class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
          >
            <div
              v-for="product in products.data"
              :key="'discover-' + product.slug"
              @click="showProduct(product.slug)"
              class="cursor-pointer"
            >
              <ProductCard :product="product" />
            </div>
          </div>

          <div v-else>
            <div
              class="rounded-3xl border border-zinc-200 bg-zinc-50 py-10 text-center text-zinc-500 shadow-sm transition-colors dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400"
            >
              <StoreIcon
                class="mx-auto mb-4 h-10 w-10 text-zinc-300 dark:text-zinc-600"
              />
              <h2 class="font-black text-zinc-900 dark:text-white">
                No products found
              </h2>
              <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Try searching for something else.
              </p>
            </div>
          </div>

          <div class="mt-12 flex justify-center">
            <Pagination :links="props.products.meta.links" />
          </div>
        </div>
      </section>
    </main>

    <Footer />
  </div>
</template>
