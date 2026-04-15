<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import ProductCard from '@/components/ProductCard.vue';

// Mock data for the specific Vendor/Seller
const vendor = {
    name: "Industrial Tech Supply Co.",
    joined: "2020",
    followers: "12.4k",
    rating: 4.9,
    banner: "/assets/Our Capabilities.png", // Reusing your existing asset
    logo: "🏢" // You can replace this with an actual image later
};

// Mock data for Best Sellers
const bestSellers = Array.from({ length: 6 }, (_, i) => ({
    id: i + 1,
    image: '', 
    title: `Premium Tool ${i + 1}`,
    location: 'Makati City',
    price: `₱${(Math.random() * 2000 + 500).toFixed(2)}`,
    sold: Math.floor(Math.random() * 500) + 100,
    rating: 5.0
}));

// Mock data for All Items
const allItems = Array.from({ length: 12 }, (_, i) => ({
    id: i + 100,
    image: '', 
    title: `General Supply ${i + 1}`,
    location: 'Makati City',
    price: `₱${(Math.random() * 1000 + 100).toFixed(2)}`,
    sold: Math.floor(Math.random() * 100) + 5,
    rating: 4.5
}));
</script>

<template>
    <Head :title="`${vendor.name} - Store`" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>

    <section class="flex w-full justify-center px-4 mt-4">
        <div class="w-full max-w-7xl bg-white rounded-2xl shadow-sm border border-neutral-100 overflow-hidden">
            
            <div class="h-32 md:h-48 w-full bg-gray-200 relative">
                <img :src="vendor.banner" alt="Store Banner" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-black/20"></div>
            </div>

            <div class="p-6 md:p-8 flex flex-col md:flex-row items-center md:items-end justify-between gap-6 -mt-16 relative z-10">
                
                <div class="flex flex-col md:flex-row items-center md:items-end gap-4">
                    <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-full border-4 border-white shadow-md flex items-center justify-center text-5xl">
                        {{ vendor.logo }}
                    </div>
                    <div class="text-center md:text-left mb-2">
                        <h1 class="text-2xl md:text-3xl font-black text-gray-800">{{ vendor.name }}</h1>
                        <div class="flex items-center justify-center md:justify-start gap-3 mt-1 text-sm text-gray-600 font-medium">
                            <span>⭐ {{ vendor.rating }} Rating</span>
                            <span>|</span>
                            <span>👥 {{ vendor.followers }} Followers</span>
                            <span>|</span>
                            <span>Joined {{ vendor.joined }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 w-full md:w-auto">
                    <button class="flex-1 md:flex-none px-6 py-2 border-2 border-[#009933] text-[#009933] rounded-xl font-bold hover:bg-green-50 transition-colors">
                        💬 Chat
                    </button>
                    <button class="flex-1 md:flex-none px-8 py-2 bg-[#009933] text-white rounded-xl font-bold hover:bg-green-700 transition-colors shadow-sm">
                        + Follow
                    </button>
                </div>

            </div>
        </div>
    </section>

    <section class="flex w-full justify-center px-4 mt-8">
        <div class="w-full max-w-7xl bg-white rounded-2xl p-6 shadow-sm border border-neutral-100">
            <h2 class="text-xl font-black text-[#009966] mb-4">🏆 Best Sellers</h2>
            
            <div class="flex overflow-x-auto gap-4 pb-4 snap-x custom-scrollbar">
                <div v-for="product in bestSellers" :key="'best-' + product.id" class="w-[75vw] sm:w-[45vw] md:w-[calc(25%-12px)] shrink-0 snap-start">
                    <ProductCard :product="product" />
                </div>
            </div>
        </div>
    </section>

    <section class="flex w-full justify-center px-4 mt-8 mb-24">
        <div class="w-full max-w-7xl bg-white rounded-2xl p-6 shadow-sm border border-neutral-100">
            
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-xl font-black text-gray-800">📦 All Items</h2>
                
                <select class="rounded-xl border border-gray-200 text-sm p-2 text-gray-600 focus:border-[#009933] focus:ring-[#009933] outline-none">
                    <option>Latest</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                </select>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <div v-for="product in allItems" :key="'all-' + product.id">
                    <ProductCard :product="product" />
                </div>
            </div>

        </div>
    </section>

</template>

<style scoped>
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