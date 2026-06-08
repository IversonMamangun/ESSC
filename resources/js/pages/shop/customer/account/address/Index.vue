<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
  MapPinIcon,
  PackageIcon,
  CheckCircle2Icon,
  PlusIcon,
  PhoneIcon,
  Building2Icon,
  UserIcon,
} from 'lucide-vue-next';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import UserAccountSidebar from '@/components/accounts/UserAccountSidebar.vue';
import shop from '@/routes/shop';
import type { User, UserAddress } from '@/types';

const props = defineProps<{
  user: User;
  addresses: UserAddress[];
}>();

const createAddress = () => {
  router.visit(shop.account.addresses.create());
};
</script>

<template>
  <Head title="My Addresses" />
  <div class="flex min-h-screen flex-col">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
      <Navbar />
    </div>

    <main
      class="mx-auto w-full max-w-7xl grow px-4 py-8 sm:px-6 md:py-12 lg:px-8"
    >
      <div class="flex flex-col gap-8 lg:flex-row">
        <UserAccountSidebar :name="user.name" :avatar="user.avatar" />

        <div class="min-w-0 flex-1">
          <div
            class="rounded-3xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm transition-colors md:p-10 dark:border-zinc-800 dark:bg-zinc-900"
          >
            <div
              class="mb-8 flex flex-col justify-between gap-4 border-b border-zinc-200 pb-6 sm:flex-row sm:items-center dark:border-zinc-800"
            >
              <div>
                <h1 class="text-2xl font-black text-zinc-900 dark:text-white">
                  My Addresses
                </h1>
                <p class="mt-1 text-zinc-500 dark:text-zinc-400">
                  Manage where your items will be shipped
                </p>
              </div>

              <button
                @click="createAddress"
                class="flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#009933] px-5 py-2.5 font-bold text-white shadow-md transition-all hover:bg-green-700 active:scale-95"
              >
                <PlusIcon class="h-4 w-4" /> Add New Address
              </button>
            </div>

            <div
              v-if="props.addresses.length === 0"
              class="flex flex-col items-center justify-center py-12 text-center"
            >
              <div
                class="mb-4 flex h-20 w-20 items-center justify-center rounded-full border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-800"
              >
                <MapPinIcon class="h-8 w-8 text-zinc-300 dark:text-zinc-500" />
              </div>
              <h3
                class="mb-2 text-lg font-black text-zinc-800 dark:text-zinc-200"
              >
                No addresses yet
              </h3>
              <p class="max-w-sm text-zinc-500 dark:text-zinc-400">
                Add a delivery address so you can start shopping and checking
                out quickly.
              </p>
            </div>

            <div v-else class="grid grid-cols-1 gap-6 md:grid-cols-2">
              <div
                v-for="address in props.addresses"
                :key="address.id"
                class="relative rounded-2xl border-2 p-6 transition-all"
                :class="
                  address.is_default
                    ? 'border-[#009933] bg-green-50/30 dark:bg-green-900/10'
                    : 'border-zinc-200 bg-white hover:border-zinc-300 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:border-zinc-600'
                "
              >
                <div
                  v-if="address.is_default"
                  class="absolute top-4 right-4 flex items-center gap-1 rounded-md bg-[#009933] px-2.5 py-1 text-[10px] font-black tracking-wider text-white uppercase shadow-sm"
                >
                  <CheckCircle2Icon class="h-3 w-3" /> Default
                </div>

                <div class="mb-4 flex items-center gap-2">
                  <span
                    class="flex items-center gap-1.5 text-xs font-bold tracking-wider text-zinc-500 uppercase dark:text-zinc-400"
                  >
                    <Building2Icon
                      v-if="address.label === 'Office'"
                      class="h-3.5 w-3.5"
                    />
                    <MapPinIcon v-else class="h-3.5 w-3.5" />
                    {{ address.label }}
                  </span>
                </div>

                <h3
                  class="mb-1 text-lg font-black text-zinc-900 dark:text-white"
                >
                  {{ address.recipient_name }}
                </h3>
                <p
                  class="mb-4 flex items-center gap-2 text-sm font-medium text-zinc-500 dark:text-zinc-400"
                >
                  <PhoneIcon class="h-3 w-3" /> {{ address.recipient_number }}
                </p>

                <p
                  class="text-sm leading-relaxed text-zinc-700 dark:text-zinc-300"
                >
                  {{ address.unit_bldg_house }}, {{ address.street }} <br />
                  {{ address.city }}, {{ address.province }}
                  {{ address.postal_code }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>
