<script setup lang="ts">
import { computed } from 'vue';

const currentYear = computed(() => new Date().getFullYear());
</script>

<template>
    <footer class="w-full bg-[#009933] mt-4">
        <div class="mx-auto flex w-full max-w-7xl flex-col p-6 md:p-10 lg:p-12">
            
            <div class="grid grid-cols-1 gap-10 text-white sm:grid-cols-2 lg:grid-cols-5 lg:gap-8">
                
                <div class="flex flex-col items-center text-center lg:items-start lg:text-left sm:col-span-2 lg:col-span-2 lg:pr-4">
                    <img 
                        src="/assets/logos/down logo.png" 
                        class="h-16 md:h-20 object-contain mb-6" 
                        alt="Footer Logo" 
                    />
                    <h2 class="text-heading leading-relaxed text-sm md:text-base lg:text-lg font-medium text-green-50">
                        Industrial Chemical Solutions<br/>
                        Environmental Technologies<br/>
                        Agricultural Innovations
                    </h2>
                </div>

                <div class="flex flex-col items-center text-center lg:items-start lg:text-left">
                    <h5 class="mb-4 text-lg font-bold tracking-wide">Quick Links</h5>
                    <ul class="space-y-3 text-sm font-medium md:text-base text-green-100">
                        <li><a href="#home" class="transition-all hover:text-white hover:underline">Home</a></li>
                        <li><a href="#about" class="transition-all hover:text-white hover:underline">About Us</a></li>
                        <li><a href="#products" class="transition-all hover:text-white hover:underline">Products & Solutions</a></li>
                    </ul>
                </div>

                <div class="flex flex-col items-center text-center lg:items-start lg:text-left">
                    <h5 class="mb-4 text-lg font-bold tracking-wide">Explore</h5>
                    <ul class="space-y-3 text-sm font-medium md:text-base text-green-100">
                        <li><a href="#industries" class="transition-all hover:text-white hover:underline">Industries Served</a></li>
                        <li><a href="#capabilities" class="transition-all hover:text-white hover:underline">Capabilities</a></li>
                        <li><a href="#clients" class="transition-all hover:text-white hover:underline">Clients</a></li>
                    </ul>
                </div>

                <div class="flex flex-col items-center text-center lg:items-start lg:text-left">
                    <h5 class="mb-4 text-lg font-bold tracking-wide">Connect</h5>
                    <ul class="space-y-3 text-sm font-medium md:text-base text-green-100">
                        <li><a href="#news" class="transition-all hover:text-white hover:underline">News & Updates</a></li>
                        <li><a href="#contact" class="transition-all hover:text-white hover:underline">Contact Us</a></li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="flex w-full flex-col items-center justify-evenly gap-3 bg-[#006633] px-6 py-4 text-center text-xs text-green-100 md:flex-row md:px-12 md:text-sm">
            <div class="leading-relaxed">
                © {{ currentYear }} 
                <a href="Everlastingssc.com" class="font-semibold text-white hover:underline">Everlasting Star Sales Corporation</a>. 
                <br class="md:hidden" />
                All Rights Reserved.
            </div>
            <div>
                Designed & Developed by 
                <a 
                    class="font-semibold text-white hover:underline" 
                    href="https://bb88advertising.com/" 
                    target="_blank" 
                    rel="noopener noreferrer"
                >
                    BB 88 Advertising and Digital Solutions Inc.
                </a>
            </div>
        </div>
    </footer>
</template>