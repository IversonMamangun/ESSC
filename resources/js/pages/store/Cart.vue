<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { 
    ShoppingCart, Store, Trash2, 
    Minus, Plus, ShoppingBag, ArrowRight 
} from 'lucide-vue-next';
import { ref, computed, onMounted } from 'vue';
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

const selectedItems = ref<number[]>([]);

const toggleSelectAll = (event: Event) => {
    const isChecked = (event.target as HTMLInputElement).checked;

    if (isChecked) {
        selectedItems.value = props.cartItems.map(item => item.id);
    } else {
        selectedItems.value = [];
    }
};

const selectedProducts = computed(() => 
    props.cartItems.filter(item => selectedItems.value.includes(item.id))
);

const subtotal = computed(() => 
    selectedProducts.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
);

const delivery = computed(() => selectedProducts.value.length > 0 ? 150 : 0);
const total = computed(() => subtotal.value + delivery.value);

const proceedToCheckout = () => {
    if (selectedItems.value.length === 0) {
        return;
    }

    router.get('/checkout', { selected_ids: selectedItems.value });
};

const updateQuantity = (productId: number, currentQty: number, change: number) => {
    const newQty = currentQty + change;
    
    if (newQty < 1) {
        return;
    }

    router.patch(`/cart/${productId}`, { quantity: newQty }, { preserveScroll: true });
};

const removeItem = (productId: number) => {
    if (confirm('Are you sure you want to remove this item?')) {
        selectedItems.value = selectedItems.value.filter(id => id !== productId);
        router.delete(`/cart/${productId}`, { preserveScroll: true });
    }
};

// Formatting helper for currency
const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(price);
};


onMounted(() => {
    document.documentElement.classList.remove('dark');
    
});
</script>

<template>
    <Head title="Shopping Cart" />
    
    <div class="min-h-screen bg-gray-50 dark:bg-zinc-950 transition-colors duration-300">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8">
            <Navbar />
        </div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col lg:flex-row gap-10">
                
                <div class="flex-1 space-y-6">
                    
                    <div class="flex items-center justify-between mb-2 pb-2">
                        <h1 class="text-3xl font-black text-zinc-900 dark:text-white tracking-tight flex items-center gap-3">
                            <ShoppingCart class="w-8 h-8 text-[#009933]" />
                            Shopping Cart
                        </h1>
                        <span class="text-zinc-500 dark:text-zinc-400 font-bold bg-zinc-200 dark:bg-zinc-800 px-3 py-1 rounded-full text-sm">
                            {{ cartItems.length }} Items
                        </span>
                    </div>

                    <div v-if="cartItems.length === 0" class="bg-white dark:bg-zinc-900 rounded-3xl p-16 text-center shadow-sm border border-zinc-200 dark:border-zinc-800">
                        <div class="w-24 h-24 bg-zinc-50 dark:bg-zinc-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <ShoppingBag class="w-12 h-12 text-zinc-300 dark:text-zinc-600" />
                        </div>
                        <h2 class="text-2xl font-black text-zinc-800 dark:text-white mb-3">Your cart is completely empty</h2>
                        <p class="text-zinc-500 dark:text-zinc-400 mb-8 max-w-md mx-auto">Looks like you haven't added any industrial tools or equipment yet. Discover our top deals today!</p>
                        <Link href="/store" class="bg-[#009933] text-white px-8 py-4 rounded-xl font-bold hover:bg-green-700 transition-all shadow-md inline-flex items-center gap-2">
                            Browse Products <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>

                    <div v-else class="space-y-4">
                        
                        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-4 shadow-sm border border-zinc-200 dark:border-zinc-800 flex items-center gap-4 sticky top-[100px] z-40">
                            <input 
                                type="checkbox" 
                                @change="toggleSelectAll"
                                :checked="selectedItems.length === cartItems.length && cartItems.length > 0"
                                class="w-5 h-5 text-[#009933] bg-zinc-100 border-zinc-300 rounded focus:ring-[#009933] dark:bg-zinc-800 dark:border-zinc-700 cursor-pointer transition-all"
                            >
                            <span class="font-black text-zinc-800 dark:text-zinc-100 uppercase tracking-wider text-sm">Select All Items</span>
                        </div>

                        <div v-for="item in cartItems" :key="item.id" class="bg-white dark:bg-zinc-900 rounded-2xl p-4 sm:p-6 shadow-sm border border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 group transition-all hover:border-[#009933]/50">
                            
                            <div class="flex items-center justify-between sm:justify-start w-full sm:w-auto">
                                <input 
                                    type="checkbox" 
                                    :value="item.id" 
                                    v-model="selectedItems"
                                    class="w-5 h-5 text-[#009933] bg-zinc-100 border-zinc-300 rounded focus:ring-[#009933] dark:bg-zinc-800 dark:border-zinc-700 cursor-pointer shrink-0 transition-all"
                                >
                                <button @click="removeItem(item.id)" class="sm:hidden p-2 text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <Trash2 class="w-5 h-5" />
                                </button>
                            </div>

                            <div class="w-24 h-24 sm:w-28 sm:h-28 bg-zinc-100 dark:bg-zinc-800 rounded-xl overflow-hidden shrink-0 border border-zinc-200 dark:border-zinc-700 mx-auto sm:mx-0">
                                <img :src="item.image" :alt="item.title" class="w-full h-full object-cover" />
                            </div>
                            
                            <div class="flex-1 text-center sm:text-left flex flex-col justify-center">
                                <div class="flex items-center justify-center sm:justify-start gap-1.5 text-xs text-zinc-500 dark:text-zinc-400 font-bold mb-2 uppercase tracking-wide">
                                    <Store class="w-3.5 h-3.5" />
                                    {{ item.store }}
                                </div>
                                <Link :href="'/product/' + item.id" class="text-lg font-bold text-zinc-800 dark:text-zinc-100 hover:text-[#009933] dark:hover:text-[#009933] line-clamp-2 leading-tight transition-colors">
                                    {{ item.title }}
                                </Link>
                                <p class="text-[#009933] font-black text-xl mt-2 tracking-tight">{{ formatPrice(item.price) }}</p>
                            </div>
                            
                            <div class="flex items-center justify-center sm:justify-end gap-4 sm:w-48 shrink-0 mt-4 sm:mt-0">
                                <div class="flex items-center bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl p-1">
                                    <button 
                                        @click="updateQuantity(item.id, item.quantity, -1)" 
                                        :disabled="item.quantity <= 1" 
                                        class="w-8 h-8 flex items-center justify-center bg-white dark:bg-zinc-700 rounded-lg text-zinc-600 dark:text-zinc-300 hover:text-[#009933] disabled:opacity-40 transition-colors shadow-sm"
                                    >
                                        <Minus class="w-4 h-4" />
                                    </button>
                                    <span class="w-10 text-center font-black text-sm text-zinc-800 dark:text-white">{{ item.quantity }}</span>
                                    <button 
                                        @click="updateQuantity(item.id, item.quantity, 1)" 
                                        class="w-8 h-8 flex items-center justify-center bg-white dark:bg-zinc-700 rounded-lg text-zinc-600 dark:text-zinc-300 hover:text-[#009933] transition-colors shadow-sm"
                                    >
                                        <Plus class="w-4 h-4" />
                                    </button>
                                </div>

                                <button @click="removeItem(item.id)" class="hidden sm:flex p-2.5 text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors" title="Remove item">
                                    <Trash2 class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-[400px] shrink-0">
                    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-8 shadow-xl border border-zinc-100 dark:border-zinc-800 sticky top-32">
                        <h2 class="text-2xl font-black text-zinc-800 dark:text-white mb-6 tracking-tight">Order Summary</h2>
                        
                        <div class="flex flex-col gap-4 text-sm mb-6">
                            <div class="flex justify-between text-zinc-600 dark:text-zinc-400 font-medium">
                                <span>Subtotal ({{ selectedItems.length }} items)</span>
                                <span class="font-bold text-zinc-900 dark:text-white">{{ formatPrice(subtotal) }}</span>
                            </div>
                            <div class="flex justify-between text-zinc-600 dark:text-zinc-400 font-medium">
                                <span>Shipping Fee Estimate</span>
                                <span class="font-bold text-zinc-900 dark:text-white">{{ formatPrice(delivery) }}</span>
                            </div>
                        </div>
                        
                        <div class="border-t-2 border-dashed border-zinc-200 dark:border-zinc-800 pt-6 mb-8">
                            <div class="flex justify-between items-end">
                                <span class="font-bold text-zinc-500 dark:text-zinc-500 uppercase tracking-widest text-xs mb-1">Total Amount</span>
                                <span class="text-3xl font-black text-[#009933] tracking-tighter">{{ formatPrice(total) }}</span>
                            </div>
                        </div>
                        
                        <button 
                            @click="proceedToCheckout"
                            class="w-full py-5 rounded-2xl font-black text-lg transition-all shadow-lg flex items-center justify-center gap-2 uppercase tracking-wide"
                            :class="selectedItems.length > 0 
                                ? 'bg-[#009933] text-white hover:bg-green-700 shadow-green-900/10 active:scale-[0.98]' 
                                : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-400 cursor-not-allowed shadow-none'"
                            :disabled="selectedItems.length === 0"
                        >
                            Checkout ({{ selectedItems.length }})
                        </button>
                    </div>
                </div>

            </div>
        </main>
        <Footer />
    </div>
</template>