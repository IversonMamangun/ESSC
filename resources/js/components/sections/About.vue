<script lang="ts">
</script>

<template>
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
              <p class="text-gray-600 leading-relaxed">
                To deliver reliable, high-performance, and environmentally responsible chemical solutions that support operational efficiency, preventive maintenance, and safety across multiple industries.
              </p>
            </div>

            <div class="flex flex-col gap-4 p-6 bg-white rounded-2xl items-center shadow-lg shadow-[#83aa8e]/30 border border-gray-50">
              <img src="/assets/icons and vector/Vision.png" alt="Vision Icon" class="w-12 h-12 object-contain">
              <h4 class="text-xl font-bold text-[#009933]">Vision</h4>
              <p class="text-gray-600 leading-relaxed">
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
</template>