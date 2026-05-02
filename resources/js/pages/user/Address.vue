<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Footer from '@/components/sections/Footer.vue';
import { User, MapPin, Package, ChevronRight, CheckCircle2 } from 'lucide-vue-next';

const props = defineProps<{
    user: {
        name: string;
        avatar: string | null;
        address: string | null;
        city: string | null;
        province: string | null;
        zip: string | null;
    };
}>();

const form = useForm({
    address: props.user.address || '',
    city: props.user.city || '',
    province: props.user.province || '',
    zip: props.user.zip || '',
});

const submitAddress = () => {
    form.post('/account/address', { preserveScroll: true });
};
</script>

<template>
    <Head title="My Address" />
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8"><Navbar /></div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="w-full lg:w-64 shrink-0">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-full bg-zinc-200 border-2 border-white shadow-sm overflow-hidden flex items-center justify-center">
                            <img v-if="props.user.avatar" :src="'/storage/' + props.user.avatar" class="w-full h-full object-cover" />
                            <User v-else class="w-6 h-6 text-zinc-500" />
                        </div>
                        <div>
                            <p class="font-black text-zinc-900 leading-tight">{{ props.user.name || 'User' }}</p>
                            <span class="text-xs text-zinc-500 font-bold flex items-center gap-1 mt-0.5">Edit Profile <ChevronRight class="w-3 h-3" /></span>
                        </div>
                    </div>

                    <nav class="space-y-1">
                        <Link href="/account" class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-600 hover:bg-zinc-100 font-bold transition-colors">
                            <User class="w-5 h-5" /> My Profile
                        </Link>
                        <Link href="/account/address" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 text-[#009933] font-black transition-colors">
                            <MapPin class="w-5 h-5" /> Addresses
                        </Link>
                        <Link href="/purchases" class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-600 hover:bg-zinc-100 font-bold transition-colors">
                            <Package class="w-5 h-5" /> My Purchases
                        </Link>
                    </nav>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="bg-white rounded-3xl shadow-sm border border-zinc-200 p-6 md:p-10">
                        <div class="mb-8 pb-6 border-b border-zinc-100">
                            <h1 class="text-2xl font-black text-zinc-900">Delivery Address</h1>
                            <p class="text-zinc-500 mt-1">Manage where your items will be shipped</p>
                        </div>

                        <form @submit.prevent="submitAddress" class="space-y-6 max-w-2xl">
                            <div>
                                <label class="block text-sm font-bold text-zinc-700 mb-2">Street Name, Building, House No.</label>
                                <input type="text" v-model="form.address" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-3 outline-none focus:border-[#009933]">
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-zinc-700 mb-2">City / Municipality</label>
                                    <input type="text" v-model="form.city" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-3 outline-none focus:border-[#009933]">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-zinc-700 mb-2">Province</label>
                                    <input type="text" v-model="form.province" class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-3 outline-none focus:border-[#009933]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-zinc-700 mb-2">Postal / Zip Code</label>
                                <input type="text" v-model="form.zip" class="w-full sm:w-1/2 bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-3 outline-none focus:border-[#009933]">
                            </div>

                            <button type="submit" :disabled="form.processing" class="mt-4 px-8 py-3.5 bg-[#009933] text-white font-black rounded-xl hover:bg-green-700 transition-all active:scale-95 shadow-md flex items-center gap-2">
                                <CheckCircle2 class="w-5 h-5" /> Save Address
                            </button>
                            <p v-if="form.recentlySuccessful" class="text-sm font-bold text-[#009933] mt-2">Address saved successfully!</p>
                        </form>
                    </div>
                </div>

            </div>
        </main>
        <Footer />
    </div>
</template>