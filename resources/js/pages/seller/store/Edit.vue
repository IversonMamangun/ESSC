<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

const props = defineProps({
    store: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    description: props.store.description || '',
    logo: null,
    cover_image: null,
});

const logoPreview = ref(props.store.logo_url || null);
const coverPreview = ref(props.store.cover_url || null);

const logoInput = ref(null);
const coverInput = ref(null);

const triggerLogoInput = () => logoInput.value.click();
const triggerCoverInput = () => coverInput.value.click();

const handleLogoChange = (e) => {
    const file = e.target.files[0];

    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const handleCoverChange = (e) => {
    const file = e.target.files[0];

    if (file) {
        form.cover_image = file;
        coverPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('seller.store.update', props.store.id), {
        preserveScroll: true,
        onSuccess: () => {
        },
    });
};
</script>

<template>
    <Head title="Edit Store Profile" />

    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 transition-colors duration-300 flex flex-col">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8">
            <Navbar />
        </div>

        <main class="flex-grow pb-20 mt-8 px-4 flex justify-center">
            <div class="w-full max-w-5xl bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                
                <input type="file" ref="logoInput" class="hidden" accept="image/*" @change="handleLogoChange" />
                <input type="file" ref="coverInput" class="hidden" accept="image/*" @change="handleCoverChange" />

                <div 
                    class="relative h-48 md:h-60 bg-zinc-100 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-800 cursor-pointer group overflow-hidden"
                    @click="triggerCoverInput"
                    title="Click to change cover image"
                >
                    <img 
                        v-if="coverPreview" 
                        :src="coverPreview" 
                        alt="Store Cover" 
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                    />
                    <div v-else class="w-full h-full flex items-center justify-center text-zinc-400 dark:text-zinc-600">
                        <span class="text-sm font-medium">Click to upload cover image</span>
                    </div>

                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white gap-2 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path d="M12 9a3.75 3.75 0 1 0 0 7.5A3.75 3.75 0 0 0 12 9Z" />
                            <path fill-rule="evenodd" d="M9.344 3.071a.876.876 0 0 1 .83-.398h3.652c.6 0 1.125.332 1.319.87l.842 2.316a.75.75 0 0 0 .7.495h5.633a.75.75 0 0 1 .75.75v13.19a.75.75 0 0 1-.75.75H3.75a.75.75 0 0 1-.75-.75V7.133a.75.75 0 0 1 .75-.75h5.633a.75.75 0 0 0 .701-.495l.842-2.316Z" clip-rule="evenodd" />
                        </svg>
                        Change Cover
                    </div>

                    <div v-if="form.errors.cover_image" class="absolute top-2 right-2 bg-red-600 text-white text-xs px-3 py-1 rounded-full shadow-lg">
                        {{ form.errors.cover_image }}
                    </div>
                </div>

                <div class="p-6 md:p-12 pt-20 md:pt-20 relative">

                    <div class="absolute -top-12 left-8 md:left-12 z-10">
                        <div 
                            class="w-24 h-24 md:w-28 md:h-28 rounded-2xl bg-white dark:bg-zinc-900 p-1.5 shadow-md border border-zinc-200 dark:border-zinc-700 cursor-pointer group relative overflow-hidden"
                            @click="triggerLogoInput"
                            title="Click to change logo"
                        >
                            <img 
                                v-if="logoPreview" 
                                :src="logoPreview" 
                                alt="Store Logo" 
                                class="w-full h-full object-cover rounded-xl transition-transform duration-300 group-hover:scale-105"
                            />
                            <div v-else class="w-full h-full bg-zinc-100 dark:bg-zinc-800 rounded-xl flex items-center justify-center text-zinc-400">
                                <span class="text-xs font-bold">LOGO</span>
                            </div>

                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white text-xs font-bold gap-1 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path d="M12 9a3.75 3.75 0 1 0 0 7.5A3.75 3.75 0 0 0 12 9Z" />
                                    <path fill-rule="evenodd" d="M9.344 3.071a.876.876 0 0 1 .83-.398h3.652c.6 0 1.125.332 1.319.87l.842 2.316a.75.75 0 0 0 .7.495h5.633a.75.75 0 0 1 .75.75v13.19a.75.75 0 0 1-.75.75H3.75a.75.75 0 0 1-.75-.75V7.133a.75.75 0 0 1 .75-.75h5.633a.75.75 0 0 0 .701-.495l.842-2.316Z" clip-rule="evenodd" />
                                </svg>
                                Edit
                            </div>
                        </div>
                        <p v-if="form.errors.logo" class="mt-2 text-xs text-red-600 bg-red-50 dark:bg-red-950/50 p-1 rounded px-2 border border-red-200 dark:border-red-800">{{ form.errors.logo }}</p>
                    </div>

                    <div class="border-b border-zinc-200 dark:border-zinc-800 pb-4 mb-8 flex items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-zinc-900 dark:text-white">Store Settings</h1>
                            <p class="text-zinc-500 dark:text-zinc-400 mt-1">Manage public profile details and branding.</p>
                        </div>
                        
                        <button
                            type="submit"
                            form="storeUpdateForm"
                            :disabled="form.processing"
                            class="inline-flex justify-center rounded-xl bg-[#009933] py-2.5 px-5 text-sm font-bold text-white shadow-sm hover:bg-[#007a29] focus:outline-none focus:ring-2 focus:ring-[#009933] focus:ring-offset-2 disabled:opacity-50 transition-colors whitespace-nowrap"
                        >
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>

                    <form id="storeUpdateForm" @submit.prevent="submit" class="space-y-8">
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Identity</h3>
                                <p class="text-xs text-zinc-500 mt-1">Standard business name definition.</p>
                            </div>
                            <div class="md:col-span-2 space-y-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Store Name</label>
                                    <div class="p-3 px-4 bg-zinc-50 dark:bg-zinc-950/50 border border-zinc-200 dark:border-zinc-800/80 rounded-xl text-zinc-700 dark:text-zinc-300 font-medium">
                                        {{ props.store.name }}
                                    </div>
                                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-2">The store name cannot be customized here.</p>
                                </div>
                            </div>
                        </div>

                        <hr class="border-zinc-200 dark:border-zinc-800" />

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">Biography</h3>
                                <p class="text-xs text-zinc-500 mt-1">Tell your customers what makes your online shop unique.</p>
                            </div>
                            <div class="md:col-span-2">
                                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">
                                    Description
                                </label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    rows="6"
                                    class="block w-full rounded-xl border-zinc-200 shadow-sm focus:border-[#009933] focus:ring-[#009933] focus:ring-1 dark:bg-zinc-950 dark:border-zinc-800 dark:text-white sm:text-sm p-4 transition-colors placeholder-zinc-400"
                                    placeholder="Tell customers about your store..."
                                ></textarea>
                                <p v-if="form.errors.description" class="mt-2 text-sm text-red-600">{{ form.errors.description }}</p>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </main>

        <Footer />
    </div>
</template>