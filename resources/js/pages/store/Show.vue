<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

const props = defineProps<{
    product: any;
}>();

const page = usePage();
const user = computed(() => page.props.auth.user);

const quantity = ref(1);
// Assuming product.images is an array or JSON from DB
const activeImage = ref(props.product?.images?.[0] || '/assets/store/online-store.jpg');

const increaseQuantity = () => {
    if (quantity.value < props.product.stock) quantity.value++;
};

const decreaseQuantity = () => {
    if (quantity.value > 1) quantity.value--;
};

// Handlers for Auth
const handleAddToCart = () => {
    if (!user.value) {
        router.visit('/login'); // Use string URL instead of route()
        return;
    }
    router.post('/cart', {
        product_id: props.product.id,
        quantity: quantity.value
    });
};

const handleBuyNow = () => {
    if (!user.value) {
        router.visit('/login'); // Use string URL instead of route()
        return;
    }
    // Direct checkout logic
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
            <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
                <Link href="/" class="hover:text-[#009933]">Home</Link>
                <span>›</span>
                <Link href="/store" class="hover:text-[#009933]">Online Store</Link>
                <span>›</span>
                <span class="text-gray-800 font-medium truncate">{{ props.product.title }}</span>
            </nav>

            <div class="flex flex-col md:flex-row gap-8 lg:gap-12">
                <div class="w-full md:w-7/12 flex flex-col">
                    <h1 class="text-2xl md:text-3xl font-black text-gray-800 leading-tight">
                        {{ props.product.title }}
                    </h1>
                    
                    <div class="bg-green-50 p-6 rounded-2xl mt-6 border border-green-100">
                        <div class="flex items-end gap-3">
                            <span class="text-4xl font-black text-[#009933]">₱{{ props.product.price }}</span>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-6">
                        <span class="text-sm text-gray-500 w-20">Quantity</span>
                        <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden">
                            <button @click="decreaseQuantity" class="w-10 h-10 bg-gray-50 hover:bg-gray-100 flex items-center justify-center font-bold text-xl text-gray-600">-</button>
                            <div class="w-14 h-10 flex items-center justify-center font-bold text-gray-800 border-x border-gray-300 bg-white">
                                {{ quantity }}
                            </div>
                            <button @click="increaseQuantity" class="w-10 h-10 bg-gray-50 hover:bg-gray-100 flex items-center justify-center font-bold text-xl text-gray-600">+</button>
                        </div>
                        <span class="text-xs text-gray-400">{{ props.product.stock }} pieces available</span>
                    </div>

                    <div class="mt-10 flex gap-4">
                        <button @click="handleAddToCart" class="flex-1 py-4 border-2 border-[#009933] bg-green-50 text-[#009933] rounded-xl font-bold text-lg hover:bg-green-100 transition-colors flex items-center justify-center gap-2">
                            🛒 Add to Cart
                        </button>
                        <button @click="handleBuyNow" class="flex-1 py-4 bg-[#009933] text-white rounded-xl font-bold text-lg hover:bg-green-700 transition-colors shadow-md hover:shadow-lg flex items-center justify-center">
                            Buy Now
                        </button>
                    </div>

                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-gray-100">
                <h2 class="text-xl font-black text-gray-800 mb-4 bg-gray-50 inline-block px-4 py-2 rounded-lg">Product Description</h2>
                <p class="text-gray-600 leading-relaxed max-w-4xl">
                    {{ props.product.description }}
                </p>
            </div>
        </div>
    </main>
</template>