<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ArrowLeft, Upload, Package, Info, Plus, X, Image as ImageIcon } from 'lucide-vue-next';

defineProps<{
    categories: Array<{ id: number; name: string }>;
}>();

const form = useForm({
    title: '',
    category_id: '',
    price: '',
    stock: '',
    description: '',
    // Changed to an array
    images: [] as File[],
});

// For multiple live previews
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
    // Reset input so the same file can be picked again if deleted
    target.value = '';
};

const removeImage = (index: number) => {
    form.images.splice(index, 1);
    imagePreviews.value.splice(index, 1);
};

const submit = () => {
    // Check if at least one image is uploaded
    if (form.images.length === 0) {
        alert("Please upload at least one image of your product.");
        return;
    }

    form.post('/seller/products', {
        forceFormData: true, // Necessary for arrays of files
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Add New Product" />

    <div class="min-h-screen bg-gray-50 dark:bg-neutral-950 py-10 transition-colors duration-300">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <Package class="w-8 h-8 text-[#009933]" />
                        Add New Product
                    </h1>
                    <p class="text-gray-500 dark:text-neutral-400 mt-1">Fill in the details to list your item.</p>
                </div>
                <Link href="/seller/dashboard" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#009933] dark:text-neutral-400 transition-colors">
                    <ArrowLeft class="w-4 h-4 mr-1" />
                    Back to Dashboard
                </Link>
            </div>

            <div class="bg-white dark:bg-neutral-900 rounded-2xl shadow-sm border border-gray-100 dark:border-neutral-800 overflow-hidden">
                <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-8">
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <Label class="dark:text-neutral-300 flex items-center gap-2">
                                Product Images 
                                <span class="text-xs font-normal text-red-500 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded-full">(Min. 1 Required)</span>
                            </Label>
                            <span class="text-xs text-gray-400">{{ form.images.length }} / 5 images</span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                            <div v-for="(src, index) in imagePreviews" :key="index" class="relative aspect-square rounded-xl border-2 border-gray-100 dark:border-neutral-800 overflow-hidden group">
                                <img :src="src" class="w-full h-full object-cover" />
                                <button 
                                    type="button" 
                                    @click="removeImage(index)"
                                    class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shadow-lg"
                                >
                                    <X class="w-3 h-3" />
                                </button>
                                <div v-if="index === 0" class="absolute bottom-0 left-0 right-0 bg-[#009933] text-white text-[10px] text-center py-0.5 font-bold uppercase">
                                    Cover Photo
                                </div>
                            </div>

                            <label 
                                v-if="form.images.length < 5"
                                for="multi-image" 
                                class="aspect-square flex flex-col items-center justify-center border-2 border-dashed border-gray-300 dark:border-neutral-700 rounded-xl hover:border-[#009933] dark:hover:border-green-500 hover:bg-green-50 dark:hover:bg-neutral-800 transition-all cursor-pointer group"
                            >
                                <Plus class="w-6 h-6 text-gray-400 group-hover:text-[#009933] transition-colors" />
                                <span class="text-[10px] mt-1 font-bold text-gray-400 group-hover:text-[#009933]">Add Image</span>
                                <input id="multi-image" type="file" class="hidden" multiple @change="handleImageUpload" accept="image/*" />
                            </label>
                        </div>
                        <InputError :message="form.errors.images" class="mt-2" />
                    </div>

                    <hr class="border-gray-100 dark:border-neutral-800" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <Label for="title" class="dark:text-neutral-300">Product Title</Label>
                            <Input id="title" v-model="form.title" class="mt-1.5 dark:bg-neutral-800 dark:border-neutral-700" placeholder="What are you selling?" required />
                        </div>

                        <div>
                            <Label for="category_id" class="dark:text-neutral-300">Category</Label>
                            <select id="category_id" v-model="form.category_id" class="mt-1.5 w-full rounded-xl border border-input bg-background dark:bg-neutral-800 dark:border-neutral-700 px-3 h-10 text-sm dark:text-neutral-200" required>
                                <option value="" disabled>Select a category</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label for="price" class="dark:text-neutral-300">Price (₱)</Label>
                                <Input id="price" type="number" v-model="form.price" class="mt-1.5 dark:bg-neutral-800 dark:border-neutral-700" placeholder="0.00" required />
                            </div>
                            <div>
                                <Label for="stock" class="dark:text-neutral-300">Stock</Label>
                                <Input id="stock" type="number" v-model="form.stock" class="mt-1.5 dark:bg-neutral-800 dark:border-neutral-700" placeholder="0" required />
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <Label for="description" class="dark:text-neutral-300">Description</Label>
                            <textarea id="description" v-model="form.description" rows="4" class="mt-1.5 w-full rounded-xl border border-input bg-background dark:bg-neutral-800 dark:border-neutral-700 px-3 py-2 text-sm dark:text-neutral-200" placeholder="Provide details about your product..." required></textarea>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 dark:border-neutral-800 flex justify-end">
                        <Button 
                            type="submit" 
                            class="w-full sm:w-auto bg-[#009933] hover:bg-green-700 text-white font-bold h-12 px-10 rounded-xl transition-all active:scale-95"
                            :disabled="form.processing"
                        >
                            {{ form.processing ? 'Publishing...' : 'Publish Product' }}
                        </Button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>