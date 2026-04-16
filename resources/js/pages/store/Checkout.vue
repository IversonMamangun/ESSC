<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

// 1. Receive the real dynamic Order Summary from the Backend (CheckoutController)
const props = defineProps<{
    orderSummary: {
        subtotal: number;
        tax: number;
        shipping: number;
        total: number;
        items: Array<{
            name: string;
            qty: number;
            price: number;
        }>;
    }
}>();

// 2. Form State using Inertia's useForm
// This automatically handles processing states and validation errors
const form = useForm({
    email: '',
    firstName: '',
    lastName: '',
    address: '',
    city: '',
    province: '',
    zip: '',
    paymentMethod: 'credit_card'
});

// 3. Real Checkout Submission
const placeOrder = () => {
    // This sends the POST request to our checkout route
    form.post('/checkout', {
        preserveScroll: true,
        // The backend handles the actual redirect on success, 
        // but if validation fails, Inertia automatically catches the errors!
    });
};

// Formats prices to Philippine Peso
const formatPrice = (value: number) => {
    return `₱${value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
};
</script>

<template>
    <Head title="Checkout - Store" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>

    <main class="flex w-full justify-center px-4 mt-8 mb-24">
        <div class="w-full max-w-7xl flex flex-col-reverse lg:flex-row gap-8">
            
            <div class="w-full lg:w-3/5">
                
                <div class="mb-8">
                    <h1 class="text-2xl md:text-3xl font-black text-gray-800">Checkout</h1>
                    <p class="text-gray-500 mt-1">Please enter your details below to complete your order.</p>
                </div>

                <form @submit.prevent="placeOrder" class="flex flex-col gap-8">
                    
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-100">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">1. Contact Information</h2>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input v-model="form.email" type="email" required placeholder="juan@example.com" class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none transition-colors">
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-100">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">2. Shipping Address</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input v-model="form.firstName" type="text" required class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input v-model="form.lastName" type="text" required class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Complete Address</label>
                                <input v-model="form.address" type="text" required placeholder="House No., Street, Barangay" class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City/Municipality</label>
                                <input v-model="form.city" type="text" required class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                <input v-model="form.province" type="text" required class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                                <input v-model="form.zip" type="text" required class="w-full rounded-xl border border-gray-300 p-3 focus:ring-2 focus:ring-green-300 focus:border-[#009933] outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-100">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">3. Payment Method</h2>
                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition-colors" :class="form.paymentMethod === 'credit_card' ? 'border-[#009933] bg-green-50' : 'border-gray-200 hover:bg-gray-50'">
                                <input type="radio" v-model="form.paymentMethod" value="credit_card" class="text-[#009933] focus:ring-[#009933] w-5 h-5">
                                <span class="font-bold text-gray-800">Credit / Debit Card</span>
                            </label>
                            
                            <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition-colors" :class="form.paymentMethod === 'gcash' ? 'border-[#009933] bg-green-50' : 'border-gray-200 hover:bg-gray-50'">
                                <input type="radio" v-model="form.paymentMethod" value="gcash" class="text-[#009933] focus:ring-[#009933] w-5 h-5">
                                <span class="font-bold text-gray-800">GCash</span>
                            </label>

                            <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition-colors" :class="form.paymentMethod === 'cod' ? 'border-[#009933] bg-green-50' : 'border-gray-200 hover:bg-gray-50'">
                                <input type="radio" v-model="form.paymentMethod" value="cod" class="text-[#009933] focus:ring-[#009933] w-5 h-5">
                                <span class="font-bold text-gray-800">Cash on Delivery (COD)</span>
                            </label>
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full bg-[#009933] text-white py-4 rounded-xl font-black text-lg hover:bg-green-700 transition-colors shadow-md disabled:bg-gray-400 flex items-center justify-center gap-2 mt-4"
                    >
                        <span v-if="form.processing">Processing...</span>
                        <span v-else>Place Order - {{ formatPrice(orderSummary.total) }}</span>
                    </button>
                </form>
            </div>

            <div class="w-full lg:w-2/5">
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200 sticky top-32">
                    <h2 class="text-xl font-black text-gray-800 mb-6">Order Summary</h2>
                    
                    <div class="flex flex-col gap-4 mb-6 border-b border-gray-200 pb-6">
                        <div v-for="(item, index) in orderSummary.items" :key="index" class="flex justify-between items-start text-sm">
                            <div class="flex gap-2">
                                <span class="font-bold text-gray-500">{{ item.qty }}x</span>
                                <span class="text-gray-800 line-clamp-2 pr-4">{{ item.name }}</span>
                            </div>
                            <span class="font-bold text-gray-800 shrink-0">{{ formatPrice(item.price) }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 text-sm text-gray-600 mb-6 border-b border-gray-200 pb-6">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-bold text-gray-800">{{ formatPrice(orderSummary.subtotal) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="font-bold text-gray-800">{{ formatPrice(orderSummary.shipping) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Taxes</span>
                            <span class="font-bold text-gray-800">{{ formatPrice(orderSummary.tax) }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end">
                        <span class="text-lg font-bold text-gray-800">Total</span>
                        <span class="text-3xl font-black text-[#009933]">{{ formatPrice(orderSummary.total) }}</span>
                    </div>
                </div>
            </div>

        </div>
    </main>
</template>