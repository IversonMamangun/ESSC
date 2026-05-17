<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3' 
import { User, Package, LogOut, ShoppingCart, LayoutDashboard } from 'lucide-vue-next';
import { ref, onMounted, computed } from 'vue'
import { home, logout, login, register } from '@/routes';

const isMenuOpen = ref(false)
const activeLink = ref('Home')
const isDarkMode = ref(false)
const isDropdownOpen = ref(false)

const page = usePage()
const user = computed(() => page.props.auth?.user)

onMounted(() => {
  if (
    document.documentElement.classList.contains('dark') ||
    window.matchMedia('(prefers-color-scheme: dark)').matches
  ) {
    isDarkMode.value = true
    document.documentElement.classList.add('dark')
  }
})

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
  isDropdownOpen.value = false 
}

const setActive = (linkName: string, sectionId: string) => {
  activeLink.value = linkName
  isMenuOpen.value = false
  
  if (window.location.pathname !== '/') {
    router.visit('/', {
      onSuccess: () => {
        setTimeout(() => {
          const element = document.getElementById(sectionId)

          if (element) {
            const y = element.getBoundingClientRect().top + window.scrollY - 100
            window.scrollTo({ top: y, behavior: 'smooth' })
          }
        }, 100)
      }
    })

    return; 
  }

  const element = document.getElementById(sectionId)

  if (element) {
    const y = element.getBoundingClientRect().top + window.scrollY - 100 
    window.scrollTo({ top: y, behavior: 'smooth' })
  }
}

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value
  
  if (isDarkMode.value) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

const navLinks = [
  { name: 'Home', id: 'home' },
  { name: 'About Us', id: 'about' },
  { name: 'Products & Solutions', id: 'products' },
  { name: 'Online Store', id: 'Store', url: '/store' },   
  { name: 'Industries Served', id: 'industries' },
  { name: 'Capabilities', id: 'capabilities' },
  { name: 'Clients', id: 'clients' },
  { name: 'News & Updates', id: 'news' },
  { name: 'Contact Us', id: 'contact' }
]
</script>

<template>
  <nav class="relative bg-white dark:bg-[#263d2f] w-full max-w-7xl mx-auto rounded-2xl shadow-lg border border-neutral-100 dark:border-neutral-800 transition-all duration-300 z-[100]">
    
    <div class="flex items-center justify-between w-full p-3 lg:px-4">
      
      <div class="flex shrink-0 items-center z-20 mr-2">
        <a href="#" @click.prevent="setActive('Home', 'home')" class="block focus:outline-none focus:ring-2 focus:ring-[#009933] rounded-md">
          <img 
            src="/assets/logos/logo top.png" 
            alt="Company Logo" 
            class="h-9 md:h-11 w-auto object-contain transition-transform duration-300 hover:scale-105"
          />
        </a>
      </div>
      
      <div class="hidden xl:flex flex-1 items-center justify-center gap-1">
        <template v-for="link in navLinks" :key="link.name">
          <a 
            v-if="link.url"
            :href="link.url"
            :class="activeLink === link.name ? 'text-[#009933] font-bold' : 'text-neutral-700 dark:text-gray-300'" 
            class="whitespace-nowrap text-[13px] font-medium hover:text-[#009933] dark:hover:text-[#009933] transition-colors duration-300 px-2 py-1"
          >
            {{ link.name }}
          </a>
          <a 
            v-else
            href="#" 
            @click.prevent="setActive(link.name, link.id)" 
            :class="activeLink === link.name ? 'text-[#009933] font-bold' : 'text-neutral-700 dark:text-gray-300'" 
            class="whitespace-nowrap text-[13px] font-medium hover:text-[#009933] dark:hover:text-[#009933] transition-colors duration-300 px-2 py-1"
          >
            {{ link.name }}
          </a>
        </template>
      </div>

      <div class="flex items-center gap-1 sm:gap-2 shrink-0 z-20">
        
        <Link href="/cart" class="relative p-2 text-neutral-600 dark:text-gray-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-[#009933]">
          <ShoppingCart class="w-5 h-5" />
        </Link>

        <div class="hidden xl:block relative">
          
          <template v-if="!user">
            <Link :href="login()" class="text-[13px] font-bold bg-[#009933] text-white px-4 py-2 rounded-xl hover:bg-green-700 transition-all active:scale-95 ml-1">
              Log in
            </Link>
          </template>

          <template v-else>
            <button 
              @click="isDropdownOpen = !isDropdownOpen" 
              class="flex items-center justify-center p-0.5 rounded-full border-2 border-transparent hover:border-[#009933] transition-colors focus:outline-none focus:ring-2 focus:ring-[#009933] focus:ring-offset-1 dark:focus:ring-offset-neutral-900 ml-1"
            >
              <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center overflow-hidden border border-neutral-200 dark:border-neutral-700 shrink-0">
                <img v-if="user.avatar" :src="`/storage/${user.avatar}`" class="w-full h-full object-cover" />
                <span v-else class="text-[#009933] font-black text-sm">{{ user.name.charAt(0) }}</span>
              </div>
            </button>

            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
              <div 
                v-if="isDropdownOpen" 
                @click.away="isDropdownOpen = false"
                class="absolute top-full right-0 mt-2 w-64 bg-white dark:bg-neutral-900 rounded-2xl shadow-xl border border-neutral-100 dark:border-neutral-800 py-2 z-[100] overflow-hidden"
              >
                <div class="px-4 py-3 border-b border-neutral-100 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-800/50 mb-2">
                  <p class="text-sm font-bold text-neutral-900 dark:text-white truncate">{{ user.name }}</p>
                  <p class="text-xs text-neutral-500 dark:text-neutral-400 truncate">{{ user.email }}</p>
                </div>
                
                <Link href="/account" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-neutral-700 dark:text-gray-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 hover:text-[#009933]">
                  <User class="w-4 h-4 text-neutral-400" /> My Profile
                </Link>
                
                <Link v-if="user.user_type?.slug === 'buyer' || !user.user_type" href="/purchases" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-neutral-700 dark:text-gray-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 hover:text-[#009933]">
                  <Package class="w-4 h-4 text-[#009933]" /> My Purchases
                </Link>
                
                <template v-if="user.user_type?.slug === 'seller' || user.user_type?.slug === 'admin'">
                  <div class="h-px bg-neutral-100 dark:bg-neutral-800 my-2"></div>
                  <Link href="/seller/dashboard" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-neutral-700 dark:text-gray-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 hover:text-blue-600 transition-colors">
                    <LayoutDashboard class="w-4 h-4 text-blue-600" /> Seller Dashboard
                  </Link>
                </template>

                <div class="h-px bg-neutral-100 dark:bg-neutral-800 my-2"></div>
                
                <Link :href="logout()" method="post" as="button" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 text-left transition-colors">
                  <LogOut class="w-4 h-4" /> Log out
                </Link>
              </div>
            </transition>
          </template>
        </div>

        <button 
          @click="toggleDarkMode" 
          type="button" 
          class="inline-flex items-center p-2 w-9 h-9 md:w-10 md:h-10 justify-center rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-[#009933] transition-colors" 
        >
          <svg v-if="isDarkMode" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-neutral-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
          </svg>
        </button>

        <button 
          @click="toggleMenu" 
          type="button" 
          class="xl:hidden inline-flex items-center p-2 w-9 h-9 md:w-10 md:h-10 justify-center rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-[#009933] transition-colors" 
        >
          <svg class="w-5 h-5 text-neutral-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

      </div>
    </div>

    <transition name="menu-slide">
      <div v-show="isMenuOpen" class="xl:hidden absolute top-[calc(100%+0.5rem)] left-0 w-full border border-neutral-100 dark:border-neutral-800 bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl overflow-hidden z-[100]">
        
        <div class="max-h-[75vh] overflow-y-auto custom-scrollbar flex flex-col">
          <ul class="flex flex-col p-3 font-medium space-y-1">
            <li v-for="link in navLinks" :key="link.name">
              <a 
                v-if="link.url"
                :href="link.url"
                :class="activeLink === link.name ? 'text-[#009933] bg-green-50 dark:bg-neutral-800' : 'text-neutral-700 dark:text-gray-300'" 
                class="block py-3 px-4 hover:bg-neutral-50 dark:hover:bg-neutral-800 rounded-xl transition-colors duration-300"
              >
                {{ link.name }}
              </a>
              <a 
                v-else
                href="#" 
                @click.prevent="setActive(link.name, link.id)" 
                :class="activeLink === link.name ? 'text-[#009933] bg-green-50 dark:bg-neutral-800' : 'text-neutral-700 dark:text-gray-300'" 
                class="block py-3 px-4 hover:bg-neutral-50 dark:hover:bg-neutral-800 rounded-xl transition-colors duration-300"
              >
                {{ link.name }}
              </a>
            </li>
          </ul>

          <div class="p-4 border-t border-neutral-100 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900/50 flex flex-col gap-3">
            <template v-if="user">
              
              <div class="flex items-center gap-3 px-2 mb-2">
                <div class="w-10 h-10 rounded-full bg-green-100 border border-[#009933]/20 overflow-hidden shrink-0">
                  <img v-if="user.avatar" :src="`/storage/${user.avatar}`" class="w-full h-full object-cover" />
                  <span v-else class="flex items-center justify-center w-full h-full text-[#009933] font-black">{{ user.name.charAt(0) }}</span>
                </div>
                <div class="flex flex-col min-w-0">
                  <span class="text-sm font-bold text-neutral-900 dark:text-white truncate">{{ user.name }}</span>
                  <span class="text-xs text-neutral-500 dark:text-neutral-400 truncate">{{ user.email }}</span>
                </div>
              </div>

              <Link href="/account" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-neutral-700 dark:text-gray-300 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 hover:border-[#009933] transition-colors">
                <User class="w-5 h-5 text-neutral-400" /> My Profile
              </Link>
              
              <Link v-if="user.user_type?.slug === 'buyer' || !user.user_type" href="/purchases" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-[#009933] bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-900 hover:bg-green-100 transition-colors">
                <Package class="w-5 h-5" /> My Purchases
              </Link>

              <Link v-if="user.user_type?.slug === 'seller' || user.user_type?.slug === 'admin'" href="/seller/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-blue-600 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900 hover:bg-blue-100 transition-colors">
                <LayoutDashboard class="w-5 h-5" /> Seller Dashboard
              </Link>

              <Link :href="logout()" method="post" as="button" class="w-full rounded-xl bg-red-600 px-4 py-3 text-center text-sm font-bold text-white shadow-md active:scale-[0.98] transition-all block focus:outline-none mt-2">
                Log out
              </Link>
            </template>

            <template v-else>
              <Link :href="login()" class="w-full rounded-xl bg-[#009933] px-4 py-3.5 text-center text-sm font-bold text-white shadow-md active:scale-[0.98] transition-all block focus:outline-none">
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

.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 10px; }
.dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; }
</style>