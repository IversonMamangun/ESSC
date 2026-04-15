<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
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
        margin: 10,
        nav: false, 
        dots: false,
        responsive: {
            0: { items: 1 },
            768: { items: 2 },
            1024: { items: 3 },
        },
    });
});
</script>

<template>
    <section
        class="flex flex-col items-center justify-center"
    >
        <div class="relative w-full max-w-7xl">
            <div class="owl-carousel home-solutions-carousel owl-theme relative">
                <div
                    v-for="solution in solutions"
                    :key="solution.id"                >
                    <img
                        :src="solution.image"
                        :alt="solution.title"
                        class="block h-56 w-full object-cover rounded-xl"
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