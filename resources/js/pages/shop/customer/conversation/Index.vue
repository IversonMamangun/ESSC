<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ChevronRightIcon, MessageCircleMoreIcon } from 'lucide-vue-next';
import UserAccountSidebar from '@/components/accounts/UserAccountSidebar.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Pagination from '@/components/Pagination.vue';
import { Card, CardContent } from '@/components/ui/card';
import shop from '@/routes/shop';
import type { User, PaginatedCustomerConversationIndex } from '@/types';

const props = defineProps<{
  user: User;
  conversations: PaginatedCustomerConversationIndex;
}>();

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
  <Head title="Conversation" />

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
          <div
            v-if="props.conversations.data.length > 0"
            class="overflow-hidden rounded-xl border bg-card shadow-sm"
          >
            <Link
              v-for="conversation in props.conversations.data"
              :key="conversation.id"
              :href="shop.conversations.show(conversation.uuid)"
              class="group relative flex min-h-[76px] items-center gap-3 border-b px-4 py-3.5 transition-colors outline-none last:border-b-0 hover:bg-muted/50 focus-visible:bg-muted/50 focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-inset"
            >
              <!-- Avatar -->
              <div
                class="relative flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-full bg-[#009933] text-sm font-bold text-white ring-1 ring-black/5 dark:ring-white/10"
              >
                <img
                  v-if="conversation.store.logo"
                  :src="conversation.store.logo"
                  :alt="conversation.store.name"
                  class="h-full w-full object-cover"
                />

                <span v-else>
                  {{ conversation.store.name.charAt(0).toUpperCase() }}
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
                        conversation.user_unread_count > 0
                          ? 'font-bold'
                          : 'font-semibold'
                      "
                    >
                      {{ conversation.store.name }}
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
                        conversation.user_unread_count > 0,
                    }"
                  >
                    {{ conversation.last_message ?? 'No messages yet' }}
                  </p>

                  <span
                    v-if="conversation.user_unread_count > 0"
                    class="flex h-5 min-w-5 shrink-0 items-center justify-center rounded-full bg-[#009933] px-1.5 text-[11px] font-bold text-white"
                  >
                    {{
                      conversation.user_unread_count > 99
                        ? '99+'
                        : conversation.user_unread_count
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
                <MessageCircleMoreIcon
                  class="h-7 w-7 text-muted-foreground/60"
                />
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
      </div>
    </main>
    <Footer />
  </div>
</template>
