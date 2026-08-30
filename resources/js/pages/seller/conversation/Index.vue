<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
  ChevronRightIcon,
  MessageCircleMoreIcon,
  StarIcon,
  AwardIcon,
  BoxIcon,
  AlertCircleIcon,
} from 'lucide-vue-next';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Pagination from '@/components/Pagination.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import SellerStoreHeader from '@/components/SellerStoreHeader.vue';
import seller from '@/routes/seller';
import type { Store, PaginatedSellerConversationIndex } from '@/types';

const props = defineProps<{
  conversations: PaginatedSellerConversationIndex;
  store: Store;
}>();

const breadcrumbs = [
  {
    title: 'Dashboard',
    href: seller.dashboard(),
  },
  {
    title: 'Conversations',
    href: seller.conversations.index(),
  },
];

function formatConversationTime(date: string | null) {
  if (!date) return '';

  const d = new Date(date);
  const now = new Date();
  const isToday = d.toDateString() === now.toDateString();

  if (isToday) {
    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  }

  const isThisYear = d.getFullYear() === now.getFullYear();

  return d.toLocaleDateString([], {
    month: 'short',
    day: 'numeric',
    year: isThisYear ? undefined : 'numeric',
  });
}
</script>

<template>
  <Head title="Conversations" />

  <div class="flex min-h-screen flex-col transition-colors duration-300">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8"><Navbar /></div>

    <main class="mx-auto w-full max-w-7xl grow px-4 py-10 sm:px-6 lg:px-8">
      <div class="mb-5 px-5">
        <Breadcrumbs :breadcrumbs="breadcrumbs" />
      </div>

      <div v-if="store.is_active" class="flex flex-col gap-4">
        <SellerStoreHeader :store="store">
          <template #details>
            <div
              class="flex items-center rounded-lg border border-zinc-200 bg-zinc-100 px-3 py-1.5 text-sm text-zinc-600 transition-colors dark:border-zinc-700/50 dark:bg-zinc-800/50 dark:text-zinc-300"
            >
              <StarIcon class="mr-1.5 h-4 w-4 fill-current text-amber-400" />
              <span class="font-bold text-zinc-800 dark:text-zinc-100">
                {{ store.rating.toFixed(1) }}
              </span>
              <span class="ml-1 text-xs text-zinc-600 dark:text-zinc-400">
                ({{ store.reviews_count }} reviews)
              </span>
            </div>

            <div
              class="flex items-center rounded-lg border border-zinc-200 bg-zinc-100 px-3 py-1.5 text-sm text-zinc-600 transition-colors dark:border-zinc-700/50 dark:bg-zinc-800/50 dark:text-zinc-300"
            >
              <BoxIcon
                class="mr-1.5 h-4 w-4 fill-white text-zinc-400 dark:fill-black"
              />
              <span class="font-bold text-zinc-800 dark:text-zinc-100">
                {{ store.sold_count }}
              </span>
              <span class="ml-1 text-xs text-zinc-600 dark:text-zinc-400"
                >sold</span
              >
            </div>
          </template>
          <template #actions>
            <span
              v-if="store.is_official"
              class="mx-auto flex w-max items-center rounded bg-[#009933] py-2 ps-2 pe-3.5 text-[10px] font-black tracking-wider text-white uppercase shadow-sm md:mx-0"
            >
              <AwardIcon class="mr-1.5 h-4 w-4 fill-amber-400" />
              Official Shop
            </span>
          </template>
        </SellerStoreHeader>

        <div
          v-if="props.conversations.data.length > 0"
          class="overflow-hidden rounded-xl border bg-card shadow-sm"
        >
          <Link
            v-for="conversation in props.conversations.data"
            :key="conversation.id"
            :href="seller.conversations.show(conversation.id)"
            class="group relative flex min-h-[76px] items-center gap-3 border-b px-4 py-3.5 transition-colors outline-none last:border-b-0 hover:bg-muted/50 focus-visible:bg-muted/50 focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-inset"
          >
            <!-- Avatar -->
            <div
              class="relative flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-full bg-[#009933] text-sm font-bold text-white ring-1 ring-black/5 dark:ring-white/10"
            >
              <img
                v-if="conversation.user.avatar"
                :src="conversation.user.avatar"
                :alt="conversation.user.name"
                class="h-full w-full object-cover"
              />

              <span v-else>
                {{ conversation.user.name.charAt(0).toUpperCase() }}
              </span>

              <!-- Online indicator todo -->
              <!--
            <span
              class="absolute right-0 bottom-0 h-3 w-3 rounded-full border-2 border-card bg-emerald-500"
            />
          -->
            </div>

            <!-- Conversation content -->
            <div class="min-w-0 flex-1">
              <!-- Name + time -->
              <div class="flex items-center justify-between gap-3">
                <div class="flex min-w-0 items-center gap-2">
                  <span
                    class="truncate text-sm text-foreground transition-colors group-hover:text-[#009933]"
                    :class="
                      conversation.store_unread_count > 0
                        ? 'font-bold'
                        : 'font-semibold'
                    "
                  >
                    {{ conversation.user.name }}
                  </span>
                </div>

                <span class="shrink-0 text-xs text-muted-foreground">
                  {{ formatConversationTime(conversation.last_message_at) }}
                </span>
              </div>

              <!-- Last message + unread badge -->
              <div class="mt-1 flex items-center justify-between gap-3">
                <p
                  class="truncate text-sm text-muted-foreground"
                  :class="{
                    'font-medium text-foreground':
                      conversation.store_unread_count > 0,
                  }"
                >
                  {{ conversation.last_message ?? 'No messages yet' }}
                </p>

                <span
                  v-if="conversation.store_unread_count > 0"
                  class="flex h-5 min-w-5 shrink-0 items-center justify-center rounded-full bg-[#009933] px-1.5 text-[11px] font-bold text-white"
                >
                  {{
                    conversation.store_unread_count > 99
                      ? '99+'
                      : conversation.store_unread_count
                  }}
                </span>
              </div>
            </div>

            <!-- Chevron -->
            <ChevronRightIcon class="h-5 w-5 text-muted-foreground" />
          </Link>
        </div>

        <!-- Empty state -->
        <Card v-else class="rounded-xl shadow-sm">
          <CardContent
            class="flex flex-col items-center gap-3 py-16 text-center"
          >
            <div
              class="flex h-14 w-14 items-center justify-center rounded-full bg-muted"
            >
              <MessageCircleMoreIcon class="h-7 w-7 text-muted-foreground/60" />
            </div>

            <div>
              <p class="font-semibold">No conversations yet</p>
              <p class="mt-1 text-sm text-muted-foreground">
                Messages from your customers will show up here.
              </p>
            </div>
          </CardContent>
        </Card>

        <!-- Pagination -->
        <div class="flex justify-center">
          <Pagination :links="props.conversations.meta.links" />
        </div>
      </div>

      <div v-else class="flex flex-col gap-8">
        <Alert variant="destructive">
          <AlertCircleIcon class="mt-1 h-5 w-5" />
          <AlertTitle class="text-xl font-semibold">Store Inactive</AlertTitle>
          <AlertDescription class="mt-1">
            The store {{ props.store.name }} is currently deactivated.
            <span> Please contact support for more information. </span>
          </AlertDescription>
        </Alert>
      </div>
    </main>
  </div>
</template>
