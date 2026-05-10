<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ChevronLeft, Save, Image as ImageIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

const props = defineProps<{
    product: {
        id: number;
        title: string;
        description: string;
        price: number;
        stock: number;
        image: string | null;
        category_id: number | null;
    };
    categories: Array<{ id: number; name: string }>;
}>();

// Display the existing image if they have one
const imagePreview = ref<string | null>(props.product.image ? `/storage/${props.product.image}` : null);

// Initialize form with the database data
const form = useForm({
    title: props.product.title,
    description: props.product.description,
    price: props.product.price,
    stock: props.product.stock,
    category_id: props.product.category_id || '',
    image: null as File | null,
});

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    
    if (target.files && target.files[0]) {
        const file = target.files[0];
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const updateProduct = () => {
    // We use .post() here because we updated your web.php route to Route::post
    // This allows Inertia to successfully upload the new image file!
    form.post(`/seller/products/${props.product.id}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Product" />
    
    <!-- REMOVED bg-zinc-50 dark:bg-zinc-950 to use your default app background -->
    <div class="min-h-screen transition-colors duration-300 flex flex-col">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8">
            <Navbar />
        </div>

        <main class="flex-grow max-w-4xl mx-auto w-full px-4 sm:px-6 py-10">
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-zinc-900 dark:text-white">Edit Product</h1>
                    <p class="text-zinc-500 dark:text-zinc-400 mt-1 font-medium">Update your product details and inventory.</p>
                </div>
                <Link href="/seller/dashboard" class="flex items-center text-sm font-bold text-zinc-500 hover:text-[#009933] transition-colors">
                    <ChevronLeft class="w-4 h-4 mr-1" /> Back to Dashboard
                </Link>
            </div>

            <!-- Set Card to zinc-50 -->
            <div class="bg-zinc-50 dark:bg-zinc-900 rounded-3xl p-8 shadow-sm border border-zinc-200 dark:border-zinc-800 transition-colors">
                <form @submit.prevent="updateProduct" class="space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Product Title</label>
                            <input type="text" v-model="form.title" required class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white shadow-sm">
                            <span v-if="form.errors.title" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.title }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Category</label>
                            <select v-model="form.category_id" required class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white shadow-sm">
                                <option value="" disabled>Select Category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                            <span v-if="form.errors.category_id" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.category_id }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Price (PHP)</label>
                            <input type="number" step="0.01" v-model="form.price" required class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white shadow-sm">
                            <span v-if="form.errors.price" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.price }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Stock Quantity</label>
                            <input type="number" v-model="form.stock" required class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white shadow-sm">
                            <span v-if="form.errors.stock" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.stock }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Description</label>
                        <textarea v-model="form.description" required rows="4" class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white resize-none shadow-sm"></textarea>
                        <span v-if="form.errors.description" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.description }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Product Image <span class="font-normal text-zinc-500">(Optional: Only upload to change the current image)</span></label>
                        <div class="flex items-center gap-4">
                            <div class="w-24 h-24 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-700 flex items-center justify-center overflow-hidden bg-white dark:bg-zinc-800 shrink-0 shadow-sm">
                                <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover">
                                <ImageIcon v-else class="w-8 h-8 text-zinc-400" />
                            </div>
                            <input type="file" @change="handleImageUpload" accept="image/*" class="text-sm font-bold text-zinc-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-green-50 file:text-[#009933] hover:file:bg-green-100 cursor-pointer shadow-sm transition-colors">
                        </div>
                        <span v-if="form.errors.image" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.image }}</span>
                    </div>

                    <div class="pt-6 border-t border-zinc-200 dark:border-zinc-800 flex justify-end">
                        <button type="submit" :disabled="form.processing" class="bg-[#009933] text-white px-8 py-3.5 rounded-xl font-black hover:bg-green-700 transition-colors shadow-md flex items-center justify-center gap-2 disabled:bg-zinc-400 active:scale-95">
                            <span v-if="form.processing">Updating...</span>
                            <span v-else class="flex items-center gap-2"><Save class="w-5 h-5" /> Save Changes</span>
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>
</template>