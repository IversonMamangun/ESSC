<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Footer from '@/components/sections/Footer.vue';
import { User, MapPin, Package, Camera, ChevronRight, CheckCircle2 } from 'lucide-vue-next';

const props = defineProps<{
    user: {
        id: number;
        name: string;
        email: string;
        phone: string | null;
        avatar: string | null;
    };
}>();

const form = useForm({
    name: props.user.name || '',
    email: props.user.email || '',
    phone: props.user.phone || '',
    avatar: null as File | null,
});

const avatarPreview = ref<string | null>(
    props.user.avatar ? `/storage/${props.user.avatar}` : null
);

const handleAvatarChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        const file = target.files[0];
        form.avatar = file;
        avatarPreview.value = URL.createObjectURL(file);
    }
};

const submitProfile = () => {
    form.post('/account/profile', {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="My Profile" />
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 transition-colors duration-300">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8"><Navbar /></div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="w-full lg:w-64 shrink-0">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-full bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center overflow-hidden border-2 border-white dark:border-zinc-900 shadow-sm shrink-0">
                            <img v-if="avatarPreview" :src="avatarPreview" class="w-full h-full object-cover" />
                            <User v-else class="w-6 h-6 text-zinc-500" />
                        </div>
                        <div>
                            <p class="font-black text-zinc-900 dark:text-white leading-tight">{{ props.user.name || 'User' }}</p>
                        </div>
                    </div>

                    <nav class="space-y-1">
                        <Link href="/account" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 dark:bg-green-900/10 text-[#009933] font-black transition-colors">
                            <User class="w-5 h-5" /> My Profile
                        </Link>
                        <Link href="/account/address" class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800/50 font-bold transition-colors">
                            <MapPin class="w-5 h-5" /> Addresses
                        </Link>
                        <Link href="/purchases" class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800/50 font-bold transition-colors">
                            <Package class="w-5 h-5" /> My Purchases
                        </Link>
                    </nav>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-zinc-200 dark:border-zinc-800 p-6 md:p-10 transition-colors">
                        <div class="mb-8 pb-6 border-b border-zinc-100 dark:border-zinc-800">
                            <h1 class="text-2xl font-black text-zinc-900 dark:text-white">My Profile</h1>
                            <p class="text-zinc-500 dark:text-zinc-400 mt-1">Manage your basic information</p>
                        </div>

                        <form @submit.prevent="submitProfile" class="flex flex-col-reverse lg:flex-row gap-12">
                            <div class="flex-1 space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Full Name</label>
                                    <input type="text" v-model="form.name" class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-[#009933]/20 focus:border-[#009933] transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Email Address</label>
                                    <input type="email" v-model="form.email" class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-[#009933]/20 focus:border-[#009933] transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Phone Number</label>
                                    <input type="text" v-model="form.phone" class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-[#009933]/20 focus:border-[#009933] transition-all">
                                </div>

                                <button type="submit" :disabled="form.processing" class="mt-4 px-8 py-3.5 bg-[#009933] text-white font-black rounded-xl hover:bg-green-700 transition-all active:scale-95 shadow-md flex items-center gap-2">
                                    <CheckCircle2 class="w-5 h-5" /> Save Profile
                                </button>
                                <p v-if="form.recentlySuccessful" class="text-sm font-bold text-[#009933] mt-2">Saved successfully!</p>
                            </div>

                            <div class="lg:w-1/3 flex flex-col items-center border-l border-zinc-100 dark:border-zinc-800 lg:pl-12">
                                <div class="relative group cursor-pointer mb-6">
                                    <div class="w-32 h-32 rounded-full bg-zinc-100 dark:bg-zinc-800 border-4 border-white dark:border-zinc-900 shadow-lg overflow-hidden flex items-center justify-center">
                                        <img v-if="avatarPreview" :src="avatarPreview" class="w-full h-full object-cover" />
                                        <User v-else class="w-16 h-16 text-zinc-400" />
                                    </div>
                                    <label for="avatar" class="absolute inset-0 bg-black/50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer text-white">
                                        <Camera class="w-6 h-6 mb-1" />
                                        <span class="text-xs font-bold">Edit</span>
                                        <input id="avatar" type="file" class="hidden" @change="handleAvatarChange" />
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </main>
        <Footer />
    </div>
</template>