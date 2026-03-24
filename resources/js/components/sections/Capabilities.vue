<script setup lang="ts">
import { ref, onMounted } from 'vue';

// Extracted capabilities
const capabilities = [
  'Bulk supply and scheduled product delivery',
  'Customized product sourcing',
  'Direct distribution to facilities and project sites',
  'Technical product support and application guidance',
  'Logistics coordination and inventory management'
];

// Extracted logos for the carousel
const logos = [
  '/assets/logos/logo1.png',
  '/assets/logos/logo2.png',
  '/assets/logos/logo3.png',
  '/assets/logos/logo4.png',
  '/assets/logos/logo5.png',
  '/assets/logos/logo6.png'
];

// Reference to the carousel DOM element
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
      responsive: {
        0: { items: 2 },     // 2 items on mobile
        600: { items: 4 },   // 4 items on tablets
        1000: { items: 6 }   // 6 items on desktop
      }
    });
  }
});
</script>

<template>
  <section class="flex flex-col items-center w-full gap-12 pb-16 md:pb-24">
    
    <div class="relative flex w-full max-w-7xl mx-auto flex-col items-center justify-center py-8 md:py-12 overflow-hidden md:rounded-2xl md:mt-6 shadow-sm min-h-[200px] md:min-h-[250px]">
      <img 
        src="/assets/Our Capabilities.png" 
        alt="Our Capabilities Background" 
        class="absolute inset-0 h-full w-full object-cover" 
      />
      <div class="absolute inset-0 bg-black/40"></div>

      <div class="relative z-10 flex w-full max-w-4xl flex-col items-center text-center px-6">
        <h2 class="mb-2 text-2xl font-bold text-white md:text-4xl">
          Our Capabilities
        </h2>
        <p class="text-sm text-gray-100 md:text-lg leading-relaxed max-w-2xl">
          Everlasting Star Sales Corporation has the operational capability to support government and private sector procurement requirements including:
        </p>
      </div>
    </div>

    <div class="mx-auto grid w-full max-w-7xl grid-cols-1 gap-4 px-4 md:grid-cols-2 lg:px-12">
      <div
        v-for="(item, index) in capabilities"
        :key="index"
        :class="[
          'bg-[#009933] px-6 md:px-10 py-4 text-center text-white rounded-2xl shadow-md font-medium flex items-center justify-center min-h-[4rem]',
          index === 4 ? 'md:col-span-2 md:mx-auto md:w-1/2' : '' 
        ]"
      >
        {{ item }}
      </div>
    </div>

    <div class="mx-auto flex w-full max-w-7xl flex-col gap-8 px-4 lg:px-12 mt-4">
      
      <div class="flex flex-col gap-2">
        <h2 class="text-[#009933] text-xl font-bold md:text-2xl">
          Commitment to Quality
        </h2>
        <p class="text-gray-700 leading-relaxed text-base md:text-lg">
          ESSC ensures that all products meet industry safety standards and regulatory requirements. The company promotes environmentally responsible chemical solutions that help organizations maintain their facilities while supporting sustainability initiatives.
        </p>
      </div>

      <div class="flex flex-col gap-2">
        <h2 class="text-[#009933] text-xl font-bold md:text-2xl">
          Partial list of valued Customers
        </h2>
        <p class="text-gray-700 leading-relaxed text-base md:text-lg">
          ESSC proudly serves various industrial companies, agricultural organizations, and institutions including:
        </p>
      </div>

      <div class="w-full py-4 px-2">
        <div ref="carouselRef" class="owl-carousel owl-theme">
          <div 
            class="item flex justify-center" 
            v-for="(logo, index) in logos" 
            :key="index"
          >
            <img 
              :src="logo" 
              alt="Client Logo" 
              class="h-24 w-24 md:h-32 md:w-32 rounded-full object-contain bg-white shadow-md border border-gray-100 p-3 mx-auto hover:scale-105 transition-transform duration-300"
            >
          </div>
        </div>
      </div>

    </div>
  </section>
</template>