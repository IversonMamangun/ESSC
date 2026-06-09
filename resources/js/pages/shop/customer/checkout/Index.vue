<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import {
  MapPinIcon,
  TruckIcon,
  CreditCardIcon,
  WalletIcon,
  PackageIcon,
  ShieldCheckIcon,
  CheckCircle2Icon,
} from 'lucide-vue-next';
import { computed } from 'vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

interface CheckoutItem {
  name: string;
  qty: number;
  price: number;
}

// 1. Define Props to flexibly accept items nested OR top-level from backend
const props = defineProps<{
  orderSummary: {
    subtotal: number;
    shipping: number;
    tax: number;
    total: number;
    items?: CheckoutItem[];
  };
  items?: CheckoutItem[]; // Backup fallback if Laravel returns items as a flat root prop
  selectedIds: number[];
  addresses: Array<{
    id: number;
    label: string;
    recipient_name: string;
    phone: string;
    address: string;
    city: string;
    province: string;
    zip: string;
    is_default: number | boolean;
  }>;
}>();

// Unified safe data-fallback catcher for your item loops
const checkoutItems = computed<CheckoutItem[]>(() => {
  return props.items || props.orderSummary?.items || [];
});

// Find default address to pre-select it
const defaultAddress = props.addresses.find(addr => addr.is_default) || props.addresses[0];

// 2. Standard Inertia Form Management
const form = useForm({
  selected_ids: props.selectedIds,
  address_id: defaultAddress ? defaultAddress.id : null,
  paymentMethod: 'cod',
  note: '',
});

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
  }).format(price);
};

// 3. Dynamic payload processing via form helper
const placeOrder = () => {
  if (!form.address_id) {
    alert('Please select or add a delivery address before placing an order.');
    return;
  }
  
  form.post('/checkout', {
    preserveScroll: true,
  });
};

const totalItems = computed(() => {
  return checkoutItems.value.reduce((total, item) => total + item.qty, 0);
});
</script>

<template>
  <Head title="Checkout" />
  <div class="min-h-screen bg-zinc-50 dark:bg-[#29321F] text-zinc-900 dark:text-zinc-50">
    <TopBar />

    <div class="sticky top-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border-b border-zinc-100 dark:border-zinc-800">
      <Navbar />
    </div>

    <main class="mx-auto max-w-7xl px-4 py-10">
      <div class="grid gap-8 lg:grid-cols-[1fr_420px]">

        <div class="space-y-6">

          <!-- Delivery Address Block -->
          <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-5 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <MapPinIcon class="h-5 w-5 text-[#009933]" />
                <h2 class="text-xl font-bold tracking-tight">Delivery Address</h2>
              </div>
            </div>

            <div v-if="addresses.length > 0" class="grid gap-3">
              <label
                v-for="addr in addresses"
                :key="addr.id"
                class="relative flex cursor-pointer flex-col rounded-xl border p-4 transition-all duration-200 focus-within:ring-2 focus-within:ring-[#009933]/40"
                :class="form.address_id === addr.id 
                  ? 'border-[#009933] bg-green-50/30 dark:bg-green-950/10' 
                  : 'border-zinc-200 hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50'"
              >
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2">
                    <input
                      v-model="form.address_id"
                      type="radio"
                      name="address_selection"
                      :value="addr.id"
                      class="h-4 w-4 text-[#009933] focus:ring-[#009933]"
                    />
                    <span class="text-xs font-black uppercase tracking-wider text-zinc-400 bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 rounded">
                      {{ addr.label }}
                    </span>
                    <span v-if="addr.is_default" class="text-[10px] font-bold text-white bg-[#009933] px-1.5 py-0.5 rounded">
                      DEFAULT
                    </span>
                  </div>
                  <CheckCircle2Icon v-if="form.address_id === addr.id" class="h-5 w-5 text-[#009933]" />
                </div>

                <div class="mt-3 font-bold text-base">{{ addr.recipient_name }}</div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ addr.phone }}</div>
                <div class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                  {{ addr.address }}, {{ addr.city }}, {{ addr.province }}, {{ addr.zip }}
                </div>
              </label>
            </div>

            <div v-else class="text-center py-6 border border-dashed border-zinc-200 dark:border-zinc-800 rounded-xl">
              <p class="text-sm text-zinc-500 mb-3">No delivery addresses configured yet.</p>
              <a href="/account/addresses/create" class="inline-flex text-xs font-bold text-[#009933] hover:underline">
                + Add New Address
              </a>
            </div>
            
            <div v-if="form.errors.address_id" class="mt-2 text-xs font-semibold text-red-500">
              {{ form.errors.address_id }}
            </div>
          </div>

          <!-- Order Items Block (Fixed) -->
          <div class="rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900 overflow-hidden">
            <div class="border-b border-zinc-100 dark:border-zinc-800 p-6">
              <h2 class="flex items-center gap-2 text-xl font-bold tracking-tight">
                <PackageIcon class="h-5 w-5 text-[#009933]" />
                Order Items
              </h2>
            </div>

            <!-- Empty state check added for visibility defense -->
            <div v-if="checkoutItems.length === 0" class="p-8 text-center text-sm text-zinc-400">
              No items selected for purchase.
            </div>

            <div v-else class="divide-y divide-zinc-100 dark:divide-zinc-800">
              <div
                v-for="item in checkoutItems"
                :key="item.name"
                class="flex items-center justify-between p-6 hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition-colors"
              >
                <div>
                  <h3 class="font-bold text-zinc-800 dark:text-zinc-200">{{ item.name }}</h3>
                  <p class="text-sm text-zinc-400 mt-0.5">Quantity &times; {{ item.qty }}</p>
                </div>
                <div class="font-black text-[#009933] text-lg">
                  {{ formatPrice(item.price) }}
                </div>
              </div>
            </div>
          </div>

          <!-- Payment Method Selection -->
          <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <h2 class="mb-5 flex items-center gap-2 text-xl font-bold tracking-tight">
              <CreditCardIcon class="h-5 w-5 text-[#009933]" />
              Payment Method
            </h2>

            <div class="grid gap-3 sm:grid-cols-3">
              <label
                class="flex cursor-pointer flex-col items-center justify-center rounded-xl border p-4 text-center transition-all duration-200"
                :class="form.paymentMethod === 'cod' 
                  ? 'border-[#009933] bg-green-50/30 dark:bg-green-950/10 ring-1 ring-[#009933]' 
                  : 'border-zinc-200 hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50'"
              >
                <input v-model="form.paymentMethod" type="radio" value="cod" class="sr-only" />
                <TruckIcon class="h-6 w-6 mb-2" :class="form.paymentMethod === 'cod' ? 'text-[#009933]' : 'text-zinc-400'" />
                <span class="text-sm font-bold">Cash on Delivery</span>
              </label>

              <label
                class="flex cursor-pointer flex-col items-center justify-center rounded-xl border p-4 text-center transition-all duration-200"
                :class="form.paymentMethod === 'gcash' 
                  ? 'border-[#009933] bg-green-50/30 dark:bg-green-950/10 ring-1 ring-[#009933]' 
                  : 'border-zinc-200 hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50'"
              >
                <input v-model="form.paymentMethod" type="radio" value="gcash" class="sr-only" />
                <WalletIcon class="h-6 w-6 mb-2" :class="form.paymentMethod === 'gcash' ? 'text-[#009933]' : 'text-zinc-400'" />
                <span class="text-sm font-bold">GCash Wallet</span>
              </label>

              <label
                class="flex cursor-pointer flex-col items-center justify-center rounded-xl border p-4 text-center transition-all duration-200"
                :class="form.paymentMethod === 'credit_card' 
                  ? 'border-[#009933] bg-green-50/30 dark:bg-green-950/10 ring-1 ring-[#009933]' 
                  : 'border-zinc-200 hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50'"
              >
                <input v-model="form.paymentMethod" type="radio" value="credit_card" class="sr-only" />
                <CreditCardIcon class="h-6 w-6 mb-2" :class="form.paymentMethod === 'credit_card' ? 'text-[#009933]' : 'text-zinc-400'" />
                <span class="text-sm font-bold">Credit/Debit Card</span>
              </label>
            </div>
            
            <div v-if="form.errors.paymentMethod" class="mt-2 text-xs font-semibold text-red-500">
              {{ form.errors.paymentMethod }}
            </div>
          </div>

          <!-- Notes Section -->
          <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <h2 class="mb-3 text-base font-bold">Message to Seller (Optional)</h2>
            <textarea
              v-model="form.note"
              rows="3"
              placeholder="Add any instructions for your order shipment..."
              class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-transparent p-4 text-sm focus:border-[#009933] focus:ring-1 focus:ring-[#009933] outline-none resize-none"
            />
          </div>

        </div>

        <!-- Sidebar Summary Block -->
        <aside>
          <div class="sticky top-28 rounded-3xl border border-zinc-200 bg-white p-8 shadow-lg dark:border-zinc-800 dark:bg-zinc-900">
            <h2 class="mb-6 text-2xl font-black tracking-tight">Order Summary</h2>

            <div class="space-y-4 text-sm text-zinc-600 dark:text-zinc-400">
              <div class="flex justify-between">
                <span>Subtotal ({{ totalItems }} items)</span>
                <span class="font-semibold text-zinc-900 dark:text-zinc-100">
                  {{ formatPrice(orderSummary.subtotal) }}
                </span>
              </div>

              <div class="flex justify-between">
                <span>Estimated Shipping</span>
                <span class="font-semibold text-zinc-900 dark:text-zinc-100">
                  {{ formatPrice(orderSummary.shipping) }}
                </span>
              </div>

              <div class="flex justify-between">
                <span>Tax (12% VAT Included)</span>
                <span class="font-semibold text-zinc-900 dark:text-zinc-100">
                  {{ formatPrice(orderSummary.tax) }}
                </span>
              </div>
            </div>

            <div class="my-6 border-t-2 border-dashed border-zinc-100 dark:border-zinc-800 pt-6">
              <div class="flex items-baseline justify-between">
                <span class="font-black text-base">Total Order Payment</span>
                <span class="text-3xl font-black text-[#009933]">
                  {{ formatPrice(orderSummary.total) }}
                </span>
              </div>
            </div>

            <button
              @click="placeOrder"
              :disabled="form.processing"
              class="w-full rounded-2xl bg-[#009933] py-4 font-black text-white tracking-wide text-center shadow-md transition-all duration-150 hover:bg-green-700 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-60"
            >
              <span v-if="form.processing">Processing Order...</span>
              <span v-else>Place Order</span>
            </button>

            <div v-if="form.errors.selected_ids" class="mt-3 text-center text-xs font-semibold text-red-500">
              {{ form.errors.selected_ids }}
            </div>

            <div class="mt-5 flex items-center justify-center gap-2 text-[11px] text-zinc-400 dark:text-zinc-500 text-center">
              <ShieldCheckIcon class="h-4 w-4 text-zinc-400 flex-shrink-0" />
              Secure multi-layer encrypted checkout system.
            </div>
          </div>
        </aside>

      </div>
    </main>

    <Footer />
  </div>
</template>