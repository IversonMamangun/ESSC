<script setup lang="ts">
import type { Order } from '@/types';
import { Link } from '@inertiajs/vue3';
import shop from '@/routes/shop';
import {
  TruckIcon,
  StarIcon,
  RotateCcwIcon,
  MessageSquareIcon,
} from 'lucide-vue-next';
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from '@/components/ui/accordion';
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
} from '@/components/ui/card';
import OrderStatusBadge from '@/components/orders/OrderStatusBadge.vue';

const props = defineProps<{
  order: Order;
}>();

// define emits to pass events to parent
defineEmits<{
  (e: 'track', id: number): void;
  (e: 'buyAgain', id: number): void;
  (e: 'rate', id: number): void;
  (e: 'viewRating', id: number): void;
}>();

const formatPrice = (value: number) => {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
  }).format(value);
};
</script>

<template>
  <Card>
    <CardHeader class="flex flex-row items-center justify-between">
      <div>
        <Link
          :href="shop.orders.show.url(order.order_number)"
          class="font-semibold transition-colors hover:text-[#009933] hover:underline"
        >
          {{ order.store_name }}
        </Link>
      </div>
      <OrderStatusBadge :status="order.status" />
    </CardHeader>

    <CardContent>
      <Accordion type="single" collapsible>
        <AccordionItem value="items">
          <AccordionTrigger class="cursor-pointer text-blue-500">
            {{ order.items.length }} item(s)
          </AccordionTrigger>
          <AccordionContent>
            <div class="space-y-4">
              <div
                v-for="(item, index) in order.items"
                :key="index"
                class="flex gap-4"
              >
                <Link
                  :href="shop.orders.show.url(order.order_number)"
                  class="shrink-0 transition-opacity hover:opacity-80"
                >
                  <img
                    :src="
                      item.product_image ?? '/images/placeholder-product.png'
                    "
                    :alt="item.product_name"
                    class="h-20 w-20 rounded-lg border object-cover"
                  />
                </Link>
                <div class="flex flex-1 justify-between">
                  <div>
                    <Link
                      :href="shop.orders.show.url(order.order_number)"
                      class="line-clamp-1 font-medium transition-colors hover:text-[#009933] hover:underline"
                    >
                      {{ item.product_name }}
                    </Link>
                    <p
                      v-if="item.variant_name"
                      class="text-sm text-muted-foreground"
                    >
                      {{ item.variant_name }}
                    </p>
                    <p class="text-sm text-muted-foreground">
                      Qty: {{ item.quantity }}
                    </p>
                  </div>
                  <div class="font-semibold">{{ formatPrice(item.price) }}</div>
                </div>
              </div>
            </div>
          </AccordionContent>
        </AccordionItem>
      </Accordion>
    </CardContent>

    <CardFooter
      class="flex flex-col-reverse gap-4 border-t pt-4 sm:flex-row sm:items-center sm:justify-between"
    >
      <div class="flex w-full flex-wrap gap-2 sm:w-auto">
        <template v-if="order.status === 'to-receive'">
          <Link
            :href="shop.orders.show.url(order.order_number)"
            class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-bold text-white transition-colors hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
          >
            <TruckIcon class="mr-2 h-4 w-4" />
            Track Order
          </Link>
        </template>

        <template v-if="order.status === 'completed'">
          <button
            @click="$emit('buyAgain', order.id)"
            class="inline-flex cursor-pointer items-center justify-center rounded-xl bg-[#009933] px-4 py-2 text-sm font-bold text-white transition-colors hover:bg-[#007722]"
          >
            <RotateCcwIcon class="mr-2 h-4 w-4" />
            Buy Again
          </button>

          <!-- <button
            v-if="!order.is_rated"
            @click="$emit('rate', order.id)"
            class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-bold text-amber-700 hover:bg-amber-100 transition-colors dark:border-amber-900/30 dark:bg-amber-950/25 dark:text-amber-400 cursor-pointer"
          >
            <StarIcon class="mr-2 h-4 w-4 fill-amber-400 text-amber-400" />
            Rate Product
          </button>
          
          <button
            v-else
            @click="$emit('viewRating', order.id)"
            class="inline-flex items-center justify-center rounded-xl border border-zinc-200 bg-white px-4 py-2 text-sm font-bold text-zinc-600 hover:bg-zinc-50 transition-colors dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700 cursor-pointer"
          >
            <MessageSquareIcon class="mr-2 h-4 w-4" />
            View Rating
          </button> -->
        </template>
      </div>

      <div
        class="flex w-full flex-col items-end gap-0.5 border-b pb-3 sm:w-auto sm:border-0 sm:pb-0"
      >
        <div class="text-xs text-muted-foreground">
          Shipping: {{ formatPrice(order.shipping_fee) }}
        </div>
        <div class="text-lg font-black text-zinc-900 dark:text-white">
          Total:
          <span class="text-[#009933]">{{ formatPrice(order.total) }}</span>
        </div>
      </div>
    </CardFooter>
  </Card>
</template>
