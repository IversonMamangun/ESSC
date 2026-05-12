<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ChevronLeft, Save, Image as ImageIcon, Video, X } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

const props = defineProps<{
    product: {
        id: number;
        title: string;
        description: string;
        price: number;
        discount_price: number | null;
        is_top_deal: boolean | number;
        stock: number;
        image: string | null;
        video: string | null;
        category_id: number | null;
    };
    categories: Array<{ id: number; name: string }>;
}>();

// Display existing media
const imagePreview = ref<string | null>(props.product.image ? `/storage/${props.product.image}` : null);
const videoPreview = ref<string | null>(props.product.video ? `/storage/${props.product.video}` : null);

// Initialize form
const form = useForm({
    title: props.product.title,
    description: props.product.description,
    price: props.product.price,
    discount_price: props.product.discount_price || '',
    is_top_deal: !!props.product.is_top_deal, // Force to boolean true/false
    stock: props.product.stock,
    category_id: props.product.category_id || '',
    image: null as File | null,
    video: null as File | null,
});

// Real-time Discount Math
const discountPreview = computed(() => {
    const p = parseFloat(form.price as any);
    const d = parseFloat(form.discount_price as any);
    if (!p || !d || p <= 0 || d >= p) return null;
    return Math.round(((p - d) / p) * 100);
});

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const handleVideoUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (!file) return;

    const videoElement = document.createElement('video');
    videoElement.preload = 'metadata';
    
    videoElement.onloadedmetadata = function() {
        window.URL.revokeObjectURL(videoElement.src);
        if (videoElement.duration > 61) {
            alert(`Video is too long! Maximum length is 1 minute. Your video is ${Math.round(videoElement.duration)} seconds.`);
            target.value = ''; 
            return;
        }
        form.video = file;
        videoPreview.value = URL.createObjectURL(file);
    }
    videoElement.src = URL.createObjectURL(file);
};

const updateProduct = () => {
    form.post(`/seller/products/${props.product.id}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Product" />
    
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

            <div class="bg-zinc-50 dark:bg-zinc-900 rounded-3xl p-8 shadow-sm border border-zinc-200 dark:border-zinc-800 transition-colors">
                <form @submit.prevent="updateProduct" class="space-y-8">
                    
                    <!-- Media Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Product Image (Optional to change)</label>
                            <div class="flex items-center gap-4">
                                <div class="w-32 h-32 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-700 flex items-center justify-center overflow-hidden bg-white dark:bg-zinc-800 shrink-0 shadow-sm">
                                    <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover">
                                    <ImageIcon v-else class="w-8 h-8 text-zinc-400" />
                                </div>
                                <input type="file" @change="handleImageUpload" accept="image/*" class="text-sm font-bold text-zinc-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-green-50 file:text-[#009933] hover:file:bg-green-100 cursor-pointer shadow-sm transition-colors">
                            </div>
                            <span v-if="form.errors.image" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.image }}</span>
                        </div>

                        <!-- Video Upload -->
                        <div>
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Product Video (Optional to change)</label>
                            <div class="flex items-center gap-4">
                                <div class="w-32 h-32 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-700 flex items-center justify-center overflow-hidden bg-black shrink-0 shadow-sm relative group">
                                    <video v-if="videoPreview" :src="videoPreview" class="w-full h-full object-cover"></video>
                                    <Video v-else class="w-8 h-8 text-zinc-400" />
                                </div>
                                <div class="flex flex-col gap-1">
                                    <input type="file" @change="handleVideoUpload" accept="video/mp4,video/webm,video/quicktime" class="text-sm font-bold text-zinc-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer shadow-sm transition-colors">
                                    <span class="text-xs text-zinc-400 font-medium">Max 1 Min (MP4, WebM)</span>
                                </div>
                            </div>
                            <span v-if="form.errors.video" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.video }}</span>
                        </div>
                    </div>

                    <hr class="border-zinc-200 dark:border-zinc-800" />

                    <!-- Product Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 relative">
                        <div class="sm:col-span-2 md:col-span-4">
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Product Title</label>
                            <input type="text" v-model="form.title" required class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white shadow-sm">
                            <span v-if="form.errors.title" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.title }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Category</label>
                            <select v-model="form.category_id" required class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white shadow-sm">
                                <option value="" disabled>Select Category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                            </select>
                            <span v-if="form.errors.category_id" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.category_id }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Price (₱)</label>
                            <input type="number" step="0.01" min="0" v-model="form.price" required class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white shadow-sm">
                            <span v-if="form.errors.price" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.price }}</span>
                        </div>

                        <!-- Real-time Computed Discount Field -->
                        <div class="relative">
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Discount Price</label>
                            <div class="relative">
                                <input type="number" step="0.01" min="0" v-model="form.discount_price" class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 pr-16 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white shadow-sm" placeholder="Blank if none">
                                <div v-if="discountPreview" class="absolute right-2 top-[50%] translate-y-[-50%] flex items-center bg-red-100 text-red-600 text-xs font-black px-2 py-0.5 rounded">
                                    -{{ discountPreview }}%
                                </div>
                            </div>
                            <span v-if="form.errors.discount_price" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.discount_price }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Stock Quantity</label>
                            <input type="number" v-model="form.stock" required class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white shadow-sm">
                            <span v-if="form.errors.stock" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.stock }}</span>
                        </div>

                        <div class="sm:col-span-2 md:col-span-4 mt-2">
                            <label for="top_deal" class="flex items-start gap-3 bg-white dark:bg-zinc-800/50 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 cursor-pointer hover:border-[#009933] transition-colors group">
                                <div class="mt-0.5">
                                    <input type="checkbox" id="top_deal" v-model="form.is_top_deal" class="w-5 h-5 text-[#009933] rounded focus:ring-[#009933] border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 cursor-pointer transition-colors">
                                </div>
                                <div class="flex flex-col select-none">
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white group-hover:text-[#009933] transition-colors">Feature as a Top Deal</span>
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Checking this box will place it directly on the main store homepage carousel.</span>
                                </div>
                            </label>
                            <span v-if="form.errors.is_top_deal" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.is_top_deal }}</span>
                        </div>

                        <div class="sm:col-span-2 md:col-span-4">
                            <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-1.5">Description</label>
                            <textarea v-model="form.description" required rows="4" class="w-full rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-3.5 focus:border-[#009933] outline-none transition-colors text-zinc-900 dark:text-white resize-none shadow-sm"></textarea>
                            <span v-if="form.errors.description" class="text-red-500 text-xs font-bold mt-1">{{ form.errors.description }}</span>
                        </div>
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