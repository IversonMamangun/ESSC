<script setup lang="ts">
import { onMounted } from 'vue';

// Management Team Data for the carousel
const managementTeam = [
  {
    id: 1,
    name: 'Reynaldo C. Sanchez',
    position: 'President',
    image: '/assets/sample.png', // Main background photo
    icon: '/assets/icons and vector/11.png' 
  },
  {
    id: 2,
    name: 'Thomas Ray S. Sanchez',
    position: 'Chairman',
    image: '/assets/sample.png',
    icon: '/assets/icons and vector/11.png'
  },
  {
    id: 3,
    name: 'Raylene S. Sanchez',
    position: 'Chief Executive Officer',
    image: '/assets/sample.png',
    icon: '/assets/icons and vector/11.png'
  },
  {
    id: 4,
    name: 'Reynaldo S. Sanchez Jr',
    position: 'Director',
    image: '/assets/sample.png',
    icon: '/assets/icons and vector/11.png'
  },
  {
    id: 5,
    name: 'Jumaila Sanchez-Silvano ',
    position: 'Business Development Manager',
    image: '/assets/sample.png',
    icon: '/assets/icons and vector/11.png'
  },
  
];

onMounted(() => {
  const $ = (window as any).$;

  // Initialize the Management Team Carousel
  $('.management-carousel').owlCarousel({
    loop: true,
    margin: 24,
    nav: true,
    dots: false,
    navText: [
      "<img src='/assets/icons and vector/9.png' alt='Previous' class='w-12 h-12 transition hover:scale-110 drop-shadow-md'>",
      "<img src='/assets/icons and vector/10.png' alt='Next' class='w-12 h-12 transition hover:scale-110 drop-shadow-md'>" 
    ],
    responsive: {
      0: { items: 1 },
      768: { items: 2 },
      1024: { items: 3 },
      1280: { items: 3 } 
    }
  });
});
</script>

<template>
  <div>
    <section class="flex flex-col items-center justify-center px-4 py-16 md:py-24">
      <div class="w-full max-w-7xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
          
          <div class="flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <h2 class="text-3xl md:text-4xl font-bold text-[#009933]">About Us</h2>
              
              <div class="inline-flex items-center gap-3 py-1.5 px-2 bg-[#009933] rounded-2xl w-fit">
                <span class="text-white text-sm font-semibold px-3 tracking-wide">MAJOR BUSINESS ACTIVITY</span>
                <a href="#" class="bg-white px-5 py-1.5 rounded-xl text-[#009933] font-semibold hover:bg-gray-100 transition">
                  View
                </a>
              </div> 
            </div>

            <div class="flex flex-col gap-4">
              <h3 class="text-xl md:text-2xl font-bold text-[#009933]">Company Overview</h3>
              <p class="text-gray-700 text-lg leading-relaxed">
                Everlasting Star Sales Corporation (ESSC) is a Filipino-owned enterprise established on December 16, 2015. The company specializes in the manufacturing, trading, distribution, and supply of industrial chemicals, environmental technologies, and agricultural enhancement products.
              </p>
              <p class="text-gray-700 text-lg leading-relaxed">
                ESSC provides high-performance solutions that support infrastructure reliability, industrial productivity, and sustainable agriculture throughout the Philippines.
              </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 ">
              
              <div class="flex flex-col gap-4 p-6 bg-white rounded-2xl items-center shadow-lg shadow-[#83aa8e]/30 border border-gray-50 ">
                <img src="/assets/icons and vector/Mission.png" alt="Mission Icon" class="w-12 h-12 object-contain">
                <h4 class="text-xl font-bold text-[#009933]">Mission</h4>
                <p class="text-gray-600 leading-relaxed text-center sm:text-left">
                  To deliver reliable, high-performance, and environmentally responsible chemical solutions that support operational efficiency, preventive maintenance, and safety across multiple industries.
                </p>
              </div>

              <div class="flex flex-col gap-4 p-6 bg-white rounded-2xl items-center shadow-lg shadow-[#83aa8e]/30 border border-gray-50">
                <img src="/assets/icons and vector/Vision.png" alt="Vision Icon" class="w-12 h-12 object-contain">
                <h4 class="text-xl font-bold text-[#009933]">Vision</h4>
                <p class="text-gray-600 leading-relaxed text-center sm:text-left">
                  To become a leading national supplier of industrial, environmental, and agricultural solutions trusted by government institutions, businesses, and communities throughout the Philippines.
                </p>
              </div>

            </div>
          </div>

          <div class="w-full h-full flex items-center justify-center">
            <img 
              src="/assets/About Us.png" 
              alt="About ESSC" 
              class="w-full h-auto object-cover rounded-3xl shadow-xl"
            >
          </div>
        </div>
      </div>
    </section>
      
    <section class="fade-in-up relative z-30 w-full py-12 md:py-16">
      
      <div class="flex items-center justify-center max-w-7xl mx-auto px-4 mb-12">
        <div class="h-1 flex-1 bg-[#009933] rounded-r-xl sm:h-2 md:h-2 lg:h-3"></div>
        <h2 class="whitespace-nowrap px-4 text-center text-xl font-semibold text-[#009933] sm:px-6 sm:py-3 sm:text-2xl md:text-4xl">
          Management Team
        </h2>
        <div class="h-1 flex-1 bg-[#009933] rounded-l-xl sm:h-2 md:h-2 lg:h-3"></div>
      </div>

      <div class="w-full max-w-7xl mx-auto px-4 md:px-16 lg:px-24">
        <div class="management-carousel owl-carousel owl-theme relative">
          
          <div 
            v-for="member in managementTeam" 
            :key="member.id"
            class="relative rounded-2xl shadow-lg border border-gray-100 overflow-hidden h-[400px] group"
          >
            <img 
              :src="member.image" 
              :alt="member.name" 
              class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
            />

            <div class="absolute bottom-4 left-4 right-4 bg-white/95 backdrop-blur-sm rounded-xl px-4 pb-4 pt-6 shadow-xl text-center">
              
              <div class="absolute -top-6 left-4 w-12 h-14 rounded-full p-1">
                <img :src="member.icon" alt="Role Icon" class="w-full h-full object-contain" />
              </div>

              <h3 class="text-xl font-bold text-[#009933] mt-2">{{ member.name }}</h3>
              <p class="text-sm text-gray-600 font-medium mt-1">{{ member.position }}</p>
              
            </div>
          </div>

        </div>
      </div>

    </section>
  </div>
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

.owl-theme .owl-nav .owl-prev { left: -4rem; }
.owl-theme .owl-nav .owl-next { right: -4rem; }

@media (max-width: 1024px) {
  .owl-theme .owl-nav .owl-prev { left: -2rem; }
  .owl-theme .owl-nav .owl-next { right: -2rem; }
}

@media (max-width: 768px) {
  .owl-theme .owl-nav .owl-prev { left: 0.5rem; }
  .owl-theme .owl-nav .owl-next { right: 0.5rem; }
}
</style>