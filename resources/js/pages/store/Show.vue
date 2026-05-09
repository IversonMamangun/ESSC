<script setup lang="ts">
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { 
    Store, Star, MapPin, ShoppingCart, 
    Zap, ChevronRight, ShieldCheck, 
    Plus, Minus 
} from 'lucide-vue-next'; 
import { ref, computed } from 'vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';

const props = defineProps<{
    product: any;
}>();

const page = usePage();
const user = computed(() => page.props.auth.user);

const quantity = ref(1);

const activeImage = computed(() => {
    const img = props.product?.images?.[0];
    if (!img) return '/assets/store/online-store.jpg'; 
    if (img.startsWith('http') || img.startsWith('/assets') || img.startsWith('/storage/')) return img;
    return '/storage/' + img;
});

const increaseQuantity = () => {
    if (quantity.value < props.product.stock) quantity.value++;
};

const decreaseQuantity = () => {
    if (quantity.value > 1) quantity.value--;
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
    // Changed to a POST request pointing to our new Buy Now route
    router.post('/cart/buy-now', {
        product_id: props.product.id,
        quantity: quantity.value
    });
};
</script>

<template>
    <Head :title="`${props.product.title} - Store`" />
    
    <!-- REMOVED bg-zinc-50 dark:bg-zinc-950 to use global background, added flex flex-col -->
    <div class="min-h-screen transition-colors duration-300 flex flex-col">
        <TopBar />
        <div class="sticky top-0 z-50 mt-8">
            <Navbar />
        </div>

        <!-- Added flex-grow so the footer stays at the bottom -->
        <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 md:py-12 mb-20">
            
            <nav class="flex items-center gap-2 text-sm font-medium text-zinc-500 dark:text-zinc-400 mb-8">
                <Link href="/" class="hover:text-[#009933] transition-colors">Home</Link>
                <ChevronRight class="w-4 h-4" />
                <Link href="/store" class="hover:text-[#009933] transition-colors">Online Store</Link>
                <ChevronRight class="w-4 h-4" />
                <span class="text-zinc-900 dark:text-white truncate max-w-[200px] md:max-w-none">{{ props.product.title }}</span>
            </nav>

            <div class="bg-white dark:bg-zinc-900 rounded-[2rem] shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden transition-colors">
                <div class="flex flex-col lg:flex-row">
                    
                    <div class="lg:w-1/2 p-6 md:p-8 border-b lg:border-b-0 lg:border-r border-zinc-200 dark:border-zinc-800">
                        <div class="aspect-square rounded-2xl overflow-hidden bg-zinc-50 dark:bg-zinc-800 relative group border border-zinc-200 dark:border-zinc-700">
                            <img 
                                :src="activeImage" 
                                :alt="props.product.title" 
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                            />
                        </div>
                    </div>

                    <div class="lg:w-1/2 p-6 md:p-10 flex flex-col">
                        <div class="mb-6">
                            <span class="bg-green-100 dark:bg-green-900/30 text-[#009933] dark:text-green-400 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border border-green-200 dark:border-green-800/50 mb-4 inline-block">
                                Official Product
                            </span>
                            <h1 class="text-2xl md:text-4xl font-black text-zinc-900 dark:text-white leading-tight">
                                {{ props.product.title }}
                            </h1>
                            
                            <div class="flex flex-wrap items-center gap-4 mt-4 text-sm">
                                <div class="flex items-center gap-1 text-amber-400">
                                    <Star v-for="i in 5" :key="i" class="w-4 h-4 fill-current" />
                                    <span class="ml-1 text-zinc-900 dark:text-white font-black">{{ props.product.rating || '5.0' }}</span>
                                </div>
                                <div class="w-px h-3 bg-zinc-300 dark:bg-zinc-700"></div>
                                <span class="text-zinc-500 dark:text-zinc-400 font-bold">{{ props.product.reviews || '0' }} Ratings</span>
                                <div class="w-px h-3 bg-zinc-300 dark:bg-zinc-700"></div>
                                <span class="text-zinc-500 dark:text-zinc-400 font-bold">{{ props.product.sold || '0' }} Sold</span>
                            </div>
                        </div>

                        <div class="bg-zinc-50 dark:bg-zinc-800/50 p-6 rounded-3xl mb-8 border border-zinc-200 dark:border-zinc-800 transition-colors">
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl md:text-5xl font-black text-[#009933] tracking-tighter">
                                    ₱{{ props.product.price }}
                                </span>
                            </div>
                            <div class="mt-4 flex items-center gap-2 text-xs text-[#009933] font-bold">
                                <ShieldCheck class="w-4 h-4" />
                                100% Authentic Guarantee
                            </div>
                        </div>

                        <div v-if="props.product.storeId" class="flex items-center justify-between p-4 bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm mb-8 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-green-50 dark:bg-green-900/20 text-[#009933] rounded-xl flex items-center justify-center font-black text-xl shadow-inner border border-green-100 dark:border-green-800">
                                    <Store class="w-6 h-6" />
                                </div>
                                <div>
                                    <p class="font-black text-zinc-900 dark:text-white">{{ props.product.storeName }}</p>
                                    <div class="flex items-center text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                                        <MapPin class="w-3 h-3 mr-1" /> {{ props.product.location || 'Local' }}
                                    </div>
                                </div>
                            </div>
                            <Link 
                                :href="`/shop/${props.product.storeId}`" 
                                class="px-5 py-2 text-xs font-black uppercase tracking-widest text-[#009933] border-2 border-[#009933] rounded-xl hover:bg-[#009933] hover:text-white transition-all active:scale-95"
                            >
                                View Shop
                            </Link>
                        </div>

                        <div class="space-y-4 mb-10">
                            <label class="text-zinc-500 dark:text-zinc-400 font-bold uppercase tracking-widest text-xs ml-1">Select Quantity</label>
                            <div class="flex items-center gap-6">
                                <div class="flex items-center bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-1 shadow-inner transition-colors">
                                    <button @click="decreaseQuantity" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-white dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-300 transition-all active:scale-90">
                                        <Minus class="w-5 h-5" />
                                    </button>
                                    <div class="w-14 text-center font-black text-zinc-900 dark:text-white">{{ quantity }}</div>
                                    <button @click="increaseQuantity" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-white dark:hover:bg-zinc-700 text-zinc-600 dark:text-zinc-300 transition-all active:scale-90">
                                        <Plus class="w-5 h-5" />
                                    </button>
                                </div>
                                <span class="text-xs font-bold text-zinc-400 dark:text-zinc-500">{{ props.product.stock }} pieces available</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 mt-auto">
                            <button @click="handleAddToCart" class="flex-1 py-4 border-2 border-[#009933] text-[#009933] font-black uppercase tracking-widest rounded-2xl hover:bg-green-50 dark:hover:bg-green-900/10 transition-all flex items-center justify-center gap-3 active:scale-95 shadow-sm">
                                <ShoppingCart class="w-5 h-5" /> Add To Cart
                            </button>
                            <button @click="handleBuyNow" class="flex-1 py-4 bg-[#009933] text-white font-black uppercase tracking-widest rounded-2xl hover:bg-green-700 transition-all shadow-lg shadow-green-900/20 flex items-center justify-center gap-3 active:scale-95">
                                <Zap class="w-5 h-5 fill-current" /> Buy Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white dark:bg-zinc-900 rounded-[2rem] p-8 md:p-12 shadow-sm border border-zinc-200 dark:border-zinc-800 transition-colors">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-8 w-1.5 bg-[#009933] rounded-full"></div>
                    <h2 class="text-2xl font-black text-zinc-900 dark:text-white uppercase tracking-widest">Description</h2>
                </div>
                <div class="text-zinc-600 dark:text-zinc-300 leading-relaxed max-w-4xl whitespace-pre-wrap text-lg font-medium">
                    {{ props.product.description }}
                </div>
            </div>
            
        </main>
        
        <Footer />
    </div>
</template>