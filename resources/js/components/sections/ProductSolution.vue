<script setup lang="ts">
import { ref, onMounted, nextTick } from 'vue';

const productCategories = [
  {
    id: 1,
    title: 'Industrial Chemical Products',
    subtitle: 'ESSC supplies a wide range of industrial maintenance chemicals including:',
    footer: 'These products help extend equipment life, reduce maintenance costs, and improve operational efficiency.',
    items: [
      { id: 1, name: 'Preventive Maintenance Chemicals', image: '/assets/Industrial Chemical Products/Preventive maintenance.png' },
      { id: 2, name: 'Industrial Cleaners & Degreasers', image: '/assets/Industrial Chemical Products/Industrial cleaners.png' },
      { id: 3, name: 'Rust Removers & Corrosion Inhibitors', image: '/assets/Industrial Chemical Products/Rust removers and.png' },
      { id: 4, name: 'Protective & Specialty Coatings', image: '/assets/Industrial Chemical Products/Protective and.png' },
      { id: 5, name: 'Industrial Lubricants & Penetrants', image: '/assets/Industrial Chemical Products/Industrial lubricants.png' },
      { id: 6, name: 'Surface Treatment Chemicals', image: '/assets/Industrial Chemical Products/Surface treatment.png' },
    ],
  },
  {
    id: 2,
    title: 'Aerosol Maintenance Products',
    subtitle: 'ESSC provides specialized aerosol-based solutions including:',
    footer: null,
    items: [
      { id: 1, name: 'Multi-purpose Lubricants', image: '/assets/Aerosol Maintenance Products/Multi-purpose.png' },
      { id: 2, name: 'Electrical Contact Cleaners', image: '/assets/Aerosol Maintenance Products/Electrical contact.png' },
      { id: 3, name: 'Rust Penetrants', image: '/assets/Aerosol Maintenance Products/Rust penetrants.png' },
      { id: 4, name: 'Protective Spray Coatings', image: '/assets/Aerosol Maintenance Products/Protective spray.png' },
      { id: 5, name: 'Industrial Aerosol Cleaners', image: '/assets/Aerosol Maintenance Products/Industrial aerosol.png' },
    ],
  },
  {
    id: 3,
    title: 'Environmental & Sanitation Solutions',
    subtitle: 'Innovative eco-friendly sanitation and cleaning technologies:',
    footer: null,
    items: [
      { id: 1, name: 'Wastewater Treatment Solutions', image: '/assets/Environmental & Sanitation Solutions/Wastewater treatment.png' },
      { id: 2, name: 'Microbial & Probiotic Systems', image: '/assets/Environmental & Sanitation Solutions/Microbial and probiotic.png' },
      { id: 3, name: 'Eco-friendly Cleaning Solutions', image: '/assets/Environmental & Sanitation Solutions/Eco-friendly cleaning.png' },
      { id: 4, name: 'Odor Control Technologies', image: '/assets/Environmental & Sanitation Solutions/Odor control.png' },
    ],
  },
  {
    id: 4,
    title: 'Agricultural Enhancement Products',
    subtitle: 'ESSC supports sustainable farming through innovative agricultural technologies including:',
    footer: null,
    items: [
      { id: 1, name: 'Soil Conditioners', image: '/assets/Agricultural Enhancement Products/Soil conditioners.png' },
      { id: 2, name: 'Bio-organic Fertilizers', image: '/assets/Agricultural Enhancement Products/Bio-organic fertilizers.png' },
      { id: 3, name: 'Microbial Soil Activators', image: '/assets/Agricultural Enhancement Products/Microbial soil activators.png' },
      { id: 4, name: 'Plant Growth Enhancers', image: '/assets/Agricultural Enhancement Products/Plant growth enhancers.png' },
      { id: 5, name: 'Nutrient Supplements', image: '/assets/Agricultural Enhancement Products/Nutrient supplements.png' },
      { id: 6, name: 'Eco-friendly Crop Protection', image: '/assets/Agricultural Enhancement Products/Environment-friendly.png' },
    ],
  },
  {
    id: 5,
    title: 'Renewable Energy Solutions',
    subtitle: 'ESSC also provides solar-powered infrastructure systems such as:',
    footer: 'These solutions support energy efficiency and sustainable development projects.',
    items: [
      { id: 1, name: 'Solar Street Lights', image: '/assets/Industrial Chemical Products/Preventive maintenance.png' },
      { id: 2, name: 'Solar Flood Lights', image: '/assets/Industrial Chemical Products/Industrial cleaners.png' },
      { id: 3, name: 'Solar Commercial Lighting Systems', image: '/assets/Industrial Chemical Products/Rust removers and.png' },
    ],
  },
];

const industries = [
  'Government Agencies',
  'Local Government Units',
  'Industrial Manufacturing',
  'Agricultural Enterprises',
  'Construction and Infrastructure',
  'Healthcare Institutions',
  'Educational Institutions',
  'Transportation and Fleet Maintenance',
  'Environmental and Sanitation Programs'
];

const showAll = ref(false);

const initCarousel = () => {
  const $ = (window as any).$;
  
  $('.industrial-carousel:not(.owl-loaded)').owlCarousel({
    loop: true, 
    autoplay: true, 
    autoplayTimeout: 3000,
    margin: 24,
    nav: true,
    dots: false,
    navText: [
      "<img src='/assets/icons and vector/9.png' alt='Previous' class='w-12 h-12 transition hover:scale-110 drop-shadow-md'>",
      "<img src='/assets/icons and vector/10.png' alt='Next' class='w-12 h-12 transition hover:scale-110 drop-shadow-md'>" 
    ],
    responsive: {
      0: { items: 1 },
      640: { items: 2 },
      1024: { items: 3 },
      1280: { items: 4 }
    }
  });
};

onMounted(() => {
  initCarousel();
});

const toggleCategories = async () => {
  showAll.value = !showAll.value; 
  
  if (showAll.value) {
    await nextTick();
    initCarousel();
  }
};
</script>

<template>
  <section class="fade-in-up relative z-30 w-full py-16">
    <div class="mx-auto mb-12 flex max-w-7xl items-center justify-center px-4">
      <h2 class="px-4 text-center text-3xl font-bold whitespace-nowrap text-[#009933] sm:px-6 sm:py-3 sm:text-2xl md:text-4xl">
        Products and Solutions Page
      </h2>
    </div>

    <template v-for="(category, index) in productCategories" :key="category.id">
      
      <div v-if="showAll || index === 0" class="mx-auto w-full max-w-7xl px-4 md:px-16 lg:px-24 mb-16">
        
        <h2 class="text-2xl font-bold text-center text-[#009933] mb-2">
          {{ category.title }}
        </h2>
        
        <p class="text-center text-gray-600 mb-6">
          {{ category.subtitle }}
        </p>

        <div class="relative w-full">
          <div class="industrial-carousel owl-carousel owl-theme">
            
            <div
              v-for="item in category.items"
              :key="item.id"
              class=" bg-white p-2 rounded-xl shadow-md text-center hover:shadow-lg transition flex flex-col items-center justify-center w-full"
            >
              <img
                :src="item.image"
                :alt="item.name"
                class="h-1/2 w-auto object-contain mb-3 hover:scale-105 transition duration-300"
              />
              <h3 class="text-sm md:text-base font-semibold text-gray-800 px-2 line-clamp-2 leading-snug">
                {{ item.name }}
              </h3>
            </div>

          </div>
        </div>

        <p v-if="category.footer" class="text-center text-gray-700 mt-6">
          {{ category.footer }}
        </p>
        
      </div>
    </template>

    <div class="flex justify-center mt-4 w-full px-4">
      <button 
        @click="toggleCategories"
        class="bg-[#009933] text-white font-semibold text-lg px-10 py-3 rounded-xl shadow hover:bg-green-700 transition-colors"
      >
        {{ showAll ? 'View Less' : 'See All Categories' }}
      </button>
    </div>
  </section>
 <section class="relative flex w-full items-center justify-center py-16 md:py-24 min-h-[300px]">
    
    <img 
      src="/assets/Industries We Serve.png" 
      alt="Industries We Serve" 
      class="absolute inset-0 h-full w-full object-cover lg:object-fill"
    />
    
    <div class="absolute inset-0"></div>

    <div class="relative z-10 w-full max-w-7xl px-4 md:px-16 lg:px-24 flex flex-col items-start text-left">
      
      <h2 class="mb-4 text-3xl font-bold text-white md:text-4xl lg:text-5xl">
        Industries We Serve
      </h2>
      
      <p class="mb-6 text-lg text-gray-200 md:text-xl">
        ESSC supplies solutions to a wide range of industries including:
      </p>

      <ul class="list-inside list-disc space-y-2 text-base font-medium text-white md:text-lg">
        <li>National Government Agencies</li>
        <li>Local Government Units (LGUs)</li>
        <li>Government-Owned and Controlled Corporations (GOCCs)</li>
        <li>Infrastructure and Public Works Projects</li>
        <li>Hospitals and Healthcare Facilities</li>
        <li>Educational Institutions</li>
        <li>Industrial and Manufacturing Facilities</li>
        <li>Agricultural Enterprises</li>
        <li>Transportation and Fleet Maintenance Operations</li>
      </ul>

    </div>
  </section>
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

.owl-theme .owl-nav .owl-prev { left: -3.5rem; }
.owl-theme .owl-nav .owl-next { right: -3.5rem; }

@media (max-width: 1024px) {
  .owl-theme .owl-nav .owl-prev { left: -1.5rem; }
  .owl-theme .owl-nav .owl-next { right: -1.5rem; }
}

@media (max-width: 768px) {
  .owl-theme .owl-nav .owl-prev { left: 0.5rem; }
  .owl-theme .owl-nav .owl-next { right: 0.5rem; }
}
</style>