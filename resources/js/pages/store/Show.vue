<script setup lang="ts">
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { Store, Star, MapPin } from 'lucide-vue-next'; 
import { ref, computed } from 'vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

const props = defineProps<{
    product: any;
}>();

const page = usePage();
const user = computed(() => page.props.auth.user);

const quantity = ref(1);

// Smart Image Resolver
const activeImage = computed(() => {
    const img = props.product?.images?.[0];

    if (!img) {
        return '/assets/store/online-store.jpg'; 
    }

    if (img.startsWith('http') || img.startsWith('/assets') || img.startsWith('/storage/')) {
        return img;
    }

    return '/storage/' + img;
});

const increaseQuantity = () => {
    if (quantity.value < props.product.stock) {
        quantity.value++;
    }
};

const decreaseQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

const handleAddToCart = () => {
    if (!user.value) {
        router.visit('/login'); 

        return;
    }

    router.post('/cart', {
        product_id: props.product.id,
        quantity: quantity.value
    });
};

const handleBuyNow = () => {
    if (!user.value) {
        router.visit('/login'); 

        return;
    }
    
    router.visit(`/checkout?direct=${props.product.id}&qty=${quantity.value}`);
};
</script>

<template>
    <Head :title="`${props.product.title} - Store`" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>

    <main class="flex w-full justify-center px-4 mt-6 mb-24">
        <div class="w-full max-w-7xl bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 md:p-8">
            
            <nav class="text-sm text-gray-500 mb-8 flex items-center gap-2">
                <Link href="/" class="hover:text-[#009933]">Home</Link>
                <span>›</span>
                <Link href="/store" class="hover:text-[#009933]">Online Store</Link>
                <span>›</span>
                <span class="text-gray-800 font-medium truncate">{{ props.product.title }}</span>
            </nav>

            <div class="flex flex-col md:flex-row gap-8 lg:gap-12">
                
                <div class="w-full md:w-5/12 shrink-0">
                    <div class="bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 aspect-square relative shadow-sm">
                        <img 
                            :src="activeImage" 
                            :alt="props.product.title" 
                            class="absolute inset-0 w-full h-full object-cover" 
                        />
                    </div>
                </div>

                <div class="w-full md:w-7/12 flex flex-col">
                    <h1 class="text-2xl md:text-4xl font-black text-gray-800 leading-tight">
                        {{ props.product.title }}
                    </h1>
                    
                    <div class="flex items-center text-sm text-gray-500 mt-3 space-x-4">
                        <div class="flex items-center text-amber-400">
                            <Star class="w-4 h-4 fill-current" />
                            <Star class="w-4 h-4 fill-current" />
                            <Star class="w-4 h-4 fill-current" />
                            <Star class="w-4 h-4 fill-current" />
                            <Star class="w-4 h-4 fill-current" />
                            <span class="ml-2 text-gray-600 font-medium">5.0</span>
                        </div>
                        <span>|</span>
                        <span>0 Ratings</span>
                        <span>|</span>
                        <span>0 Sold</span>
                    </div>

                    <div class="bg-green-50 p-6 rounded-2xl mt-6 border border-green-100 shadow-sm">
                        <div class="flex items-end gap-3">
                            <span class="text-4xl font-black text-[#009933]">₱{{ props.product.price }}</span>
                        </div>
                    </div>

                    <div v-if="props.product.storeId" class="mt-6 flex items-center justify-between bg-white border border-gray-100 shadow-sm p-4 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-50 text-[#009933] rounded-full flex items-center justify-center">
                                <Store class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ props.product.storeName }}</p>
                                <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                    <MapPin class="w-3 h-3 mr-1" /> Philippines
                                </div>
                            </div>
                        </div>
                        <Link 
                            :href="`/shop/${props.product.storeId}`" 
                            class="px-4 py-2 text-sm font-bold text-[#009933] border border-[#009933] rounded-lg hover:bg-[#009933] hover:text-white transition-colors"
                        >
                            View Shop
                        </Link>
                    </div>

                    <div class="mt-8 flex items-center gap-6">
                        <span class="text-sm text-gray-500 font-medium w-20">Quantity</span>
                        <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden shadow-sm">
                            <button @click="decreaseQuantity" class="w-10 h-10 bg-gray-50 hover:bg-gray-100 flex items-center justify-center font-bold text-xl text-gray-600 transition-colors">-</button>
                            <div class="w-14 h-10 flex items-center justify-center font-bold text-gray-800 border-x border-gray-300 bg-white">
                                {{ quantity }}
                            </div>
                            <button @click="increaseQuantity" class="w-10 h-10 bg-gray-50 hover:bg-gray-100 flex items-center justify-center font-bold text-xl text-gray-600 transition-colors">+</button>
                        </div>
                        <span class="text-sm text-gray-500">{{ props.product.stock }} pieces available</span>
                    </div>

                    <div class="mt-8 flex gap-4">
                        <button @click="handleAddToCart" class="flex-1 py-4 border-2 border-[#009933] bg-green-50 text-[#009933] rounded-xl font-bold text-lg hover:bg-green-100 transition-colors flex items-center justify-center gap-2 shadow-sm">
                            🛒 Add to Cart
                        </button>
                        <button @click="handleBuyNow" class="flex-1 py-4 bg-[#009933] text-white rounded-xl font-bold text-lg hover:bg-green-700 transition-colors shadow-md hover:shadow-lg flex items-center justify-center">
                            Buy Now
                        </button>
                    </div>

                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-gray-100">
                <h2 class="text-xl font-black text-gray-800 mb-6 bg-gray-50 inline-block px-4 py-2 rounded-lg border border-gray-100">Product Description</h2>
                <div class="text-gray-600 leading-relaxed max-w-4xl whitespace-pre-wrap text-lg">
                    {{ props.product.description }}
                </div>
            </div>
        </div>
    </main>
</template>