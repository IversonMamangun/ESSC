<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Star, MapPin } from 'lucide-vue-next'; 

const props = defineProps<{
    product: any;
}>();

// Smart Image Resolver
const imageUrl = computed(() => {
    // Check multiple possible image property names just in case
    const img = props.product.image || props.product.image_url || props.product.images?.[0];
    if (!img) return '/assets/store/online-store.jpg'; 
    if (img.startsWith('http') || img.startsWith('/assets') || img.startsWith('/storage/')) return img;
    return '/storage/' + img;
});

// UI Placeholders - Now checks multiple possible backend names
const rating = computed(() => props.product.rating || '5.0');

// Checks for 'sold_count', 'sold', or defaults to '0'
const soldCount = computed(() => props.product.sold_count ?? props.product.sold ?? '0');

// Checks for 'stock', 'quantity', 'qty', or defaults to '0'
const availableStock = computed(() => props.product.stock ?? props.product.quantity ?? '0');

// Attempt to grab the city from the related store, default to 'Local' if null
const location = computed(() => props.product.store?.city ?? props.product.location ?? 'Local'); 
</script>

<template>
    <Link :href="`/product/${product.id}`" class="block group h-full">
        <!-- UPDATED: Replaced gray borders/backgrounds with our zinc theme -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden border border-zinc-200 dark:border-zinc-800 hover:shadow-lg hover:border-[#009933]/50 transition-all duration-300 h-full flex flex-col">
            
            <div class="relative w-full pt-[100%] overflow-hidden bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-100 dark:border-zinc-800">
                <img 
                    :src="imageUrl" 
                    :alt="product.title" 
                    class="absolute top-0 left-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                />
            </div>
            
            <div class="p-4 flex flex-col flex-grow">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 line-clamp-2 leading-tight flex-grow group-hover:text-[#009933] transition-colors">
                    {{ product.title }}
                </h3>
                
                <div class="mt-3">
                    <p class="text-[#009933] font-black text-lg tracking-tight">₱{{ product.price }}</p>
                </div>

                <div class="flex items-center text-xs text-zinc-500 dark:text-zinc-400 mt-2 space-x-2 font-medium">
                    <div class="flex items-center text-amber-400">
                        <Star class="w-3.5 h-3.5 fill-current" />
                        <span class="ml-1 text-zinc-700 dark:text-zinc-300 font-bold">{{ rating }}</span>
                    </div>
                    <span class="text-zinc-300 dark:text-zinc-600">|</span>
                    <span>{{ soldCount }} sold</span>
                </div>

                <div class="mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between text-[11px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                    <span :class="availableStock > 0 ? 'text-zinc-600 dark:text-zinc-300' : 'text-red-500'">
                        {{ availableStock > 0 ? `${availableStock} available` : 'Sold Out' }}
                    </span>
                    
                    <div class="flex items-center truncate ml-2">
                        <MapPin class="w-3 h-3 mr-1 shrink-0" />
                        <span class="truncate">{{ location }}</span>
                    </div>
                </div>
            </div>
            
        </div>
    </Link>
</template>