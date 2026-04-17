<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

const props = defineProps<{
    store: {
        id: number;
        name: string;
        description: string;
        is_active: boolean;
    } | null;
}>();

// Form for creating the store if it doesn't exist yet
const form = useForm({
    name: '',
    description: '',
});

const submitStore = () => {
    form.post('/seller/store');
};
</script>

<template>
    <Head title="Seller Dashboard" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>

    <main class="flex w-full justify-center px-4 mt-8 mb-24">
        <div class="w-full max-w-7xl flex flex-col gap-8">
            
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-800">Seller Dashboard</h1>
                    <p class="text-gray-500 mt-1">Manage your storefront and products.</p>
                </div>
            </div>

            <div v-if="!props.store" class="bg-white rounded-2xl p-8 shadow-sm border border-neutral-100 flex flex-col md:flex-row gap-12 items-start">
                <div class="w-full md:w-1/2">
                    <h2 class="text-2xl font-black text-[#009933] mb-4">Set Up Your Storefront</h2>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        Before you can start selling industrial equipment and products, you need to create your official storefront profile. This is what buyers will see when they view your items.
                    </p>

                    <form @submit.prevent="submitStore" class="flex flex-col gap-5">
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Store Name</label>
                            <input 
                                id="name" 
                                type="text" 
                                v-model="form.name" 
                                required 
                                placeholder="e.g. Industrial Tech Supply Co." 
                                class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none transition-colors"
                            >
                            <span v-if="form.errors.name" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.name }}</span>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-gray-700 mb-1">Store Description</label>
                            <textarea 
                                id="description" 
                                v-model="form.description" 
                                required 
                                rows="4"
                                placeholder="Tell buyers what kind of products you sell..." 
                                class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none transition-colors resize-none"
                            ></textarea>
                            <span v-if="form.errors.description" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.description }}</span>
                        </div>

                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full bg-[#009933] text-white py-3.5 rounded-xl font-bold text-lg hover:bg-green-700 transition-colors shadow-md disabled:bg-gray-400 mt-2 flex items-center justify-center gap-2"
                        >
                            <span v-if="form.processing">Creating Store...</span>
                            <span v-else>Open My Store</span>
                        </button>
                    </form>
                </div>
                
                <div class="w-full md:w-1/2 bg-green-50 rounded-2xl p-8 border border-green-100 hidden md:block">
                    <h3 class="text-lg font-black text-gray-800 mb-4">Why sell with us?</h3>
                    <ul class="space-y-4 text-gray-600">
                        <li class="flex items-center gap-3">✅ Reach thousands of B2B and retail buyers.</li>
                        <li class="flex items-center gap-3">✅ Secure, standard database architecture.</li>
                        <li class="flex items-center gap-3">✅ Easy-to-use product management tools.</li>
                    </ul>
                </div>
            </div>

            <div v-else class="flex flex-col gap-8">
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-100 flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-[#009933] text-white rounded-xl flex items-center justify-center text-2xl font-black shadow-sm">
                            {{ props.store.name.charAt(0) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-800">{{ props.store.name }}</h2>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold mt-1" :class="props.store.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                {{ props.store.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                <Link 
                    href="/seller/products/create" 
                    class="bg-[#009933] text-white px-6 py-3 rounded-xl font-bold hover:bg-green-700 transition-colors shadow-sm inline-flex items-center justify-center"
                >
                    + Add New Product
                </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-100">
                        <h3 class="text-gray-500 font-bold text-sm mb-2">Total Products</h3>
                        <p class="text-3xl font-black text-gray-800">0</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-100">
                        <h3 class="text-gray-500 font-bold text-sm mb-2">Pending Orders</h3>
                        <p class="text-3xl font-black text-orange-500">0</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-100">
                        <h3 class="text-gray-500 font-bold text-sm mb-2">Total Sales</h3>
                        <p class="text-3xl font-black text-[#009933]">₱0.00</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-neutral-100">
                    <div class="text-6xl mb-4">📦</div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">No products yet</h2>
                    <p class="text-gray-500 mb-6">You haven't listed any items for sale. Start adding products to your catalog.</p>
                </div>

            </div>

        </div>
    </main>
</template>