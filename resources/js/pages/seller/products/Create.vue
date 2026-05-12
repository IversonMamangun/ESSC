<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft, Package, Plus, X, CheckCircle2 } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

defineProps<{
    categories: Array<{ id: number; name: string }>;
}>();

const form = useForm({
    title: '',
    category_id: '',
    price: '',
    discount_price: '',
    stock: '',
    description: '',
    is_top_deal: false, 
    images: [] as File[],
});

const imagePreviews = ref<string[]>([]);

const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;

    if (target.files && target.files.length > 0) {
        const newFiles = Array.from(target.files);
        
        newFiles.forEach(file => {
            form.images.push(file);
            imagePreviews.value.push(URL.createObjectURL(file));
        });
    }

    target.value = '';
};

const removeImage = (index: number) => {
    form.images.splice(index, 1);
    imagePreviews.value.splice(index, 1);
};

const submit = () => {
    if (form.images.length === 0) {
        alert("Please upload at least one image of your product.");
        return;
    }

    form.post('/seller/products', {
        forceFormData: true, 
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Add New Product" />

    <div class="min-h-screen py-10 transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-zinc-900 dark:text-white flex items-center gap-2">
                        <Package class="w-8 h-8 text-[#009933]" />
                        Add New Product
                    </h1>
                    <p class="text-zinc-500 dark:text-zinc-400 mt-1 font-medium">Fill in the details to list your item.</p>
                </div>
                <Link href="/seller/dashboard" class="inline-flex items-center text-sm font-bold text-zinc-500 hover:text-[#009933] dark:text-zinc-400 transition-colors">
                    <ArrowLeft class="w-4 h-4 mr-1" />
                    Back to Dashboard
                </Link>
            </div>

            <div class="bg-zinc-50 dark:bg-zinc-900 rounded-3xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden transition-colors">
                <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-8">
                    
                    <!-- Image Upload Section -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <Label class="dark:text-zinc-300 flex items-center gap-2 font-bold">
                                Product Images 
                                <span class="text-xs font-bold text-red-500 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded-full shadow-sm">(Min. 1 Required)</span>
                            </Label>
                            <span class="text-xs text-zinc-500 font-bold">{{ form.images.length }} / 5 images</span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                            <div v-for="(src, index) in imagePreviews" :key="index" class="relative aspect-square rounded-xl border-2 border-zinc-200 dark:border-zinc-800 overflow-hidden group shadow-sm bg-white dark:bg-zinc-800">
                                <img :src="src" class="w-full h-full object-cover" />
                                <button 
                                    type="button" 
                                    @click="removeImage(index)"
                                    class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow-lg"
                                >
                                    <X class="w-3 h-3" />
                                </button>
                                <div v-if="index === 0" class="absolute bottom-0 left-0 right-0 bg-[#009933] text-white text-[10px] text-center py-0.5 font-black uppercase">
                                    Cover Photo
                                </div>
                            </div>

                            <label 
                                v-if="form.images.length < 5"
                                for="multi-image" 
                                class="aspect-square flex flex-col items-center justify-center border-2 border-dashed border-zinc-300 dark:border-zinc-700 rounded-xl hover:border-[#009933] dark:hover:border-green-500 hover:bg-green-50 dark:hover:bg-zinc-800 transition-all cursor-pointer group bg-white dark:bg-zinc-800"
                            >
                                <Plus class="w-6 h-6 text-zinc-400 group-hover:text-[#009933] transition-colors" />
                                <span class="text-[10px] mt-1 font-black uppercase text-zinc-400 group-hover:text-[#009933]">Add Image</span>
                                <input id="multi-image" type="file" class="hidden" multiple @change="handleImageUpload" accept="image/*" />
                            </label>
                        </div>
                        <InputError :message="form.errors.images" class="mt-2" />
                    </div>

                    <hr class="border-zinc-200 dark:border-zinc-800" />

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        
                        <div class="sm:col-span-2 md:col-span-4">
                            <Label for="title" class="dark:text-zinc-300 font-bold">Product Title</Label>
                            <Input id="title" v-model="form.title" class="mt-1.5 bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 rounded-xl" placeholder="What are you selling?" required />
                            <InputError :message="form.errors.title" class="mt-2" />
                        </div>

                        <div>
                            <Label for="category_id" class="dark:text-zinc-300 font-bold">Category</Label>
                            <select id="category_id" v-model="form.category_id" class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white dark:bg-zinc-800 dark:border-zinc-700 px-3 h-10 text-sm dark:text-zinc-200 focus:ring-[#009933] focus:border-[#009933] outline-none transition-colors" required>
                                <option value="" disabled>Select a category</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                            <InputError :message="form.errors.category_id" class="mt-2" />
                        </div>

                        <div>
                            <Label for="price" class="dark:text-zinc-300 font-bold">Price (₱)</Label>
                            <Input id="price" type="number" step="0.01" min="0" v-model="form.price" class="mt-1.5 bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 rounded-xl" placeholder="0.00" required />
                            <InputError :message="form.errors.price" class="mt-2" />
                        </div>

                        <div>
                            <Label for="discount_price" class="dark:text-zinc-300 font-bold flex items-center gap-1">
                                Discount Price <span class="text-[10px] font-normal text-zinc-400 bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded">Optional</span>
                            </Label>
                            <Input id="discount_price" type="number" step="0.01" min="0" v-model="form.discount_price" class="mt-1.5 bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 rounded-xl" placeholder="Leave blank if none" />
                            <InputError :message="form.errors.discount_price" class="mt-2" />
                        </div>

                        <div>
                            <Label for="stock" class="dark:text-zinc-300 font-bold">Available Stock</Label>
                            <Input id="stock" type="number" min="0" v-model="form.stock" class="mt-1.5 bg-white dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 rounded-xl" placeholder="0" required />
                            <InputError :message="form.errors.stock" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2 md:col-span-4 mt-2">
                            <label for="top_deal" class="flex items-start gap-3 bg-white dark:bg-zinc-800/50 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700 cursor-pointer hover:border-[#009933] dark:hover:border-[#009933] transition-colors group">
                                <div class="mt-0.5">
                                    <input 
                                        type="checkbox" 
                                        id="top_deal" 
                                        v-model="form.is_top_deal"
                                        class="w-5 h-5 text-[#009933] rounded focus:ring-[#009933] border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 cursor-pointer transition-colors"
                                    >
                                </div>
                                <div class="flex flex-col select-none">
                                    <span class="text-sm font-bold text-zinc-900 dark:text-white group-hover:text-[#009933] transition-colors">
                                        Feature as a Top Deal
                                    </span>
                                    <span class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                                        Checking this box will highlight this product and place it directly on the main store homepage carousel.
                                    </span>
                                </div>
                            </label>
                            <InputError :message="form.errors.is_top_deal" class="mt-2" />
                        </div>

                        <div class="sm:col-span-2 md:col-span-4">
                            <Label for="description" class="dark:text-zinc-300 font-bold">Description</Label>
                            <textarea id="description" v-model="form.description" rows="5" class="mt-1.5 w-full rounded-xl border border-zinc-200 bg-white dark:bg-zinc-800 dark:border-zinc-700 px-3 py-2 text-sm dark:text-zinc-200 focus:ring-2 focus:ring-[#009933]/20 focus:border-[#009933] outline-none transition-colors resize-none" placeholder="Provide details about your product..." required></textarea>
                            <InputError :message="form.errors.description" class="mt-2" />
                        </div>
                    </div>

                    <div class="pt-6 border-t border-zinc-200 dark:border-zinc-800 flex justify-end">
                        <Button 
                            type="submit" 
                            class="w-full sm:w-auto bg-[#009933] hover:bg-green-700 text-white font-black h-12 px-10 rounded-xl transition-all active:scale-95 shadow-md flex items-center justify-center gap-2"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Publishing...</span>
                            <span v-else class="flex items-center gap-2">
                                <CheckCircle2 class="w-5 h-5" /> Publish Product
                            </span>
                        </Button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>