<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';

defineProps<{
    status?: string;
    canResetPassword?: boolean;
    canRegister?: boolean;
}>();

// Toggle state: 'email' or 'otp'
const loginMethod = ref('email'); 

// Form 1: Traditional Email
const emailForm = useForm({
    email: '',
    password: '',
    remember: false,
});

// Form 2: Phone OTP
const otpForm = useForm({
    phone: '',
});

const submitEmail = () => {
    emailForm.post('/login', {
        onFinish: () => emailForm.reset('password'),
    });
};

const submitOtp = () => {
    // We will build this route on the backend next!
    otpForm.post('/login/otp'); 
};
</script>

<template>
    <AuthBase
        title="Welcome back"
        description="Choose how you want to log in to your account."
    >
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div class="flex bg-gray-100 p-1 rounded-lg mb-6">
            <button 
                @click="loginMethod = 'email'"
                :class="['flex-1 py-2 text-sm font-bold rounded-md transition-all', loginMethod === 'email' ? 'bg-white shadow text-[#009933]' : 'text-gray-500 hover:text-gray-700']"
            >
                Email & Password
            </button>
            <button 
                @click="loginMethod = 'otp'"
                :class="['flex-1 py-2 text-sm font-bold rounded-md transition-all', loginMethod === 'otp' ? 'bg-white shadow text-[#009933]' : 'text-gray-500 hover:text-gray-700']"
            >
                Phone Number (OTP)
            </button>
        </div>

        <form v-if="loginMethod === 'email'" @submit.prevent="submitEmail" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Email Address</Label>
                    <Input
                        id="email"
                        type="email"
                        v-model="emailForm.email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="juan@example.com"
                    />
                    <InputError :message="emailForm.errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Password</Label>
                        <TextLink
                            v-if="canResetPassword"
                            href="/forgot-password"
                            class="text-sm text-[#009933] hover:text-green-700 font-medium"
                        >
                            Forgot password?
                        </TextLink>
                    </div>
                    <PasswordInput
                        id="password"
                        v-model="emailForm.password"
                        required
                        autocomplete="current-password"
                        placeholder="Password"
                    />
                    <InputError :message="emailForm.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label for="remember" class="flex items-center space-x-3 cursor-pointer">
                        <Checkbox 
                            id="remember" 
                            v-model:checked="emailForm.remember" 
                            class="text-[#009933] focus:ring-[#009933]" 
                        />
                        <span>Remember me</span>
                    </Label>
                </div>

                <Button
                    type="submit"
                    class="mt-4 w-full bg-[#009933] hover:bg-green-700 text-white transition-colors"
                    :disabled="emailForm.processing"
                >
                    <Spinner v-if="emailForm.processing" class="mr-2" />
                    Log in with Email
                </Button>
            </div>
        </form>

        <form v-if="loginMethod === 'otp'" @submit.prevent="submitOtp" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="phone">Phone Number</Label>
                    <Input
                        id="phone"
                        type="tel"
                        v-model="otpForm.phone"
                        required
                        autofocus
                        autocomplete="tel"
                        placeholder="09123456789"
                        class="text-lg py-6"
                    />
                    <InputError :message="otpForm.errors.phone" />
                </div>

                <Button
                    type="submit"
                    class="mt-4 w-full bg-[#009933] hover:bg-green-700 text-white transition-colors py-6 text-lg font-bold"
                    :disabled="otpForm.processing"
                >
                    <Spinner v-if="otpForm.processing" class="mr-2" />
                    Send OTP Code
                </Button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-muted-foreground" v-if="canRegister">
            Don't have an account?
            <TextLink href="/register" class="text-[#009933] hover:text-green-700 hover:underline font-bold">
                Sign up
            </TextLink>
        </div>
    </AuthBase>
</template>