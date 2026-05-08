<script setup lang="ts">
import { Head, router, usePage, Link } from '@inertiajs/vue3';
import { ref, onMounted, nextTick, computed } from 'vue'; 
import ProductCard from '@/components/ProductCard.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Pagination from '@/components/Pagination.vue'; 

const props = defineProps<{
    topDeals: Array<any>;
    discoverItems: any; 
}>();

const showAllDeals = ref(false);

const initCarousel = () => {
    const $ = (window as any).$;
    
    if ($('.top-deals-carousel').length) {
        $('.top-deals-carousel').owlCarousel({
            loop: false,
            margin: 16,
            nav: false,
            dots: false,
            responsive: {
                0: { items: 2 },
                768: { items: 3 },
                1024: { items: 4 },
            },
        });
    }
};

const destroyCarousel = () => {
    const $ = (window as any).$;
    $('.top-deals-carousel').trigger('destroy.owl.carousel');
};

const toggleDeals = async () => {
    if (!showAllDeals.value) {
        destroyCarousel();
        showAllDeals.value = true;
    } else {
        showAllDeals.value = false;
        await nextTick();
        initCarousel();
    }
};

onMounted(() => {
    initCarousel();
});
</script>

<template>
    <Head title="Store" />
    
    <!-- REMOVED bg-zinc-50 dark:bg-zinc-950 so it uses your default app background -->
    <div class="min-h-screen transition-colors duration-300 flex flex-col">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8">
            <Navbar />
        </div>
        
        <main class="flex-grow pb-20">
            <section class="flex w-full justify-center p-4">
                <div class="relative flex w-full max-w-7xl items-center justify-center group rounded-3xl overflow-hidden border border-zinc-200 dark:border-zinc-800 shadow-sm">
                    <img src="/assets/store/online-store.jpg" alt="Store Background" class="h-40 md:h-52 w-full object-cover brightness-75 dark:brightness-50 transition-all duration-500 group-hover:brightness-90 dark:group-hover:brightness-75" />
                    
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-4">
                        <h1 class="absolute text-5xl md:text-7xl font-black tracking-normal text-[#009933] [text-shadow:2px_2px_0_#fff,-1px_-1px_0_#fff,1px_-1px_0_#fff,-1px_1px_0_#fff,1px_1px_0_#fff] dark:[text-shadow:2px_2px_0_#18181b,-1px_-1px_0_#18181b,1px_-1px_0_#18181b,-1px_1px_0_#18181b,1px_1px_0_#18181b] drop-shadow-2xl mb-4 transition-all">
                            ONLINE STORE
                        </h1>
                    </div>
                </div>
            </section>

            <section class="flex w-full justify-center px-4 mt-8">
                <!-- Switched the card backgrounds so they stand out against your default background -->
                <div class="w-full max-w-7xl bg-zinc-50 dark:bg-zinc-900 rounded-3xl p-6 md:p-8 shadow-sm border border-zinc-200 dark:border-zinc-800 transition-colors">
                    <div class="flex items-center justify-between mb-6 border-b border-zinc-200 dark:border-zinc-800 pb-4">
                        <h2 class="text-2xl font-black text-zinc-900 dark:text-white">Top Deals</h2>
                        <button @click="toggleDeals" class="text-[#009933] font-bold hover:underline text-sm focus:outline-none bg-green-50 dark:bg-green-900/20 px-4 py-2 rounded-lg transition-colors">
                            {{ showAllDeals ? 'Hide' : 'See all' }}
                        </button>
                    </div>
                    
                    <div :class="{ 'hidden': showAllDeals }">
                        <div class="owl-carousel top-deals-carousel owl-theme">
                            <div v-for="product in props.topDeals" :key="'carousel-' + product.id">
                                <ProductCard :product="product" />
                            </div>
                        </div>
                    </div>

                    <div v-if="showAllDeals" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 pb-4">
                        <div v-for="product in props.topDeals" :key="'grid-' + product.id">
                            <ProductCard :product="product" />
                        </div>
                    </div>
                </div>
            </section>
            
            <section id="discover-section" class="flex w-full justify-center px-4 mt-8">
                <!-- Switched the card backgrounds so they stand out against your default background -->
                <div class="w-full max-w-7xl bg-zinc-50 dark:bg-zinc-900 rounded-3xl p-6 md:p-8 shadow-sm border border-zinc-200 dark:border-zinc-800 transition-colors">
                    <div class="mb-8 border-b border-zinc-200 dark:border-zinc-800 pb-4">
                        <h2 class="text-2xl font-black text-zinc-900 dark:text-white">Discover</h2>
                        <p class="text-zinc-500 dark:text-zinc-400 mt-1">Explore our latest arrivals and catalog</p>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        <div v-for="product in discoverItems.data" :key="'discover-' + product.id">
                            <ProductCard :product="product" />
                        </div>
                    </div>
                    
                    <Pagination :links="discoverItems.links" />

                </div>
            </section>
        </main>

        <Footer />
    </div>
</template>