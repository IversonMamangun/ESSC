<script setup lang="ts">
import { ref, onMounted } from 'vue';

const logos = [
    '/assets/logos/logo1.png',
    '/assets/logos/logo2.png',
    '/assets/logos/logo3.png',
    '/assets/logos/logo4.png',
    '/assets/logos/logo5.png',
    '/assets/logos/logo6.png',
];

const carouselRef = ref(null);

onMounted(() => {
    const $ = (window as any).$;

    if ($ && carouselRef.value) {
        $(carouselRef.value).owlCarousel({
            loop: true,
            margin: 30,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            lazyLoad: true,
            responsive: {
                0: { items: 3 },
                640: { items: 5 },
                1024: { items: 6 },
                1280: { items: 6 },
            },
        });
    }
});
</script>

<template>
    <section class="mx-auto mt-4 flex w-full max-w-7xl flex-col gap-8 px-4 lg:px-12 pb-16">
        
        <div class="flex flex-col gap-2">
            <h2 class="text-xl font-bold text-[#009933] md:text-2xl">
                Commitment to Quality
            </h2>
            <p class="text-base leading-relaxed text-gray-700 dark:text-gray-300 md:text-lg">
                ESSC ensures that all products meet industry safety
                standards and regulatory requirements. The company promotes
                environmentally responsible chemical solutions that help
                organizations maintain their facilities while supporting
                sustainability initiatives.
            </p>
        </div>

        <div class="flex flex-col gap-2">
            <h2 class="text-xl font-bold text-[#009933] md:text-2xl">
                Partial list of valued Customers
            </h2>
            <p class="text-base leading-relaxed text-gray-700 dark:text-gray-300 md:text-lg">
                ESSC proudly serves various industrial companies,
                agricultural organizations, and institutions including:
            </p>
        </div>

        <div class="mx-auto mt-4 flex w-full max-w-7xl flex-col items-center gap-8">
            <div class="flex w-full max-w-2xl items-center">
                <div ref="carouselRef" class="owl-carousel owl-theme gap-2">
                    <div
                        class="item flex justify-center"
                        v-for="(logo, index) in logos"
                        :key="index"
                    >
                        <img
                            :src="logo"
                            alt="Client Logo"
                            class="h-22 max-h-25 w-25 min-h-2 min-w-2 max-w-full rounded-full border border-white bg-white object-contain shadow-md transition-transform duration-300 md:h-auto md:w-auto"
                        />
                    </div>
                </div>
            </div>
            
            <p class="text-center text-gray-700 dark:text-gray-300">
                And many more partners across the country.
            </p>
        </div>

    </section>
</template>