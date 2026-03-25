<script setup lang="ts">
import About from '@/components/sections/About.vue';
import Capabilities from '@/components/sections/Capabilities.vue';
import Contact from '@/components/sections/Contact.vue';
import Home from '@/components/sections/Home.vue';
import Navbar from '@/components/sections/Navbar.vue';
import NewsUpdate from '@/components/sections/NewsUpdate.vue';
import ProductSolution from '@/components/sections/ProductSolution.vue';
import TopBar from '@/components/sections/TopBar.vue';


withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <TopBar />
    <Navbar />
    <Home />
    <About />
    <ProductSolution />
    <Capabilities />
    <NewsUpdate />
    <Contact />
</template>
