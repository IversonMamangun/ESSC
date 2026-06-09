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
  ChevronLeftIcon,
  SaveIcon,
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
import InputError from '@/components/InputError.vue';
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
                  Create Address
                </h1>
                <p class="mt-1 text-zinc-500 dark:text-zinc-400">
                  Add a new address to your account
                </p>
              </div>

              <Link
                :href="shop.account.addresses.index.url()"
                class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-lg border bg-accent px-3 py-2 text-sm font-medium shadow transition-all hover:bg-accent-foreground/10 active:scale-95"
              >
                <ChevronLeftIcon class="h-4 w-4" />
                <span>Back to Addresses</span>
              </Link>
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
                <InputError :message="form.errors.label" />
              </div>

              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Recipient Name</Label
                  >
                  <Input
                    type="text"
                    v-model="form.recipient_name"
                    placeholder="e.g. John Doe"
                    required
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <InputError :message="form.errors.recipient_name" />
                </div>
                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Phone Number</Label
                  >
                  <Input
                    type="text"
                    v-model="form.recipient_number"
                    placeholder="e.g. 09123456789"
                    required
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <InputError :message="form.errors.recipient_number" />
                </div>
              </div>

              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >House No. / Unit / Floor / Building / Blk & Lot</Label
                  >
                  <Input
                    type="text"
                    v-model="form.unit_bldg_house"
                    placeholder="e.g. Unit D, 2nd Floor, Blk 123, Lot 456"
                    required
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <InputError :message="form.errors.unit_bldg_house" />
                </div>

                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Street</Label
                  >
                  <Input
                    type="text"
                    v-model="form.street"
                    placeholder="e.g. 123 Main St"
                    required
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <InputError :message="form.errors.street" />
                </div>
              </div>

              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Region</Label
                  >
                  <Select v-model="userAddress.selectedRegion">
                    <SelectTrigger
                      class="w-full cursor-pointer rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none focus:ring-2 focus:ring-ring/50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                    >
                      <SelectValue placeholder="Select region" />
                    </SelectTrigger>

                    <SelectContent>
                      <SelectItem
                        v-for="region in userAddress.regions"
                        :key="region.code"
                        :value="region.name"
                        class="cursor-pointer"
                      >
                        {{ region.name }}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                  <InputError :message="form.errors.region" />
                </div>
                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Province</Label
                  >
                  <Select v-model="userAddress.selectedProvince">
                    <SelectTrigger
                      class="w-full cursor-pointer rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none focus:ring-2 focus:ring-ring/50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                      :disabled="
                        !userAddress.selectedRegion ||
                        userAddress.selectedRegion === 'NCR'
                      "
                    >
                      <SelectValue
                        :placeholder="
                          !userAddress.selectedRegion
                            ? 'Select region first'
                            : userAddress.selectedRegion === 'NCR'
                              ? 'NCR has no province, proceed to city'
                              : 'Select province'
                        "
                      />
                    </SelectTrigger>

                    <SelectContent>
                      <SelectItem
                        v-for="province in userAddress.provinces"
                        :key="province.code"
                        :value="province.name"
                        class="cursor-pointer"
                      >
                        {{ province.name }}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                  <InputError :message="form.errors.province" />
                </div>

                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >City</Label
                  >
                  <Select v-model="userAddress.selectedCity">
                    <SelectTrigger
                      class="w-full cursor-pointer rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none focus:ring-2 focus:ring-ring/50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                      :disabled="
                        userAddress.selectedRegion !== 'NCR' &&
                        !userAddress.selectedProvince
                      "
                    >
                      <SelectValue
                        :placeholder="
                          userAddress.selectedRegion !== 'NCR' &&
                          !userAddress.selectedProvince
                            ? 'Select province first'
                            : 'Select city'
                        "
                      />
                    </SelectTrigger>

                    <SelectContent>
                      <SelectItem
                        v-for="city in userAddress.cities"
                        :key="city.code"
                        :value="city.name"
                        class="cursor-pointer"
                      >
                        {{ city.name }}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                  <InputError :message="form.errors.city" />
                </div>

                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Barangay</Label
                  >
                  <Select v-model="userAddress.selectedBarangay">
                    <SelectTrigger
                      class="w-full cursor-pointer rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none focus:ring-2 focus:ring-ring/50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                      :disabled="!userAddress.selectedCity"
                    >
                      <SelectValue
                        :placeholder="
                          !userAddress.selectedCity
                            ? 'Select city first'
                            : 'Select barangay'
                        "
                      />
                    </SelectTrigger>

                    <SelectContent>
                      <SelectItem
                        v-for="barangay in userAddress.cities"
                        :key="barangay.code"
                        :value="barangay.name"
                        class="cursor-pointer"
                      >
                        {{ barangay.name }}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                  <InputError :message="form.errors.barangay" />
                </div>
                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Postal Code</Label
                  >
                  <Input
                    type="text"
                    v-model="form.postal_code"
                    placeholder="e.g. 1234"
                    maxlength="4"
                    required
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none focus:ring-2 focus:ring-ring/50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <InputError :message="form.errors.postal_code" />
                </div>
                <div>
                  <Label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Landmark</Label
                  >
                  <Input
                    type="text"
                    v-model="form.landmark"
                    placeholder="e.g. near the mall"
                    required
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none focus:ring-2 focus:ring-ring/50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <InputError :message="form.errors.landmark" />
                </div>
              </div>

              <div class="mt-6 flex justify-end">
                <Button
                  type="submit"
                  :disabled="form.processing"
                  :loading="form.processing"
                  class="cursor-pointer"
                >
                  <SaveIcon class="h-4 w-4" />
                  {{ form.processing ? 'Creating...' : 'Create Address' }}
                </Button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
    <Footer />
  </div>
</template>
