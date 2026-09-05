<script setup lang="ts">
import { Head, useForm, usePoll, Link } from '@inertiajs/vue3';
import {
  MessageCircleMoreIcon,
  PaperclipIcon,
  SendIcon,
  FileTextIcon,
  ArrowLeftIcon,
} from 'lucide-vue-next';
import { ref, computed, nextTick, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
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
import UserAccountSidebar from '@/components/accounts/UserAccountSidebar.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import { toast } from 'vue-sonner';
import shop from '@/routes/shop';
import type {
  Auth,
  User,
  CustomerConversationShow,
  ConversationMessage,
  MessageAttachmentType,
} from '@/types';

const props = defineProps<{
  user: User;
  conversation: CustomerConversationShow;
}>();

const page = usePage<{ auth: Auth }>();
const currentUserId = computed(() => page.props.auth.user?.id);

const scrollContainer = ref<HTMLElement | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);

interface RenderGroup {
  key: string;
  sender: User;
  own: boolean;
  items: ConversationMessage[];
}

const groupedMessages = computed<RenderGroup[]>(() => {
  const groups: RenderGroup[] = [];

  for (const message of props.conversation.messages) {
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

function attachmentUrl(attachment: MessageAttachmentType): string {
  return shop.conversations.attachments.show({
    conversation: props.conversation.uuid,
    attachment: attachment.id,
  }).url;
}

const lastMessage = computed(() => props.conversation.messages.at(-1));
function isLastOwnMessage(message: ConversationMessage): boolean {
  return (
    lastMessage.value?.id === message.id &&
    message.sender.id === currentUserId.value
  );
}

function scrollToBottom() {
  nextTick(() => {
    scrollContainer.value?.scrollTo({
      top: scrollContainer.value.scrollHeight,
      behavior: 'smooth',
    });
  });
}

// Poll only the `conversation` prop (partial reload) so Inertia::merge()
// on the backend actually applies — a full reload would just replace it.
usePoll(10000, { only: ['conversation'] });

// --- Sending ---
const form = useForm<{ body: string; attachments: File[] }>({
  body: '',
  attachments: [],
});

function handleFileChange(event: Event) {
  const input = event.target as HTMLInputElement;
  form.attachments = input.files ? Array.from(input.files) : [];
}

function sendMessage() {
  if (form.processing) return;
  if (!form.body.trim() && form.attachments.length === 0) return;

  form.post(shop.conversations.messages.store.url(props.conversation.uuid), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      form.reset('body', 'attachments');
      if (fileInput.value) fileInput.value.value = '';
      scrollToBottom();
      toast.success('Message sent successfully!');
    },
  });
}

onMounted(() => {
  scrollToBottom();
});
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
            class="mx-2 mb-3 flex flex-wrap items-center justify-between gap-4"
          >
            <Link
              :href="shop.conversations.index.url()"
              class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200"
            >
              <ArrowLeftIcon class="h-4 w-4" /> Back to Conversations
            </Link>
            <div class="text-sm text-zinc-800 dark:text-white">
              {{ conversation.store.name }}
            </div>
          </div>

          <div class="flex h-[calc(80vh-8rem)] flex-col gap-3">
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
                          <template
                            v-for="attachment in message.attachments"
                            :key="attachment.id"
                          >
                            <a
                              v-if="attachment.mime_type.startsWith('image/')"
                              :href="attachmentUrl(attachment)"
                              target="_blank"
                            >
                              <img
                                :src="attachmentUrl(attachment)"
                                :alt="attachment.original_name"
                                class="max-h-64 w-auto rounded-md object-cover"
                              />
                            </a>

                            <video
                              v-else-if="
                                attachment.mime_type.startsWith('video/')
                              "
                              :src="attachmentUrl(attachment)"
                              controls
                              class="max-h-64 max-w-full rounded-md"
                            />

                            <a
                              v-else
                              :href="attachmentUrl(attachment)"
                              target="_blank"
                              class="flex items-center gap-1.5 rounded-md bg-black/5 px-2 py-1.5 text-xs underline underline-offset-2 dark:bg-white/10"
                            >
                              <FileTextIcon class="size-3.5 shrink-0" />
                              <span class="truncate">{{
                                attachment.original_name
                              }}</span>
                            </a>
                          </template>
                        </div>
                      </BubbleContent>
                    </Bubble>

                    <MessageFooter>
                      <span class="text-xs text-muted-foreground">
                        {{ formatDateTime(message.created_at) }}
                      </span>

                      <span
                        v-if="isLastOwnMessage(message)"
                        class="text-xs text-muted-foreground"
                      >
                        &nbsp;&nbsp;·&nbsp;
                        {{
                          message.read_at
                            ? `Seen ${formatDateTime(message.read_at)}`
                            : 'Sent'
                        }}
                      </span>
                    </MessageFooter>
                  </MessageContent>
                </Message>
              </MessageGroup>

              <div
                v-if="groupedMessages.length === 0"
                class="flex flex-1 flex-col items-center justify-center gap-2 text-muted-foreground"
              >
                <MessageCircleMoreIcon class="size-8" />
                <p class="text-sm">No messages yet. Say hello.</p>
              </div>
            </div>

            <!-- Composer -->
            <form class="flex flex-col gap-2" @submit.prevent="sendMessage">
              <Textarea
                v-model="form.body"
                placeholder="Type a message..."
                rows="3"
                @keydown.enter.exact.prevent="sendMessage"
              />

              <p v-if="form.errors.body" class="text-xs text-destructive">
                {{ form.errors.body }}
              </p>
              <p
                v-if="form.errors['attachments.0']"
                class="text-xs text-destructive"
              >
                {{ form.errors['attachments.0'] }}
              </p>

              <div class="flex items-center justify-between gap-2">
                <label
                  class="flex cursor-pointer items-center gap-1.5 text-sm text-muted-foreground hover:text-foreground"
                >
                  <PaperclipIcon class="size-4" />
                  <span v-if="form.attachments.length > 0"
                    >{{ form.attachments.length }} file(s) selected</span
                  >
                  <span v-else>Attach files</span>
                  <input
                    ref="fileInput"
                    type="file"
                    multiple
                    accept=".jpg,.jpeg,.png,.pdf,.mp4,.webm,.mov"
                    class="hidden"
                    @change="handleFileChange"
                  />
                </label>

                <Button type="submit" :disabled="form.processing">
                  <SendIcon class="mr-1.5 size-4" />
                  Send
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
