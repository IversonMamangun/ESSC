<script setup lang="ts">
import { onMounted, ref } from 'vue';

const isModalOpen = ref(false);

const managementTeam = [
  {
    id: 1,
    name: 'Reynaldo C. Sanchez',
    position: 'President',
    image: '/assets/sample.png', 
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
    name: 'Jumaila Sanchez-Silvano',
    position: 'Business Development Manager',
    image: '/assets/sample.png',
    icon: '/assets/icons and vector/11.png'
  },
];

const valuedCustomers = [
  "Acme Tools Manufacturing Co.", "Alloy Engineering", "Arms Corporation of the Philippines", 
  "Autubus Industries Inc.", "Autometics Care", "Ansan International", 
  "Greenhouse Paradise Country Farms", "Asmico Industrial & Fabrication Works", "Frabelle Fishing Corp.", 
  "General Metal Corp.", "Integral Machineries", "Mactan Rock Industries Inc.", 
  "Metal Heat Expert Trading", "Metal Improvement Inc.", "MERALCO", 
  "Metal Industry Research Development Corporation", "New Long Industries", "NSB Engineering Design & Fabrication2", 
  "Rojemson Industrial Sales", "Onatech Incorporated.110", "Rice-up Irrigators Farmers Agricultural Cooperative", 
  "RL Graphics Arts Inc.", "Royale Fishing Corp", "P.Imes Corporation", 
  "Biorganism Corporation", "Sealand Industrial Supply", "Shooters Guns and Arms Manufacturing", 
  "Solid Parts Corporation", "Star Galaxy Industrial Fabrication Inc.", "Suburbia Automotive Ventures Inc.", 
  "Trans-Pacific Journey Fishing Corp.", "Trimekstal Industrial Supplies", "Universal Aquarius"
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
    <section class="flex flex-col items-center justify-center px-4 py-8 md:py-24">
      <div class="w-full max-w-7xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
          
          <div class="flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <h2 class="text-3xl md:text-4xl font-bold text-[#009933]">About Us</h2>
              
              <div class="inline-flex items-center gap-3 py-1.5 px-2 bg-[#009933] rounded-2xl w-fit">
                <span class="text-white text-sm font-semibold px-3 tracking-wide">MAJOR BUSINESS ACTIVITY</span>
                <button 
                  @click="isModalOpen = true" 
                  class="bg-white px-5 py-1.5 rounded-xl text-[#009933] font-semibold hover:bg-gray-100 transition focus:outline-none"
                >
                  View
                </button>
              </div> 
            </div>

            <div class="flex flex-col gap-4">
              <h3 class="text-xl md:text-2xl font-bold text-[#009933]">Company Overview</h3>
              <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed">
                Everlasting Star Sales Corporation (ESSC) is a Filipino-owned enterprise established on December 16, 2015. The company specializes in the manufacturing, trading, distribution, and supply of industrial chemicals, environmental technologies, and agricultural enhancement products.
              </p>
              <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed">
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
      
    <section class="fade-in-up relative z-30 w-full py-8 md:py-16">
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
            class="relative rounded-2xl shadow-lg border border-gray-100 overflow-hidden h-100 group"
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

    <Teleport to="body">
      <Transition name="fade">
        <div 
          v-if="isModalOpen" 
          class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 sm:p-6"
          @click.self="isModalOpen = false"
        >
          <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden">
            
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50">
              <h2 class="text-2xl font-bold text-[#009933]">Company Profile & Product Information</h2>
              <button 
                @click="isModalOpen = false" 
                class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition focus:outline-none"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div class="p-6 sm:p-8 overflow-y-auto flex flex-col gap-10 text-gray-700 leading-relaxed">
              
              <section class="bg-green-50/50 p-6 rounded-xl border border-green-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Company Name</h4>
                    <p class="font-bold text-gray-800 text-lg">Everlasting Star Sales Corporation</p>
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Date Established</h4>
                    <p class="font-bold text-gray-800 text-lg">December 16, 2015</p>
                  </div>
                  <div class="md:col-span-2">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Business Address</h4>
                    <p class="font-bold text-gray-800"># 85 Doña Justina St., cor. Dalton St., Filinvest II, Batasan Hills, Quezon City Office</p>
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Registrations</h4>
                    <p class="font-semibold text-gray-700">S.E.C. Registration: <span class="font-normal text-gray-600">CS201524535</span></p>
                    <p class="font-semibold text-gray-700">Municipal License: <span class="font-normal text-gray-600">16-000462</span></p>
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Banking Institution</h4>
                    <p class="font-semibold text-gray-700">Asia United Bank</p>
                    <p class="text-gray-600 text-sm">Account Name: Everlasting Star Sales Corporation</p>
                    <p class="text-gray-600 text-sm">Contact: Ms. Cherry | (632) 4310-5357</p>
                  </div>
                </div>
              </section>

              <section class="flex flex-col gap-4">
                <h3 class="text-2xl font-bold text-[#009933] border-b pb-2">Major Business Activity</h3>
                <p>
                  <strong>Everlasting Star Sales Corporation (ESSC)</strong> is a dynamic Filipino-owned enterprise engaged in the manufacturing, trading, wholesale distribution, and retail supply of specialized industrial, environmental, and agricultural enhancement solutions. The company delivers high-performance products and technologies designed to support industrial productivity, infrastructure reliability, environmental sustainability, and agricultural development across the Philippines.
                </p>
                <p>
                  With a commitment to innovation and quality, ESSC provides a comprehensive portfolio of advanced industrial maintenance chemicals and specialty solutions, including oil additives, high-performance industrial lubricating oils, performance lubricants, preventive maintenance chemicals, rust inhibitors, corrosion protection products, heat treatment salts, water treatment chemicals, and aerosol-based maintenance systems used in manufacturing plants, utilities, transportation fleets, construction operations, and large-scale industrial facilities. These products are engineered to enhance equipment longevity, reduce operational downtime, and improve overall system efficiency.
                </p>
                <p>
                  Complementing its industrial product lines, the company supplies environmental and sanitation technologies, including biological and microbial solutions such as cultured bacteria formulations, probiotic-based odor control systems, and eco-friendly cleaning solutions designed for wastewater management, facility sanitation, and environmental rehabilitation.
                </p>
                <p>
                  Recognizing the critical importance of food security and sustainable agriculture, ESSC also provides innovative agricultural enhancement products that support soil health, plant growth, and sustainable farming practices. These include soil conditioners, bio-organic fertilizers, microbial soil activators, plant growth enhancers, nutrient supplements, and environmentally friendly crop protection solutions designed to improve soil fertility, increase crop productivity, and support regenerative agricultural systems. These technologies are particularly valuable for modern greenhouse operations, precision agriculture, and climate-resilient farming initiatives.
                </p>
                <p>
                  In support of the country’s transition toward sustainable infrastructure, Everlasting Star Sales Corporation also participates in the renewable energy and infrastructure supply sector, providing solar-powered lighting systems including solar floodlights, solar street lighting systems, and commercial solar illumination solutions designed for municipalities, infrastructure projects, agricultural facilities, and industrial estates seeking energy-efficient and environmentally responsible lighting alternatives.
                </p>
                <p>
                  Additionally, ESSC is engaged in construction-related trading and materials supply, including the procurement and responsible trading of metal materials and recyclable scrap metals, contributing to sustainable resource management and industrial material recovery.
                </p>
              </section>

              <section class="flex flex-col gap-4">
                <h3 class="text-2xl font-bold text-[#009933] border-b pb-2">Brief History & Company Overview</h3>
                <p>
                  Everlasting Star Sales Corporation (ESSC) is a 100% Filipino-owned corporation established in 2015, engaged in the trading, manufacturing, and distribution of industrial and maintenance chemical products serving both public and private sector clients across the Philippines.
                </p>
                <p>
                  The company was founded with the mission of delivering reliable, high-performance, and environmentally responsible chemical solutions that support operational efficiency, preventive maintenance, and safety across multiple industries.
                </p>
                <p>
                  Over the years, Everlasting Star Sales Corporation has built a strong reputation for product quality, dependable supply capability, and responsive customer support, enabling government agencies and institutional clients to maintain their facilities, equipment, and infrastructure efficiently.
                </p>
                <p>
                  ESSC continuously aligns its operations with the Philippine Government Procurement Reform Act (RA 9184) and maintains full compliance with the requirements for participating in government bidding and procurement processes.
                </p>
              </section>

              <section class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                  <h3 class="text-xl font-bold text-[#009933] mb-4">Core Business Activities</h3>
                  <div class="space-y-4">
                    <div>
                      <h4 class="font-bold text-gray-900">Industrial Chemical Products</h4>
                      <ul class="list-disc pl-5 space-y-1 text-sm text-gray-600 mt-1">
                        <li>Preventive maintenance chemicals</li>
                        <li>Industrial cleaners and degreasers</li>
                        <li>Rust removers and corrosion inhibitors</li>
                        <li>Protective coatings and specialty coatings</li>
                        <li>Industrial lubricants and penetrants</li>
                        <li>Surface treatment chemicals</li>
                        <li>Agricultural related products</li>
                      </ul>
                    </div>
                    <div>
                      <h4 class="font-bold text-gray-900">Aerosol Maintenance Products</h4>
                      <ul class="list-disc pl-5 space-y-1 text-sm text-gray-600 mt-1">
                        <li>Multi-purpose lubricants</li>
                        <li>Electrical contact cleaners</li>
                        <li>Rust penetrants</li>
                        <li>Protective spray coatings</li>
                        <li>Industrial aerosol cleaners</li>
                      </ul>
                    </div>
                    <div>
                      <h4 class="font-bold text-gray-900">Facility & Equipment Solutions</h4>
                      <ul class="list-disc pl-5 space-y-1 text-sm text-gray-600 mt-1">
                        <li>Heavy-duty cleaning agents & degreasing solutions</li>
                        <li>Environmental sanitation chemicals</li>
                        <li>Maintenance and protection chemicals for machinery</li>
                      </ul>
                    </div>
                  </div>
                </div>

                <div class="space-y-8">
                  <div>
                    <h3 class="text-xl font-bold text-[#009933] mb-4">Capability and Supply Capacity</h3>
                    <ul class="list-disc pl-5 space-y-1 text-sm text-gray-600">
                      <li>Bulk supply and scheduled delivery of chemical products</li>
                      <li>Customized product sourcing based on agency requirements</li>
                      <li>Direct distribution to government offices, facilities, and project sites</li>
                      <li>Technical assistance and product application guidance</li>
                      <li>Reliable inventory management and logistics coordination</li>
                    </ul>
                  </div>
                  <div>
                    <h3 class="text-xl font-bold text-[#009933] mb-4">Sectors Served</h3>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-2 gap-y-1 list-disc pl-5 text-sm text-gray-600">
                      <li>National Government Agencies</li>
                      <li>Local Government Units (LGUs)</li>
                      <li>GOCCs</li>
                      <li>Infrastructure & Public Works</li>
                      <li>Hospitals & Healthcare</li>
                      <li>Educational Institutions</li>
                      <li>Transportation Fleets</li>
                      <li>Environmental Programs</li>
                      <li>Industrial & Manufacturing</li>
                    </ul>
                  </div>
                </div>
              </section>

              <section class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-xl border border-gray-100">
                <div>
                  <h3 class="text-lg font-bold text-[#009933] mb-2">Commitment to Quality & Safety</h3>
                  <p class="text-sm text-gray-600">
                    Committed to providing products that meet high standards of quality, reliability, and safety. The company ensures all supplied products adhere to industry regulations. ESSC promotes the use of environment-friendly chemical solutions that support environmental sustainability.
                  </p>
                </div>
                <div>
                  <h3 class="text-lg font-bold text-[#009933] mb-2">Corporate Commitment</h3>
                  <p class="text-sm text-gray-600">
                    Dedicated to becoming a trusted partner by delivering high-quality products, reliable supply services, and cost-efficient solutions. Through innovation and integrity, ESSC aims to contribute to the safe and efficient operation of infrastructure nationwide.
                  </p>
                </div>
              </section>

              <section class="flex flex-col gap-4">
                <h3 class="text-2xl font-bold text-[#009933] border-b pb-2">Partial List of Valued Customers</h3>
                <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-3 mt-2">
                  <li 
                    v-for="(customer, index) in valuedCustomers" 
                    :key="index"
                    class="flex items-start text-sm text-gray-700"
                  >
                    <span class="text-[#009933] font-bold mr-2">{{ index + 1 }}.</span>
                    {{ customer }}
                  </li>
                </ul>
              </section>

            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

  </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

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