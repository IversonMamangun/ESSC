<script lang="ts">
</script>

<template>
  <section class="flex flex-col items-center justify-center px-4 py-16 md:py-24">
    
    <div class="w-full max-w-400 flex flex-col items-center">
      
      <h1 class="text-3xl md:text-5xl lg:text-6xl text-center font-bold text-gray-900 leading-tight w-full mb-8">
        <span class="text-[#009933]">Reliable Industrial Solutions</span> for the Philippines
      </h1>
          
      <div class="relative w-full rounded-2xl overflow-hidden shadow-2xl">
        
        <img
          src="/assets/landing page pic.png"
          alt="Industrial solutions in the Philippines"
          class="w-full h-120 md:h-140 object-cover block"
        />

        <div class="absolute inset-0 flex flex-col items-center justify-center p-6 md:p-12 text-center">
          
          <p class="text-gray-100 mb-6 text-lg md:text-xl lg:text-2xl max-w-6xl leading-relaxed">
            Everlasting Star Sales Corporation (ESSC) is a 100% Filipino-owned company providing high-performance industrial chemicals, environmental solutions, agricultural enhancement products, and infrastructure technologies for government and private sector institutions nationwide.
          </p>
          
          <p class="text-gray-100 mb-8 text-lg md:text-xl lg:text-2xl max-w-6xl leading-relaxed">
            With a commitment to quality, reliability, and innovation, ESSC supports industries by delivering solutions that improve efficiency, protect infrastructure, and promote sustainable operations.
          </p>
          
          <h2 class="text-white font-bold text-xl md:text-2xl lg:text-3xl mb-10 max-w-6xl leading-snug">
            Trusted by Government Agencies, Industrial Facilities, and Agricultural Enterprises Across the Philippines.
          </h2>

          <div class="flex flex-wrap justify-center gap-4">
            <router-link
              to="/learn-more"
              class="px-8 py-3 bg-[#009933] text-white font-medium text-lg rounded-lg hover:bg-green-700 transition"
            >
              Learn More
            </router-link>

            <router-link
              to="/contact"
              class="px-8 py-3 bg-white text-[#009933] font-medium text-lg rounded-lg hover:bg-gray-100 transition"
            >
              Contact Us
            </router-link>
          </div>

        </div>
      </div>

    </div>
  </section>
</template>