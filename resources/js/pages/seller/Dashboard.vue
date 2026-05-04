<script setup lang="ts">
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import { 
    Store, Package, TrendingUp, ShoppingBag, 
    Plus, Edit, Trash2, ExternalLink 
} from 'lucide-vue-next';

interface Product {
    id: number;
    title: string;
    price: number;
    stock: number;
    image: string | null;
    category?: { name: string };
    created_at: string;
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
}>();

const form = useForm({
    name: '',
    description: '',
});

const submitStore = () => {
    form.post('/seller/store');
};

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(price);
};

// --- DELETE LOGIC ---
const deleteProduct = (id: number) => {
    if (confirm('Are you sure you want to delete this product? This cannot be undone.')) {
        router.delete(`/seller/products/${id}`, {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Seller Dashboard" />
    
    <div class="min-h-screen bg-gray-50 dark:bg-zinc-950 transition-colors duration-300">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8">
            <Navbar />
        </div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            
            <div class="mb-8">
                <h1 class="text-3xl font-black text-zinc-900 dark:text-white flex items-center gap-3">
                    <Store class="w-8 h-8 text-[#009933]" />
                    Seller Center
                </h1>
                <p class="text-zinc-500 dark:text-zinc-400 mt-1 font-medium">Manage your storefront, products, and orders.</p>
            </div>

            <div v-if="!props.store" class="bg-white dark:bg-zinc-900 rounded-3xl p-8 shadow-sm border border-zinc-200 dark:border-zinc-800 flex flex-col md:flex-row gap-12 items-start">
                <div class="w-full md:w-1/2">
                    <h2 class="text-2xl font-black text-[#009933] mb-4">Set Up Your Storefront</h2>
                    <p class="text-zinc-600 dark:text-zinc-400 mb-8 leading-relaxed">
                        Before you can start selling equipment and products, you need to create your official storefront profile. This is what buyers will see when they view your items.
                    </p>

                    <form @submit.prevent="submitStore" class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Store Name</label>
                            <input 
                                id="name" 
                                type="text" 
                                v-model="form.name" 
                                required 
                                placeholder="e.g. Industrial Tech Supply Co." 
                                class="w-full rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:ring-2 focus:ring-[#009933]/20 focus:border-[#009933] outline-none transition-colors dark:text-white"
                            >
                            <span v-if="form.errors.name" class="text-red-500 text-xs font-bold mt-1.5 block">{{ form.errors.name }}</span>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Store Description</label>
                            <textarea 
                                id="description" 
                                v-model="form.description" 
                                required 
                                rows="4"
                                placeholder="Tell buyers what kind of products you sell..." 
                                class="w-full rounded-xl bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:ring-2 focus:ring-[#009933]/20 focus:border-[#009933] outline-none transition-colors resize-none dark:text-white"
                            ></textarea>
                            <span v-if="form.errors.description" class="text-red-500 text-xs font-bold mt-1.5 block">{{ form.errors.description }}</span>
                        </div>

                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full bg-[#009933] text-white py-4 rounded-xl font-black text-lg hover:bg-green-700 transition-colors shadow-md disabled:bg-zinc-400 mt-2 flex items-center justify-center gap-2"
                        >
                            <span v-if="form.processing">Creating Store...</span>
                            <span v-else>Open My Store</span>
                        </button>
                    </form>
                </div>
                
                <div class="w-full md:w-1/2 bg-green-50 dark:bg-green-900/10 rounded-3xl p-8 border border-green-100 dark:border-green-900/30 hidden md:block">
                    <h3 class="text-xl font-black text-zinc-800 dark:text-white mb-6">Why sell with us?</h3>
                    <ul class="space-y-5 text-zinc-600 dark:text-zinc-300 font-medium">
                        <li class="flex items-center gap-3"><span class="text-xl">🚀</span> Reach thousands of B2B and retail buyers.</li>
                        <li class="flex items-center gap-3"><span class="text-xl">🔒</span> Secure, standard database architecture.</li>
                        <li class="flex items-center gap-3"><span class="text-xl">⚙️</span> Easy-to-use product management tools.</li>
                    </ul>
                </div>
            </div>

            <div v-else class="flex flex-col gap-8">
                
                <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 md:p-8 shadow-sm border border-zinc-200 dark:border-zinc-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-[#009933] text-white rounded-2xl flex items-center justify-center text-3xl font-black shadow-md overflow-hidden shrink-0">
                            <img v-if="props.store.logo" :src="'/storage/' + props.store.logo" class="w-full h-full object-cover" />
                            <span v-else>{{ props.store.name.charAt(0) }}</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-3">
                                <h2 class="text-2xl font-black text-zinc-900 dark:text-white">{{ props.store.name }}</h2>
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider" :class="props.store.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'">
                                    {{ props.store.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <Link :href="`/shop/${props.store.id}`" class="text-sm text-zinc-500 hover:text-[#009933] dark:text-zinc-400 flex items-center gap-1 mt-1 font-medium transition-colors">
                                View Store Profile <ExternalLink class="w-3 h-3" />
                            </Link>
                        </div>
                    </div>
                    
                    <Link 
                        href="/seller/products/create" 
                        class="bg-[#009933] text-white px-6 py-3.5 rounded-xl font-bold hover:bg-green-700 transition-colors shadow-md flex items-center justify-center gap-2 shrink-0 active:scale-95"
                    >
                        <Plus class="w-5 h-5" /> Add New Product
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-sm border border-zinc-200 dark:border-zinc-800 flex items-center gap-5">
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-2xl">
                            <Package class="w-8 h-8" />
                        </div>
                        <div>
                            <h3 class="text-zinc-500 dark:text-zinc-400 font-bold text-sm">Total Products</h3>
                            <p class="text-3xl font-black text-zinc-900 dark:text-white">{{ props.products.length }}</p>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-sm border border-zinc-200 dark:border-zinc-800 flex items-center gap-5">
                        <div class="p-4 bg-orange-50 dark:bg-orange-900/20 text-orange-500 dark:text-orange-400 rounded-2xl">
                            <ShoppingBag class="w-8 h-8" />
                        </div>
                        <div>
                            <h3 class="text-zinc-500 dark:text-zinc-400 font-bold text-sm">Pending Orders</h3>
                            <p class="text-3xl font-black text-zinc-900 dark:text-white">0</p>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 shadow-sm border border-zinc-200 dark:border-zinc-800 flex items-center gap-5">
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 text-[#009933] rounded-2xl">
                            <TrendingUp class="w-8 h-8" />
                        </div>
                        <div>
                            <h3 class="text-zinc-500 dark:text-zinc-400 font-bold text-sm">Total Sales</h3>
                            <p class="text-3xl font-black text-zinc-900 dark:text-white">₱0.00</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    <div class="p-6 border-b border-zinc-200 dark:border-zinc-800">
                        <h2 class="text-xl font-black text-zinc-900 dark:text-white">Product Catalog</h2>
                    </div>

                    <div v-if="props.products.length === 0" class="p-16 text-center">
                        <div class="w-24 h-24 bg-zinc-50 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <Package class="w-10 h-10 text-zinc-400" />
                        </div>
                        <h3 class="text-xl font-bold text-zinc-800 dark:text-white mb-2">No products listed yet</h3>
                        <p class="text-zinc-500 dark:text-zinc-400 mb-6">Your catalog is empty. Start adding products to reach buyers.</p>
                        <Link href="/seller/products/create" class="text-[#009933] font-bold hover:underline">
                            + Add your first product
                        </Link>
                    </div>

                    <div v-else class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-sm text-zinc-600 dark:text-zinc-300 whitespace-nowrap">
                            <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-800 dark:text-zinc-200 uppercase text-xs font-black tracking-wider border-b border-zinc-200 dark:border-zinc-800">
                                <tr>
                                    <th class="px-6 py-4">Product Info</th>
                                    <th class="px-6 py-4">Category</th>
                                    <th class="px-6 py-4">Price</th>
                                    <th class="px-6 py-4">Stock</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                                <tr v-for="product in props.products" :key="product.id" class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 overflow-hidden shrink-0 flex items-center justify-center">
                                                <img v-if="product.image" :src="'/storage/' + product.image" class="w-full h-full object-cover" />
                                                <Package v-else class="w-6 h-6 text-zinc-400" />
                                            </div>
                                            <div class="font-bold text-zinc-900 dark:text-white max-w-[200px] truncate">
                                                {{ product.title }}
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 rounded-md text-xs font-bold">
                                            {{ product.category?.name || 'Uncategorized' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 font-bold text-[#009933]">
                                        {{ formatPrice(product.price) }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span :class="product.stock > 0 ? 'text-zinc-900 dark:text-zinc-100 font-bold' : 'text-red-500 font-bold'">
                                            {{ product.stock > 0 ? product.stock : 'Out of Stock' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-3">
                                            
                                            <Link :href="`/seller/products/${product.id}/edit`" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors inline-block">
                                                <Edit class="w-4 h-4" />
                                            </Link>

                                            <button @click="deleteProduct(product.id)" type="button" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                <Trash2 class="w-4 h-4" />
                                            </button>

                                        </div>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
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