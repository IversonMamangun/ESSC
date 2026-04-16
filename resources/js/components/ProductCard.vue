<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    product: {
        id: number;
        image: string;
        title: string;
        location: string;
        price: string;
        sold: number;
        rating: number;
    }
}>();
</script>

<template>
    <div class="w-full h-full bg-white border border-neutral-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col">
        
        <Link :href="'/product/' + product.id" class="w-full h-32 bg-gray-100 rounded-lg mb-3 flex items-center justify-center text-gray-400 overflow-hidden shrink-0">
            <img v-if="product.image" :src="product.image" :alt="product.title" class="w-full h-full object-cover" />
            <span v-else class="text-xs uppercase font-bold">Image</span>
        </Link>
        
        <Link :href="'/product/' + product.id" class="text-sm font-bold text-gray-800 line-clamp-1 hover:text-[#009933] transition-colors" :title="product.title">
            {{ product.title }}
        </Link>
        
        <div class="flex items-center text-xs text-gray-500 mt-1 gap-2">
            <span class="text-yellow-500 font-bold">⭐ {{ product.rating }}</span>
            <span>|</span>
            <span>{{ product.sold }} sold</span>
        </div>

        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1 truncate">
            <span>📍</span> {{ product.location }}
        </p>
        
        <p class="text-[#009933] text-lg font-black mt-2">
            {{ product.price }} 
        </p>
        
        <div class="mt-auto pt-3 flex gap-2">
            <Link :href="'/product/' + product.id" class="flex-1 bg-[#009933] text-white py-1.5 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors flex items-center justify-center">
                Buy Now
            </Link>
            <Link :href="'/product/' + product.id" class="p-1.5 border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors flex items-center justify-center" title="Add to cart">
                🛒
            </Link>
        </div>

    </div>
</template>