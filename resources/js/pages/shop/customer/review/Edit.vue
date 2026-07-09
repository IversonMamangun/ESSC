<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeftIcon } from 'lucide-vue-next';
import UserAccountSidebar from '@/components/accounts/UserAccountSidebar.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import CustomerForm from '@/components/review/CustomerForm.vue';
import shop from '@/routes/shop';
import type { User, Order } from '@/types';

const props = defineProps<{ user: User; order: Order }>();
</script>

<template>
  <Head :title="`Edit Review — Order #${order.order_number}`" />
  <div class="flex min-h-screen flex-col transition-colors duration-300">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8"><Navbar /></div>

    <main
      class="mx-auto w-full max-w-7xl flex-grow px-4 py-8 sm:px-6 md:py-12 lg:px-8"
    >
      <div class="flex flex-col gap-8 lg:flex-row">
        <UserAccountSidebar :name="user.name" :avatar="user.avatar" />

        <div class="min-w-0 flex-1">
          <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <Link
              :href="shop.orders.index.url()"
              class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200"
            >
              <ArrowLeftIcon class="h-4 w-4" /> Back to Purchases
            </Link>
            <div class="text-sm text-zinc-500">
              Order No:
              <span class="font-bold text-zinc-800 dark:text-white"
                >#{{ order.order_number }}</span
              >
            </div>
          </div>

          <CustomerForm
            mode="edit"
            :order-number="order.order_number"
            :store-name="order.store_name"
            :items="order.items"
          >
            <template #cancel>
              <Link
                :href="shop.orders.index.url()"
                class="cursor-pointer rounded-xl border border-zinc-200 bg-white px-6 py-3 text-sm font-bold text-zinc-600 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700"
              >
                Cancel
              </Link>
            </template>
          </CustomerForm>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>
