<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
// 1. Import the icons we need for the UI
import { Star, MapPin } from 'lucide-vue-next'; 

const props = defineProps<{
    product: any;
}>();

// Smart Image Resolver
const imageUrl = computed(() => {
    const img = props.product.image;
    if (!img) return '/assets/store/online-store.jpg'; 
    if (img.startsWith('http') || img.startsWith('/assets')) return img;
    return '/storage/' + img;
});

// UI Placeholders for data we haven't built yet
const rating = computed(() => props.product.rating || '4.5');
const soldCount = computed(() => props.product.sold_count || '0');

// Attempt to grab the city from the related store, default to 'Philippines' if null
const location = computed(() => props.product.store?.city || 'Philippines'); 
</script>

<template>
    <Link :href="`/product/${product.id}`" class="block group h-full">
        <div class="bg-white rounded-lg overflow-hidden border border-gray-100 hover:shadow-md transition-shadow h-full flex flex-col">
            
            <div class="relative w-full pt-[100%] overflow-hidden bg-gray-50 border-b border-gray-100">
                <img 
                    :src="imageUrl" 
                    :alt="product.title" 
                    class="absolute top-0 left-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                />
            </div>
            
            <div class="p-3 flex flex-col flex-grow">
                <h3 class="text-sm font-medium text-gray-800 line-clamp-2 leading-tight flex-grow">
                    {{ product.title }}
                </h3>
                
                <div class="mt-2">
                    <p class="text-[#009933] font-bold text-lg">₱{{ product.price }}</p>
                </div>

                <div class="flex items-center text-xs text-gray-500 mt-1 space-x-2">
                    <div class="flex items-center text-amber-400">
                        <Star class="w-3 h-3 fill-current" />
                        <span class="ml-1 text-gray-600 font-medium">{{ rating }}</span>
                    </div>
                    <span>|</span>
                    <span>{{ soldCount }} sold</span>
                </div>

                <div class="mt-3 pt-2 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span class="font-medium text-gray-600">{{ product.stock }} available</span>
                    
                    <div class="flex items-center truncate ml-2">
                        <MapPin class="w-3 h-3 mr-1 shrink-0" />
                        <span class="truncate">{{ location }}</span>
                    </div>
                </div>
            </div>
            
        </div>
    </Link>
</template>