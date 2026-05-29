<script setup lang="ts">
import { onMounted } from 'vue';

const solutions = [
    {
        id: 1,
        title: 'Industrial Chemical Products',
        image: '/assets/Industrial Chemical Products.png',
    },
    {
        id: 2,
        title: 'Environmental & Sanitation Solutions',
        image: '/assets/Environmental & Sanitation.png',
    },
    {
        id: 3,
        title: 'Agricultural Enhancement Products',
        image: '/assets/Agricultural Enhancement.png',
    },
    {
        id: 4,
        title: 'Infrastructure & Energy Solutions',
        image: '/assets/Infrastructure & Energy.png',
    },
];

onMounted(() => {
    const $ = (window as any).$;

    $('.home-solutions-carousel').owlCarousel({
        loop: true,
        margin: 16, // Matches the spacing scale of your online store section
        nav: false, 
        dots: false,
        responsive: {
            0: { items: 1 },
            640: { items: 2 },
            1024: { items: 3 },
            1280: { items: 3 },
        },
    });
});
</script>

<template>
    <section class="flex w-full justify-center p-4">
        <div class="w-full max-w-7xl">
            <div class="owl-carousel home-solutions-carousel owl-theme relative">
                <div
                    v-for="solution in solutions"
                    :key="solution.id"
                >
                    <img
                        :src="solution.image"
                        :alt="solution.title"
                        class="block w-full h-40 md:h-52 object-cover rounded-3xl border border-zinc-200 shadow-sm dark:border-zinc-800"
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<style>
.owl-theme .owl-nav {
    display: none;
}
</style>