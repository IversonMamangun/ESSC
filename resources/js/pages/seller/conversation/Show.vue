<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import {
  MessageCircleMoreIcon,
  StarIcon,
  AwardIcon,
  BoxIcon,
  PaperclipIcon,
  SendIcon,
  FileTextIcon,
  AlertCircleIcon,
} from 'lucide-vue-next';
import {
  ref,
  computed,
  nextTick,
  onMounted,
  onBeforeUnmount,
  watch,
} from 'vue';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
  Message,
  MessageAvatar,
  MessageContent,
  MessageFooter,
  MessageGroup,
  MessageHeader,
} from '@/components/ui/message';
import { Bubble, BubbleContent } from '@/components/ui/bubble';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import SellerStoreHeader from '@/components/SellerStoreHeader.vue';
import seller from '@/routes/seller';
import type {
  Auth,
  User,
  Store,
  SellerConversationShow,
  ConversationMessage,
} from '@/types';

const props = defineProps<{
  conversation: SellerConversationShow;
  store: Store;
}>();

const page = usePage<{ auth: Auth }>();
const currentUserId = computed(() => page.props.auth.user?.id);

const messages = ref<ConversationMessage[]>([...props.conversation.messages]);
const body = ref('');
const files = ref<File[]>([]);
const scrollContainer = ref<HTMLElement | null>(null);

interface RenderGroup {
  key: string;
  sender: User;
  own: boolean;
  items: ConversationMessage[];
}

const groupedMessages = computed<RenderGroup[]>(() => {
  const groups: RenderGroup[] = [];

  for (const message of messages.value) {
    const own = message.sender.id === currentUserId.value;
    const last = groups[groups.length - 1];

    if (last && last.sender.id === message.sender.id) {
      last.items.push(message);
    } else {
      groups.push({
        key: `${message.sender.id}-${message.id}`,
        sender: message.sender,
        own,
        items: [message],
      });
    }
  }

  return groups;
});
function initials(name: string) {
  return name
    .split(' ')
    .map((part) => part[0])
    .join('')
    .slice(0, 2)
    .toUpperCase();
}

function formatDateTime(date: string) {
  return new Date(date).toLocaleString([], {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}

function scrollToBottom() {
  nextTick(() => {
    scrollContainer.value?.scrollTo({
      top: scrollContainer.value.scrollHeight,
      behavior: 'smooth',
    });
  });
}

// --- Polling (no realtime yet) ---
const POLL_INTERVAL_MS = 4000;
let pollTimer: ReturnType<typeof setInterval> | null = null;

function poll() {
  router.reload({
    only: ['conversation'],
  });
}

watch(
  () => props.conversation.messages,
  (newMessages, oldMessages) => {
    const hasNewMessages =
      newMessages.length !== (oldMessages?.length ?? messages.value.length);
    messages.value = [...newMessages];
    if (hasNewMessages) {
      scrollToBottom();
    }
  },
);

function handleFileChange(event: Event) {
  const input = event.target as HTMLInputElement;
  files.value = input.files ? Array.from(input.files) : [];
}

function sendMessage() {
  //
}

onMounted(() => {
  scrollToBottom();
  pollTimer = setInterval(poll, POLL_INTERVAL_MS);
});

onBeforeUnmount(() => {
  if (pollTimer) clearInterval(pollTimer);
});

const breadcrumbs = [
  {
    title: 'Dashboard',
    href: seller.dashboard(),
  },
  {
    title: 'Conversations',
    href: seller.conversations.index(),
  },
  {
    title: `${props.conversation.user.name}`,
    href: seller.conversations.show(props.conversation.id),
  },
];
</script>

<template>
  <Head title="Conversation" />

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

        <div class="flex h-[calc(80vh-8rem)] flex-col gap-3 p-4">
          <!-- Messages -->
          <div
            ref="scrollContainer"
            class="flex flex-1 flex-col gap-6 overflow-y-auto rounded-lg border bg-card p-4"
          >
            <MessageGroup v-for="group in groupedMessages" :key="group.key">
              <Message
                v-for="(message, index) in group.items"
                :key="message.id"
                :align="group.own ? 'end' : 'start'"
              >
                <MessageAvatar v-if="index === group.items.length - 1">
                  <Avatar>
                    <AvatarImage
                      v-if="group.sender.avatar"
                      :src="group.sender.avatar"
                      :alt="group.sender.name"
                    />
                    <AvatarFallback>{{
                      initials(group.sender.name)
                    }}</AvatarFallback>
                  </Avatar>
                </MessageAvatar>
                <MessageAvatar v-else class="invisible" />

                <MessageContent>
                  <MessageHeader v-if="index === 0">
                    <span class="text-sm font-medium">{{
                      group.sender.name
                    }}</span>
                  </MessageHeader>

                  <Bubble :variant="group.own ? 'default' : 'secondary'">
                    <BubbleContent>
                      <p
                        v-if="message.body"
                        class="text-sm whitespace-pre-wrap"
                      >
                        {{ message.body }}
                      </p>

                      <div
                        v-if="message.attachments.length > 0"
                        class="mt-2 flex flex-col gap-1.5"
                        :class="{ 'pt-2': message.body }"
                      >
                        <a
                          v-for="attachment in message.attachments"
                          :key="attachment.id"
                          :href="attachment.url"
                          target="_blank"
                          class="flex items-center gap-1.5 rounded-md bg-black/5 px-2 py-1.5 text-xs underline underline-offset-2 dark:bg-white/10"
                        >
                          <FileTextIcon class="size-3.5 shrink-0" />
                          <span class="truncate">{{
                            attachment.original_name
                          }}</span>
                        </a>
                      </div>
                    </BubbleContent>
                  </Bubble>

                  <MessageFooter>
                    <span class="text-xs text-muted-foreground">
                      {{ formatDateTime(message.created_at) }}
                    </span>
                  </MessageFooter>
                </MessageContent>
              </Message>
            </MessageGroup>

            <div
              v-if="messages.length === 0"
              class="flex flex-1 flex-col items-center justify-center gap-2 text-muted-foreground"
            >
              <MessageCircleMoreIcon class="size-8" />
              <p class="text-sm">No messages yet. Say hello.</p>
            </div>
          </div>

          <!-- Composer -->
          <form class="flex flex-col gap-2" @submit.prevent="sendMessage">
            <Textarea
              v-model="body"
              placeholder="Type a message..."
              rows="3"
              @keydown.enter.exact.prevent="sendMessage"
            />

            <div class="flex items-center justify-between gap-2">
              <label
                class="flex cursor-pointer items-center gap-1.5 text-sm text-muted-foreground hover:text-foreground"
              >
                <PaperclipIcon class="size-4" />
                <span v-if="files.length > 0"
                  >{{ files.length }} file(s) selected</span
                >
                <span v-else>Attach files</span>
                <input
                  type="file"
                  multiple
                  accept=".jpg,.jpeg,.png,.pdf"
                  class="hidden"
                  @change="handleFileChange"
                />
              </label>

              <Button type="submit">
                <SendIcon class="mr-1.5 size-4" />
                Send
              </Button>
            </div>
          </form>
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
