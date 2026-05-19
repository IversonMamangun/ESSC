<script setup>
import { Head, useForm } from '@inertiajs/vue3';

// Receive the store prop from the controller
const props = defineProps({
    store: {
        type: Object,
        required: true,
    },
});

// Initialize the form with the existing description
const form = useForm({
    description: props.store.description || '',
    logo: null,
    cover_image: null,
});

// Handle the form submission
const submit = () => {
    form.post(route('seller.store.update', props.store.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Form submission succeeded (you can add a toast notification here if desired)
        },
    });
};
</script>

<template>
    <!-- Standard Inertia Head for setting the page title -->
    <Head title="Edit Store Profile" />

    <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-800 shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white mb-6">
                Edit Store Profile
            </h1>

            <!-- Read-only Store Name (Fixed from title to name) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Store Name</label>
                <div class="mt-1 p-2 bg-zinc-100 dark:bg-zinc-900 rounded-md text-zinc-600 dark:text-zinc-400">
                    {{ props.store.name }}
                </div>
                <p class="text-xs text-zinc-500 mt-1">The store name cannot be changed.</p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Description
                    </label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        class="mt-1 block w-full rounded-md border-zinc-300 shadow-sm focus:border-[#009933] focus:ring-[#009933] dark:bg-zinc-900 dark:border-zinc-700 dark:text-white sm:text-sm"
                        placeholder="Tell customers about your store..."
                    ></textarea>
                    <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">{{ form.errors.description }}</p>
                </div>

                <!-- Logo Upload -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Store Logo
                    </label>
                    <input
                        type="file"
                        @input="form.logo = $event.target.files[0]"
                        accept="image/*"
                        class="mt-1 block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#009933] file:text-white hover:file:bg-[#007a29] dark:file:bg-[#009933] dark:hover:file:bg-[#007a29]"
                    />
                    <p v-if="form.errors.logo" class="mt-2 text-sm text-red-600">{{ form.errors.logo }}</p>
                </div>

                <!-- Cover Image Upload -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Cover Image
                    </label>
                    <input
                        type="file"
                        @input="form.cover_image = $event.target.files[0]"
                        accept="image/*"
                        class="mt-1 block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#009933] file:text-white hover:file:bg-[#007a29] dark:file:bg-[#009933] dark:hover:file:bg-[#007a29]"
                    />
                    <p v-if="form.errors.cover_image" class="mt-2 text-sm text-red-600">{{ form.errors.cover_image }}</p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex justify-center rounded-md border border-transparent bg-[#009933] py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-[#007a29] focus:outline-none focus:ring-2 focus:ring-[#009933] focus:ring-offset-2 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
