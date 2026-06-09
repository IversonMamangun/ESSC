<script setup lang="ts">
import { MapPinIcon, Building2Icon, SaveIcon, PlusIcon } from 'lucide-vue-next';
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
import InputError from '@/components/InputError.vue';
import type { InertiaForm } from '@inertiajs/vue3';

interface PsgcItem {
  code: string;
  name: string;
}

export interface AddressDropdownState {
  regions: PsgcItem[];
  provinces: PsgcItem[];
  cities: PsgcItem[];
  barangays: PsgcItem[];
  selectedRegion: string;
  selectedProvince: string;
  selectedCity: string;
  selectedBarangay: string;
  isNcr: boolean;
}

export interface AddressFields {
  label: string;
  recipient_name: string;
  recipient_number: string;
  region: string;
  province: string;
  city: string;
  barangay: string;
  street: string;
  postal_code: string;
  unit_bldg_house: string;
  landmark: string;
  is_default: boolean;
}

const props = defineProps<{
  form: InertiaForm<AddressFields>;
  userAddress: AddressDropdownState;
  /** Show "Set as default" checkbox — only relevant in edit mode */
  showDefaultToggle?: boolean;
  /** Prevent unchecking when the address is already the default */
  isAlreadyDefault?: boolean;
  submitLabel?: string;
}>();

const emit = defineEmits<{
  submit: [];
}>();
</script>

<template>
  <form @submit.prevent="emit('submit')" class="space-y-6">
    <!-- Label -->
    <div>
      <label
        class="mb-3 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
      >
        Address Label
      </label>
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

    <!-- Recipient -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div>
        <Label
          class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
        >
          Recipient Name
        </Label>
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
        >
          Phone Number
        </Label>
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

    <!-- Unit / Street -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
      <div>
        <Label
          class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
        >
          House No. / Unit / Floor / Building / Blk &amp; Lot
        </Label>
        <Input
          type="text"
          v-model="form.unit_bldg_house"
          placeholder="e.g. Unit D, 2nd Floor, Blk 123"
          required
          class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
        />
        <InputError :message="form.errors.unit_bldg_house" />
      </div>
      <div>
        <Label
          class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
        >
          Street
        </Label>
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

    <!-- Region / Province / City / Barangay -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
      <!-- Region -->
      <div>
        <Label
          class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
        >
          Region
        </Label>
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

      <!-- Province -->
      <div>
        <Label
          class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
        >
          Province
        </Label>
        <Select
          v-model="userAddress.selectedProvince"
          :disabled="!userAddress.selectedRegion || userAddress.isNcr"
        >
          <SelectTrigger
            class="w-full cursor-pointer rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none focus:ring-2 focus:ring-ring/50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
          >
            <SelectValue
              :placeholder="
                !userAddress.selectedRegion
                  ? 'Select region first'
                  : userAddress.isNcr
                    ? 'NCR — proceed to city'
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

      <!-- City -->
      <div>
        <Label
          class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
        >
          City / Municipality
        </Label>
        <Select
          v-model="userAddress.selectedCity"
          :disabled="!userAddress.isNcr && !userAddress.selectedProvince"
        >
          <SelectTrigger
            class="w-full cursor-pointer rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none focus:ring-2 focus:ring-ring/50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
          >
            <SelectValue
              :placeholder="
                !userAddress.isNcr && !userAddress.selectedProvince
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

      <!-- Barangay -->
      <div>
        <Label
          class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
        >
          Barangay
        </Label>
        <Select
          v-model="userAddress.selectedBarangay"
          :disabled="!userAddress.selectedCity"
        >
          <SelectTrigger
            class="w-full cursor-pointer rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none focus:ring-2 focus:ring-ring/50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
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
              v-for="barangay in userAddress.barangays"
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

      <!-- Postal Code -->
      <div>
        <Label
          class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
        >
          Postal Code
        </Label>
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

      <!-- Landmark -->
      <div>
        <Label
          class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
        >
          Landmark
        </Label>
        <Input
          type="text"
          v-model="form.landmark"
          placeholder="e.g. near the mall"
          class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-5 text-zinc-900 transition-all outline-none focus:ring-2 focus:ring-ring/50 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
        />
        <InputError :message="form.errors.landmark" />
      </div>
    </div>

    <!-- Set as default toggle (edit only) -->
    <div v-if="showDefaultToggle">
      <Label
        class="flex cursor-pointer items-center gap-3 rounded-xl border-2 p-4 transition-all"
        :class="
          form.is_default
            ? 'border-[#009933] bg-green-50/50 dark:bg-green-900/10'
            : 'border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800'
        "
        :for="isAlreadyDefault ? undefined : 'is_default_toggle'"
      >
        <input
          id="is_default_toggle"
          type="checkbox"
          v-model="form.is_default"
          :disabled="isAlreadyDefault"
          class="h-4 w-4 cursor-pointer accent-[#009933] disabled:cursor-not-allowed"
        />
        <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300">
          Set as default address
        </span>
        <span
          v-if="isAlreadyDefault"
          class="ml-auto text-xs font-medium text-[#009933]"
        >
          Already default
        </span>
      </Label>
    </div>

    <!-- Submit -->
    <div class="flex justify-end">
      <Button
        type="submit"
        :disabled="form.processing"
        class="cursor-pointer gap-2"
      >
        <SaveIcon v-if="submitLabel !== 'Create Address'" class="h-4 w-4" />
        <PlusIcon v-else class="h-4 w-4" />
        {{ form.processing ? 'Saving…' : (submitLabel ?? 'Save Address') }}
      </Button>
    </div>
  </form>
</template>
