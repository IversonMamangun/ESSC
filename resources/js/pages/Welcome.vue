<script setup lang="ts">
import About from '@/components/sections/About.vue';
import Capabilities from '@/components/sections/Capabilities.vue';
import Client from '@/components/sections/Client.vue';
import Contact from '@/components/sections/Contact.vue';
import Footer from '@/components/sections/Footer.vue';
import Home from '@/components/sections/Home.vue';
import Industries from '@/components/sections/Industries.vue';
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
    
    <div class="sticky top-0 z-50">
        <Navbar />
    </div>

    <main>
        <section id="home">
            <Home />
        </section>

        <section id="about">
            <About />
        </section>

        <section id="products">
            <ProductSolution />
        </section>
        <section id="industries">
            <Industries />
        </section>

        <section id="capabilities">
            <Capabilities />
        </section>
        <section id="clients">
            <Client />
        </section>


        <section id="news">
            <NewsUpdate />
        </section>

        <section id="contact">
            <Contact />
        </section>
    </main>

    <Footer />
</template>