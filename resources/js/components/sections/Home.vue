<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const solutions = [
    {
        id: 1,
        title: 'Industrial Chemical Products',
        description:
            'Preventive maintenance chemicals, industrial cleaners, corrosion protection, and specialty coatings designed to improve equipment performance and lifespan.',
        image: '/assets/Industrial Chemical Products.png',
    },
    {
        id: 2,
        title: 'Environmental & Sanitation Solutions',
        description:
            'Eco-friendly cleaning systems, microbial technologies, and wastewater treatment solutions that support environmental protection.',
        image: '/assets/Environmental & Sanitation.png',
    },
    {
        id: 3,
        title: 'Agricultural Enhancement Products',
        description:
            'Soil conditioners, bio-organic fertilizers, microbial activators, and plant growth enhancers that support modern sustainable agriculture.',
        image: '/assets/Agricultural Enhancement.png',
    },
    {
        id: 4,
        title: 'Infrastructure & Energy Solutions',
        description:
            'Solar lighting systems including solar streetlights and floodlights designed for municipalities, infrastructure projects, and agricultural estates.',
        image: '/assets/Infrastructure & Energy.png',
    },
];

onMounted(() => {
    const $ = (window as any).$;

    $('.home-solutions-carousel').owlCarousel({
        loop: true,
        margin: 24,
        nav: true,
        dots: false,
        navText: [
            "<img src='/assets/icons and vector/9.png' alt='Previous' class='w-12 h-12 transition hover:scale-110 drop-shadow-md'>",
            "<img src='/assets/icons and vector/10.png' alt='Next' class='w-12 h-12 transition hover:scale-110 drop-shadow-md'>",
        ],
        responsive: {
            0: { items: 1 },
            768: { items: 2 },
            1024: { items: 3 },
        },
    });
});

const commitments = [
    {
        id: 1,
        text: 'High-quality industrial chemical products',
        image: '/assets/icons and vector/8.png',
        alt: 'Industrial Chemicals',
    },
    {
        id: 2,
        text: 'Reliable nationwide supply capability',
        image: '/assets/icons and vector/8.png',
        alt: 'Nationwide Supply',
    },
    {
        id: 3,
        text: 'Sustainable and environmentally responsible solutions',
        image: '/assets/icons and vector/8.png',
        alt: 'Sustainable Solutions',
    },
    {
        id: 4,
        text: 'Professional technical and customer support',
        image: '/assets/icons and vector/8.png',
        alt: 'Customer Support',
    },
];
</script>

<template>
    <section
        class="flex flex-col items-center justify-center px-4 py-16 md:py-24"
    >
        <div class="flex w-full max-w-7xl flex-col items-center">
            <h1
                class="mb-8 w-full text-center text-2xl leading-tight font-bold text-gray-900 dark:text-white md:text-5xl lg:text-6xl"
            >
                <span class="text-[#009933]"
                    >Reliable Industrial Solutions</span
                >
                for the Philippines
            </h1>

            <div
                class="relative mb-12 w-full overflow-hidden rounded-2xl shadow-2xl"
            >
                <img
                    src="/assets/landing page pic.png"
                    alt="Industrial solutions in the Philippines"
                    class="block h-120 w-full object-cover md:h-140"
                />

                <div
                    class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center md:p-12"
                >
                    <p
                        class="mb-6 max-w-6xl text-lg leading-relaxed text-gray-100 md:text-xl lg:text-2xl"
                    >
                        Everlasting Star Sales Corporation (ESSC) is a 100%
                        Filipino-owned company providing high-performance
                        industrial chemicals, environmental solutions,
                        agricultural enhancement products, and infrastructure
                        technologies for government and private sector
                        institutions nationwide.
                    </p>

                    <p
                        class="mb-8 max-w-6xl text-lg leading-relaxed text-gray-100 md:text-xl lg:text-2xl"
                    >
                        With a commitment to quality, reliability, and
                        innovation, ESSC supports industries by delivering
                        solutions that improve efficiency, protect
                        infrastructure, and promote sustainable operations.
                    </p>

                    <h2
                        class="mb-10 max-w-6xl text-xl leading-snug font-bold text-white md:text-2xl lg:text-3xl"
                    >
                        Trusted by Government Agencies, Industrial Facilities,
                        and Agricultural Enterprises Across the Philippines.
                    </h2>

                    <div class="flex flex-wrap justify-center gap-4">
                        <Link
                            href="#about"
                            class="rounded-lg bg-white px-8 py-3 text-lg font-semibold text-[#009933] transition hover:bg-gray-100"
                        >
                            Learn More
                        </Link>

                        <Link
                            href="#contact"
                            class="rounded-lg border-2 border-white bg-transparent px-8 py-3 text-lg font-semibold text-white transition hover:bg-white/20"
                        >
                            Contact Us
                        </Link>
                    </div>
                </div>
            </div>

            <div
                class="flex w-full flex-wrap items-center justify-center gap-6 md:gap-8"
            >
                <img
                    src="/assets/icons and vector/1.png"
                    alt="Partner 1"
                    class="h-15 w-auto object-contain md:h-22"
                />
                <img
                    src="/assets/icons and vector/2.png"
                    alt="Partner 2"
                    class="h-15 w-auto object-contain md:h-22"
                />
                <img
                    src="/assets/icons and vector/3.png"
                    alt="Partner 3"
                    class="h-15 w-auto object-contain md:h-22"
                />
                <img
                    src="/assets/icons and vector/4.png"
                    alt="Partner 4"
                    class="h-15 w-auto object-contain md:h-22"
                />
                <img
                    src="/assets/icons and vector/5.png"
                    alt="Partner 5"
                    class="h-15 w-auto object-contain md:h-22"
                />
            </div>
        </div>
    </section>

    <section class="flex flex-col items-center justify-center px-4 py-8">
        <div class="w-full max-w-7xl">
            <div
                class="grid grid-cols-1 items-center gap-12 md:grid-cols-2 lg:gap-16"
            >
                <div class="flex flex-col gap-4">
                    <h2
                        class="text-3xl leading-tight font-bold text-[#009933] md:text-4xl lg:text-5xl"
                    >
                        About ESSC
                    </h2>
                    <p class="text-lg leading-relaxed text-gray-700 md:text-xl dark:text-gray-300">
                        Established in 2015, Everlasting Star Sales Corporation
                        has grown into a trusted supplier of industrial
                        maintenance chemicals, environmental sanitation
                        solutions, and agricultural enhancement technologies.
                    </p>
                    <p class="text-lg leading-relaxed text-gray-700 md:text-xl dark:text-gray-300">
                        The company serves both public and private sector
                        clients and actively supports government procurement
                        programs by maintaining compliance with national
                        procurement standards.
                    </p>
                </div>

                <div class="flex w-full items-start gap-4 md:gap-6">
                    <img
                        src="/assets/6.png"
                        alt="ESSC Facility"
                        width="600"
                        height="337"
                        class="aspect-video w-2/3 rounded-2xl border border-gray-100 object-cover shadow-lg"
                    />
                    <img
                        src="/assets/7.png"
                        alt="ESSC Operations"
                        width="300"
                        height="300"
                        class="aspect-square w-1/3 rounded-2xl border border-gray-100 object-cover shadow-lg"
                    />
                </div>
            </div>
        </div>
    </section>

    <section class="flex flex-col items-center justify-center px-4 pb-16">
        <div class="flex w-full max-w-7xl flex-col">
            <h2
                class="mx-2 rounded-2xl bg-[#006633] px-8 py-6 text-left text-xl leading-snug font-bold text-white shadow-xl md:px-12 md:text-2xl"
            >
                ESSC is committed to delivering:
            </h2>

            <div
                class="mt-16 grid w-full grid-cols-1 gap-8 pt-8 md:grid-cols-2 md:gap-12 lg:grid-cols-4"
            >
                <div
                    v-for="item in commitments"
                    :key="item.id"
                    class="relative mt-8 flex flex-col items-center rounded-2xl border border-gray-100 bg-white px-6 pt-10 pb-2 text-center shadow-[0_0_18px_rgba(0,0,0,0.05)] shadow-[#7ca78a] md:mt-0"
                >
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 transform"
                    >
                        <img
                            :src="item.image"
                            :alt="item.alt"
                            class="h-24 w-24 object-contain p-3 md:h-28 md:w-28"
                        />
                    </div>

                    <p
                        class="mt-2 text-lg leading-relaxed font-medium text-gray-800"
                    >
                        {{ item.text }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section
        class="flex flex-col items-center justify-center px-4 py-8 md:px-16 md:py-12 lg:px-20"
    >
        <div class="relative w-full max-w-7xl">
            <h2
                class="mb-12 text-center text-3xl font-bold text-[#009933] md:text-left md:text-4xl"
            >
                Our Core Solutions
            </h2>

            <div class="owl-carousel home-solutions-carousel owl-theme relative">
                <div
                    v-for="solution in solutions"
                    :key="solution.id"
                    class="flex h-full min-h-105 flex-col overflow-hidden rounded-xl border border-gray-100 bg-white shadow-lg"
                >
                    <img
                        :src="solution.image"
                        :alt="solution.title"
                        class="block h-56 w-full object-cover"
                    />

                    <div class="flex grow flex-col p-6 md:p-8">
                        <h3 class="mb-4 text-xl font-bold text-[#009933]">
                            {{ solution.title }}
                        </h3>

                        <p
                            class="mb-8 line-clamp-1 text-base leading-relaxed text-gray-600"
                            :title="solution.description"
                        >
                            {{ solution.description }}
                        </p>

                        <a
                            href="#"
                            class="mt-auto block w-full rounded-lg bg-[#006633] py-3 text-center font-semibold text-white transition hover:bg-green-800"
                        >
                            Read more
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<style>
.owl-theme .owl-nav {
    margin: 0 !important;
}

.owl-theme .owl-nav [class*='owl-'] {
    position: absolute !important;
    top: 50%;
    transform: translateY(-50%);
    background: transparent !important;
    padding: 0 !important;
    margin: 0 !important;
    z-index: 10;
}

.owl-theme .owl-nav .owl-prev {
    left: -4rem;
}
.owl-theme .owl-nav .owl-next {
    right: -4rem;
}

@media (max-width: 1024px) {
    .owl-theme .owl-nav .owl-prev {
        left: -2rem;
    }
    .owl-theme .owl-nav .owl-next {
        right: -2rem;
    }
}

@media (max-width: 768px) {
    .owl-theme .owl-nav .owl-prev {
        left: 0.5rem;
    }
    .owl-theme .owl-nav .owl-next {
        right: 0.5rem;
    }
}
</style>
