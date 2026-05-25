<script setup lang="ts">
import { Head, router, usePage, Link } from '@inertiajs/vue3';
import { ref, onMounted, nextTick, computed } from 'vue';
import ProductCard from '@/components/ProductCard.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Pagination from '@/components/Pagination.vue';

// Sample product data for demonstration
const sampleTopDeals = [
  {
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
  },
  {
    id: 2,
    title: 'Smart Watch',
    name: 'Smart Watch',
    price: 199.99,
    image: '/assets/store/products/smartwatch.jpg',
    description: 'Fitness tracking and notifications on your wrist',
    rating: 4.8,
    is_top_deal: true,
    stock: 100,
    sold_count: 32,
  },
  {
    id: 3,
    title: 'Laptop Backpack',
    name: 'Laptop Backpack',
    price: 49.99,
    image: '/assets/store/products/backpack.jpg',
    description: 'Water-resistant backpack with laptop compartment',
    rating: 4.3,
    is_top_deal: true,
    stock: 100,
    sold_count: 78,
  },
  {
    id: 4,
    title: 'USB-C Hub',
    name: 'USB-C Hub',
    price: 39.99,
    image: '/assets/store/products/usbhub.jpg',
    description: '7-in-1 USB-C hub with HDMI and USB 3.0',
    rating: 4.6,
    is_top_deal: true,
    stock: 100,
    sold_count: 23,
  },
  {
    id: 5,
    title: 'Wireless Mouse',
    name: 'Wireless Mouse',
    price: 29.99,
    image: '/assets/store/products/mouse.jpg',
    description: 'Ergonomic wireless mouse with long battery life',
    rating: 4.4,
    is_top_deal: true,
    stock: 100,
    sold_count: 156,
  },
  {
    id: 6,
    title: 'Mechanical Keyboard',
    name: 'Mechanical Keyboard',
    price: 89.99,
    image: '/assets/store/products/keyboard.jpg',
    description: 'RGB mechanical keyboard with blue switches',
    rating: 4.7,
    is_top_deal: true,
    stock: 100,
    sold_count: 67,
  },
  {
    id: 7,
    title: 'Monitor Stand',
    name: 'Monitor Stand',
    price: 34.99,
    image: '/assets/store/products/stand.jpg',
    description: 'Adjustable monitor stand with cable management',
    rating: 4.2,
    is_top_deal: true,
    stock: 100,
    sold_count: 89,
  },
  {
    id: 8,
    title: 'Webcam Cover',
    name: 'Webcam Cover',
    price: 9.99,
    image: '/assets/store/products/webcam.jpg',
    description: 'Privacy webcam cover for laptops',
    rating: 4.9,
    is_top_deal: true,
    stock: 100,
    sold_count: 234,
  },
];

const sampleDiscoverItems = {
  data: [
    {
      id: 101,
      title: 'Smartphone Stand',
      name: 'Smartphone Stand',
      price: 19.99,
      image: '/assets/store/products/stand.jpg',
      description: 'Adjustable aluminum smartphone stand',
      rating: 4.5,
      stock: 100,
      sold_count: 45,
    },
    {
      id: 102,
      title: 'Desk Lamp',
      name: 'Desk Lamp',
      price: 45.99,
      image: '/assets/store/products/lamp.jpg',
      description: 'LED desk lamp with wireless charging',
      rating: 4.7,
      stock: 100,
      sold_count: 32,
    },
    {
      id: 103,
      title: 'Cable Organizer',
      name: 'Cable Organizer',
      price: 15.99,
      image: '/assets/store/products/cables.jpg',
      description: 'Keep your cables neat and organized',
      rating: 4.3,
      stock: 100,
      sold_count: 78,
    },
    {
      id: 104,
      title: 'Laptop Sleeve',
      name: 'Laptop Sleeve',
      price: 24.99,
      image: '/assets/store/products/sleeve.jpg',
      description: 'Protective neoprene laptop sleeve',
      rating: 4.6,
      stock: 100,
      sold_count: 23,
    },
    {
      id: 105,
      title: 'Screen Cleaner Kit',
      name: 'Screen Cleaner Kit',
      price: 12.99,
      image: '/assets/store/products/cleaner.jpg',
      description: 'Complete screen cleaning solution',
      rating: 4.8,
      stock: 100,
      sold_count: 156,
    },
  ],
  links: [
    { url: null, label: 'Previous', active: false },
    { url: '/store?page=1', label: '1', active: true },
    { url: '/store?page=2', label: '2', active: false },
    { url: null, label: 'Next', active: false },
  ],
};

// Make props optional and use sample data as fallback
const props = defineProps<{
  topDeals?: Array<any>;
  discoverItems?: {
    data: any[];
    links: any[];
  };
}>();

// Use provided props or fallback to sample data
const topDealsData = computed(() => {
  return props.topDeals && props.topDeals.length > 0
    ? props.topDeals
    : sampleTopDeals;
});

const discoverItemsData = computed(() => {
  return props.discoverItems &&
    props.discoverItems.data &&
    props.discoverItems.data.length > 0
    ? props.discoverItems
    : sampleDiscoverItems;
});

const showAllDeals = ref(false);

// Computed property to show limited products when not showing all
const displayedTopDeals = computed(() => {
  const deals = topDealsData.value;

  if (showAllDeals.value) {
    return deals;
  }
  // Show only first 4 products initially

  return deals.slice(0, 4);
});

const toggleDeals = () => {
  showAllDeals.value = !showAllDeals.value;
};

// Function to navigate to product detail page
const goToProduct = (productId: number) => {
  router.visit(`/product/${productId}`);
};

onMounted(() => {
  document.documentElement.classList.remove('dark');
});
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
          class="group relative flex w-full max-w-7xl items-center justify-center overflow-hidden rounded-3xl border border-zinc-200 shadow-sm dark:border-zinc-800"
        >
          <img
            src="/assets/store/online-store.jpg"
            alt="Store Background"
            class="h-40 w-full object-cover brightness-75 transition-all duration-500 group-hover:brightness-90 md:h-52 dark:brightness-50 dark:group-hover:brightness-75"
          />

          <div
            class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center"
          >
            <h1
              class="absolute mb-4 text-5xl font-black tracking-normal text-[#009933] drop-shadow-2xl transition-all [text-shadow:2px_2px_0_#fff,-1px_-1px_0_#fff,1px_-1px_0_#fff,-1px_1px_0_#fff,1px_1px_0_#fff] md:text-7xl dark:[text-shadow:2px_2px_0_#18181b,-1px_-1px_0_#18181b,1px_-1px_0_#18181b,-1px_1px_0_#18181b,1px_1px_0_#18181b]"
            >
              ONLINE STORE
            </h1>
          </div>
        </div>
      </section>

      <!-- Top Deals Section -->
      <section class="mt-8 flex w-full justify-center px-4">
        <div
          class="w-full max-w-7xl rounded-3xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm transition-colors md:p-8 dark:border-zinc-800 dark:bg-zinc-900"
        >
          <div
            class="mb-6 flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-800"
          >
            <h2 class="text-2xl font-black text-zinc-900 dark:text-white">
              Top Deals
            </h2>
            <button
              @click="toggleDeals"
              class="rounded-lg bg-green-50 px-4 py-2 text-sm font-bold text-[#009933] transition-colors hover:underline focus:outline-none dark:bg-green-900/20"
            >
              {{ showAllDeals ? 'Show less' : 'See all' }}
            </button>
          </div>

          <!-- Products grid - Clickable -->
          <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4">
            <div
              v-for="product in displayedTopDeals"
              :key="'topdeal-' + product.id"
              @click="goToProduct(product.id)"
              class="cursor-pointer transition-transform duration-200 hover:scale-105"
            >
              <ProductCard :product="product" />
            </div>
          </div>
        </div>
      </section>

      <!-- Discover Section -->
      <section
        id="discover-section"
        class="mt-8 flex w-full justify-center px-4"
      >
        <div
          class="w-full max-w-7xl rounded-3xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm transition-colors md:p-8 dark:border-zinc-800 dark:bg-zinc-900"
        >
          <div class="mb-8 border-b border-zinc-200 pb-4 dark:border-zinc-800">
            <h2 class="text-2xl font-black text-zinc-900 dark:text-white">
              Discover
            </h2>
            <p class="mt-1 text-zinc-500 dark:text-zinc-400">
              Explore our latest arrivals and catalog
            </p>
          </div>

          <!-- Products grid - Clickable -->
          <div
            class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
          >
            <div
              v-for="product in discoverItemsData.data"
              :key="'discover-' + product.id"
              @click="goToProduct(product.id)"
              class="cursor-pointer transition-transform duration-200 hover:scale-105"
            >
              <ProductCard :product="product" />
            </div>
          </div>

          <Pagination :links="discoverItemsData.links" />
        </div>
      </section>
    </main>

    <Footer />
  </div>
</template>
