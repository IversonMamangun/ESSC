<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login, register } from '@/routes';
import TopBar from '@/components/sections/TopBar.vue';
import Navbar from '@/components/sections/Navbar.vue';

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
</template>
