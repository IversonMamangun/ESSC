<script setup lang="ts">
</script>

<template>
    <section class="mx-auto w-full max-w-7xl px-6 py-12 lg:py-16">
        <h2 class="mb-10 text-center text-3xl font-bold text-[#009933] md:text-4xl">
            Contact Us
        </h2>

        <div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:gap-16">
            
            <div class="flex flex-col">
                <form class="flex flex-col gap-4"> 
                    <input 
                        type="text" 
                        placeholder="Your Name" 
                        class="w-full rounded-md border dark:placeholder-gray-300 border-[#009933] px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#009933] focus:ring-offset-1"
                    />
                    
                    <input 
                        type="email" 
                        placeholder="Email Address" 
                        class="w-full rounded-md border dark:placeholder-gray-300 border-[#009933] px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#009933] focus:ring-offset-1"
                    />
                    
                    <input 
                        type="text" 
                        placeholder="Subject" 
                        class="w-full rounded-md border dark:placeholder-gray-300 border-[#009933] px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#009933] focus:ring-offset-1"
                    />
                    
                    <textarea 
                        rows="5" 
                        placeholder="Message" 
                        class="w-full resize-none rounded-md dark:placeholder-gray-300 border border-[#009933] px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#009933] focus:ring-offset-1"
                    ></textarea>
                    
                    <button 
                        type="submit" 
                        class="mt-2 w-full rounded-md bg-[#009933] px-6 py-3 font-semibold text-white transition-colors hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-[#009933] focus:ring-offset-2 cursor-pointer"
                    >
                        Send Message
                    </button>
                </form>
            </div>

            <div class="flex flex-col justify-center gap-8 md:pl-6">
                
                <div>
                    <h3 class="text-2xl font-semibold text-[#009933] lg:text-3xl">
                        Get in touch
                    </h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                        Have questions or need more information about our products and services? Reach out to us and our team will get back to you shortly.
                    </p>
                </div>

                <div class="mt-2 flex flex-col gap-6">
                    
                    <div class="flex items-start gap-4">
                        <div class="flex shrink-0 items-center justify-center pt-1">
                            <img src="/assets/Contact Us/Call us.png" alt="Phone" class="h-12 w-12 object-contain" />
                        </div>
                        <div class="flex flex-col">
                            <h3 class="text-lg font-semibold text-[#009933]">Call Us</h3>
                            <p class="text-gray-800 dark:text-gray-300">(632) 4310-5357</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex shrink-0 items-center justify-center pt-1">
                            <img src="/assets/Contact Us/email.png" alt="Email" class="h-12 w-12 object-contain" />
                        </div>
                        <div class="flex flex-col">
                            <h3 class="text-lg font-semibold text-[#009933]">Email Us</h3>
                            <p class="text-gray-800 dark:text-gray-300">Sample.essc@gmail.com</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex shrink-0 items-center justify-center pt-1">
                            <img src="/assets/Contact Us/address.png" alt="Location" class="h-12 w-12 object-contain" />
                        </div>
                        <div class="flex flex-col">
                            <h3 class="text-lg font-semibold text-[#009933]">Address</h3>
                            <p class="text-gray-800 dark:text-gray-300">#85 Doña Justina Street corner Dalton StreetFilinvest II, Batasan HillsQuezon City, Philippines</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
</template>