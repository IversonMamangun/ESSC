<script setup lang="ts">
import { ref, onMounted, nextTick, computed} from 'vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import { Head } from '@inertiajs/vue3';
import AdCarousel from '@/components/AdCarousel.vue'; 
import ProductCard from '@/components/ProductCard.vue';
import Footer from '@/components/sections/Footer.vue';

const showAllDeals = ref(false);

const topDeals = Array.from({ length: 16 }, (_, i) => ({
    id: i + 1,
    image: '/assets/store/item.jpg', 
    title: `Industrial Equipment ${i + 1}`,
    location: 'Metro Manila',
    price: `₱${(Math.random() * 5000 + 500).toFixed(2)}`,
    sold: Math.floor(Math.random() * 500) + 10,
    rating: 4.8
}));

// Function to initialize the OwlCarousel
const initCarousel = () => {
    const $ = (window as any).$;
    $('.top-deals-carousel').owlCarousel({
        loop: false, // Set to false so they don't infinitely loop the 16 items
        margin: 16,
        nav: false,
        dots: false,
        responsive: {
            0: { items: 2 },
            768: { items: 3 },
            1024: { items: 4 }, // Shows 4 items on desktop
        },
    });
};

// Function to safely destroy the carousel before Vue removes it
const destroyCarousel = () => {
    const $ = (window as any).$;
    $('.top-deals-carousel').trigger('destroy.owl.carousel');
};

// The toggle function
const toggleDeals = async () => {
    if (!showAllDeals.value) {
        // Turning ON Grid Mode
        destroyCarousel(); // Kill the carousel first
        showAllDeals.value = true; // Tell Vue to render the grid
    } else {
        // Turning ON Carousel Mode
        showAllDeals.value = false; // Tell Vue to render the carousel HTML
        await nextTick(); // Wait a millisecond for Vue to actually put the HTML on the screen
        initCarousel(); // Re-initialize OwlCarousel on the fresh HTML
    }
};

onMounted(() => {
    initCarousel(); // Initialize when the page first loads
});

const allDiscoverItems = Array.from({ length: 100 }, (_, i) => ({
    id: i + 100, // Offset IDs so they don't clash with Top Deals
    image: '/assets/store/item.jpg', 
    title: `Discover Product ${i + 1}`,
    location: 'Cebu City',
    price: `₱${(Math.random() * 2000 + 100).toFixed(2)}`,
    sold: Math.floor(Math.random() * 1000) + 5,
    rating: (Math.random() * 1 + 4).toFixed(1) // Random rating between 4.0 and 5.0
}));

// Pagination Setup
const currentPage = ref(1);
const itemsPerPage = 20; // Show 20 items per page (gives us exactly 5 pages)

// Calculate total pages
const totalPages = computed(() => Math.ceil(allDiscoverItems.length / itemsPerPage));

// Get only the items for the current page
const paginatedDiscoverItems = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return allDiscoverItems.slice(start, end);
});

// Function to change page and scroll to top of Discover section
const changePage = (page: number) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        
        // Smoothly scroll up slightly so the user sees the new items
        const discoverSection = document.getElementById('discover-section');
        if (discoverSection) {
            const y = discoverSection.getBoundingClientRect().top + window.scrollY - 120;
            window.scrollTo({ top: y, behavior: 'smooth' });
        }
    }
};
</script>

<template>
    <Head title="Store" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>
    
    <section class="flex w-full justify-center p-4">
        <div class="relative flex w-full max-w-7xl items-center justify-center">
            <img
                src="/assets/store/online-store.jpg"
                alt="Our Capabilities Background"
                class="h-32 w-full rounded-2xl object-cover shadow-sm"
            />
            <h1 class="absolute text-5xl md:text-7xl font-black tracking-normal text-[#009933] 
                [text-shadow:2px_2px_0_#fff,_-1px_-1px_0_#fff,_1px_-1px_0_#fff,_-1px_1px_0_#fff,_1px_1px_0_#fff] 
                drop-shadow-2xl">
                ONLINE STORE
            </h1>
        </div>
    </section>

    <section class="flex w-full justify-center px-4 mt-2">
        <div class="flex w-full max-w-7xl flex-col md:flex-row justify-between gap-4 rounded-2xl bg-[#009933] p-2 shadow-sm">
            <div class="w-full md:w-1/2 relative">
                <input 
                    type="text" 
                    placeholder="Search for products..." 
                    class="w-full rounded-xl bg-white border-none py-3 pl-3 pr-14 text-[#009933] placeholder:text-green-700/50 focus:ring-2 focus:ring-green-300 shadow-sm"
                />
                <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#009933] text-white p-2 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </div>

            <div class="flex w-full md:w-auto gap-4">
                <select class="w-full md:w-48 rounded-xl bg-white border-none p-3 font-medium text-[#009933] focus:ring-2 focus:ring-green-300 shadow-sm outline-none">
                    <option>All Categories</option>
                    <option>Electronics</option>
                    <option>Hardware</option>
                    <option>Office</option>
                </select>
                
                <select class="w-full md:w-48 rounded-xl bg-white border-none p-3 font-medium text-[#009933] focus:ring-2 focus:ring-green-300 shadow-sm outline-none">
                    <option>Sort by</option>
                    <option>Best Sellers</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                </select>
            </div>
        </div>
    </section>

    <section class="flex w-full justify-center px-4 mt-4">
        <div class="w-full max-w-7xl bg-white p-4 rounded-2xl">
            <AdCarousel />
        </div>
    </section>

    <section class="flex w-full justify-center px-4 mt-8">
        <div class="w-full max-w-7xl bg-white rounded-2xl p-6 shadow-sm border border-neutral-100">
            
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-black text-[#009966]">Top Deals</h2>
                
                <button 
                    @click="toggleDeals" 
                    class="text-[#009933] font-bold hover:underline text-sm focus:outline-none"
                >
                    {{ showAllDeals ? 'Hide' : 'See all' }}
                </button>
            </div>
            
            <div :class="{ 'hidden': showAllDeals }">
                <div class="owl-carousel top-deals-carousel owl-theme">
                    <div v-for="product in topDeals" :key="'carousel-' + product.id">
                        <ProductCard :product="product" />
                    </div>
                </div>
            </div>

            <div v-if="showAllDeals" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 pb-4">
                <div v-for="product in topDeals" :key="'grid-' + product.id">
                    <ProductCard :product="product" />
                </div>
            </div>
            
        </div>
    </section>
    
    <section id="discover-section" class="flex w-full justify-center px-4 mt-8 mb-20">
        <div class="w-full max-w-7xl p-4 bg-white rounded-2xl">
            
            <div class="text-center mb-8">
                <h2 class="text-3xl font-black text-[#009966] tracking-wide">Discover</h2>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <div v-for="product in paginatedDiscoverItems" :key="'discover-' + product.id">
                    <ProductCard :product="product" />
                </div>
            </div>

            <div class="flex justify-center items-center gap-2 mt-12">
                
                <button 
                    @click="changePage(currentPage - 1)" 
                    :disabled="currentPage === 1"
                    class="px-4 py-2 rounded-lg font-bold transition-colors border border-gray-200"
                    :class="currentPage === 1 ? 'text-gray-400 bg-gray-50 cursor-not-allowed' : 'text-[#009933] bg-white hover:bg-green-50'"
                >
                    &laquo; Prev
                </button>

                <button 
                    v-for="page in totalPages" 
                    :key="'page-' + page"
                    @click="changePage(page)"
                    class="w-10 h-10 rounded-lg font-bold transition-colors border"
                    :class="currentPage === page ? 'bg-[#009933] text-white border-[#009933] shadow-md' : 'bg-white text-gray-600 border-gray-200 hover:border-[#009933] hover:text-[#009933]'"
                >
                    {{ page }}
                </button>

                <button 
                    @click="changePage(currentPage + 1)" 
                    :disabled="currentPage === totalPages"
                    class="px-4 py-2 rounded-lg font-bold transition-colors border border-gray-200"
                    :class="currentPage === totalPages ? 'text-gray-400 bg-gray-50 cursor-not-allowed' : 'text-[#009933] bg-white hover:bg-green-50'"
                >
                    Next &raquo;
                </button>

            </div>

        </div>
    </section>
    <Footer />
</template>

<style scoped>
.owl-theme .owl-nav {
    display: none;
}
.custom-scrollbar::-webkit-scrollbar {
    height: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1; 
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cccccc; 
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #009933; 
}
</style>