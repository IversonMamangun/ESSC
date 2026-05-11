<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Store, MapPin, Package, Star, MessageCircle, Plus } from 'lucide-vue-next';
import ProductCard from '@/components/ProductCard.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import Pagination from '@/components/Pagination.vue'; 

const props = defineProps<{
    store: any;
    products: any;
}>();
</script>

<template>
    <Head :title="store.name" />
    
    <!-- REMOVED bg-zinc-50 dark:bg-zinc-950 to use global background -->
    <div class="min-h-screen transition-colors duration-300 flex flex-col">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8">
            <Navbar />
        </div>

        <main class="flex-grow pb-20">
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 md:mt-12 mb-8">
                <!-- UPDATED: bg-white -> bg-zinc-50 -->
                <div class="bg-zinc-50 dark:bg-zinc-900 rounded-3xl shadow-sm border border-zinc-200 dark:border-zinc-800 p-6 md:p-8 flex flex-col lg:flex-row lg:items-center justify-between gap-8 transition-colors">
                    
                    <div class="flex flex-col md:flex-row md:items-center gap-6">
                        <div class="relative shrink-0 mx-auto md:mx-0">
                            <div class="w-24 h-24 md:w-28 md:h-28 rounded-full overflow-hidden border-4 border-zinc-50 dark:border-zinc-800 shadow-sm bg-zinc-50 dark:bg-zinc-800 text-[#009933] flex items-center justify-center transition-colors">
                                <img v-if="store.logo" :src="'/storage/' + store.logo" class="w-full h-full object-cover" />
                                <Store v-else class="w-10 h-10 md:w-12 md:h-12" />
                            </div>
                            <div class="absolute bottom-2 right-2 md:bottom-3 md:right-3 w-5 h-5 bg-green-500 border-2 border-white dark:border-zinc-900 rounded-full shadow-sm transition-colors"></div>
                        </div>

                        <div class="text-center md:text-left">
                            <div class="flex flex-col md:flex-row md:items-center gap-3 justify-center md:justify-start">
                                <h1 class="text-2xl md:text-3xl font-black text-zinc-900 dark:text-white leading-tight">
                                    {{ store.name }}
                                </h1>
                                <span class="bg-[#009933] text-white text-[10px] font-black px-2.5 py-1 rounded uppercase tracking-wider shadow-sm inline-block w-max mx-auto md:mx-0">
                                    Preferred
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-4">
                                <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800/50 px-3 py-1.5 rounded-lg border border-zinc-100 dark:border-zinc-700/50 transition-colors">
                                    <Star class="w-4 h-4 text-amber-400 fill-current mr-1.5" />
                                    <span class="font-bold text-zinc-800 dark:text-zinc-100">{{ store.rating || '4.9' }}</span>
                                    <span class="ml-1 text-zinc-400 dark:text-zinc-500 text-xs">(1.2k)</span>
                                </div>
                                <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800/50 px-3 py-1.5 rounded-lg border border-zinc-100 dark:border-zinc-700/50 transition-colors">
                                    <span class="font-bold text-zinc-800 dark:text-zinc-100">{{ store.followers_count || '5.4k' }}</span>
                                    <span class="ml-1.5 text-zinc-500 text-xs">Followers</span>
                                </div>
                                <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-300 bg-zinc-50 dark:bg-zinc-800/50 px-3 py-1.5 rounded-lg border border-zinc-100 dark:border-zinc-700/50 transition-colors">
                                    <MapPin class="w-4 h-4 mr-1.5 text-zinc-400 dark:text-zinc-500" />
                                    <span class="text-zinc-700 dark:text-zinc-300 text-xs">{{ store.city || 'Philippines' }}</span>
                                </div>
                            </div>
                            
                            <p class="mt-4 text-sm text-zinc-500 dark:text-zinc-400 line-clamp-2 max-w-2xl mx-auto md:mx-0 leading-relaxed">
                                {{ store.description || 'Official Store: Quality products and fast shipping. Welcome to our online shop!' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-3 w-full lg:w-auto shrink-0 border-t lg:border-t-0 border-zinc-100 dark:border-zinc-800 pt-6 lg:pt-0 transition-colors">
                        <button class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-3 border-2 border-[#009933] text-[#009933] dark:text-[#00cc44] font-bold rounded-xl hover:bg-green-50 dark:hover:bg-green-900/20 transition-all active:scale-95 shadow-sm">
                            <MessageCircle class="w-5 h-5" />
                            Chat
                        </button>

                        <button class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-6 py-3 bg-[#009933] text-white font-bold rounded-xl hover:bg-green-700 transition-all active:scale-95 shadow-md">
                            <Plus class="w-5 h-5" />
                            Follow
                        </button>
                    </div>
                </div>
            </section>

            <section class="flex w-full justify-center px-4 mt-4">
                <div class="w-full max-w-7xl">
                    <h2 class="text-xl font-black text-zinc-800 dark:text-white mb-6 border-b border-zinc-200 dark:border-zinc-800 pb-3 flex items-center gap-2 transition-colors">
                        <Package class="w-5 h-5 text-[#009933]" /> All Items
                    </h2>
                    
                    <div v-if="products.data.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        <div v-for="product in products.data" :key="product.id">
                            <ProductCard :product="product" />
                        </div>
                    </div>

                      <div v-else class="text-center py-20 text-zinc-500 dark:text-zinc-400 bg-zinc-50 dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800 shadow-sm transition-colors">
                        <Store class="w-12 h-12 mx-auto text-zinc-300 dark:text-zinc-600 mb-4" />
                        <p class="font-bold">This seller hasn't uploaded any products yet!</p>
                    </div>

                    <Pagination :links="products.links" />

                </div>
            </section>
        </main>

        <Footer />
    </div>
</template>