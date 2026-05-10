<script setup lang="ts">
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import { 
    Store, Package, TrendingUp, ShoppingBag, 
    Plus, Edit, Trash2, ExternalLink, Clock, Truck, CheckCircle2, AlertCircle
} from 'lucide-vue-next';
import { ref, computed } from 'vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';


interface Product {
    id: number;
    title: string;
    price: number;
    stock: number;
    image: string | null;
    category?: { name: string };
}

interface Order {
    id: number;
    tracking_number: string;
    status: string;
    total_price: number;
    created_at: string;
    user?: { name: string, email: string };
    products: Product[];
}

const props = defineProps<{
    store: {
        id: number;
        name: string;
        description: string;
        is_active: boolean;
        logo?: string;
    } | null;
    products: Product[]; 
    orders: Order[];
}>();

const form = useForm({
    name: '',
    description: '',
});

const submitStore = () => form.post('/seller/store');

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(price);
};

const deleteProduct = (id: number) => {
    if (confirm('Are you sure you want to delete this product? This cannot be undone.')) {
        router.delete(`/seller/products/${id}`, { preserveScroll: true });
    }
};

// --- NEW: Automated Inventory Splitting ---
const activeTab = ref('products'); // 'products', 'sold', or 'orders'

// Automatically filter products into Active and Sold Out
const activeProducts = computed(() => {
    return props.products.filter(p => p.stock > 0);
});

const soldOutProducts = computed(() => {
    return props.products.filter(p => p.stock <= 0);
});

const pendingOrdersCount = computed(() => {
    return props.orders.filter(o => o.status === 'pending' || o.status === 'To Pay').length;
});

const updateOrderStatus = (orderId: number, newStatus: string) => {
    if (confirm(`Update this order to: ${newStatus}?`)) {
        router.patch(`/seller/orders/${orderId}/status`, { status: newStatus }, { preserveScroll: true });
    }
};

const getStatusColor = (status: string) => {
    const s = status.toLowerCase();

    if (s.includes('pay') || s.includes('pending')) {
    return 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';
    }

    if (s.includes('ship')){
     return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
    }

    if (s.includes('receive')) {
    return 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400';
    }

    if (s.includes('complete')) {
        return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
    }

    return 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-300';
};
</script>

<template>
    <Head title="Seller Dashboard" />
    
    <div class="min-h-screen transition-colors duration-300 flex flex-col">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8"><Navbar /></div>

        <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
            
            <div class="mb-8">
                <h1 class="text-3xl font-black text-zinc-900 dark:text-white flex items-center gap-3">
                    <Store class="w-8 h-8 text-[#009933]" /> Seller Center
                </h1>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1 font-medium">Manage your storefront, products, and orders.</p>
            </div>

            <div v-if="!props.store" class="bg-zinc-50 dark:bg-zinc-900 rounded-3xl p-8 shadow-sm border border-zinc-200 dark:border-zinc-800 flex flex-col md:flex-row gap-12 items-start transition-colors">
                <div class="w-full md:w-1/2">
                    <h2 class="text-2xl font-black text-[#009933] mb-4">Set Up Your Storefront</h2>
                    <form @submit.prevent="submitStore" class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Store Name</label>
                            <input type="text" v-model="form.name" required class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Store Description</label>
                            <textarea v-model="form.description" required rows="4" class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white resize-none"></textarea>
                        </div>
                        <button type="submit" :disabled="form.processing" class="w-full bg-[#009933] text-white py-4 rounded-xl font-black text-lg hover:bg-green-700 transition-colors shadow-md disabled:bg-zinc-400 mt-2">
                            Open My Store
                        </button>
                    </form>
                </div>
            </div>

            <div v-else class="flex flex-col gap-8">
                
                <div class="bg-zinc-50 dark:bg-zinc-900 rounded-3xl p-6 shadow-sm border border-zinc-200 dark:border-zinc-800 flex flex-col md:flex-row md:items-center justify-between gap-6 transition-colors">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-[#009933] text-white rounded-2xl flex items-center justify-center text-3xl font-black shadow-md overflow-hidden shrink-0">
                            <img v-if="props.store.logo" :src="'/storage/' + props.store.logo" class="w-full h-full object-cover" />
                            <span v-else>{{ props.store.name.charAt(0) }}</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-zinc-900 dark:text-white">{{ props.store.name }}</h2>
                            <Link :href="`/shop/${props.store.id}`" class="text-sm text-zinc-500 hover:text-[#009933] dark:text-zinc-400 flex items-center gap-1 mt-1 font-medium transition-colors">
                                View Store Profile <ExternalLink class="w-3 h-3" />
                            </Link>
                        </div>
                    </div>
                    
                    <Link href="/seller/products/create" class="bg-[#009933] text-white px-6 py-3.5 rounded-xl font-bold hover:bg-green-700 transition-colors shadow-md flex items-center justify-center gap-2 shrink-0 active:scale-95">
                        <Plus class="w-5 h-5" /> Add New Product
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-zinc-50 dark:bg-zinc-900 rounded-3xl p-6 shadow-sm border border-zinc-200 dark:border-zinc-800 flex items-center gap-5 transition-colors">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-2xl"><Package class="w-8 h-8" /></div>
                        <div>
                            <h3 class="text-zinc-500 dark:text-zinc-400 font-bold text-sm">Active Products</h3>
                            <p class="text-3xl font-black text-zinc-900 dark:text-white">{{ activeProducts.length }}</p>
                        </div>
                    </div>
                    <div class="bg-zinc-50 dark:bg-zinc-900 rounded-3xl p-6 shadow-sm border border-zinc-200 dark:border-zinc-800 flex items-center gap-5 transition-colors">
                        <div class="p-4 bg-orange-50 dark:bg-orange-900/20 text-orange-500 dark:text-orange-400 rounded-2xl"><ShoppingBag class="w-8 h-8" /></div>
                        <div>
                            <h3 class="text-zinc-500 dark:text-zinc-400 font-bold text-sm">Pending Orders</h3>
                            <p class="text-3xl font-black text-zinc-900 dark:text-white">{{ pendingOrdersCount }}</p>
                        </div>
                    </div>
                    <div class="bg-zinc-50 dark:bg-zinc-900 rounded-3xl p-6 shadow-sm border border-zinc-200 dark:border-zinc-800 flex items-center gap-5 transition-colors">
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 text-[#009933] rounded-2xl"><TrendingUp class="w-8 h-8" /></div>
                        <div>
                            <h3 class="text-zinc-500 dark:text-zinc-400 font-bold text-sm">Total Sales</h3>
                            <p class="text-3xl font-black text-zinc-900 dark:text-white">₱0.00</p>
                        </div>
                    </div>
                </div>

                <div class="bg-zinc-50 dark:bg-zinc-900 rounded-3xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden transition-colors">
                    
                    <div class="flex border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/50">
                        <button @click="activeTab = 'products'" :class="activeTab === 'products' ? 'border-[#009933] text-[#009933] bg-green-50/50 dark:bg-green-900/10' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300'" class="flex-1 py-4 text-center font-black border-b-2 transition-all flex items-center justify-center gap-2">
                            Active Products <span class="bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 text-xs px-2 py-0.5 rounded-full">{{ activeProducts.length }}</span>
                        </button>
                        <button @click="activeTab = 'sold'" :class="activeTab === 'sold' ? 'border-red-500 text-red-600 bg-red-50/50 dark:bg-red-900/10' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300'" class="flex-1 py-4 text-center font-black border-b-2 transition-all flex items-center justify-center gap-2">
                            Sold Out <span v-if="soldOutProducts.length > 0" class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs px-2 py-0.5 rounded-full">{{ soldOutProducts.length }}</span>
                        </button>
                        <button @click="activeTab = 'orders'" :class="activeTab === 'orders' ? 'border-[#009933] text-[#009933] bg-green-50/50 dark:bg-green-900/10' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300'" class="flex-1 py-4 text-center font-black border-b-2 transition-all flex items-center justify-center gap-2">
                            Orders <span v-if="pendingOrdersCount > 0" class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ pendingOrdersCount }}</span>
                        </button>
                    </div>

                    <!-- ACTIVE PRODUCTS TAB -->
                    <div v-if="activeTab === 'products'">
                        <div v-if="activeProducts.length === 0" class="p-16 text-center">
                            <div class="w-24 h-24 bg-white dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-zinc-200 dark:border-zinc-700">
                                <Package class="w-10 h-10 text-zinc-400" />
                            </div>
                            <h3 class="text-xl font-bold text-zinc-800 dark:text-white mb-2">No active products</h3>
                        </div>

                        <div v-else class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-300 whitespace-nowrap">
                                <thead class="bg-white dark:bg-zinc-800/50 text-zinc-800 dark:text-zinc-200 uppercase text-xs font-black tracking-wider border-b border-zinc-200 dark:border-zinc-800">
                                    <tr>
                                        <th class="px-6 py-4">Product Info</th>
                                        <th class="px-6 py-4">Price</th>
                                        <th class="px-6 py-4">Stock</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                    <tr v-for="product in activeProducts" :key="product.id" class="hover:bg-white dark:hover:bg-zinc-800/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-xl bg-white dark:bg-zinc-800 overflow-hidden shrink-0 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                                                    <img v-if="product.image" :src="'/storage/' + product.image" class="w-full h-full object-cover" />
                                                </div>
                                                <div class="font-bold text-zinc-900 dark:text-white max-w-[200px] truncate">{{ product.title }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-[#009933]">{{ formatPrice(product.price) }}</td>
                                        <td class="px-6 py-4 font-bold">{{ product.stock }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <Link :href="`/seller/products/${product.id}/edit`" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg inline-block mr-2"><Edit class="w-4 h-4" /></Link>
                                            <button @click="deleteProduct(product.id)" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg"><Trash2 class="w-4 h-4" /></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- SOLD OUT / ARCHIVED TAB -->
                    <div v-if="activeTab === 'sold'">
                        <div v-if="soldOutProducts.length === 0" class="p-16 text-center">
                            <div class="w-24 h-24 bg-white dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-zinc-200 dark:border-zinc-700">
                                <CheckCircle2 class="w-10 h-10 text-green-500" />
                            </div>
                            <h3 class="text-xl font-bold text-zinc-800 dark:text-white mb-2">Inventory looks good!</h3>
                            <p class="text-zinc-500 dark:text-zinc-400">None of your products are currently sold out.</p>
                        </div>

                        <div v-else class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-300 whitespace-nowrap">
                                <thead class="bg-red-50 dark:bg-red-900/10 text-red-800 dark:text-red-300 uppercase text-xs font-black tracking-wider border-b border-zinc-200 dark:border-zinc-800">
                                    <tr>
                                        <th class="px-6 py-4">Product Info</th>
                                        <th class="px-6 py-4">Price</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                    <tr v-for="product in soldOutProducts" :key="product.id" class="bg-zinc-100/50 dark:bg-zinc-800/30 transition-colors">
                                        <td class="px-6 py-4 opacity-75">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-xl bg-white dark:bg-zinc-800 overflow-hidden shrink-0 border border-zinc-200 dark:border-zinc-700 shadow-sm grayscale">
                                                    <img v-if="product.image" :src="'/storage/' + product.image" class="w-full h-full object-cover" />
                                                </div>
                                                <div class="font-bold text-zinc-900 dark:text-white max-w-[200px] truncate line-through decoration-zinc-400">{{ product.title }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-zinc-500">{{ formatPrice(product.price) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="flex items-center gap-1.5 text-red-500 dark:text-red-400 font-black text-xs uppercase tracking-wider bg-red-100 dark:bg-red-900/30 px-3 py-1 rounded-full w-fit">
                                                <AlertCircle class="w-3.5 h-3.5" /> Sold Out
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <Link :href="`/seller/products/${product.id}/edit`" class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 px-4 py-2 text-zinc-700 dark:text-zinc-300 hover:text-[#009933] hover:border-[#009933] rounded-lg inline-block mr-2 font-bold text-xs transition-colors shadow-sm">
                                                Restock
                                            </Link>
                                            <button @click="deleteProduct(product.id)" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg"><Trash2 class="w-4 h-4" /></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ORDERS TAB -->
                    <div v-if="activeTab === 'orders'">
                        <div v-if="props.orders.length === 0" class="p-16 text-center">
                            <h3 class="text-xl font-bold text-zinc-800 dark:text-white mb-2">No orders yet</h3>
                            <p class="text-zinc-500 dark:text-zinc-400">When buyers purchase your items, they will appear here.</p>
                        </div>

                        <div v-else class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-300 whitespace-nowrap">
                                <thead class="bg-white dark:bg-zinc-800/50 text-zinc-800 dark:text-zinc-200 uppercase text-xs font-black tracking-wider border-b border-zinc-200 dark:border-zinc-800">
                                    <tr>
                                        <th class="px-6 py-4">Order ID / Buyer</th>
                                        <th class="px-6 py-4">Items</th>
                                        <th class="px-6 py-4">Total</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                    <tr v-for="order in props.orders" :key="order.id" class="hover:bg-white dark:hover:bg-zinc-800/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-zinc-900 dark:text-white">{{ order.tracking_number || `ORD-${order.id}` }}</div>
                                            <div class="text-xs text-zinc-500 mt-1">{{ order.user?.name || 'Guest Buyer' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-zinc-500 space-y-1">
                                                <div v-for="item in order.products" :key="item.id">
                                                    1x {{ item.title.substring(0, 20) }}...
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-[#009933]">{{ formatPrice(order.total_price) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider shadow-sm" :class="getStatusColor(order.status)">
                                                {{ order.status === 'pending' ? 'To Pay' : order.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button v-if="order.status === 'pending' || order.status === 'To Pay'" @click="updateOrderStatus(order.id, 'To Ship')" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-700 active:scale-95 transition-all shadow-sm">
                                                Prepare Shipment
                                            </button>
                                            
                                            <button v-else-if="order.status === 'To Ship'" @click="updateOrderStatus(order.id, 'To Receive')" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-700 active:scale-95 transition-all shadow-sm">
                                                Mark as Shipped
                                            </button>
                                            
                                            <span v-else-if="order.status === 'Completed'" class="text-[#009933] font-bold text-xs flex items-center justify-end gap-1"><CheckCircle2 class="w-4 h-4"/> Done</span>
                                            <span v-else class="text-zinc-400 text-xs italic">Waiting for Buyer</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { height: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 10px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; }
</style>