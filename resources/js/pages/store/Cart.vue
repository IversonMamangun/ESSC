<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

// 1. Mock Data for Cart Items
const cartItems = ref([
    {
        id: 1,
        title: "Heavy Duty Industrial Drill Pro 2000",
        price: 4500.00,
        quantity: 1,
        image: "/assets/Our Capabilities.png",
        store: "Industrial Tech Supply Co."
    },
    {
        id: 2,
        title: "Safety Goggles (Anti-Fog)",
        price: 350.00,
        quantity: 2,
        image: "/assets/logos/logo top.png",
        store: "Safety First Gear"
    }
]);

// 2. Cart Logic (Increase, Decrease, Remove)
const increaseQty = (id: number) => {
    const item = cartItems.value.find(i => i.id === id);
    if (item) item.quantity++;
};

const decreaseQty = (id: number) => {
    const item = cartItems.value.find(i => i.id === id);
    if (item && item.quantity > 1) item.quantity--;
};

const removeItem = (id: number) => {
    cartItems.value = cartItems.value.filter(i => i.id !== id);
};

// 3. Order Calculations
const subtotal = computed(() => {
    return cartItems.value.reduce((total, item) => total + (item.price * item.quantity), 0);
});

// Assuming a standard 12% tax rate for example
const tax = computed(() => subtotal.value * 0.12); 

const total = computed(() => subtotal.value + tax.value);

// Helper function to format currency
const formatPrice = (value: number) => {
    return `₱${value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
};
</script>

<template>
    <Head title="Shopping Cart - Store" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>

    <main class="flex w-full justify-center px-4 mt-8 mb-24">
        <div class="w-full max-w-7xl flex flex-col lg:flex-row gap-8">
            
            <div class="w-full lg:w-2/3">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl md:text-3xl font-black text-gray-800">Shopping Cart</h1>
                    <span class="text-gray-500 font-medium">{{ cartItems.length }} Items</span>
                </div>

                <div v-if="cartItems.length === 0" class="bg-white rounded-2xl p-12 text-center shadow-sm border border-neutral-100">
                    <div class="text-6xl mb-4">🛒</div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Your cart is empty</h2>
                    <p class="text-gray-500 mb-6">Looks like you haven't added anything to your cart yet.</p>
                    <Link href="/store" class="bg-[#009933] text-white px-6 py-3 rounded-xl font-bold hover:bg-green-700 transition-colors">
                        Continue Shopping
                    </Link>
                </div>

                <div v-else class="flex flex-col gap-4">
                    <div 
                        v-for="item in cartItems" 
                        :key="item.id" 
                        class="bg-white rounded-2xl p-4 md:p-6 shadow-sm border border-neutral-100 flex flex-col md:flex-row items-start md:items-center gap-4 md:gap-6"
                    >
                        <div class="w-24 h-24 shrink-0 bg-gray-50 rounded-xl border border-gray-100 p-2 flex items-center justify-center">
                            <img :src="item.image" :alt="item.title" class="w-full h-full object-contain mix-blend-multiply" />
                        </div>

                        <div class="flex-1">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">{{ item.store }}</p>
                            <h3 class="text-lg font-bold text-gray-800 leading-tight mb-2 line-clamp-2">
                                <a href="#" class="hover:text-[#009933] transition-colors">{{ item.title }}</a>
                            </h3>
                            <p class="text-[#009933] font-black">{{ formatPrice(item.price) }}</p>
                        </div>

                        <div class="flex items-center justify-between w-full md:w-auto gap-6 mt-4 md:mt-0">
                            
                            <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden">
                                <button @click="decreaseQty(item.id)" class="w-8 h-8 bg-gray-50 hover:bg-gray-100 flex items-center justify-center font-bold text-gray-600 transition-colors">-</button>
                                <div class="w-10 h-8 flex items-center justify-center font-bold text-sm text-gray-800 border-x border-gray-300 bg-white">
                                    {{ item.quantity }}
                                </div>
                                <button @click="increaseQty(item.id)" class="w-8 h-8 bg-gray-50 hover:bg-gray-100 flex items-center justify-center font-bold text-gray-600 transition-colors">+</button>
                            </div>

                            <button @click="removeItem(item.id)" class="text-gray-400 hover:text-red-500 transition-colors p-2" title="Remove Item">
                                🗑️
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-100 sticky top-32">
                    <h2 class="text-xl font-black text-gray-800 mb-6">Order Summary</h2>
                    
                    <div class="flex flex-col gap-4 text-sm text-gray-600 border-b border-gray-100 pb-6 mb-6">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-bold text-gray-800">{{ formatPrice(subtotal) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Estimated Tax (12%)</span>
                            <span class="font-bold text-gray-800">{{ formatPrice(tax) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="text-gray-400 italic">Calculated at checkout</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end mb-8">
                        <span class="text-lg font-bold text-gray-800">Total</span>
                        <span class="text-3xl font-black text-[#009933]">{{ formatPrice(total) }}</span>
                    </div>

                    <button 
                        :disabled="cartItems.length === 0"
                        class="w-full bg-[#009933] text-white py-4 rounded-xl font-bold text-lg hover:bg-green-700 transition-colors shadow-md disabled:bg-gray-300 disabled:cursor-not-allowed"
                    >
                        Proceed to Checkout
                    </button>
                    
                    <div class="mt-4 flex items-center justify-center gap-2 text-xs text-gray-400">
                        <span>🔒</span> Secure SSL Checkout
                    </div>
                </div>
            </div>

        </div>
    </main>
</template>