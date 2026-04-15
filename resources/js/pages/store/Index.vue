<script setup lang="ts">
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Store" />
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
        <Navbar />
    </div>
    
    <main class="min-h-screen p-8 text-center">
        <h1 class="text-2xl font-bold">Welcome to the Online Store</h1>
    </main>
    <section>
        
    </section>
</template>