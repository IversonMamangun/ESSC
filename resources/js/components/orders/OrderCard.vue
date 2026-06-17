<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

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
import shop from '@/routes/shop';
import type { Order } from '@/types';
import OrderStatusBadge from './OrderStatusBadge.vue';

const props = defineProps<{
  order: Order;
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
          :href="shop.orders.show.url({ order: order.id })" 
          class="font-semibold hover:text-[#009933] transition-colors hover:underline"
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
            {{ order.items.length }}
            item(s)
          </AccordionTrigger>

          <AccordionContent>
            <div class="space-y-4">
              <div
                v-for="(item, index) in order.items"
                :key="index"
                class="flex gap-4"
              >
                <Link 
                  :href="shop.orders.show.url({ order: order.id })" 
                  class="shrink-0 hover:opacity-80 transition-opacity"
                >
                  <img
                    :src="item.product_image ?? '/images/placeholder-product.png'"
                    :alt="item.product_name"
                    class="h-20 w-20 rounded-lg border object-cover"
                  />
                </Link>

                <div class="flex flex-1 justify-between">
                  <div>
                    <Link 
                      :href="shop.orders.show.url({ order: order.id })" 
                      class="font-medium hover:text-[#009933] transition-colors line-clamp-1 hover:underline"
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
                      Qty:
                      {{ item.quantity }}
                    </p>
                  </div>

                  <div class="font-semibold">
                    {{ formatPrice(item.price) }}
                  </div>
                </div>
              </div>
            </div>
          </AccordionContent>
        </AccordionItem>
      </Accordion>
    </CardContent>

    <CardFooter class="flex flex-col items-end gap-2 border-t pt-4">
      <div class="text-sm text-muted-foreground">
        Shipping:
        {{ formatPrice(order.shipping_fee) }}
      </div>

      <div class="text-lg font-bold">
        Total:
        {{ formatPrice(order.total) }}
      </div>
    </CardFooter>
  </Card>
</template>