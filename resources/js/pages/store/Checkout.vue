<script setup lang="ts">
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import { 
    MapPin, CreditCard, AlertCircle, 
    ChevronLeft, CheckCircle2, ShieldCheck
} from 'lucide-vue-next';
import { computed } from 'vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Footer from '@/components/sections/Footer.vue';

const props = defineProps<{
    orderSummary: {
        subtotal: number;
        tax: number;
        shipping: number;
        total: number;
        items: Array<{ name: string; qty: number; price: number }>;
    };
    selectedIds: number[];
    user: {
        name: string;
        phone: string | null;
        address: string | null;
        city: string | null;
        province: string | null;
        zip: string | null;
    };
}>();

// The form is now much cleaner since we don't send address text inputs anymore!
const form = useForm({
    selected_ids: props.selectedIds,
    paymentMethod: 'cod',
});

// Check if they have the required delivery info in the database
const isProfileComplete = computed(() => {
    return props.user.phone && props.user.address && props.user.city && props.user.province;
});

const submitOrder = () => {
    if (!isProfileComplete.value) {
        return; // Failsafe
    }
    
    form.post('/checkout');
};
</script>

<template>
    <Head title="Checkout" />
    <div class="min-h-screen bg-gray-50 dark:bg-zinc-950 transition-colors duration-300">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8">
            <Navbar />
        </div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-zinc-900 dark:text-white tracking-tight flex items-center gap-3">
                        <ShieldCheck class="w-8 h-8 text-[#009933]" />
                        Secure Checkout
                    </h1>
                    <p class="text-zinc-500 dark:text-zinc-400 mt-1 font-medium">Complete your purchase safely and securely.</p>
                </div>
                <Link href="/cart" class="flex items-center text-sm font-bold text-[#009933] hover:text-green-700 transition-colors">
                    <ChevronLeft class="w-4 h-4 mr-1" />
                    Back to Shopping Cart
                </Link>
            </div>

            <div class="flex flex-col lg:flex-row gap-10">
                
                <div class="flex-1 space-y-8">
                    
                    <div v-if="$page.props.errors.stock" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 p-4 rounded-2xl flex items-start gap-3 shadow-sm">
                        <AlertCircle class="w-5 h-5 shrink-0" />
                        <p class="font-bold">{{ $page.props.errors.stock }}</p>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden relative">
                        <div class="h-1 w-full bg-[repeating-linear-gradient(45deg,#009933,#009933_10px,transparent_10px,transparent_20px)] dark:bg-[repeating-linear-gradient(45deg,#009933,#009933_10px,#18181b_10px,#18181b_20px)] opacity-70"></div>

                        <div class="p-6 md:p-8">
                            <div class="flex items-center gap-2 mb-4">
                                <MapPin class="w-5 h-5 text-[#009933]" />
                                <h2 class="text-lg font-bold text-zinc-800 dark:text-zinc-100">Delivery Address</h2>
                            </div>
                            
                            <div v-if="isProfileComplete" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-zinc-800 dark:text-zinc-200">
                                    <div class="font-bold text-base whitespace-nowrap">
                                        {{ user.name }} <span class="text-zinc-500 dark:text-zinc-400 font-medium ml-1">({{ user.phone }})</span>
                                    </div>
                                    <div class="hidden sm:block w-px h-4 bg-zinc-300 dark:bg-zinc-700"></div>
                                    <div class="text-sm font-medium">
                                        {{ user.address }}, {{ user.city }}, {{ user.province }}, {{ user.zip }}
                                    </div>
                                    <span class="border border-[#009933] text-[#009933] text-[10px] uppercase font-black tracking-wider px-2 py-0.5 rounded-sm w-max">
                                        Default
                                    </span>
                                </div>
                                
                                <Link href="/account/address" class="text-sm font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 uppercase tracking-wide shrink-0">
                                    Change
                                </Link>
                            </div>

                            <div v-else class="py-4 flex flex-col items-center justify-center border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-xl bg-zinc-50 dark:bg-zinc-800/50">
                                <p class="text-zinc-500 dark:text-zinc-400 text-sm font-medium mb-3">You need a delivery address to place an order.</p>
                                
                                <Link 
                                    href="/account/address" 
                                    class="flex items-center gap-2 px-6 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-lg font-bold text-sm shadow-md hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-colors"
                                >
                                    + Add Delivery Address
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-6 md:p-8 shadow-sm border border-zinc-200 dark:border-zinc-800">
                        <div class="flex items-center gap-3 mb-8 border-b dark:border-zinc-800 pb-6">
                            <div class="p-2.5 bg-green-50 dark:bg-green-900/20 rounded-xl">
                                <CreditCard class="w-6 h-6 text-[#009933]" />
                            </div>
                            <h2 class="text-xl font-bold text-zinc-800 dark:text-zinc-100">Payment Method</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="relative flex items-center gap-4 p-5 border-2 rounded-2xl cursor-pointer transition-all" 
                                :class="form.paymentMethod === 'cod' ? 'border-[#009933] bg-green-50/50 dark:bg-green-900/10' : 'border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800'">
                                <input type="radio" v-model="form.paymentMethod" value="cod" class="w-5 h-5 text-[#009933] focus:ring-[#009933] border-zinc-300 dark:bg-zinc-700">
                                <div class="flex flex-col">
                                    <span class="font-bold text-zinc-800 dark:text-zinc-100">Cash on Delivery</span>
                                    <span class="text-xs text-zinc-500">Pay when you receive items</span>
                                </div>
                                <CheckCircle2 v-if="form.paymentMethod === 'cod'" class="absolute top-4 right-4 w-5 h-5 text-[#009933]" />
                            </label>
                            
                            <label class="relative flex items-center gap-4 p-5 border-2 rounded-2xl cursor-pointer transition-all" 
                                :class="form.paymentMethod === 'gcash' ? 'border-[#009933] bg-green-50/50 dark:bg-green-900/10' : 'border-zinc-100 dark:border-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-800'">
                                <input type="radio" v-model="form.paymentMethod" value="gcash" class="w-5 h-5 text-[#009933] focus:ring-[#009933] border-zinc-300 dark:bg-zinc-700">
                                <div class="flex flex-col">
                                    <span class="font-bold text-zinc-800 dark:text-zinc-100">GCash</span>
                                    <span class="text-xs text-zinc-500">Digital wallet payment</span>
                                </div>
                                <CheckCircle2 v-if="form.paymentMethod === 'gcash'" class="absolute top-4 right-4 w-5 h-5 text-[#009933]" />
                            </label>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-[400px] shrink-0">
                    <div class="bg-white dark:bg-zinc-900 rounded-3xl p-8 shadow-xl border border-zinc-100 dark:border-zinc-800 sticky top-32">
                        <h2 class="text-2xl font-black text-zinc-800 dark:text-white mb-6 tracking-tight">Summary</h2>
                        
                        <div class="flex flex-col gap-4 mb-8 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                            <div v-for="item in orderSummary.items" :key="item.name" class="flex justify-between items-start text-sm">
                                <div class="flex-1 pr-4">
                                    <p class="font-bold text-zinc-700 dark:text-zinc-200 line-clamp-1">{{ item.name }}</p>
                                    <p class="text-zinc-500 dark:text-zinc-500 font-medium">Qty: {{ item.qty }}</p>
                                </div>
                                <span class="font-black text-zinc-800 dark:text-zinc-200">₱{{ item.price.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                            </div>
                        </div>

                        <div class="border-t dark:border-zinc-800 pt-6 space-y-3 mb-8">
                            <div class="flex justify-between text-zinc-600 dark:text-zinc-400 text-sm font-medium">
                                <span>Subtotal</span>
                                <span class="text-zinc-900 dark:text-white">₱{{ orderSummary.subtotal.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                            </div>
                            <div class="flex justify-between text-zinc-600 dark:text-zinc-400 text-sm font-medium">
                                <span>VAT (12%)</span>
                                <span class="text-zinc-900 dark:text-white">₱{{ orderSummary.tax.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                            </div>
                            <div class="flex justify-between text-zinc-600 dark:text-zinc-400 text-sm font-medium">
                                <span>Shipping Fee</span>
                                <span class="text-zinc-900 dark:text-white">₱{{ orderSummary.shipping.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                            </div>
                        </div>
                        
                        <div class="border-t-2 border-dashed dark:border-zinc-800 pt-6 mb-10">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-zinc-500 dark:text-zinc-500 uppercase tracking-widest text-xs">Total Amount</span>
                                <span class="text-3xl font-black text-[#009933] tracking-tighter">₱{{ orderSummary.total.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                            </div>
                        </div>
                        
                        <button 
                            @click="submitOrder"
                            :disabled="form.processing || !isProfileComplete"
                            class="w-full bg-[#009933] text-white py-5 rounded-2xl font-black text-lg hover:bg-green-700 transition-all shadow-lg shadow-green-900/10 active:scale-[0.98] flex items-center justify-center gap-3 disabled:bg-zinc-400 disabled:cursor-not-allowed uppercase tracking-wide"
                        >
                            <span v-if="form.processing">Processing...</span>
                            <span v-else-if="!isProfileComplete">Address Required</span>
                            <span v-else>Confirm & Pay Now</span>
                        </button>
                        
                        <div class="mt-6 text-center">
                            <p class="text-[11px] text-zinc-400 dark:text-zinc-500 font-medium px-4">
                                By placing this order, you agree to our Terms of Service and Privacy Policy.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 10px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #27272a; }
</style>