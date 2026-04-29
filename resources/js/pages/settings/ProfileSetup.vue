<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    phone: '',
    address: '',
    city: '',
    province: '',
    zip: '',
});

const submit = () => {
    form.patch(route('profile.update'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-lg mt-10">
        <h2 class="text-xl font-bold mb-4">Complete Your Profile</h2>
        <p class="text-gray-600 mb-6">Almost there! We need your shipping details for the ESSC Marketplace.</p>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label>Phone Number</label>
                <input v-model="form.phone" type="text" class="w-full border rounded p-2" placeholder="09123456789">
                <div v-if="form.errors.phone" class="text-red-500 text-sm">{{ form.errors.phone }}</div>
            </div>

            <div>
                <label>Address</label>
                <input v-model="form.address" type="text" class="w-full border rounded p-2">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>City</label>
                    <input v-model="form.city" type="text" class="w-full border rounded p-2">
                </div>
                <div>
                    <label>Province</label>
                    <input v-model="form.province" type="text" class="w-full border rounded p-2">
                </div>
            </div>

            <button type="submit" :disabled="form.processing" class="bg-blue-600 text-white px-4 py-2 rounded">
                Save & Start Shopping
            </button>
        </form>
    </div>
</template>