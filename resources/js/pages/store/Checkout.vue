<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue'; // Added onMounted
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

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

const form = useForm({
    selected_ids: props.selectedIds,
    fullName: '', 
    phone: '',
    address: '',
    city: '',
    province: '',
    zip: '',
    paymentMethod: 'cod',
});

const useAccountDetails = ref(false);
const showProfileModal = ref(false); 

// Helper function to check if database has all required fields
const isProfileComplete = () => {
    return props.user.phone && props.user.address && props.user.city && props.user.province;
};

// Fill the form with DB data
const fillFormWithUserData = () => {
    form.fullName = props.user.name;
    form.phone = props.user.phone || '';
    form.address = props.user.address || '';
    form.city = props.user.city || '';
    form.province = props.user.province || '';
    form.zip = props.user.zip || '';
};

// What happens when you manually click the checkbox
const toggleAccountDetails = () => {
    if (useAccountDetails.value) {
        if (!isProfileComplete()) {
            // Uncheck and show warning if missing data
            useAccountDetails.value = false;
            showProfileModal.value = true;
        } else {
            fillFormWithUserData();
        }
    } else {
        // Clear fields if unchecked
        form.fullName = '';
        form.phone = '';
        form.address = '';
        form.city = '';
        form.province = '';
        form.zip = '';
    }
};

// NEW: Auto-check and fill the moment the page loads!
onMounted(() => {
    if (isProfileComplete()) {
        useAccountDetails.value = true;
        fillFormWithUserData();
    }
});

const submitOrder = () => {
    form.post('/checkout');
};
</script>

<template>
    <Head title="Secure Checkout" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>

    <main class="flex w-full justify-center px-4 mt-8 mb-24 relative">
        <div class="w-full max-w-6xl flex flex-col lg:flex-row gap-8">
            
            <div class="flex-1">
                <h1 class="text-2xl font-black text-gray-800 mb-6">Secure Checkout</h1>

                <div v-if="$page.props.errors.stock" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 font-bold">
                    {{ $page.props.errors.stock }}
                </div>

                <form @submit.prevent="submitOrder" class="flex flex-col gap-6">
                    
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-100">
                        
                        <div class="flex justify-between items-center border-b pb-4 mb-4">
                            <h2 class="text-lg font-bold text-gray-800">Delivery Address</h2>
                            
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input 
                                    type="checkbox" 
                                    v-model="useAccountDetails" 
                                    @change="toggleAccountDetails"
                                    class="w-4 h-4 text-[#009933] rounded focus:ring-[#009933] cursor-pointer"
                                >
                                <span class="text-sm font-bold text-gray-600 group-hover:text-[#009933] transition-colors">
                                    Use my account details
                                </span>
                            </label>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Full Name</label>
                                <input v-model="form.fullName" type="text" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-[#009933]">
                                <span v-if="form.errors.fullName" class="text-red-500 text-xs">{{ form.errors.fullName }}</span>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Phone Number</label>
                                <input v-model="form.phone" type="text" placeholder="09123456789" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-[#009933]">
                                <span v-if="form.errors.phone" class="text-red-500 text-xs">{{ form.errors.phone }}</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Street Address / Bldg / Barangay</label>
                            <input v-model="form.address" type="text" required placeholder="House No., Street Name, Barangay" class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-[#009933]">
                            <span v-if="form.errors.address" class="text-red-500 text-xs">{{ form.errors.address }}</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">City</label>
                                <input v-model="form.city" type="text" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-[#009933]">
                                <span v-if="form.errors.city" class="text-red-500 text-xs">{{ form.errors.city }}</span>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Province</label>
                                <input v-model="form.province" type="text" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-[#009933]">
                                <span v-if="form.errors.province" class="text-red-500 text-xs">{{ form.errors.province }}</span>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Zip Code</label>
                                <input v-model="form.zip" type="text" required class="w-full rounded-xl border border-gray-300 p-3 outline-none focus:border-[#009933]">
                                <span v-if="form.errors.zip" class="text-red-500 text-xs">{{ form.errors.zip }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-100">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Payment Method</h2>
                        
                        <div class="flex flex-col gap-3">
                            <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition" :class="{'border-[#009933] bg-green-50': form.paymentMethod === 'cod'}">
                                <input type="radio" v-model="form.paymentMethod" value="cod" class="w-5 h-5 text-[#009933] focus:ring-[#009933]">
                                <span class="font-bold text-gray-800">Cash on Delivery (COD)</span>
                            </label>
                            
                            <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer hover:bg-gray-50 transition" :class="{'border-[#009933] bg-green-50': form.paymentMethod === 'gcash'}">
                                <input type="radio" v-model="form.paymentMethod" value="gcash" class="w-5 h-5 text-[#009933] focus:ring-[#009933]">
                                <span class="font-bold text-gray-800">GCash</span>
                            </label>
                        </div>
                    </div>

                </form>
            </div>

            <div class="w-full lg:w-96 shrink-0">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-neutral-100 sticky top-32">
                    <h2 class="text-xl font-black text-gray-800 mb-6">Order Summary</h2>
                    
                    <div class="flex flex-col gap-3 text-sm mb-4">
                        <div v-for="item in orderSummary.items" :key="item.name" class="flex justify-between text-gray-600">
                            <span class="truncate pr-4">{{ item.qty }}x {{ item.name }}</span>
                            <span class="font-bold">₱{{ item.price.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 flex flex-col gap-3 text-sm mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-bold">₱{{ orderSummary.subtotal.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>VAT (12%)</span>
                            <span class="font-bold">₱{{ orderSummary.tax.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Delivery Fee</span>
                            <span class="font-bold">₱{{ orderSummary.shipping.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="text-2xl font-black text-[#009933]">₱{{ orderSummary.total.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
                        </div>
                    </div>
                    
                    <button 
                        @click="submitOrder"
                        :disabled="form.processing"
                        class="w-full bg-[#009933] text-white py-4 rounded-xl font-bold text-lg hover:bg-green-700 transition-colors shadow-md flex items-center justify-center gap-2 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    >
                        <span v-if="form.processing">Processing...</span>
                        <span v-else>Place Order</span>
                    </button>
                    
                    <div class="mt-4 text-center">
                        <Link href="/cart" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition-colors">
                            Return to Cart
                        </Link>
                    </div>
                </div>
            </div>

        </div>

        <div v-if="showProfileModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
            <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl transform transition-all">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">⚠️</span>
                </div>
                
                <h3 class="text-xl font-black text-gray-800 text-center mb-2">Profile Incomplete</h3>
                <p class="text-sm text-gray-600 text-center mb-8">
                    We couldn't find a phone number linked to your account. Please update your profile to use the auto-fill feature.
                </p>
                
                <div class="flex flex-col gap-3">
                    <Link 
                        href="/settings/profile" 
                        class="w-full bg-[#009933] text-white py-3 rounded-xl font-bold text-center hover:bg-green-700 transition-colors block"
                    >
                        Complete Profile Now
                    </Link>
                    
                    <button 
                        @click="showProfileModal = false" 
                        class="w-full bg-gray-100 text-gray-700 py-3 rounded-xl font-bold hover:bg-gray-200 transition-colors"
                    >
                        Cancel & Enter Manually
                    </button>
                </div>
            </div>
        </div>

    </main>
</template>