<script setup lang="ts">
import { Head, router, usePage, Link } from '@inertiajs/vue3';
import { LayoutDashboard } from 'lucide-vue-next';
import { ref, onMounted, nextTick, computed } from 'vue'; 
import ProductCard from '@/components/ProductCard.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

const props = defineProps<{
    topDeals: Array<any>;
    discoverItems: any; 
}>();

// --- AUTH CHECK LOGIC ---
const page = usePage();
const user = computed(() => page.props.auth.user);

// Check if the user is a Seller or Admin
const canAccessSellerCenter = computed(() => {
    // Accessing the slug from the shared auth object
    const role = user.value?.user_type?.slug;
    
    return role === 'seller' || role === 'admin';
});

// --- CAROUSEL & UI LOGIC ---
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

const currentPage = ref(props.discoverItems?.current_page || 1);
const totalPages = ref(props.discoverItems?.last_page || 1);
const items = ref(props.discoverItems?.data || []);

const changePage = (page: number) => {
    if (page === currentPage.value) return;
    router.get('/store', { page: page }, { preserveState: true, preserveScroll: true });
};
</script>

<template>
    <Head title="Store" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>
    
    <section class="flex w-full justify-center p-4">
        <div class="relative flex w-full max-w-7xl items-center justify-center group">
            <img src="/assets/store/online-store.jpg" alt="Store Background" class="h-40 md:h-52 w-full rounded-2xl object-cover shadow-sm brightness-75 transition-all duration-500 group-hover:brightness-90" />
            
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-4">
                <h1 class="absolute text-5xl md:text-7xl font-black tracking-normal text-[#009933] [text-shadow:2px_2px_0_#fff,-1px_-1px_0_#fff,1px_-1px_0_#fff,-1px_1px_0_#fff,1px_1px_0_#fff] drop-shadow-2xl mb-4">
                    ONLINE STORE
                </h1>

                <div class="mt-20 md:mt-32">
                    <Link 
                        v-if="canAccessSellerCenter"
                        href="/seller/dashboard" 
                        class="flex items-center gap-2 bg-white text-[#009933] px-6 py-3 rounded-xl font-black shadow-xl hover:bg-[#009933] hover:text-white transition-all transform hover:scale-105 active:scale-95"
                    >
                        <LayoutDashboard class="w-5 h-5" />
                        Go to Seller Center
                    </Link>
                </div>
            </div>
        </div>
    </section>

    <section class="flex w-full justify-center px-4 mt-8">
        <div class="w-full max-w-7xl bg-white rounded-2xl p-6 shadow-sm border border-neutral-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-black text-[#009966]">Top Deals</h2>
                <button @click="toggleDeals" class="text-[#009933] font-bold hover:underline text-sm focus:outline-none">
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

            <div v-if="showAllDeals" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 pb-4">
                <div v-for="product in props.topDeals" :key="'grid-' + product.id">
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
                <div v-for="product in items" :key="'discover-' + product.id">
                    <ProductCard :product="product" />
                </div>
            </div>
            
            <div v-if="totalPages > 1" class="mt-12 flex items-center justify-center gap-2">
                <button 
                    v-for="page in totalPages" 
                    :key="page"
                    @click="changePage(page)"
                    :class="[
                        'px-4 py-2 rounded-md text-sm font-bold transition-colors',
                        currentPage === page ? 'bg-[#009933] text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                    ]"
                >
                    {{ page }}
                </button>
            </div>
        </div>
    </section>
    <Footer />
</template>