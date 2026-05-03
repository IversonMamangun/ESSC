<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Footer from '@/components/sections/Footer.vue';
import { User, MapPin, Package, ChevronRight, CheckCircle2, Plus, X, Phone, Building2 } from 'lucide-vue-next';

// Define the structure of our new Address data
interface AddressType {
    id: number;
    label: string;
    recipient_name: string;
    phone: string;
    address: string;
    city: string;
    province: string;
    zip: string;
    is_default: boolean;
}

const props = defineProps<{
    user: {
        name: string;
        avatar: string | null;
    };
    addresses: AddressType[];
}>();

// Modal State
const isModalOpen = ref(false);

// The form for creating a new address
const form = useForm({
    label: 'Home',
    recipient_name: props.user.name || '',
    phone: '',
    address: '',
    city: '',
    province: '',
    zip: '',
    is_default: false,
});

const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset(); // Clear the form when closing
    form.clearErrors();
};

const submitAddress = () => {
    form.post('/account/address', { 
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};
</script>

<template>
    <Head title="My Addresses" />
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 transition-colors duration-300">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8">
            <Navbar />
        </div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="w-full lg:w-64 shrink-0">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-full bg-zinc-200 dark:bg-zinc-800 border-2 border-white dark:border-zinc-900 shadow-sm overflow-hidden flex items-center justify-center shrink-0">
                            <img v-if="props.user.avatar" :src="'/storage/' + props.user.avatar" class="w-full h-full object-cover" />
                            <User v-else class="w-6 h-6 text-zinc-500" />
                        </div>
                        <div class="min-w-0">
                            <p class="font-black text-zinc-900 dark:text-white leading-tight truncate">{{ props.user.name || 'User' }}</p>
                            <span class="text-xs text-zinc-500 font-bold flex items-center gap-1 mt-0.5">Edit Profile <ChevronRight class="w-3 h-3" /></span>
                        </div>
                    </div>

                    <nav class="space-y-1">
                        <Link href="/account" class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800/50 font-bold transition-colors">
                            <User class="w-5 h-5" /> My Profile
                        </Link>
                        <Link href="/account/address" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 dark:bg-green-900/10 text-[#009933] font-black transition-colors">
                            <MapPin class="w-5 h-5" /> Addresses
                        </Link>
                        <Link href="/purchases" class="flex items-center gap-3 px-4 py-3 rounded-xl text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800/50 font-bold transition-colors">
                            <Package class="w-5 h-5" /> My Purchases
                        </Link>
                    </nav>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-zinc-200 dark:border-zinc-800 p-6 md:p-10">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-zinc-100 dark:border-zinc-800">
                            <div>
                                <h1 class="text-2xl font-black text-zinc-900 dark:text-white">My Addresses</h1>
                                <p class="text-zinc-500 dark:text-zinc-400 mt-1">Manage where your items will be shipped</p>
                            </div>
                            
                            <button 
                                @click="openModal" 
                                class="flex items-center justify-center gap-2 px-5 py-2.5 bg-[#009933] text-white font-bold rounded-xl hover:bg-green-700 transition-all active:scale-95 shadow-md shrink-0"
                            >
                                <Plus class="w-4 h-4" /> Add New Address
                            </button>
                        </div>

                        <div v-if="props.addresses.length === 0" class="py-12 flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 bg-zinc-50 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                                <MapPin class="w-8 h-8 text-zinc-300 dark:text-zinc-600" />
                            </div>
                            <h3 class="text-lg font-black text-zinc-800 dark:text-zinc-200 mb-2">No addresses yet</h3>
                            <p class="text-zinc-500 dark:text-zinc-400 max-w-sm">Add a delivery address so you can start shopping and checking out quickly.</p>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div v-for="address in props.addresses" :key="address.id" 
                                class="relative p-6 rounded-2xl border-2 transition-all"
                                :class="address.is_default ? 'border-[#009933] bg-green-50/30 dark:bg-green-900/5' : 'border-zinc-100 dark:border-zinc-800 hover:border-zinc-200 dark:hover:border-zinc-700'"
                            >
                                <div v-if="address.is_default" class="absolute top-4 right-4 bg-[#009933] text-white text-[10px] uppercase font-black tracking-wider px-2.5 py-1 rounded-md shadow-sm flex items-center gap-1">
                                    <CheckCircle2 class="w-3 h-3" /> Default
                                </div>

                                <div class="flex items-center gap-2 mb-4">
                                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5">
                                        <Building2 v-if="address.label === 'Office'" class="w-3.5 h-3.5" />
                                        <MapPin v-else class="w-3.5 h-3.5" />
                                        {{ address.label }}
                                    </span>
                                </div>
                                
                                <h3 class="font-black text-zinc-900 dark:text-white text-lg mb-1">{{ address.recipient_name }}</h3>
                                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400 flex items-center gap-2 mb-4">
                                    <Phone class="w-3 h-3" /> {{ address.phone }}
                                </p>
                                
                                <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">
                                    {{ address.address }}<br>
                                    {{ address.city }}, {{ address.province }} {{ address.zip }}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </main>
        <Footer />

        <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="isModalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal"></div>
                
                <div class="relative w-full max-w-2xl bg-white dark:bg-zinc-900 rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
                    
                    <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100 dark:border-zinc-800">
                        <h2 class="text-xl font-black text-zinc-900 dark:text-white">Add New Address</h2>
                        <button @click="closeModal" class="p-2 text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full transition-colors">
                            <X class="w-5 h-5" />
                        </button>
                    </div>

                    <div class="p-6 overflow-y-auto custom-scrollbar">
                        <form @submit.prevent="submitAddress" class="space-y-6">
                            
                            <div>
                                <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-3">Address Label</label>
                                <div class="flex gap-4">
                                    <label class="flex-1 relative flex items-center justify-center gap-2 p-3 border-2 rounded-xl cursor-pointer transition-all" 
                                        :class="form.label === 'Home' ? 'border-[#009933] bg-green-50/50 dark:bg-green-900/10 text-[#009933]' : 'border-zinc-200 dark:border-zinc-700 text-zinc-500 hover:bg-zinc-50 dark:hover:bg-zinc-800'">
                                        <input type="radio" v-model="form.label" value="Home" class="hidden">
                                        <MapPin class="w-4 h-4" /> <span class="font-bold text-sm">Home</span>
                                    </label>
                                    <label class="flex-1 relative flex items-center justify-center gap-2 p-3 border-2 rounded-xl cursor-pointer transition-all" 
                                        :class="form.label === 'Office' ? 'border-[#009933] bg-green-50/50 dark:bg-green-900/10 text-[#009933]' : 'border-zinc-200 dark:border-zinc-700 text-zinc-500 hover:bg-zinc-50 dark:hover:bg-zinc-800'">
                                        <input type="radio" v-model="form.label" value="Office" class="hidden">
                                        <Building2 class="w-4 h-4" /> <span class="font-bold text-sm">Office</span>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Recipient Name</label>
                                    <input type="text" v-model="form.recipient_name" required class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl px-4 py-3 outline-none focus:border-[#009933] dark:text-white transition-colors">
                                    <span v-if="form.errors.recipient_name" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.recipient_name }}</span>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Phone Number</label>
                                    <input type="text" v-model="form.phone" required class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl px-4 py-3 outline-none focus:border-[#009933] dark:text-white transition-colors">
                                    <span v-if="form.errors.phone" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.phone }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Street Name, Building, House No.</label>
                                <input type="text" v-model="form.address" required class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl px-4 py-3 outline-none focus:border-[#009933] dark:text-white transition-colors">
                                <span v-if="form.errors.address" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.address }}</span>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">City / Municipality</label>
                                    <input type="text" v-model="form.city" required class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl px-4 py-3 outline-none focus:border-[#009933] dark:text-white transition-colors">
                                    <span v-if="form.errors.city" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.city }}</span>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Province</label>
                                    <input type="text" v-model="form.province" required class="w-full bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl px-4 py-3 outline-none focus:border-[#009933] dark:text-white transition-colors">
                                    <span v-if="form.errors.province" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.province }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-zinc-700 dark:text-zinc-300 mb-2">Postal / Zip Code</label>
                                <input type="text" v-model="form.zip" required class="w-full sm:w-1/2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl px-4 py-3 outline-none focus:border-[#009933] dark:text-white transition-colors">
                                <span v-if="form.errors.zip" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.zip }}</span>
                            </div>

                            <div class="pt-2">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative flex items-center justify-center w-5 h-5 border-2 rounded text-transparent bg-white dark:bg-zinc-800 transition-colors"
                                        :class="form.is_default ? 'border-[#009933] bg-[#009933] dark:bg-[#009933]' : 'border-zinc-300 dark:border-zinc-600 group-hover:border-[#009933]'">
                                        <input type="checkbox" v-model="form.is_default" class="hidden">
                                        <CheckCircle2 v-if="form.is_default" class="w-4 h-4 text-white absolute" />
                                    </div>
                                    <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300 select-none">Set as default delivery address</span>
                                </label>
                            </div>

                        </form>
                    </div>

                    <div class="p-6 border-t border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 flex justify-end gap-3">
                        <button @click="closeModal" type="button" class="px-6 py-2.5 rounded-xl font-bold text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-800 transition-colors">
                            Cancel
                        </button>
                        <button @click="submitAddress" :disabled="form.processing" class="px-8 py-2.5 bg-[#009933] text-white font-bold rounded-xl hover:bg-green-700 transition-all shadow-md disabled:opacity-50 flex items-center gap-2">
                            <span v-if="form.processing">Saving...</span>
                            <span v-else>Save Address</span>
                        </button>
                    </div>

                </div>
            </div>
        </transition>

    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 10px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; }
</style>