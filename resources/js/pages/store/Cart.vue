<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

const props = defineProps<{
    cartItems: Array<{
        id: number;
        title: string;
        price: number;
        quantity: number;
        image: string;
        store: string;
    }>;
}>();

// Track which product IDs are currently checked
const selectedItems = ref<number[]>([]);

// Select or unselect all items at once
const toggleSelectAll = (event: Event) => {
    const isChecked = (event.target as HTMLInputElement).checked;
    if (isChecked) {
        selectedItems.value = props.cartItems.map(item => item.id);
    } else {
        selectedItems.value = [];
    }
};

// Dynamically calculate totals based ONLY on selected items
const selectedProducts = computed(() => 
    props.cartItems.filter(item => selectedItems.value.includes(item.id))
);

const subtotal = computed(() => 
    selectedProducts.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
);

const delivery = computed(() => selectedProducts.value.length > 0 ? 150 : 0);
const total = computed(() => subtotal.value + delivery.value);

// Send only the checked items to the checkout page
const proceedToCheckout = () => {
    if (selectedItems.value.length === 0) return;
    
    router.get('/checkout', { 
        selected_ids: selectedItems.value 
    });
};

const updateQuantity = (productId: number, currentQty: number, change: number) => {
    const newQty = currentQty + change;
    if (newQty < 1) return;
    router.patch(`/cart/${productId}`, { quantity: newQty }, { preserveScroll: true });
};

const removeItem = (productId: number) => {
    if (confirm('Are you sure you want to remove this item?')) {
        // Also remove it from the selected array if it is deleted
        selectedItems.value = selectedItems.value.filter(id => id !== productId);
        router.delete(`/cart/${productId}`, { preserveScroll: true });
    }
};
</script>

<template>
    <Head title="Shopping Cart" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>

    <main class="flex w-full justify-center px-4 mt-8 mb-24">
        <div class="w-full max-w-6xl flex flex-col lg:flex-row gap-8">
            
            <div class="flex-1 flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl md:text-3xl font-black text-gray-800">Shopping Cart</h1>
                    <span class="text-gray-500 font-bold">{{ cartItems.length }} Items</span>
                </div>

                <div v-if="cartItems.length === 0" class="bg-white rounded-2xl p-12 text-center shadow-sm border border-neutral-100">
                    <div class="text-6xl mb-4">🛒</div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Your cart is completely empty.</h2>
                    <p class="text-gray-500 mb-6">Looks like you haven't added any industrial tools or equipment yet.</p>
                    <Link href="/store" class="bg-[#009933] text-white px-8 py-3 rounded-xl font-bold hover:bg-green-700 transition-colors shadow-sm inline-block">
                        Browse Products
                    </Link>
                </div>

                <div v-else class="flex flex-col gap-4">
                    
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
                        <input 
                            type="checkbox" 
                            @change="toggleSelectAll"
                            :checked="selectedItems.length === cartItems.length && cartItems.length > 0"
                            class="w-5 h-5 text-[#009933] rounded focus:ring-[#009933] cursor-pointer"
                        >
                        <span class="font-bold text-gray-800">Select All Items</span>
                    </div>

                    <div v-for="item in cartItems" :key="item.id" class="bg-white rounded-2xl p-4 shadow-sm border border-neutral-100 flex items-center gap-4">
                        
                        <input 
                            type="checkbox" 
                            :value="item.id" 
                            v-model="selectedItems"
                            class="w-5 h-5 text-[#009933] rounded focus:ring-[#009933] cursor-pointer shrink-0"
                        >

                        <div class="w-24 h-24 bg-gray-100 rounded-xl overflow-hidden shrink-0">
                            <img :src="item.image" :alt="item.title" class="w-full h-full object-cover" />
                        </div>
                        
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 font-bold mb-1">Store: {{ item.store }}</p>
                            <Link :href="'/product/' + item.id" class="text-lg font-bold text-gray-800 hover:text-[#009933] line-clamp-1">
                                {{ item.title }}
                            </Link>
                            <p class="text-[#009933] font-black mt-1">₱{{ item.price.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</p>
                        </div>
                        
                        <div class="flex items-center gap-1 bg-gray-50 border border-gray-200 rounded-lg p-1 shrink-0">
                            <button @click="updateQuantity(item.id, item.quantity, -1)" :disabled="item.quantity <= 1" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-md font-bold text-gray-600 hover:bg-gray-100 disabled:opacity-50 transition-colors">-</button>
                            <span class="w-8 text-center font-black text-gray-800">{{ item.quantity }}</span>
                            <button @click="updateQuantity(item.id, item.quantity, 1)" class="w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-md font-bold text-gray-600 hover:bg-gray-100 transition-colors">+</button>
                        </div>

                        <button @click="removeItem(item.id)" class="p-2 ml-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Remove item">
                            🗑️
                        </button>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-96 shrink-0">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-100 sticky top-32">
                    <h2 class="text-xl font-black text-gray-800 mb-6">Order Summary</h2>
                    
                    <div class="flex flex-col gap-4 text-sm mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal ({{ selectedItems.length }} items)</span>
                            <span class="font-bold text-gray-800">₱{{ subtotal.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Delivery Fee</span>
                            <span class="font-bold text-gray-800">₱{{ delivery.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="text-2xl font-black text-[#009933]">₱{{ total.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                        </div>
                    </div>
                    
                    <button 
                        @click="proceedToCheckout"
                        class="w-full py-4 rounded-xl font-bold text-lg transition-colors shadow-md flex items-center justify-center gap-2"
                        :class="selectedItems.length > 0 ? 'bg-[#009933] text-white hover:bg-green-700' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                        :disabled="selectedItems.length === 0"
                    >
                        Checkout ({{ selectedItems.length }})
                    </button>
                </div>
            </div>

        </div>
    </main>
</template>