<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Star, MapPin, Play } from 'lucide-vue-next'; 

const props = defineProps<{
    product: any;
}>();

// Smart Image Resolver
const imageUrl = computed(() => {
    const img = props.product.image || props.product.image_url || props.product.images?.[0];
    if (!img) return '/assets/store/online-store.jpg'; 
    if (img.startsWith('http') || img.startsWith('/assets') || img.startsWith('/storage/')) return img;
    return '/storage/' + img;
});

const rating = computed(() => props.product.rating || '5.0');
const soldCount = computed(() => props.product.sold_count ?? props.product.sold ?? '0');
const availableStock = computed(() => props.product.stock ?? props.product.quantity ?? '0');
const location = computed(() => props.product.store?.city ?? props.product.location ?? 'Local'); 

// Check if this product has a valid active discount
const isDiscounted = computed(() => {
    const price = parseFloat(props.product.price);
    const discount = parseFloat(props.product.discount_price);
    return discount && price && discount < price;
});
</script>

<template>
    <Link :href="`/product/${product.id}`" class="block group h-full relative">
        <div class="bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden border border-zinc-200 dark:border-zinc-800 hover:shadow-lg hover:border-[#009933]/50 transition-all duration-300 h-full flex flex-col relative">
            
            <div class="relative w-full pt-[100%] overflow-hidden bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-100 dark:border-zinc-800">
                <img 
                    :src="imageUrl" 
                    :alt="product.title" 
                    class="absolute top-0 left-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                />
                
                <div v-if="product.video" class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-md text-white p-1.5 sm:p-2 rounded-full shadow-sm z-10 flex items-center justify-center pointer-events-none">
                    <Play class="w-3 h-3 sm:w-4 sm:h-4 fill-white translate-x-[1px]" />
                </div>

                <div v-if="isDiscounted" class="absolute top-2 right-2 bg-red-600 text-white text-[10px] sm:text-xs font-black px-2 py-1 rounded-lg shadow-sm z-10 tracking-wider">
                    -{{ product.discount_percentage }}%
                </div>
            </div>
            
            <div class="p-4 flex flex-col grow">
                <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 line-clamp-2 leading-tight grow group-hover:text-[#009933] transition-colors">
                    {{ product.title }}
                </h3>
                
                <div class="mt-3 flex flex-wrap items-end gap-2">
                    <p class="text-[#009933] font-black text-lg tracking-tight">
                        ₱{{ isDiscounted ? product.discount_price : product.price }}
                    </p>
                    
                    <p v-if="isDiscounted" class="text-zinc-400 dark:text-zinc-500 text-xs font-semibold line-through pb-1">
                        ₱{{ product.price }}
                    </p>
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