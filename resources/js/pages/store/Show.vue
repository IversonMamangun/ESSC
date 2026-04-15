<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

// 1. Mock Data for the Product
const product = {
    id: 1,
    title: "Heavy Duty Industrial Drill Pro 2000",
    price: "₱4,500.00",
    oldPrice: "₱5,200.00",
    rating: 4.8,
    reviews: 124,
    sold: 340,
    location: "Metro Manila",
    storeName: "Industrial Tech Supply Co.",
    description: "Professional-grade industrial drill with variable speed control, ergonomic grip, and a heavy-duty motor. Built specifically for construction and manufacturing environments.",
    // We will use your existing images as placeholders
    images: [
        "/assets/Our Capabilities.png", 
        "/assets/logos/logo top.png" 
    ],
    stock: 15
};

// 2. State for the interactive parts
const quantity = ref(1);
const activeImage = ref(product.images[0]);

// 3. Logic for the Quantity Buttons
const increaseQuantity = () => {
    if (quantity.value < product.stock) {
        quantity.value++;
    }
};

const decreaseQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};
</script>

<template>
    <Head :title="`${product.title} - Store`" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>

    <main class="flex w-full justify-center px-4 mt-6 mb-24">
        <div class="w-full max-w-7xl bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 md:p-8">
            
            <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
                <a href="#" class="hover:text-[#009933]">Home</a>
                <span>›</span>
                <a href="#" class="hover:text-[#009933]">Online Store</a>
                <span>›</span>
                <span class="text-gray-800 font-medium truncate">{{ product.title }}</span>
            </nav>

            <div class="flex flex-col md:flex-row gap-8 lg:gap-12">
                
                <div class="w-full md:w-5/12 flex flex-col gap-4">
                    <div class="w-full aspect-square bg-gray-100 rounded-2xl border border-gray-200 overflow-hidden flex items-center justify-center p-4">
                        <img :src="activeImage" :alt="product.title" class="w-full h-full object-contain mix-blend-multiply" />
                    </div>
                    
                    <div class="flex gap-4 overflow-x-auto pb-2 custom-scrollbar">
                        <button 
                            v-for="(img, index) in product.images" 
                            :key="index"
                            @click="activeImage = img"
                            class="w-20 h-20 shrink-0 rounded-xl border-2 overflow-hidden transition-all bg-gray-50"
                            :class="activeImage === img ? 'border-[#009933]' : 'border-transparent hover:border-gray-300'"
                        >
                            <img :src="img" alt="Thumbnail" class="w-full h-full object-cover opacity-80 hover:opacity-100" />
                        </button>
                    </div>
                </div>

                <div class="w-full md:w-7/12 flex flex-col">
                    
                    <h1 class="text-2xl md:text-3xl font-black text-gray-800 leading-tight">
                        {{ product.title }}
                    </h1>
                    
                    <div class="flex items-center text-sm text-gray-600 mt-3 gap-4 divide-x divide-gray-300">
                        <div class="flex items-center gap-1 text-yellow-500 font-bold">
                            ⭐ {{ product.rating }}
                        </div>
                        <div class="pl-4">
                            <span class="font-bold text-gray-800">{{ product.reviews }}</span> Ratings
                        </div>
                        <div class="pl-4">
                            <span class="font-bold text-gray-800">{{ product.sold }}</span> Sold
                        </div>
                    </div>

                    <div class="bg-green-50 p-6 rounded-2xl mt-6 border border-green-100">
                        <div class="flex items-end gap-3">
                            <span class="text-4xl font-black text-[#009933]">{{ product.price }}</span>
                            <span class="text-lg text-gray-400 line-through mb-1">{{ product.oldPrice }}</span>
                            <span class="bg-[#009933] text-white text-xs font-bold px-2 py-1 rounded-lg mb-2">15% OFF</span>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col gap-3 text-sm text-gray-600">
                        <div class="flex gap-4">
                            <span class="w-20 text-gray-500">Store</span>
                            <span class="font-bold text-[#009933] hover:underline cursor-pointer">{{ product.storeName }}</span>
                        </div>
                        <div class="flex gap-4">
                            <span class="w-20 text-gray-500">Shipping</span>
                            <span>Ships from <b>{{ product.location }}</b></span>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-6">
                        <span class="text-sm text-gray-500 w-20">Quantity</span>
                        <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden">
                            <button @click="decreaseQuantity" class="w-10 h-10 bg-gray-50 hover:bg-gray-100 flex items-center justify-center font-bold text-xl text-gray-600 transition-colors">-</button>
                            <div class="w-14 h-10 flex items-center justify-center font-bold text-gray-800 border-x border-gray-300 bg-white">
                                {{ quantity }}
                            </div>
                            <button @click="increaseQuantity" class="w-10 h-10 bg-gray-50 hover:bg-gray-100 flex items-center justify-center font-bold text-xl text-gray-600 transition-colors">+</button>
                        </div>
                        <span class="text-xs text-gray-400">{{ product.stock }} pieces available</span>
                    </div>

                    <div class="mt-10 flex gap-4">
                        <button class="flex-1 py-4 border-2 border-[#009933] bg-green-50 text-[#009933] rounded-xl font-bold text-lg hover:bg-green-100 transition-colors flex items-center justify-center gap-2">
                            🛒 Add to Cart
                        </button>
                        <button class="flex-1 py-4 bg-[#009933] text-white rounded-xl font-bold text-lg hover:bg-green-700 transition-colors shadow-md hover:shadow-lg">
                            Buy Now
                        </button>
                    </div>

                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-gray-100">
                <h2 class="text-xl font-black text-gray-800 mb-4 bg-gray-50 inline-block px-4 py-2 rounded-lg">Product Description</h2>
                <p class="text-gray-600 leading-relaxed max-w-4xl">
                    {{ product.description }}
                </p>
            </div>

        </div>
    </main>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    height: 6px;
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