<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import ProductCard from '@/components/ProductCard.vue';
import Footer from '@/components/sections/Footer.vue';
import { Store, MapPin, Package } from 'lucide-vue-next';

const props = defineProps<{
    store: any;
    products: any;
}>();

// Pagination logic specifically for this seller's products
const changePage = (page: number) => {
    if (page === props.products.current_page) return;
    router.get(`/shop/${props.store.id}`, { page: page }, { preserveState: true, preserveScroll: true });
};
</script>

<template>
    <Head :title="store.name" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>

    <section class="w-full bg-white border-b border-gray-200 pt-8 pb-12 shadow-sm mt-4">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center md:items-start gap-6">
            
            <div class="w-24 h-24 bg-green-50 text-[#009933] rounded-full flex items-center justify-center border-4 border-white shadow-md shrink-0">
                <Store class="w-10 h-10" />
            </div>

            <div class="flex-grow text-center md:text-left">
                <h1 class="text-3xl font-black text-gray-900">{{ store.name }}</h1>
                
                <div class="mt-3 flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm text-gray-600 font-medium">
                    <div class="flex items-center">
                        <MapPin class="w-4 h-4 mr-1 text-[#009933]" />
                        {{ store.city || 'Philippines' }}
                    </div>
                    <div class="flex items-center">
                        <Package class="w-4 h-4 mr-1 text-[#009933]" />
                        {{ products.total }} Products
                    </div>
                </div>
                
                <p class="mt-4 text-gray-500 max-w-2xl text-sm leading-relaxed">
                    {{ store.description || 'Welcome to our official store! Browse our latest products and exclusive deals.' }}
                </p>
            </div>
        </div>
    </section>

    <section class="flex w-full justify-center px-4 mt-8 mb-20">
        <div class="w-full max-w-7xl">
            <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">All Items</h2>
            
            <div v-if="products.data.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <div v-for="product in products.data" :key="product.id">
                    <ProductCard :product="product" />
                </div>
            </div>

            <div v-else class="text-center py-20 text-gray-500">
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