<script setup lang="ts">
import { computed } from 'vue';

// Automatically computes the current year for the copyright text
const currentYear = computed(() => new Date().getFullYear());
</script>

<template>
    <footer class="w-full bg-[#009933] mt-4">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-8 p-6 md:p-10 lg:gap-12 lg:p-12">
            
            <div class="flex w-full justify-center">
                <img 
                    src="/assets/logos/down logo.png" 
                    class="h-20 object-contain md:h-28" 
                    alt="Footer Logo" 
                />
            </div>

            <div class="grid grid-cols-1 gap-8 text-white sm:grid-cols-2 lg:grid-cols-4 lg:gap-6">
                
                <div class="flex flex-col sm:col-span-2 lg:col-span-1">
                    <h2 class="text-heading leading-tight text-lg md:text-xl lg:text-2xl">
                        Industrial Chemical Solutions<br/>
                        Environmental Technologies<br/>
                        Agricultural Innovations
                    </h2>
                </div>

                <div class="flex flex-col">
                    <h5 class="mb-4 text-lg font-bold">Quick Links:</h5>
                    <ul class="space-y-3 text-sm font-medium md:text-base">
                        <li><a href="#home" class="transition-all hover:underline">Home</a></li>
                        <li><a href="#about" class="transition-all hover:underline">About Us</a></li>
                        <li><a href="#products" class="transition-all hover:underline">Products & Solutions</a></li>
                    </ul>
                </div>

                <div class="flex flex-col">
                    <h5 class="mb-4 hidden text-lg font-bold sm:block opacity-0 lg:block">Links</h5>
                    <ul class="space-y-3 text-sm font-medium md:text-base">
                        <li><a href="#industries" class="transition-all hover:underline">Industries Served</a></li>
                        <li><a href="#capabilities" class="transition-all hover:underline">Capabilities</a></li>
                        <li><a href="#clients" class="transition-all hover:underline">Clients</a></li>
                    </ul>
                </div>

                <div class="flex flex-col">
                    <h5 class="mb-4 hidden text-lg font-bold sm:block opacity-0 lg:block">Links</h5>
                    <ul class="space-y-3 text-sm font-medium md:text-base">
                        <li><a href="#news" class="transition-all hover:underline">News & Updates</a></li>
                        <li><a href="#contact" class="transition-all hover:underline">Contact Us</a></li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="flex w-full flex-col items-center justify-evenly gap-3 bg-[#006633] px-6 py-4 text-center text-xs text-white md:flex-row md:px-12 md:text-sm">
            <div class="leading-relaxed">
                © {{ currentYear }} 
                <a href="/" class="font-semibold hover:underline">Everlasting Star Sales Corporation</a>. 
                <br class="md:hidden" />
                All Rights Reserved.
            </div>
            <div>
                Designed & Developed by 
                <a 
                    class="font-semibold hover:underline" 
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