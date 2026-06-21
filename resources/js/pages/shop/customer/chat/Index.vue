<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Send, User, MoreVertical } from 'lucide-vue-next';
import TopBar from '@/components/sections/TopBar.vue';
import Navbar from '@/components/sections/Navbar.vue';
import Footer from '@/components/sections/Footer.vue';

// Sample Data
const currentUser = { name: 'Customer User', role: 'customer' };
const messages = ref([
  { id: 1, sender: 'Buyer', text: 'Hi, is this item still available?', time: '10:30 AM' },
  { id: 2, sender: 'Seller', text: 'Yes, it is! Would you like to proceed with the order?', time: '10:32 AM' },
  { id: 3, sender: 'Buyer', text: 'Great, I just placed the order. Please check!', time: '10:35 AM' },
]);

const newMessage = ref('');

const sendMessage = () => {
  if (newMessage.value.trim()) {
    messages.value.push({
      id: Date.now(),
      sender: 'Buyer', // Changed to Buyer since this is the Customer chat view
      text: newMessage.value,
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
    });
    newMessage.value = '';
  }
};
</script>

<template>
  <Head title="Chat Messages" />

  <div class="flex min-h-screen flex-col transition-colors duration-300">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
      <Navbar />
    </div>

    <!-- Main Content Layout Area -->
    <main class="mx-auto w-full max-w-7xl flex-grow px-4 py-10 sm:px-6 lg:px-8">
      <div class="mb-6">
        <h1 class="text-2xl font-black text-zinc-900 dark:text-white">Chat Inbox</h1>
      </div>

      <!-- Standardized Chat Container -->
      <div class="flex h-[650px] w-full overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-sm transition-colors dark:border-zinc-800 dark:bg-zinc-900">
        
        <!-- Sidebar: Contacts List -->
        <div class="hidden w-1/3 border-r border-zinc-200 bg-zinc-50 sm:block dark:border-zinc-800 dark:bg-zinc-950/40">
          <div class="p-4 border-b border-zinc-200 dark:border-zinc-800">
            <p class="font-bold text-zinc-800 dark:text-zinc-200">Recent Chats</p>
          </div>
          <div class="p-2 space-y-1">
            <div class="flex items-center gap-3 rounded-2xl bg-green-50/70 p-3 dark:bg-green-900/10">
              <div class="h-10 w-10 rounded-xl bg-[#009933] flex items-center justify-center text-white font-bold">S</div>
              <div class="overflow-hidden">
                <p class="text-sm font-bold text-zinc-900 dark:text-white truncate">Store Partner</p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">Great, I just placed the order...</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Workspace: Messaging Thread -->
        <div class="flex flex-1 flex-col bg-zinc-50/30 dark:bg-zinc-900">
          <!-- Chat Header Bar -->
          <div class="flex items-center justify-between border-b border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-600 dark:text-zinc-300">
                <User class="h-5 w-5" />
              </div>
              <div>
                <span class="block font-black text-zinc-900 dark:text-white">Store Partner</span>
                <span class="text-xs text-green-500 font-medium">Online</span>
              </div>
            </div>
            <button class="rounded-lg p-2 hover:bg-zinc-100 dark:hover:bg-zinc-800">
              <MoreVertical class="h-5 w-5 text-zinc-400" />
            </button>
          </div>

          <!-- Message Log Content area -->
          <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar">
            <div v-for="msg in messages" :key="msg.id" :class="['flex', msg.sender === 'Buyer' ? 'justify-end' : 'justify-start']">
              <div :class="['max-w-[75%] rounded-2xl p-3 text-sm font-medium shadow-sm transition-colors', msg.sender === 'Buyer' ? 'bg-[#009933] text-white rounded-tr-none' : 'bg-white border border-zinc-200 text-zinc-800 dark:border-zinc-800 dark:bg-zinc-800 dark:text-zinc-200 rounded-tl-none']">
                {{ msg.text }}
                <p :class="['text-[10px] opacity-70 mt-1', msg.sender === 'Buyer' ? 'text-green-100' : 'text-zinc-400']">{{ msg.time }}</p>
              </div>
            </div>
          </div>

          <!-- Text Input Actions Bar -->
          <div class="border-t border-zinc-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex gap-2">
              <input 
                v-model="newMessage"
                @keyup.enter="sendMessage"
                type="text" 
                placeholder="Write your message here..." 
                class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm focus:border-[#009933] focus:bg-white focus:outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:focus:bg-zinc-900"
              />
              <button @click="sendMessage" class="rounded-xl bg-[#009933] px-5 py-3 text-white transition-all hover:bg-green-700 active:scale-95 shadow-md flex items-center justify-center">
                <Send class="h-4 w-4" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <Footer />
  </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #e4e4e7;
  border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background: #3f3f46;
}
</style>