<script setup lang="ts">
import { ref } from 'vue';

interface NewsItem {
    id: number;
    title: string;
    excerpt: string;
    image: string;
    link: string;
}

const recentNews = ref<NewsItem[]>([
    {
        id: 1,
        title: 'ESSC Supports Sustainable Agriculture Initiatives',
        excerpt:
            'ESSC continues to promote sustainable farming by supplying bio-organic fertilizers, microbial soil activators, and plant growth enhancers to agricultural partners. These solutions help improve soil health, increase crop productivity, and support environmentally responsible farming practices.',
        image: '/assets/News & Updates/news1.png', 
        link: '#',
    },
    {
        id: 2,
        title: 'Partnership Finalized for Nationwide Distribution',
        excerpt:
            'A strategic alliance has been formed to improve logistics and ensure faster delivery times across all regions.',
        image: '/assets/News & Updates/news2.png',
        link: '#',
    },
    {
        id: 3,
        title: 'Annual Safety and Compliance Training Completed',
        excerpt:
            'Ensuring our team remains up-to-date with the latest industrial safety protocols and compliance standards.',
        image: '/assets/News & Updates/news3.png',
        link: '#',
    },
]);
</script>

<template>
    <section>
        <div
            class="relative flex min-h-32 w-full flex-col items-center justify-center overflow-hidden py-4 md:min-h-40 md:py-6"
        >
            <div class="absolute inset-0 flex w-full justify-center">
                <img
                    src="/assets/News & Updates/News & Updates.png"
                    alt="Our Capabilities Background"
                    class="h-full w-full object-cover"
                />
            </div>
            <div class="absolute inset-0 bg-black/40"></div>

            <div
                class="relative z-10 mx-auto flex w-full max-w-7xl flex-col items-center px-6 text-center"
            >
                <h2 class="mb-2 text-xl font-bold text-white md:text-3xl">
                    News & Updates
                </h2>
                <p
                    class="max-w-2xl text-sm leading-relaxed text-gray-100 md:text-base"
                >
                    Stay informed with the latest developments, announcements,
                    and milestones from Everlasting Star Sales Corporation
                    (ESSC). From product innovations to project highlights, we
                    continue to grow, serve, and innovate across industries.
                </p>
            </div>
        </div>

        <div class="mx-auto w-full max-w-6xl px-6 py-10">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-12">
                <div class="flex flex-col">
                    <div class="mb-4">
                        <h2
                            class="mb-2 text-xl leading-tight font-semibold text-[#0035AD] lg:text-2xl"
                        >
                            ESSC Expands Supply Capability for Government and
                            Industrial Projects
                        </h2>
                        <p
                            class="text-sm leading-relaxed text-gray-700 md:text-base"
                        >
                            Everlasting Star Sales Corporation (ESSC) continues
                            to strengthen its nationwide supply capability to
                            support large-scale government and industrial
                            requirements. With enhanced logistics coordination,
                            improved inventory systems, and strong supplier
                            partnerships, ESSC ensures reliable and timely
                            delivery of industrial chemicals, environmental
                            solutions, and agricultural products. This expansion
                            reinforces the company’s commitment to supporting
                            infrastructure development, operational efficiency,
                            and sustainable practices across the Philippines.
                        </p>
                    </div>

                    <div
                        class="relative mt-auto h-60 w-full overflow-hidden rounded-md shadow-md sm:h-72"
                    >
                        <img
                            src="/assets/News & Updates/news.png"
                            alt="Featured News Update"
                            class="h-full w-full object-cover"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-green-700 from-0% to-transparent to-50%"
                        ></div>

                        <div
                            class="absolute inset-x-0 bottom-0 z-10 flex items-center justify-between p-4 sm:p-5"
                        >
                            <h3 class="text-sm font-medium text-white">
                                March 2026
                            </h3>
                            <a
                                href="#"
                                class="rounded-md bg-[#0035AD] px-5 py-2 text-sm font-semibold text-white transition-colors hover:bg-blue-800 focus:ring-2 focus:ring-[#0035AD] focus:ring-offset-2 focus:outline-none"
                            >
                                Read more
                            </a>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-5 lg:pt-2">
                    <div
                        v-for="item in recentNews"
                        :key="item.id"
                        class="flex items-center gap-4"
                    >
                        <div class="flex flex-1 flex-col pr-2">
                            <h3
                                class="mb-1 text-base leading-tight font-semibold text-[#0035AD] sm:text-lg"
                            >
                                {{ item.title }}
                            </h3>
                            <p
                                class="line-clamp-2 text-xs text-gray-600 sm:text-sm"
                            >
                                {{ item.excerpt }}
                            </p>
                        </div>

                        <div
                            class="relative h-28 w-36 shrink-0 overflow-hidden rounded-md shadow-sm sm:h-32 sm:w-48"
                        >
                            <img
                                :src="item.image"
                                :alt="item.title"
                                class="h-full w-full bg-gray-200 object-cover"
                            />
                            <div class="absolute inset-0"></div>

                            <div class="absolute right-2 bottom-2 z-10">
                                <a
                                    :href="item.link"
                                    class="rounded-md bg-[#0035AD] px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-800 focus:ring-2 focus:ring-[#0035AD] focus:outline-none"
                                >
                                    Read more
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-center">
                <button
                    class="rounded-lg w-50 max-w-sm border-white hover:border-[#0035AD] border bg-[#0035AD] px-8 py-3 font-semibold text-white transition-colors hover:bg-white hover:text-[#0035AD] focus:ring-2 focus:ring-[#0035AD] focus:ring-offset-2 focus:outline-none"
                >
                    View All
                </button>
            </div>
        </div>

        <div class="mt-2 flex w-full bg-[#0035AD] px-2 py-5"></div>
    </section>
</template>
