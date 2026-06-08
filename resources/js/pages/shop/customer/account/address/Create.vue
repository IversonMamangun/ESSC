<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
  MapPinIcon,
  PackageIcon,
  CheckCircle2Icon,
  PlusIcon,
  XIcon,
  PhoneIcon,
  Building2Icon,
  UserIcon,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { ref, watchEffect, reactive } from 'vue';
import { useAddress } from '@/composables/useAddress';
import UserAccountSidebar from '@/components/accounts/UserAccountSidebar.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import shop from '@/routes/shop';
import type { User, UserAddress } from '@/types';

const props = defineProps<{
  user: User;
}>();

const form = useForm({
  label: 'home',
  recipient_name: props.user.name || '',
  recipient_number: props.user.phone || '',
  region: '',
  province: '',
  city: '',
  barangay: '',
  street: '',
  postal_code: '',
  unit_bldg_house: '',
  landmark: '',
});

const submitAddress = () => {
  form.post(shop.account.addresses.store.url(), {
    preserveScroll: true,
  });
};

const userAddress = reactive(useAddress());

watchEffect(() => {
  form.region = userAddress.selectedRegion;
  form.province = userAddress.selectedProvince;
  form.city = userAddress.selectedCity;
  form.barangay = userAddress.selectedBarangay;
});

const breadcrumbs = [
  {
    title: 'My Addresses',
    href: shop.account.addresses.index.url(),
  },
  {
    title: 'Create Address',
    href: shop.account.addresses.create.url(),
  },
];
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
            <Breadcrumbs :breadcrumbs="breadcrumbs" />
            <div
              class="mb-8 flex flex-col justify-between gap-4 border-b border-zinc-200 pb-6 sm:flex-row sm:items-center dark:border-zinc-800"
            >
              <div>
                <h1 class="text-2xl font-black text-zinc-900 dark:text-white">
                  Create Address
                </h1>
                <p class="mt-1 text-zinc-500 dark:text-zinc-400">
                  Add a new address to your account
                </p>
              </div>
            </div>

            <form @submit.prevent="submitAddress" class="space-y-6">
              <div>
                <label
                  class="mb-3 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                  >Address Label</label
                >
                <div class="flex gap-4">
                  <Label
                    class="relative flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl border-2 p-3 transition-all"
                    :class="
                      form.label === 'home'
                        ? 'border-[#009933] bg-green-50/50 text-[#009933] dark:bg-green-900/10'
                        : 'border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-700'
                    "
                  >
                    <input
                      type="radio"
                      v-model="form.label"
                      value="home"
                      class="hidden"
                    />
                    <MapPinIcon class="h-4 w-4" />
                    <span class="text-sm font-bold">Home</span>
                  </Label>
                  <Label
                    class="relative flex flex-1 cursor-pointer items-center justify-center gap-2 rounded-xl border-2 p-3 transition-all"
                    :class="
                      form.label === 'office'
                        ? 'border-[#009933] bg-green-50/50 text-[#009933] dark:bg-green-900/10'
                        : 'border-zinc-200 bg-white text-zinc-600 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-700'
                    "
                  >
                    <input
                      type="radio"
                      v-model="form.label"
                      value="office"
                      class="hidden"
                    />
                    <Building2Icon class="h-4 w-4" />
                    <span class="text-sm font-bold">Office</span>
                  </Label>
                </div>
              </div>

              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Recipient Name</Label
                  >
                  <input
                    type="text"
                    v-model="form.recipient_name"
                    required
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-zinc-900 transition-all outline-none focus:border-[#009933] focus:ring-2 focus:ring-[#009933]/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <span
                    v-if="form.errors.recipient_name"
                    class="mt-1 block text-xs font-bold text-red-500"
                    >{{ form.errors.recipient_name }}</span
                  >
                </div>
                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Phone Number</Label
                  >
                  <input
                    type="text"
                    v-model="form.recipient_number"
                    required
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-zinc-900 transition-all outline-none focus:border-[#009933] focus:ring-2 focus:ring-[#009933]/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <span
                    v-if="form.errors.recipient_number"
                    class="mt-1 block text-xs font-bold text-red-500"
                    >{{ form.errors.recipient_number }}</span
                  >
                </div>
              </div>

              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >House No. / Unit / Floor / Building / Blk & Lot</Label
                  >
                  <input
                    type="text"
                    v-model="form.unit_bldg_house"
                    required
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-zinc-900 transition-all outline-none focus:border-[#009933] focus:ring-2 focus:ring-[#009933]/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <span
                    v-if="form.errors.unit_bldg_house"
                    class="mt-1 block text-xs font-bold text-red-500"
                    >{{ form.errors.unit_bldg_house }}</span
                  >
                </div>

                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Street</Label
                  >
                  <input
                    type="text"
                    v-model="form.street"
                    required
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-zinc-900 transition-all outline-none focus:border-[#009933] focus:ring-2 focus:ring-[#009933]/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <span
                    v-if="form.errors.street"
                    class="mt-1 block text-xs font-bold text-red-500"
                    >{{ form.errors.street }}</span
                  >
                </div>
              </div>

              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >City / Municipality</Label
                  >
                  <input
                    type="text"
                    v-model="form.city"
                    required
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-zinc-900 transition-all outline-none focus:border-[#009933] focus:ring-2 focus:ring-[#009933]/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <span
                    v-if="form.errors.city"
                    class="mt-1 block text-xs font-bold text-red-500"
                    >{{ form.errors.city }}</span
                  >
                </div>
                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Province</Label
                  >
                  <input
                    type="text"
                    v-model="form.province"
                    required
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-zinc-900 transition-all outline-none focus:border-[#009933] focus:ring-2 focus:ring-[#009933]/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <span
                    v-if="form.errors.province"
                    class="mt-1 block text-xs font-bold text-red-500"
                    >{{ form.errors.province }}</span
                  >
                </div>
              </div>

              <div>
                <Label
                  class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                  >Postal Code</Label
                >
                <input
                  type="text"
                  v-model="form.postal_code"
                  required
                  class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-zinc-900 transition-all outline-none focus:border-[#009933] focus:ring-2 focus:ring-[#009933]/20 sm:w-1/2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                />
                <span
                  v-if="form.errors.postal_code"
                  class="mt-1 block text-xs font-bold text-red-500"
                  >{{ form.errors.postal_code }}</span
                >
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>
