<script setup lang="ts">
import { ref, onMounted } from 'vue';

const capabilities = [
    'Bulk supply and scheduled product delivery',
    'Customized product sourcing',
    'Direct distribution to facilities and project sites',
    'Technical product support and application guidance',
    'Logistics coordination and inventory management',
];

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
    <section
        class="flex w-full flex-col items-center gap-12 pt-10 pb-16 md:pb-24"
    >
        <div
            class="relative flex min-h-32 w-full flex-col items-center justify-center overflow-hidden py-4 md:min-h-40 md:py-6"
        >
            <div class="absolute inset-0 flex w-full justify-center">
                <img
                    src="/assets/Our Capabilities.png"
                    alt="Our Capabilities Background"
                    class="h-full w-full max-w-7xl rounded-2xl object-cover"
                />
            </div>
            <div class="absolute inset-0"></div>

            <div
                class="relative z-10 mx-auto flex w-full max-w-7xl flex-col items-center px-6 text-center"
            >
                <h2 class="mb-2 text-xl font-bold text-white md:text-3xl">
                    Our Capabilities
                </h2>
                <p
                    class="max-w-2xl text-sm leading-relaxed text-gray-100 md:text-base"
                >
                    Everlasting Star Sales Corporation has the operational
                    capability to support government and private sector
                    procurement requirements including:
                </p>
            </div>
        </div>

        <div
            class="mx-auto grid w-full max-w-7xl grid-cols-1 gap-4 px-4 md:grid-cols-2 lg:px-12"
        >
            <div
                v-for="(item, index) in capabilities"
                :key="index"
                :class="[
                    'flex min-h-16 items-center justify-center rounded-2xl bg-[#009933] px-6 py-4 text-center font-medium text-white shadow-md md:px-10',
                    index === 4 ? 'md:col-span-2 md:mx-auto md:w-1/2' : '',
                ]"
            >
                {{ item }}
            </div>
        </div>

        <div
            class="mx-auto mt-4 flex w-full max-w-7xl flex-col gap-8 px-4 lg:px-12"
        >
            <div class="flex flex-col gap-2">
                <h2 class="text-xl font-bold text-[#009933] md:text-2xl">
                    Commitment to Quality
                </h2>
                <p class="text-base leading-relaxed text-gray-700 md:text-lg">
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
                <p class="text-base leading-relaxed text-gray-700 md:text-lg">
                    ESSC proudly serves various industrial companies,
                    agricultural organizations, and institutions including:
                </p>
            </div>
            <div class="items-center mx-auto mt-4 flex w-full max-w-7xl flex-col gap-8 px-4 lg:px-12">
            <div class="flex items-center w-full max-w-2xl ">
                <div ref="carouselRef" class="owl-carousel owl-theme gap-2">
                    <div
                        class="item flex justify-center"
                        v-for="(logo, index) in logos"
                        :key="index"
                    >
                        <img
                            :src="logo"
                            alt="Client Logo"
                            class="h-22 max-h-25 w-25 max-w-full min-w-2 min-h-2 rounded-full border border-white bg-white object-contain shadow-md transition-transform duration-300 md:h-auto md:w-auto"
                        />
                    </div>
                </div>
                </div>
            </div>
            <p class="text-center text-gray-700">
                And many more partners across the country.
            </p>
        </div>
    </section>
</template>
