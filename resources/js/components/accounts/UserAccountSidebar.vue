<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
  UserIcon,
  MapPinIcon,
  PackageIcon,
  MessageCircleIcon,
} from 'lucide-vue-next';
import shop from '@/routes/shop';

interface Props {
  name: string;
  avatar?: string | null;
}
const { name, avatar = null } = defineProps<Props>();
const page = usePage();
const isActive = (path: string) => page.url.startsWith(path);
</script>

<template>
  <div class="w-full shrink-0 lg:w-64">
    <div class="mb-8 flex items-center gap-4">
      <div
        class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-white bg-zinc-200 shadow-sm dark:border-zinc-900 dark:bg-zinc-800"
      >
        <img
          v-if="avatar"
          :src="'/storage/' + avatar"
          class="h-full w-full object-cover"
          :alt="name"
        />
        <UserIcon v-else class="h-6 w-6 text-zinc-500" />
      </div>

      <div class="min-w-0">
        <p
          class="truncate leading-tight font-black text-zinc-900 dark:text-white"
        >
          {{ name }}
        </p>
      </div>
    </div>

    <nav class="space-y-1">
      <Link
        :href="shop.account.profile.edit()"
        :class="[
          isActive(shop.account.profile.edit().url)
            ? 'bg-green-50 font-black text-[#009933] dark:bg-green-900/10'
            : 'font-bold text-zinc-600 transition-colors hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800/50',
          'flex items-center gap-3 rounded-xl px-4 py-3',
        ]"
      >
        <UserIcon class="h-5 w-5" />
        Profile
      </Link>

      <Link
        :href="shop.account.addresses.index()"
        :class="[
          isActive(shop.account.addresses.index().url)
            ? 'bg-green-50 font-black text-[#009933] dark:bg-green-900/10'
            : 'font-bold text-zinc-600 transition-colors hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800/50',
          'flex items-center gap-3 rounded-xl px-4 py-3',
        ]"
      >
        <MapPinIcon class="h-5 w-5" />
        Addresses
      </Link>

      <Link
        :href="shop.orders.index()"
        :class="[
          isActive(shop.orders.index().url)
            ? 'bg-green-50 font-black text-[#009933] dark:bg-green-900/10'
            : 'font-bold text-zinc-600 transition-colors hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800/50',
          'flex items-center gap-3 rounded-xl px-4 py-3',
        ]"
      >
        <PackageIcon class="h-5 w-5" />
        Purchases
      </Link>

      <Link
        :href="shop.conversations.index()"
        :class="[
          isActive(shop.conversations.index().url)
            ? 'bg-green-50 font-black text-[#009933] dark:bg-green-900/10'
            : 'font-bold text-zinc-600 transition-colors hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800/50',
          'flex items-center gap-3 rounded-xl px-4 py-3',
        ]"
      >
        <MessageCircleIcon class="h-5 w-5" />
        Conversations
      </Link>
    </nav>
  </div>
</template>
