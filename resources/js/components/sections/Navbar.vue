<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import {
  User,
  Package,
  LogOut,
  ShoppingCart,
  LayoutDashboard,
} from 'lucide-vue-next';
import { ref, onMounted, computed } from 'vue';
import { logout, login, register } from '@/routes';

const isMenuOpen = ref(false);
const activeLink = ref('Home');
const isDarkMode = ref(false);
const isDropdownOpen = ref(false);

const page = usePage();
const user = computed(() => page.props.auth?.user);

onMounted(() => {
  if (
    document.documentElement.classList.contains('dark') ||
    window.matchMedia('(prefers-color-scheme: dark)').matches
  ) {
    isDarkMode.value = true;
    document.documentElement.classList.add('dark');
  }
});

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
  isDropdownOpen.value = false;
};

const setActive = (linkName: string, sectionId: string) => {
  activeLink.value = linkName;
  isMenuOpen.value = false;

  if (window.location.pathname !== '/') {
    router.visit('/', {
      onSuccess: () => {
        setTimeout(() => {
          const element = document.getElementById(sectionId);

          if (element) {
            const y =
              element.getBoundingClientRect().top + window.scrollY - 100;
            window.scrollTo({ top: y, behavior: 'smooth' });
          }
        }, 100);
      },
    });

    return;
  }

  const element = document.getElementById(sectionId);

  if (element) {
    const y = element.getBoundingClientRect().top + window.scrollY - 100;
    window.scrollTo({ top: y, behavior: 'smooth' });
  }
};

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;

  if (isDarkMode.value) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};

const navLinks = [
  { name: 'Home', id: 'home' },
  { name: 'About Us', id: 'about' },
  { name: 'Products & Solutions', id: 'products' },
  { name: 'Online Store', id: 'Store', url: '/home' },
  { name: 'Industries Served', id: 'industries' },
  { name: 'Capabilities', id: 'capabilities' },
  { name: 'Clients', id: 'clients' },
  { name: 'News & Updates', id: 'news' },
  { name: 'Contact Us', id: 'contact' },
];
</script>

<template>
  <nav
    class="relative z-[100] mx-auto w-full max-w-7xl rounded-2xl border border-neutral-100 bg-white shadow-lg transition-all duration-300 dark:border-neutral-800 dark:bg-[#263d2f]"
  >
    <div class="flex w-full items-center justify-between p-3 lg:px-4">
      <div class="z-20 mr-2 flex shrink-0 items-center">
        <a
          href="#"
          @click.prevent="setActive('Home', 'home')"
          class="block rounded-md focus:ring-2 focus:ring-[#009933] focus:outline-none"
        >
          <img
            src="/assets/logos/logo top.png"
            alt="Company Logo"
            class="h-9 w-auto object-contain transition-transform duration-300 hover:scale-105 md:h-11"
          />
        </a>
      </div>

      <div class="hidden flex-1 items-center justify-center gap-1 xl:flex">
        <template v-for="link in navLinks" :key="link.name">
          <a
            v-if="link.url"
            :href="link.url"
            :class="
              activeLink === link.name
                ? 'font-bold text-[#009933]'
                : 'text-neutral-700 dark:text-gray-300'
            "
            class="px-2 py-1 text-[13px] font-medium whitespace-nowrap transition-colors duration-300 hover:text-[#009933] dark:hover:text-[#009933]"
          >
            {{ link.name }}
          </a>
          <a
            v-else
            href="#"
            @click.prevent="setActive(link.name, link.id)"
            :class="
              activeLink === link.name
                ? 'font-bold text-[#009933]'
                : 'text-neutral-700 dark:text-gray-300'
            "
            class="px-2 py-1 text-[13px] font-medium whitespace-nowrap transition-colors duration-300 hover:text-[#009933] dark:hover:text-[#009933]"
          >
            {{ link.name }}
          </a>
        </template>
      </div>

      <div class="z-20 flex shrink-0 items-center gap-1 sm:gap-2">
        <Link
          href="/cart"
          class="relative rounded-xl p-2 text-neutral-600 transition-colors hover:bg-neutral-100 focus:ring-2 focus:ring-[#009933] focus:outline-none dark:text-gray-300 dark:hover:bg-neutral-800"
        >
          <ShoppingCart class="h-5 w-5" />
        </Link>

        <div class="relative hidden xl:block">
          <template v-if="!user">
            <Link
              :href="login()"
              class="ml-1 rounded-xl bg-[#009933] px-4 py-2 text-[13px] font-bold text-white transition-all hover:bg-green-700 active:scale-95"
            >
              Log in
            </Link>
          </template>

          <template v-else>
            <button
              @click="isDropdownOpen = !isDropdownOpen"
              class="ml-1 flex items-center justify-center rounded-full border-2 border-transparent p-0.5 transition-colors hover:border-[#009933] focus:ring-2 focus:ring-[#009933] focus:ring-offset-1 focus:outline-none dark:focus:ring-offset-neutral-900"
            >
              <div
                class="flex h-8 w-8 shrink-0 items-center justify-center overflow-hidden rounded-full border border-neutral-200 bg-green-100 dark:border-neutral-700"
              >
                <img
                  v-if="user.avatar"
                  :src="`/storage/${user.avatar}`"
                  class="h-full w-full object-cover"
                />
                <span v-else class="text-sm font-black text-[#009933]">{{
                  user.name.charAt(0)
                }}</span>
              </div>
            </button>

            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div
                v-if="isDropdownOpen"
                @click.away="isDropdownOpen = false"
                class="absolute top-full right-0 z-[100] mt-2 w-64 overflow-hidden rounded-2xl border border-neutral-100 bg-white py-2 shadow-xl dark:border-neutral-800 dark:bg-neutral-900"
              >
                <div
                  class="mb-2 border-b border-neutral-100 bg-neutral-50 px-4 py-3 dark:border-neutral-800 dark:bg-neutral-800/50"
                >
                  <p
                    class="truncate text-sm font-bold text-neutral-900 dark:text-white"
                  >
                    {{ user.name }}
                  </p>
                  <p
                    class="truncate text-xs text-neutral-500 dark:text-neutral-400"
                  >
                    {{ user.email }}
                  </p>
                </div>

                <Link
                  href="/account"
                  class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-neutral-700 hover:bg-neutral-50 hover:text-[#009933] dark:text-gray-300 dark:hover:bg-neutral-800"
                >
                  <User class="h-4 w-4 text-neutral-400" /> My Profile
                </Link>

                <Link
                  v-if="user.user_type?.slug === 'buyer' || !user.user_type"
                  href="/purchases"
                  class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-neutral-700 hover:bg-neutral-50 hover:text-[#009933] dark:text-gray-300 dark:hover:bg-neutral-800"
                >
                  <Package class="h-4 w-4 text-[#009933]" /> My Purchases
                </Link>

                <template
                  v-if="
                    user.user_type?.slug === 'seller' ||
                    user.user_type?.slug === 'admin'
                  "
                >
                  <div
                    class="my-2 h-px bg-neutral-100 dark:bg-neutral-800"
                  ></div>
                  <Link
                    href="/seller/dashboard"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-neutral-700 transition-colors hover:bg-neutral-50 hover:text-blue-600 dark:text-gray-300 dark:hover:bg-neutral-800"
                  >
                    <LayoutDashboard class="h-4 w-4 text-blue-600" /> Seller
                    Dashboard
                  </Link>
                </template>

                <div class="my-2 h-px bg-neutral-100 dark:bg-neutral-800"></div>

                <Link
                  :href="logout()"
                  method="post"
                  as="button"
                  class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm font-bold text-red-600 transition-colors hover:bg-red-50 dark:hover:bg-red-900/10"
                >
                  <LogOut class="h-4 w-4" /> Log out
                </Link>
              </div>
            </transition>
          </template>
        </div>

        <button
          @click="toggleDarkMode"
          type="button"
          class="inline-flex h-9 w-9 items-center justify-center rounded-lg p-2 transition-colors hover:bg-neutral-100 focus:ring-2 focus:ring-[#009933] focus:outline-none md:h-10 md:w-10 dark:hover:bg-neutral-800"
        >
          <svg
            v-if="isDarkMode"
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-yellow-500"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
            />
          </svg>
          <svg
            v-else
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-neutral-600 dark:text-gray-300"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
            />
          </svg>
        </button>

        <button
          @click="toggleMenu"
          type="button"
          class="inline-flex h-9 w-9 items-center justify-center rounded-lg p-2 transition-colors hover:bg-neutral-100 focus:ring-2 focus:ring-[#009933] focus:outline-none md:h-10 md:w-10 xl:hidden dark:hover:bg-neutral-800"
        >
          <svg
            class="h-5 w-5 text-neutral-700 dark:text-gray-200"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              v-if="!isMenuOpen"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            ></path>
            <path
              v-else
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            ></path>
          </svg>
        </button>
      </div>
    </div>

    <transition name="menu-slide">
      <div
        v-show="isMenuOpen"
        class="absolute top-[calc(100%+0.5rem)] left-0 z-[100] w-full overflow-hidden rounded-2xl border border-neutral-100 bg-white shadow-2xl xl:hidden dark:border-neutral-800 dark:bg-neutral-900"
      >
        <div
          class="custom-scrollbar flex max-h-[75vh] flex-col overflow-y-auto"
        >
          <ul class="flex flex-col space-y-1 p-3 font-medium">
            <li v-for="link in navLinks" :key="link.name">
              <a
                v-if="link.url"
                :href="link.url"
                :class="
                  activeLink === link.name
                    ? 'bg-green-50 text-[#009933] dark:bg-neutral-800'
                    : 'text-neutral-700 dark:text-gray-300'
                "
                class="block rounded-xl px-4 py-3 transition-colors duration-300 hover:bg-neutral-50 dark:hover:bg-neutral-800"
              >
                {{ link.name }}
              </a>
              <a
                v-else
                href="#"
                @click.prevent="setActive(link.name, link.id)"
                :class="
                  activeLink === link.name
                    ? 'bg-green-50 text-[#009933] dark:bg-neutral-800'
                    : 'text-neutral-700 dark:text-gray-300'
                "
                class="block rounded-xl px-4 py-3 transition-colors duration-300 hover:bg-neutral-50 dark:hover:bg-neutral-800"
              >
                {{ link.name }}
              </a>
            </li>
          </ul>

          <div
            class="flex flex-col gap-3 border-t border-neutral-100 bg-neutral-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50"
          >
            <template v-if="user">
              <div class="mb-2 flex items-center gap-3 px-2">
                <div
                  class="h-10 w-10 shrink-0 overflow-hidden rounded-full border border-[#009933]/20 bg-green-100"
                >
                  <img
                    v-if="user.avatar"
                    :src="`/storage/${user.avatar}`"
                    class="h-full w-full object-cover"
                  />
                  <span
                    v-else
                    class="flex h-full w-full items-center justify-center font-black text-[#009933]"
                    >{{ user.name.charAt(0) }}</span
                  >
                </div>
                <div class="flex min-w-0 flex-col">
                  <span
                    class="truncate text-sm font-bold text-neutral-900 dark:text-white"
                    >{{ user.name }}</span
                  >
                  <span
                    class="truncate text-xs text-neutral-500 dark:text-neutral-400"
                    >{{ user.email }}</span
                  >
                </div>
              </div>

              <Link
                href="/account"
                class="flex items-center gap-3 rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm font-bold text-neutral-700 transition-colors hover:border-[#009933] dark:border-neutral-700 dark:bg-neutral-800 dark:text-gray-300"
              >
                <User class="h-5 w-5 text-neutral-400" /> My Profile
              </Link>

              <Link
                v-if="user.user_type?.slug === 'buyer' || !user.user_type"
                href="/purchases"
                class="flex items-center gap-3 rounded-xl border border-green-100 bg-green-50 px-4 py-3 text-sm font-bold text-[#009933] transition-colors hover:bg-green-100 dark:border-green-900 dark:bg-green-900/20"
              >
                <Package class="h-5 w-5" /> My Purchases
              </Link>

              <Link
                v-if="
                  user.user_type?.slug === 'seller' ||
                  user.user_type?.slug === 'admin'
                "
                href="/seller/dashboard"
                class="flex items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm font-bold text-blue-600 transition-colors hover:bg-blue-100 dark:border-blue-900 dark:bg-blue-900/20"
              >
                <LayoutDashboard class="h-5 w-5" /> Seller Dashboard
              </Link>

              <Link
                :href="logout()"
                method="post"
                as="button"
                class="mt-2 block w-full rounded-xl bg-red-600 px-4 py-3 text-center text-sm font-bold text-white shadow-md transition-all focus:outline-none active:scale-[0.98]"
              >
                Log out
              </Link>
            </template>

            <template v-else>
              <Link
                :href="login()"
                class="block w-full rounded-xl bg-[#009933] px-4 py-3.5 text-center text-sm font-bold text-white shadow-md transition-all focus:outline-none active:scale-[0.98]"
              >
                Log in to your account
              </Link>
            </template>
          </div>
        </div>
      </div>
    </transition>
  </nav>
</template>

<style scoped>
.menu-slide-enter-active,
.menu-slide-leave-active {
  transition: all 0.3s ease-out cubic-bezier(0.4, 0, 0.2, 1);
}

.menu-slide-enter-from,
.menu-slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
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
