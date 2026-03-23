<script setup lang="ts">
// Define your data here so it's easy to update later!
const commitments = [
  {
    id: 1,
    text: "High-quality industrial chemical products",
    image: "/assets/icons and vector/8.png",
    alt: "Industrial Chemicals"
  },
  {
    id: 2,
    text: "Reliable nationwide supply capability",
    image: "/assets/icons and vector/8.png", 
    alt: "Nationwide Supply"
  },
  {
    id: 3,
    text: "Sustainable and environmentally responsible solutions",
    image: "/assets/icons and vector/8.png",
    alt: "Sustainable Solutions"
  },
  {
    id: 4,
    text: "Professional technical and customer support",
    image: "/assets/icons and vector/8.png",
    alt: "Customer Support"
  }
];
</script>

<template>
  <section class="flex flex-col items-center justify-center px-4 py-16 md:py-24">
    <div class="flex w-full max-w-7xl flex-col items-center">
      <h1 class="mb-8 w-full text-center text-2xl leading-tight font-bold text-gray-900 md:text-5xl lg:text-6xl">
        <span class="text-[#009933]">Reliable Industrial Solutions</span> for the Philippines
      </h1>

      <div class="relative mb-12 w-full overflow-hidden rounded-2xl shadow-2xl">
        <img
          src="/assets/landing page pic.png"
          alt="Industrial solutions in the Philippines"
          class="block h-120 w-full object-cover md:h-140"
        />

        <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center md:p-12 bg-black/50">
          <p class="mb-6 max-w-6xl text-lg leading-relaxed text-gray-100 md:text-xl lg:text-2xl">
            Everlasting Star Sales Corporation (ESSC) is a 100% Filipino-owned company providing high-performance industrial chemicals, environmental solutions, agricultural enhancement products, and infrastructure technologies for government and private sector institutions nationwide.
          </p>

          <p class="mb-8 max-w-6xl text-lg leading-relaxed text-gray-100 md:text-xl lg:text-2xl">
            With a commitment to quality, reliability, and innovation, ESSC supports industries by delivering solutions that improve efficiency, protect infrastructure, and promote sustainable operations.
          </p>

          <h2 class="mb-10 max-w-6xl text-xl leading-snug font-bold text-white md:text-2xl lg:text-3xl">
            Trusted by Government Agencies, Industrial Facilities, and Agricultural Enterprises Across the Philippines.
          </h2>

          <div class="flex flex-wrap justify-center gap-4">
            <router-link
              to="/learn-more"
              class="rounded-lg bg-white px-8 py-3 text-lg font-semibold text-[#009933] transition hover:bg-gray-100"
            >
              Learn More
            </router-link>

            <router-link
              to="/contact"
              class="rounded-lg border-2 border-white bg-transparent px-8 py-3 text-lg font-semibold text-white transition hover:bg-white/20"
            >
              Contact Us
            </router-link>
          </div>
        </div>
      </div>

      <div class="flex w-full flex-wrap items-center justify-center gap-6 md:gap-8">
        <img src="/assets/icons and vector/1.png" alt="Partner 1" class="h-15 w-auto object-contain md:h-22" />
        <img src="/assets/icons and vector/2.png" alt="Partner 2" class="h-15 w-auto object-contain md:h-22" />
        <img src="/assets/icons and vector/3.png" alt="Partner 3" class="h-15 w-auto object-contain md:h-22" />
        <img src="/assets/icons and vector/4.png" alt="Partner 4" class="h-15 w-auto object-contain md:h-22" />
        <img src="/assets/icons and vector/5.png" alt="Partner 5" class="h-15 w-auto object-contain md:h-22" />
      </div>
    </div>
  </section>

  <section class="flex flex-col items-center justify-center px-4 py-16">
    <div class="w-full max-w-7xl">
      <div class="grid grid-cols-1 items-center gap-12 md:grid-cols-2 lg:gap-16">
        <div class="flex flex-col gap-4">
          <h2 class="text-3xl leading-tight font-bold text-[#009933] md:text-4xl lg:text-5xl">
            About ESSC
          </h2>
          <p class="text-lg leading-relaxed text-gray-700 md:text-xl">
            Established in 2015, Everlasting Star Sales Corporation has grown into a trusted supplier of industrial maintenance chemicals, environmental sanitation solutions, and agricultural enhancement technologies.
          </p>
          <p class="text-lg leading-relaxed text-gray-700 md:text-xl">
            The company serves both public and private sector clients and actively supports government procurement programs by maintaining compliance with national procurement standards.
          </p>
        </div>

        <div class="flex w-full items-start gap-4 md:gap-6">
          <img
            src="/assets/6.png"
            alt="ESSC Facility"
            width="600"
            height="337"
            class="w-2/3 aspect-video rounded-2xl border border-gray-100 object-cover shadow-lg"
          />
          <img
            src="/assets/7.png"
            alt="ESSC Operations"
            width="300"
            height="300"
            class="w-1/3 aspect-square rounded-2xl border border-gray-100 object-cover shadow-lg"
          />
        </div>
      </div>
    </div>
  </section>

  <section class="flex flex-col items-center justify-center px-4 pb-24">
    <div class="w-full max-w-7xl flex flex-col">
      <h2 class="bg-[#006633] text-white font-bold text-xl md:text-2xl leading-snug rounded-2xl text-left py-6 px-8 md:px-12 shadow-xl mx-2">
        ESSC is committed to delivering:
      </h2>

      <div class="grid w-full grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12 mt-16 pt-8">
        
        <div 
          v-for="item in commitments" 
          :key="item.id"
          class="relative bg-white rounded-2xl shadow-[0_0_18px_rgba(0,0,0,0.05)] shadow-[#7ca78a] px-6 pb-2 pt-10 flex flex-col items-center text-center border border-gray-100 mt-8 md:mt-0"
        >
          <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <img 
              :src="item.image" 
              :alt="item.alt" 
              class="w-24 h-24 md:w-28 md:h-28 object-contain p-3"
            />
          </div>

          <p class="text-gray-800 text-lg font-medium mt-2 leading-relaxed">
            {{ item.text }}
          </p>
        </div>

      </div>
    </div>
  </section>
  
</template>