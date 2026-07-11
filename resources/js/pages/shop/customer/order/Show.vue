<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
  PhoneIcon,
  ArrowLeftIcon,
  MapPinIcon,
  PackageIcon,
  StoreIcon,
} from 'lucide-vue-next';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import UserAccountSidebar from '@/components/accounts/UserAccountSidebar.vue';
import OrderStatusBadge from '@/components/orders/OrderStatusBadge.vue';
import shop from '@/routes/shop';
import type { OrderShow } from '@/types';

const props = defineProps<{
  user: { name: string; avatar: string | null };
  order: OrderShow;
}>();

function formatCurrency(value: number) {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
    maximumFractionDigits: 2,
  }).format(Number(value));
}
function formatDate(value: string) {
  return new Date(value).toLocaleString('en-PH', {
    dateStyle: 'medium',
    timeStyle: 'short',
  });
}
function openImageInNewTab(url: string | null | undefined) {
  if (!url) return;
  window.open(url, '_blank', 'noopener,noreferrer');
}

const TIMESTAMP_LABELS: Record<string, string> = {
  created_at: 'Placed Order',
  confirmed_at: 'Confirmed',
  processing_at: 'Processing',
  packed_at: 'Packed',
  shipped_at: 'Shipped',
  delivered_at: 'Delivered',
  completed_at: 'Completed',
  cancelled_at: 'Cancelled',
  return_requested_at: 'Return Requested',
  return_approved_at: 'Return Approved',
  returned_at: 'Returned',
};

const fullAddress = computed(() => {
  if (!props.order) return '';
  const a = props.order.shipping_address;
  return [a.unit_bldg_house, a.street, a.barangay, a.city, a.province, a.region]
    .filter(Boolean)
    .join(', ')
    .concat(a.postal_code ? ` ${a.postal_code}` : '');
});
const timelineSteps = computed(() => {
  if (!props.order) return [];
  return Object.entries(props.order.timestamps ?? {})
    .filter(([, value]) => !!value)
    .map(([key, value]) => ({
      key,
      label: TIMESTAMP_LABELS[key] ?? key,
      value: value as string,
    }))
    .sort((a, b) => new Date(a.value).getTime() - new Date(b.value).getTime());
});
</script>

<template>
  <Head :title="`Order Details #${order.id}`" />

  <div class="flex min-h-screen flex-col transition-colors duration-300">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
      <Navbar />
    </div>

    <main
      class="mx-auto w-full max-w-7xl flex-grow px-4 py-8 sm:px-6 md:py-12 lg:px-8"
    >
      <div class="flex flex-col gap-8 lg:flex-row">
        <UserAccountSidebar :name="user.name" :avatar="user.avatar" />

        <div class="min-w-0 flex-1">
          <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <Link
              :href="shop.orders.index()"
              class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200"
            >
              <ArrowLeftIcon class="h-4 w-4" /> Back to Purchases
            </Link>
            <div class="mx-3.5 flex items-center gap-2">
              <span class="text-sm text-zinc-500">Status:</span>
              <OrderStatusBadge :status="order.status" />
            </div>
          </div>

          <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
              <div
                class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6 dark:border-zinc-800 dark:bg-zinc-900"
              >
                <h3
                  class="mb-4 flex items-center gap-2 text-base font-black text-zinc-800 dark:text-white"
                >
                  <MapPinIcon class="h-5 w-5 text-[#009933]" /> Delivery Address
                </h3>

                <p
                  class="text-sm font-semibold text-zinc-800 dark:text-zinc-100"
                >
                  {{ order.shipping_address.recipient_name }}
                </p>
                <p
                  class="mt-0.5 flex items-center gap-1.5 text-sm text-zinc-500 dark:text-zinc-400"
                >
                  <PhoneIcon class="h-3.5 w-3.5" />
                  {{ order.shipping_address.recipient_phone }}
                </p>
                <p
                  class="mt-1.5 text-sm leading-relaxed text-zinc-600 dark:text-zinc-300"
                >
                  {{ fullAddress }}
                </p>
                <p
                  v-if="order.shipping_address.landmark"
                  class="mt-1 text-xs text-zinc-400 dark:text-zinc-500"
                >
                  Landmark: {{ order.shipping_address.landmark }}
                </p>
              </div>

              <div
                class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6 dark:border-zinc-800 dark:bg-zinc-900"
              >
                <div
                  class="mb-4 flex items-center gap-2 border-b border-zinc-200 pb-3 dark:border-zinc-800"
                >
                  <StoreIcon class="h-5 w-5 text-zinc-400" />
                  <span class="font-black text-zinc-800 dark:text-white">{{
                    order.store_name
                  }}</span>
                </div>

                <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                  <li
                    v-for="(item, index) in order.items"
                    :key="`${item.product_sku}-${index}`"
                    class="flex items-center gap-3 py-2"
                  >
                    <button
                      v-if="item.product_image"
                      type="button"
                      class="group relative h-12 w-12 shrink-0 overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700"
                      title="View image"
                      @click="openImageInNewTab(item.product_image)"
                    >
                      <img
                        :src="item.product_image"
                        class="h-full w-full cursor-zoom-in object-cover transition-transform duration-150 group-hover:scale-110"
                      />
                    </button>
                    <div
                      v-else
                      class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg border border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800"
                    >
                      <PackageIcon class="h-5 w-5 text-zinc-400" />
                    </div>

                    <div class="min-w-0 flex-1">
                      <p
                        class="truncate text-sm font-medium text-zinc-800 dark:text-zinc-100"
                      >
                        {{ item.product_name }}
                      </p>
                      <p
                        class="mt-0.5 flex items-center gap-1.5 text-xs text-zinc-400 dark:text-zinc-500"
                      >
                        <span class="font-mono">{{ item.product_sku }}</span>
                        <template v-if="item.variant_name">
                          <span>•</span>
                          <span>{{ item.variant_name }}</span>
                        </template>
                      </p>
                    </div>

                    <div class="shrink-0 text-right">
                      <p
                        class="text-sm font-semibold text-zinc-800 dark:text-zinc-100"
                      >
                        {{ formatCurrency(item.total) }}
                      </p>
                      <p
                        class="mt-0.5 text-xs text-zinc-400 dark:text-zinc-500"
                      >
                        {{ formatCurrency(item.price) }} × {{ item.quantity }}
                      </p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <div class="space-y-6">
              <div
                class="rounded-2xl border border-zinc-200 bg-zinc-50 p-6 dark:border-zinc-800 dark:bg-zinc-900"
              >
                <h3
                  class="mb-4 text-base font-black text-zinc-800 dark:text-white"
                >
                  Order Summary
                </h3>

                <div
                  class="space-y-4 border-b border-zinc-200 pb-5 text-sm text-zinc-600 dark:border-zinc-800 dark:text-zinc-400"
                >
                  <div class="flex items-center gap-3">
                    <div
                      class="flex h-7 w-7 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400"
                    >
                      <PackageIcon class="h-4 w-4 shrink-0" />
                    </div>
                    <span
                      >Order No:
                      <strong class="text-zinc-800 dark:text-white">{{
                        order.order_number
                      }}</strong></span
                    >
                  </div>

                  <div v-if="timelineSteps.length">
                    <p
                      class="mb-2 text-xs font-semibold text-zinc-500 dark:text-zinc-400"
                    >
                      Order Timeline
                    </p>
                    <ol
                      class="space-y-3 border-l border-zinc-200 pl-4 dark:border-zinc-800"
                    >
                      <li
                        v-for="step in timelineSteps"
                        :key="step.key"
                        class="relative"
                      >
                        <span
                          class="absolute top-1 -left-[21px] h-2.5 w-2.5 rounded-full bg-blue-500"
                        />
                        <p
                          class="text-sm font-medium text-zinc-800 dark:text-zinc-100"
                        >
                          {{ step.label }}
                        </p>
                        <p class="text-xs text-zinc-400 dark:text-zinc-500">
                          {{ formatDate(step.value) }}
                        </p>
                      </li>
                    </ol>
                  </div>
                </div>

                <div class="space-y-3 pt-4 text-sm">
                  <div class="flex justify-between text-zinc-500">
                    <span>Merchandise Subtotal</span>
                    <span class="text-zinc-800 dark:text-white">{{
                      formatCurrency(order.subtotal)
                    }}</span>
                  </div>
                  <div
                    v-if="Number(order.discount) > 0"
                    class="flex justify-between text-emerald-600 dark:text-emerald-400"
                  >
                    <span>Discount</span>
                    <span>-{{ formatCurrency(order.discount) }}</span>
                  </div>
                  <div
                    v-if="Number(order.shipping_fee) > 0"
                    class="flex justify-between text-zinc-500"
                  >
                    <span>Shipping Total</span>
                    <span class="text-zinc-800 dark:text-white">{{
                      formatCurrency(order.shipping_fee)
                    }}</span>
                  </div>
                  <div
                    class="flex justify-between border-t border-zinc-200 pt-3 text-base font-black dark:border-zinc-800"
                  >
                    <span class="text-zinc-800 dark:text-white"
                      >Order Total</span
                    >
                    <span class="text-[#009933]">{{
                      formatCurrency(order.total)
                    }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>
