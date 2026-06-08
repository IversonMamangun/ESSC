<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Footer from '@/components/sections/Footer.vue';

import {
  CheckCircleIcon,
  PackageCheckIcon,
} from 'lucide-vue-next';

const props = defineProps<{
  order: {
    tracking_number: string;
    total_price: number;
    payment_method: string;
    status: string;
    created_at: string;
  };
}>();

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
  }).format(price);
};
</script>

<template>
  <Head title="Order Successful" />

  <div class="min-h-screen bg-gray-50 dark:bg-zinc-950">
    <TopBar />

    <div class="sticky top-0 z-50 mt-8">
      <Navbar />
    </div>

    <main
      class="flex min-h-[70vh] items-center justify-center px-4 py-16"
    >
      <div
        class="w-full max-w-2xl rounded-3xl border border-zinc-200 bg-white p-10 text-center shadow-xl dark:border-zinc-800 dark:bg-zinc-900"
      >
        <div
          class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-green-100"
        >
          <CheckCircleIcon
            class="h-14 w-14 text-green-600"
          />
        </div>

        <h1
          class="mb-3 text-4xl font-black text-zinc-900 dark:text-white"
        >
          Order Placed Successfully
        </h1>

        <p
          class="mx-auto mb-8 max-w-md text-zinc-500"
        >
          Thank you for your purchase.
          Your order has been received and is now being processed.
        </p>

        <div
          class="mb-8 rounded-2xl border bg-zinc-50 p-6 dark:bg-zinc-800"
        >
          <div class="text-sm text-zinc-500">
            Tracking Number
          </div>

          <div
            class="mt-2 text-2xl font-black tracking-wider"
          >
            {{ order.tracking_number }}
          </div>
        </div>

        <div
          class="mb-8 grid gap-4 rounded-2xl border p-6 text-left md:grid-cols-2"
        >
          <div>
            <p class="text-sm text-zinc-500">
              Payment Method
            </p>

            <p class="font-bold">
              {{ order.payment_method }}
            </p>
          </div>

          <div>
            <p class="text-sm text-zinc-500">
              Total Amount
            </p>

            <p
              class="font-bold text-[#009933]"
            >
              {{ formatPrice(order.total_price) }}
            </p>
          </div>

          <div>
            <p class="text-sm text-zinc-500">
              Status
            </p>

            <p class="font-bold capitalize">
              {{ order.status }}
            </p>
          </div>

          <div>
            <p class="text-sm text-zinc-500">
              Order Date
            </p>

            <p class="font-bold">
              {{ order.created_at }}
            </p>
          </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row">
          <Link
            href="/home"
            class="flex-1 rounded-xl border py-4 font-bold"
          >
            Continue Shopping
          </Link>

          <Link
            href="/purchases"
            class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-[#009933] py-4 font-bold text-white"
          >
            <PackageCheckIcon class="h-5 w-5" />
            View Orders
          </Link>
        </div>
      </div>
    </main>

    <Footer />
  </div>
</template>