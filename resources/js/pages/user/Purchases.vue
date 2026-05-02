<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Footer from '@/components/sections/Footer.vue';
import { 
    User, MapPin, Package, Clock, 
    Truck, CheckCircle2, XCircle, 
    Store, Search, ChevronRight, MessageCircle
} from 'lucide-vue-next';

// Define the shape of our order data
interface OrderItem {
    id: number;
    title: string;
    image: string;
    price: number;
    quantity: number;
}

interface Order {
    id: string; // e.g., "ORD-12345"
    store_name: string;
    status: 'To Pay' | 'To Ship' | 'To Receive' | 'Completed' | 'Cancelled';
    total_amount: number;
    items: OrderItem[];
    created_at: string;
}

const props = defineProps<{
    user: any;
    orders: Order[];
}>();

// Tab Management
const tabs = ['All', 'To Pay', 'To Ship', 'To Receive', 'Completed', 'Cancelled'];
const activeTab = ref('All');

// Filter orders based on the active tab
const filteredOrders = computed(() => {
    if (activeTab.value === 'All') return props.orders;
    return props.orders.filter(order => order.status === activeTab.value);
});

// Helper for status colors
const getStatusColor = (status: string) => {
    switch(status) {
        case 'To Pay': return 'text-amber-500';
        case 'To Ship': return 'text-blue-500';
        case 'To Receive': return 'text-indigo-500';
        case 'Completed': return 'text-[#009933]';
        case 'Cancelled': return 'text-red-500';
        default: return 'text-zinc-500';
    }
};

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(price);
};
</script>

<template>
    <Head title="My Purchases" />
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 transition-colors duration-300">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8">
            <Navbar />
        </div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="w-full lg:w-64 shrink-0">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-full bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center overflow-hidden border-2 border-white dark:border-zinc-900 shadow-sm shrink-0">
                        <img v-if="props.user?.avatar" :src="`/storage/${props.user.avatar}`" class="w-full h-full object-cover" />
                        
                        <User v-else class="w-6 h-6 text-zinc-500" />
                    </div>
                        <div>
                            <p class="font-black text-zinc-900 dark:text-white leading-tight">{{ props.user?.name || 'Buyer Account' }}</p>
                        </div>
                    </div>

                    <nav class="space-y-1">
                        <Link href="/account" class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800/50 font-bold transition-colors">
                            <User class="w-5 h-5" /> My Account
                        </Link>
                        <Link href="/account/address" class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800/50 font-bold transition-colors">
                            <MapPin class="w-5 h-5" /> Addresses
                        </Link>
                        <Link href="/purchases" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 dark:bg-green-900/10 text-[#009933] font-black transition-colors">
                            <Package class="w-5 h-5" /> My Purchases
                        </Link>
                    </nav>
                </div>

                <div class="flex-1 min-w-0">
                    
                    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden mb-6">
                        <div class="flex overflow-x-auto custom-scrollbar">
                            <button 
                                v-for="tab in tabs" 
                                :key="tab"
                                @click="activeTab = tab"
                                class="flex-1 min-w-[100px] py-4 text-sm font-black whitespace-nowrap text-center transition-all border-b-2"
                                :class="activeTab === tab 
                                    ? 'text-[#009933] border-[#009933] bg-green-50/30 dark:bg-green-900/10' 
                                    : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-zinc-800 dark:hover:text-zinc-200'"
                            >
                                {{ tab }}
                            </button>
                        </div>
                    </div>

                    <div class="relative mb-6">
                        <Search class="absolute left-4 top-3.5 w-5 h-5 text-zinc-400" />
                        <input 
                            type="text" 
                            placeholder="Search by Order ID or Product Name" 
                            class="w-full bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl py-3.5 pl-12 pr-4 outline-none focus:ring-2 focus:ring-[#009933]/20 focus:border-[#009933] text-sm dark:text-white transition-all shadow-sm"
                        >
                    </div>

                    <div v-if="filteredOrders.length === 0" class="bg-white dark:bg-zinc-900 rounded-3xl p-16 text-center shadow-sm border border-zinc-200 dark:border-zinc-800">
                        <div class="w-24 h-24 bg-zinc-50 dark:bg-zinc-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <Package class="w-10 h-10 text-zinc-300 dark:text-zinc-600" />
                        </div>
                        <h3 class="text-xl font-black text-zinc-800 dark:text-white mb-2">No orders yet</h3>
                        <p class="text-zinc-500 dark:text-zinc-400">You don't have any orders with this status.</p>
                    </div>

                    <div v-else class="space-y-6">
                        <div 
                            v-for="order in filteredOrders" 
                            :key="order.id" 
                            class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden"
                        >
                            <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800 flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    <Store class="w-4 h-4 text-zinc-400" />
                                    <span class="font-black text-zinc-800 dark:text-zinc-100">{{ order.store_name }}</span>
                                    <Link :href="`/shop/${order.store_name}`" class="px-2 py-0.5 bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-[10px] uppercase font-bold rounded flex items-center gap-1 hover:bg-zinc-200 transition-colors">
                                        View Shop <ChevronRight class="w-3 h-3" />
                                    </Link>
                                </div>
                                <div class="flex items-center gap-3 text-sm font-black uppercase tracking-wider" :class="getStatusColor(order.status)">
                                    <Clock v-if="order.status === 'To Pay'" class="w-4 h-4" />
                                    <Package v-else-if="order.status === 'To Ship'" class="w-4 h-4" />
                                    <Truck v-else-if="order.status === 'To Receive'" class="w-4 h-4" />
                                    <CheckCircle2 v-else-if="order.status === 'Completed'" class="w-4 h-4" />
                                    <XCircle v-else-if="order.status === 'Cancelled'" class="w-4 h-4" />
                                    {{ order.status }}
                                </div>
                            </div>

                            <div class="p-6 space-y-4">
                                <Link 
                                    v-for="item in order.items" 
                                    :key="item.id"
                                    :href="`/product/${item.id}`"
                                    class="flex gap-4 group"
                                >
                                    <div class="w-20 h-20 rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700 overflow-hidden shrink-0">
                                        <img :src="item.image" :alt="item.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    </div>
                                    <div class="flex-1 flex flex-col sm:flex-row justify-between gap-2">
                                        <div>
                                            <h4 class="font-bold text-zinc-800 dark:text-zinc-200 line-clamp-2 group-hover:text-[#009933] transition-colors">{{ item.title }}</h4>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400 font-medium mt-1">x{{ item.quantity }}</p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <p class="font-black text-[#009933]">{{ formatPrice(item.price) }}</p>
                                        </div>
                                    </div>
                                </Link>
                            </div>

                            <div class="px-6 py-5 bg-zinc-50 dark:bg-zinc-800/30 border-t border-zinc-100 dark:border-zinc-800 flex flex-col sm:flex-row items-end sm:items-center justify-between gap-4">
                                <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                    Order ID: <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ order.id }}</span>
                                </div>
                                <div class="flex flex-col items-end gap-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-zinc-600 dark:text-zinc-400">Order Total:</span>
                                        <span class="text-2xl font-black text-[#009933]">{{ formatPrice(order.total_amount) }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button class="px-5 py-2 rounded-xl text-zinc-600 dark:text-zinc-300 font-bold border border-zinc-300 dark:border-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors text-sm flex items-center gap-2">
                                            <MessageCircle class="w-4 h-4" /> Contact Seller
                                        </button>
                                        <button 
                                            v-if="order.status === 'Completed' || order.status === 'Cancelled'"
                                            class="px-5 py-2 rounded-xl bg-[#009933] text-white font-bold hover:bg-green-700 transition-colors shadow-md text-sm active:scale-95"
                                        >
                                            Buy Again
                                        </button>
                                        <button 
                                            v-if="order.status === 'To Receive'"
                                            class="px-5 py-2 rounded-xl bg-[#009933] text-white font-bold hover:bg-green-700 transition-colors shadow-md text-sm active:scale-95"
                                        >
                                            Order Received
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { height: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 10px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; }
</style>