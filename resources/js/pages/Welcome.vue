<script setup lang="ts">
import About from '@/components/sections/About.vue';
import Home from '@/components/sections/Home.vue';
import Navbar from '@/components/sections/Navbar.vue';
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
</template>
