<script setup lang="ts">

const capabilities = [
    'Bulk supply and scheduled product delivery',
    'Customized product sourcing',
    'Direct distribution to facilities and project sites',
    'Technical product support and application guidance',
    'Logistics coordination and inventory management',
];

</script>

<template>
    <section
        class="flex w-full flex-col items-center gap-12 pt-10 pb-16 md:pb-24"
    >
        <div
            class="relative flex min-h-32 w-full flex-col items-center justify-center overflow-hidden py-4 md:min-h-40 md:py-6"
        >
            <div class="absolute inset-0 flex w-full justify-center">
                <img
                    src="/assets/Our Capabilities.png"
                    alt="Our Capabilities Background"
                    class="h-full w-full max-w-7xl rounded-2xl object-cover"
                />
            </div>
            <div class="absolute inset-0"></div>

            <div
                class="relative z-10 mx-auto flex w-full max-w-7xl flex-col items-center px-6 text-center"
            >
                <h2 class="mb-2 text-xl font-bold text-white md:text-3xl">
                    Our Capabilities
                </h2>
                <p
                    class="max-w-2xl text-sm leading-relaxed text-gray-100 md:text-base"
                >
                    Everlasting Star Sales Corporation has the operational
                    capability to support government and private sector
                    procurement requirements including:
                </p>
            </div>
        </div>

        <div
            class="mx-auto grid w-full max-w-7xl grid-cols-1 gap-4 px-4 md:grid-cols-2 lg:px-12"
        >
            <div
                v-for="(item, index) in capabilities"
                :key="index"
                :class="[
                    'flex min-h-16 items-center justify-center rounded-2xl bg-[#009933] px-6 py-4 text-center font-medium text-white shadow-md md:px-10',
                    index === 4 ? 'md:col-span-2 md:mx-auto md:w-1/2' : '',
                ]"
            >
                {{ item }}
            </div>
        </div>

    </section>
</template>
