<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

const props = defineProps<{
    categories: Array<{ id: number; name: string }>;
}>();

const form = useForm({
    title: '',
    category_id: '',
    price: '',
    stock: '',
    description: '',
});

const submitProduct = () => {
    form.post('/seller/products');
};
</script>

<template>
    <Head title="Add Product" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>

    <main class="flex w-full justify-center px-4 mt-8 mb-24">
        <div class="w-full max-w-3xl flex flex-col gap-6">
            
            <div class="flex items-center gap-4">
                <Link href="/seller/dashboard" class="text-gray-400 hover:text-[#009933] text-xl font-bold">←</Link>
                <div>
                    <h1 class="text-2xl font-black text-gray-800">Add New Product</h1>
                    <p class="text-gray-500 text-sm mt-1">List a new item in your store.</p>
                </div>
            </div>

            <form @submit.prevent="submitProduct" class="bg-white rounded-2xl p-8 shadow-sm border border-neutral-100 flex flex-col gap-6">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Product Title</label>
                    <input type="text" v-model="form.title" required placeholder="e.g. Heavy Duty Drill" class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none transition-colors">
                    <span v-if="form.errors.title" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.title }}</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Price (₱)</label>
                        <input type="number" step="0.01" v-model="form.price" required placeholder="0.00" class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none transition-colors">
                        <span v-if="form.errors.price" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.price }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Available Stock</label>
                        <input type="number" v-model="form.stock" required placeholder="10" class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none transition-colors">
                        <span v-if="form.errors.stock" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.stock }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Category</label>
                    <select v-model="form.category_id" required class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none transition-colors bg-white">
                        <option value="" disabled>Select a category...</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                    <span v-if="form.errors.category_id" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.category_id }}</span>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                    <textarea v-model="form.description" required rows="5" placeholder="Describe the item's features and specifications..." class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none transition-colors resize-none"></textarea>
                    <span v-if="form.errors.description" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.description }}</span>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" :disabled="form.processing" class="bg-[#009933] text-white px-8 py-3 rounded-xl font-bold hover:bg-green-700 transition-colors shadow-sm disabled:bg-gray-400">
                        <span v-if="form.processing">Publishing...</span>
                        <span v-else>Publish Product</span>
                    </button>
                </div>
            </form>

        </div>
    </main>
</template>