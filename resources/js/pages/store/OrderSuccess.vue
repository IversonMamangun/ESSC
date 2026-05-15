<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle2, Package, ArrowRight, Copy, Wallet } from 'lucide-vue-next';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

const props = defineProps<{
    order: {
        tracking_number: string;
        total_price: number;
        payment_method: string;
        status: string;
        created_at: string;
    }
}>();

const copyTracking = () => {
    navigator.clipboard.writeText(props.order.tracking_number);
    alert('Tracking number copied to clipboard!');
};
</script>

<template>
    <Head title="Order Successful" />
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 transition-colors duration-300 flex flex-col">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8">
            <Navbar />
        </div>

        <main class="flex-grow flex items-center justify-center p-4 py-12">
            <div class="max-w-2xl w-full bg-white dark:bg-zinc-900 rounded-3xl shadow-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                
                <div class="bg-[#009933] px-8 py-10 text-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_white_10%,_transparent_10%)] bg-[size:20px_20px]"></div>
                    <CheckCircle2 class="w-20 h-20 text-white mx-auto mb-4 relative z-10" />
                    <h1 class="text-3xl font-black text-white relative z-10 tracking-tight">Order Confirmed!</h1>
                    <p class="text-green-100 mt-2 font-medium relative z-10">Thank you for shopping with us.</p>
                </div>

                <div class="p-8 md:p-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 pb-8 border-b border-zinc-100 dark:border-zinc-800">
                        <div>
                            <p class="text-sm font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Total Amount</p>
                            <p class="text-4xl font-black text-zinc-900 dark:text-white tracking-tighter">₱{{ order.total_price.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</p>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-sm font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest mb-1">Date</p>
                            <p class="font-bold text-zinc-800 dark:text-zinc-200">{{ order.created_at }}</p>
                        </div>
                    </div>

                    <div class="bg-zinc-50 dark:bg-zinc-800/50 rounded-2xl p-6 mb-8 border border-zinc-100 dark:border-zinc-700">
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl shrink-0">
                                <Wallet class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-zinc-900 dark:text-white mb-1">
                                    {{ order.payment_method === 'cod' ? 'Cash on Delivery' : 'GCash Payment' }}
                                </h3>
                                <p v-if="order.payment_method === 'cod'" class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                    Please prepare the exact amount of <span class="font-bold text-zinc-900 dark:text-white">₱{{ order.total_price.toLocaleString() }}</span> for the courier upon delivery.
                                </p>
                                <div v-else class="space-y-3">
                                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                        Your order is currently pending payment. Click the button below to complete your payment securely via GCash.
                                    </p>
                                    <button class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-md hover:bg-blue-700 transition-colors">
                                        Pay with GCash Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between p-5 border-2 border-dashed border-zinc-200 dark:border-zinc-700 rounded-2xl gap-4">
                        <div class="flex items-center gap-3">
                            <Package class="w-6 h-6 text-zinc-400" />
                            <div>
                                <p class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-0.5">Tracking Number</p>
                                <p class="font-black text-lg text-zinc-900 dark:text-white">{{ order.tracking_number }}</p>
                            </div>
                        </div>
                        <button @click="copyTracking" class="text-sm font-bold text-[#009933] hover:bg-green-50 dark:hover:bg-green-900/20 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <Copy class="w-4 h-4" /> Copy
                        </button>
                    </div>

                    <div class="mt-10 flex flex-col sm:flex-row gap-4">
                        <Link href="/purchases" class="flex-1 text-center py-4 rounded-xl font-bold text-zinc-600 dark:text-zinc-300 border-2 border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                            View My Purchases
                        </Link>
                        <Link href="/store" class="flex-1 flex items-center justify-center gap-2 py-4 rounded-xl font-bold text-white bg-zinc-900 dark:bg-white dark:text-zinc-900 hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-colors shadow-md">
                            Continue Shopping <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>

            </div>
        </main>
        <Footer />
    </div>
</template>