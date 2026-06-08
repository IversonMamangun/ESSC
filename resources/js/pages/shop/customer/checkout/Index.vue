<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import {
  MapPinIcon,
  TruckIcon,
  CreditCardIcon,
  WalletIcon,
  PackageIcon,
  ShieldCheckIcon,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';


const props = defineProps<{
  orderSummary: {
    subtotal: number;
    shipping: number;
    tax: number;
    total: number;
    items?: Array<{
      name: string;
      qty: number;
      price: number;
    }>;
  };
}>();

const paymentMethod = ref('cod');
const note = ref('');

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
  }).format(price);
};

const placeOrder = () => {
  router.post('/checkout', {
    paymentMethod: paymentMethod.value,
    note: note.value,
  });
};

const totalItems = computed(() => {
  return props.orderSummary.items?.reduce(
    (total, item) => total + item.qty,
    0,
  );
});
</script>

<template>
  <Head title="Checkout" />

  <div class="min-h-screen bg-gray-50 dark:bg-zinc-950">
    <TopBar />

    <div class="sticky top-0 z-50 mt-8">
      <Navbar />
    </div>

    <main class="mx-auto max-w-7xl px-4 py-8">
      <div class="grid gap-6 lg:grid-cols-[1fr_400px]">

        <!-- LEFT -->
        <div class="space-y-6">

          <!-- ADDRESS -->
          <div
            class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
          >
            <div class="mb-4 flex items-center gap-2">
              <MapPinIcon class="h-5 w-5 text-[#009933]" />
              <h2 class="text-lg font-black">
                Delivery Address
              </h2>
            </div>

            <div
              class="rounded-xl border border-dashed border-[#009933]/40 bg-green-50 p-4 dark:bg-green-950/20"
            >
              <div class="font-bold">
                Juan Dela Cruz
              </div>

              <div class="text-sm text-zinc-500">
                09123456789
              </div>

              <div class="mt-2">
                Santa Ana, Taguig City, Metro Manila
              </div>
            </div>
          </div>

          <!-- PRODUCTS -->
          <div
            class="rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
          >
            <div class="border-b p-6">
              <h2 class="flex items-center gap-2 text-lg font-black">
                <PackageIcon class="h-5 w-5 text-[#009933]" />
                Order Items
              </h2>
            </div>

            <div
              v-for="item in orderSummary.items"
              :key="item.name"
              class="flex items-center justify-between border-b p-6 last:border-b-0"
            >
              <div>
                <h3 class="font-bold">
                  {{ item.name }}
                </h3>

                <p class="text-sm text-zinc-500">
                  Quantity × {{ item.qty }}
                </p>
              </div>

              <div class="font-black text-[#009933]">
                {{ formatPrice(item.price) }}
              </div>
            </div>
          </div>

          <!-- PAYMENT -->
          <div
            class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
          >
            <h2
              class="mb-5 flex items-center gap-2 text-lg font-black"
            >
              <CreditCardIcon class="h-5 w-5 text-[#009933]" />
              Payment Method
            </h2>

            <div class="space-y-3">

              <label
                class="flex cursor-pointer items-center gap-3 rounded-xl border p-4"
              >
                <input
                  v-model="paymentMethod"
                  type="radio"
                  value="cod"
                />
                <TruckIcon class="h-5 w-5" />
                Cash on Delivery
              </label>

              <label
                class="flex cursor-pointer items-center gap-3 rounded-xl border p-4"
              >
                <input
                  v-model="paymentMethod"
                  type="radio"
                  value="gcash"
                />
                <WalletIcon class="h-5 w-5" />
                GCash
              </label>

              <label
                class="flex cursor-pointer items-center gap-3 rounded-xl border p-4"
              >
                <input
                  v-model="paymentMethod"
                  type="radio"
                  value="credit_card"
                />
                <CreditCardIcon class="h-5 w-5" />
                Credit / Debit Card
              </label>

            </div>
          </div>

          <!-- NOTE -->
          <div
            class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
          >
            <h2 class="mb-4 font-black">
              Message to Seller
            </h2>

            <textarea
              v-model="note"
              rows="4"
              placeholder="Leave a note for the seller..."
              class="w-full rounded-xl border p-3"
            />
          </div>

        </div>

        <!-- RIGHT -->
        <aside>
          <div
            class="sticky top-28 rounded-3xl border border-zinc-200 bg-white p-8 shadow-xl dark:border-zinc-800 dark:bg-zinc-900"
          >
            <h2 class="mb-6 text-2xl font-black">
              Order Summary
            </h2>

            <div class="space-y-4">

              <div class="flex justify-between">
                <span>
                  Subtotal ({{ totalItems }} items)
                </span>

                <span>
                  {{ formatPrice(orderSummary.subtotal) }}
                </span>
              </div>

              <div class="flex justify-between">
                <span>Shipping</span>

                <span>
                  {{ formatPrice(orderSummary.shipping) }}
                </span>
              </div>

              <div class="flex justify-between">
                <span>Tax</span>

                <span>
                  {{ formatPrice(orderSummary.tax) }}
                </span>
              </div>

            </div>

            <div
              class="my-6 border-t-2 border-dashed pt-6"
            >
              <div class="flex justify-between">
                <span class="font-black">
                  Total
                </span>

                <span
                  class="text-3xl font-black text-[#009933]"
                >
                  {{ formatPrice(orderSummary.total) }}
                </span>
              </div>
            </div>

            <button
              @click="placeOrder"
              class="w-full rounded-2xl bg-[#009933] py-4 font-black text-white transition hover:bg-green-700"
            >
              Place Order
            </button>

            <div
              class="mt-4 flex items-center gap-2 text-xs text-zinc-500"
            >
              <ShieldCheckIcon class="h-4 w-4" />
              Secure checkout protected by our platform.
            </div>
          </div>
        </aside>

      </div>
    </main>

    <Footer />
  </div>
</template>