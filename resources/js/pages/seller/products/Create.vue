<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

// Accept the categories passed from the Controller
defineProps<{
    categories: Array<{ id: number; name: string }>;
}>();

// Set up the form with a null value for the image
const form = useForm({
    title: '',
    category_id: '',
    price: '',
    stock: '',
    description: '',
    image: null as File | null,
});

// Handle the file selection and attach it to the form
const handleImageUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    
    if (target.files && target.files.length > 0) {
        form.image = target.files[0];
    }
};

const submit = () => {
    form.post('/seller/products', {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Add New Product" />

    <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
            <Link href="/seller/dashboard" class="text-sm text-gray-500 hover:text-gray-700">
                &larr; Back to Dashboard
            </Link>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
            <form @submit.prevent="submit" class="space-y-6">
                
                <div>
                    <Label for="title">Product Title</Label>
                    <Input id="title" type="text" v-model="form.title" class="mt-1" required placeholder="e.g. Mechanical Keyboard" />
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <Label for="category_id">Category</Label>
                        <select 
                            id="category_id" 
                            v-model="form.category_id" 
                            class="mt-1 flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            required
                        >
                            <option value="" disabled>Select a category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.category_id" class="mt-2" />
                    </div>

                    <div>
                        <Label for="stock">Available Stock</Label>
                        <Input id="stock" type="number" min="0" v-model="form.stock" class="mt-1" required />
                        <InputError :message="form.errors.stock" class="mt-2" />
                    </div>
                </div>

                <div>
                    <Label for="price">Price (₱)</Label>
                    <Input id="price" type="number" step="0.01" min="0" v-model="form.price" class="mt-1 w-full sm:w-1/2" required placeholder="0.00" />
                    <InputError :message="form.errors.price" class="mt-2" />
                </div>

                <div>
                    <Label for="description">Product Description</Label>
                    <textarea 
                        id="description" 
                        v-model="form.description" 
                        rows="4" 
                        class="mt-1 flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" 
                        required
                        placeholder="Describe the product details, specs, and features..."
                    ></textarea>
                    <InputError :message="form.errors.description" class="mt-2" />
                </div>

                <div>
                    <Label for="image">Product Image</Label>
                    <input 
                        id="image" 
                        type="file" 
                        @change="handleImageUpload" 
                        accept="image/png, image/jpeg, image/jpg, image/webp"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-[#009933] hover:file:bg-green-100"
                    />
                    <InputError :message="form.errors.image" class="mt-2" />
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <Button type="submit" class="w-full sm:w-auto bg-[#009933] hover:bg-green-700 text-white" :disabled="form.processing">
                        Publish Product
                    </Button>
                </div>

            </form>
        </div>
    </div>
</template>