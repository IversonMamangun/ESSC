<script setup lang="ts">
import { ref, onMounted } from 'vue'

const isMenuOpen = ref(false)
const activeLink = ref('Home')
const isDarkMode = ref(false)

// Sync initial dark mode state on load
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
}

// Update state, close menu, and scroll to the correct section
const setActive = (linkName: string, sectionId: string) => {
  activeLink.value = linkName
  isMenuOpen.value = false
  
  const element = document.getElementById(sectionId)
  if (element) {
    // 100px offset to account for sticky header
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

// Define the navigation links to keep the template clean
const navLinks = [
  { name: 'Home', id: 'home' },
  { name: 'About Us', id: 'about' },
  { name: 'Products & Solutions', id: 'products' },
  { name: 'Industries Served', id: 'industries' },
  { name: 'Capabilities', id: 'capabilities' },
  { name: 'Clients', id: 'clients' },
  { name: 'News & Updates', id: 'news' },
  { name: 'Contact Us', id: 'contact' }
]
</script>

<template>
  <nav class="relative bg-white dark:bg-neutral-900 w-full max-w-7xl mx-auto rounded-2xl shadow-lg border border-neutral-100 dark:border-neutral-800 transition-all duration-300 z-50">
    
    <div class="flex items-center justify-between w-full p-4 lg:px-6">
      
      <div class="flex shrink-0  items-center">
        <a href="#" @click.prevent="setActive('Home', 'home')" class="block focus:outline-none focus:ring-2 focus:ring-[#009933] rounded-md">
          <img 
            src="/assets/logos/logo top.png" 
            alt="Company Logo" 
            class="h-10 md:h-12 w-auto object-contain transition-transform duration-300 hover:scale-105"
          />
        </a>
      </div>
      
      <div class="hidden xl:flex flex-1 items-center justify-center gap-6">
        <a 
          v-for="link in navLinks" 
          :key="link.name"
          href="#" 
          @click.prevent="setActive(link.name, link.id)" 
          :class="activeLink === link.name ? 'text-[#009933] font-semibold' : 'text-neutral-700 dark:text-gray-300'" 
          class="whitespace-nowrap text-sm font-medium hover:text-[#009933] dark:hover:text-[#009933] transition-colors duration-300"
        >
          {{ link.name }}
        </a>
      </div>

      <div class="flex items-center gap-2 shrink-0 ml-auto xl:ml-0">
        
        <button 
          @click="toggleDarkMode" 
          type="button" 
          class="inline-flex items-center p-2 w-10 h-10 justify-center rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-200 transition-colors" 
          aria-label="Toggle dark mode"
        >
          <svg v-if="isDarkMode" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-500 transition-transform duration-500 transform rotate-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-neutral-600 dark:text-gray-300 transition-transform duration-500 transform -rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
          </svg>
        </button>

        <button 
          @click="toggleMenu" 
          type="button" 
          class="xl:hidden inline-flex items-center p-2 w-10 h-10 justify-center rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-neutral-200 transition-colors" 
          aria-controls="navbar-menu" 
          :aria-expanded="isMenuOpen"
        >
          <span class="sr-only">Toggle menu</span>
          <svg class="w-6 h-6 text-neutral-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

      </div>
    </div>

    <transition name="menu-slide">
      <div v-show="isMenuOpen" id="navbar-menu" class="xl:hidden absolute top-full left-0 mt-2 w-full border border-neutral-100 dark:border-neutral-800 bg-white dark:bg-neutral-900 rounded-2xl shadow-xl overflow-hidden max-h-[70vh] overflow-y-auto custom-scrollbar">
        <ul class="flex flex-col p-4 font-medium space-y-2">
          
          <li v-for="link in navLinks" :key="link.name">
            <a 
              href="#" 
              @click.prevent="setActive(link.name, link.id)" 
              :class="activeLink === link.name ? 'text-[#009933] bg-green-50 dark:bg-neutral-800' : 'text-neutral-700 dark:text-gray-300'" 
              class="block py-2.5 px-4 hover:bg-neutral-50 dark:hover:bg-neutral-800 rounded-lg transition-colors duration-300"
            >
              {{ link.name }}
            </a>
          </li>

        </ul>
      </div>
    </transition>

  </nav>
</template>

<style scoped>
/* Optional transition for the mobile menu */
.menu-slide-enter-active,
.menu-slide-leave-active {
  transition: all 0.3s ease-out;
}

.menu-slide-enter-from,
.menu-slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>