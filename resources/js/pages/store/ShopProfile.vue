<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Store, MapPin, Package, Star, MessageCircle, Plus } from 'lucide-vue-next';
import ProductCard from '@/components/ProductCard.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

const props = defineProps<{
    store: any;
    products: any;
}>();

// Pagination logic specifically for this seller's products
const changePage = (page: number) => {
    if (page === props.products.current_page) {
        return;
    }
    
    router.get(`/shop/${props.store.id}`, { page: page }, { preserveState: true, preserveScroll: true });
};
</script>

<template>
    <Head :title="store.name" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 md:mt-12 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 md:p-8 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                <div class="relative shrink-0 mx-auto md:mx-0">
                    <div class="w-24 h-24 md:w-28 md:h-28 rounded-full overflow-hidden border-4 border-gray-50 shadow-sm bg-gray-50 text-[#009933] flex items-center justify-center">
                        <img v-if="store.logo" :src="'/storage/' + store.logo" class="w-full h-full object-cover" />
                        <Store v-else class="w-10 h-10 md:w-12 md:h-12" />
                    </div>
                    <div class="absolute bottom-2 right-2 md:bottom-3 md:right-3 w-5 h-5 bg-green-500 border-2 border-white rounded-full shadow-sm"></div>
                </div>

                <div class="text-center md:text-left">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 justify-center md:justify-start">
                        <h1 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight">
                            {{ store.name }}
                        </h1>
                        <span class="bg-[#009933] text-white text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-wider shadow-sm inline-block w-max mx-auto md:mx-0">
                            Preferred
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-4">
                        <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                            <Star class="w-4 h-4 text-amber-400 fill-current mr-1.5" />
                            <span class="font-bold text-gray-800">{{ store.rating || '4.9' }}</span>
                            <span class="ml-1 text-gray-400 text-xs">(1.2k)</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                            <span class="font-bold text-gray-800">{{ store.followers_count || '5.4k' }}</span>
                            <span class="ml-1.5 text-gray-500 text-xs">Followers</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                            <MapPin class="w-4 h-4 mr-1.5 text-gray-400" />
                            <span class="text-gray-700 text-xs">{{ store.city || 'Philippines' }}</span>
                        </div>
                    </div>
                    
                    <p class="mt-4 text-sm text-gray-500 line-clamp-2 max-w-2xl mx-auto md:mx-0">
                        {{ store.description || 'Official Store: Quality products and fast shipping. Welcome to our online shop!' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-center gap-3 w-full lg:w-auto shrink-0 border-t lg:border-t-0 border-gray-100 pt-6 lg:pt-0">
                <button class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-3 border-2 border-[#009933] text-[#009933] font-bold rounded-xl hover:bg-green-50 transition-colors shadow-sm">
                    <MessageCircle class="w-5 h-5" />
                    Chat Now
                </button>

                <button class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-3 bg-[#009933] text-white font-bold rounded-xl hover:bg-green-700 transition-colors shadow-md">
                    <Plus class="w-5 h-5" />
                    Follow
                </button>
            </div>
        </div>
    </section>

    <section class="flex w-full justify-center px-4 mt-4 mb-20">
        <div class="w-full max-w-7xl">
            <h2 class="text-xl font-black text-gray-800 mb-6 border-b border-gray-200 pb-3 flex items-center gap-2">
                <Package class="w-5 h-5 text-[#009933]" /> All Items
            </h2>
            
            <div v-if="products.data.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <div v-for="product in products.data" :key="product.id">
                    <ProductCard :product="product" />
                </div>
            </div>

            <div v-else class="text-center py-20 text-gray-500 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <Store class="w-12 h-12 mx-auto text-gray-300 mb-4" />
                This seller hasn't uploaded any products yet!
            </div>

            <div v-if="products.last_page > 1" class="mt-12 flex items-center justify-center gap-2">
                <button 
                    v-for="page in products.last_page" 
                    :key="page"
                    @click="changePage(page)"
                    :class="[
                        'px-4 py-2 rounded-md text-sm font-bold transition-colors',
                        products.current_page === page 
                            ? 'bg-[#009933] text-white shadow-sm' 
                            : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'
                    ]"
                >
                    {{ page }}
                </button>
            </div>
        </div>
    </section>

    <Footer />
</template>