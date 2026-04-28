<script setup lang="ts">
import { ref, computed, nextTick } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';

// 1. Form State
const form = useForm({
    phone: '',
    otp: '',
    password: '',
    password_confirmation: '',
    verification_token: '', 
});

// 2. Navigation & UI State
const currentStep = ref(1);
const isLoading = ref(false); 
const globalError = ref(''); 

const showPassword = ref(false);
const showConfirmPassword = ref(false);

// 3. OTP Timer Logic
const countdown = ref(300); 
let timerId: ReturnType<typeof setInterval>;

const startTimer = () => {
    countdown.value = 300;
    clearInterval(timerId);
    timerId = setInterval(() => {
        if (countdown.value > 0) {
            countdown.value--;
        } else {
            clearInterval(timerId);
        }
    }, 1000);
};

const formattedTime = computed(() => {
    const m = String(Math.floor(countdown.value / 60)).padStart(2, '0');
    const s = String(countdown.value % 60).padStart(2, '0');
    return `${m}:${s}`;
});

// 4. 6-Box OTP Logic
const otpDigits = ref(['', '', '', '', '', '']);
const otpRefs = ref<HTMLInputElement[]>([]);

const handleOtpInput = (event: Event, index: number) => {
    const input = event.target as HTMLInputElement;
    const value = input.value.replace(/[^0-9]/g, '');
    otpDigits.value[index] = value;
    
    if (value && index < 5) {
        nextTick(() => {
            otpRefs.value[index + 1]?.focus();
        });
    }
    
    form.otp = otpDigits.value.join('');
    form.clearErrors('otp'); 
};

const handleOtpDelete = (event: KeyboardEvent, index: number) => {
    if (!otpDigits.value[index] && index > 0) {
        nextTick(() => {
            otpRefs.value[index - 1]?.focus();
        });
    }
};

// 5. Actions
const getHeaders = () => {
    const match = document.cookie.match(new RegExp('(^| )XSRF-TOKEN=([^;]+)'));
    const token = match ? decodeURIComponent(match[2]) : '';
    return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-XSRF-TOKEN': token
    };
};

const registerNow = async () => {
    form.clearErrors();
    globalError.value = '';
    isLoading.value = true;

    try {
        const response = await fetch('/register/initiate', {
            method: 'POST',
            headers: getHeaders(),
            body: JSON.stringify({ phone: form.phone })
        });
        
        const data = await response.json();

        if (!response.ok) {
            if (data.errors && data.errors.phone) {
                form.setError('phone', data.errors.phone[0]);
            } else {
                globalError.value = data.message || 'Failed to send OTP.';
            }
            return;
        }
        
        currentStep.value = 2;
        startTimer();
        nextTick(() => otpRefs.value[0]?.focus());
    } catch {
        globalError.value = 'An unexpected error occurred.';
    } finally {
        isLoading.value = false;
    }
};

const verifyOtp = async () => {
    form.clearErrors();
    isLoading.value = true;
    try {
        const response = await fetch('/register/verify', {
            method: 'POST',
            headers: getHeaders(),
            body: JSON.stringify({ phone: form.phone, otp: form.otp })
        });
        const data = await response.json();
        if (!response.ok) {
            form.setError('otp', data.message || 'Invalid OTP.');
            return;
        }

        form.verification_token = data.verification_token; 
        currentStep.value = 3;
    } catch {
        form.setError('otp', 'Verification failed.');
    } finally {
        isLoading.value = false;
    }
};

const resendOtp = async () => {
    globalError.value = '';
    isLoading.value = true;
    try {
        const response = await fetch('/register/resend', {
            method: 'POST',
            headers: getHeaders(),
            body: JSON.stringify({ phone: form.phone })
        });
        const data = await response.json();
        if (!response.ok) {
            globalError.value = data.message || 'Failed to resend OTP.';
            return;
        }
        startTimer(); 
        otpDigits.value = ['', '', '', '', '', ''];
        form.otp = '';
        nextTick(() => otpRefs.value[0]?.focus());
    } catch {
        globalError.value = 'Failed to resend OTP.';
    } finally {
        isLoading.value = false;
    }
};

const createAccount = async () => {
    form.clearErrors();
    isLoading.value = true;

    try {
        const response = await fetch('/register/complete', {
            method: 'POST',
            headers: getHeaders(),
            body: JSON.stringify({
                phone: form.phone,
                password: form.password,
                password_confirmation: form.password_confirmation,
                verification_token: form.verification_token
            })
        });

        if (!response.ok) {
            const data = await response.json();
            globalError.value = data.message || 'Failed to create account.';
            return;
        }

        currentStep.value = 4;
    } catch {
        globalError.value = 'An error occurred.';
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Head title="Create an Account" />

    <div class="min-h-screen relative flex items-center justify-center px-4 py-10 bg-gray-50 overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-[35vh] bg-[#009933] rounded-b-[40%] shadow-lg z-0 scale-x-110"></div>
        
        <div class="relative z-10 w-full max-w-4xl bg-white shadow-2xl rounded-2xl overflow-hidden flex flex-col md:flex-row min-h-[600px]">
            
            <div class="hidden md:block md:w-1/2 bg-gray-100 relative">
                <img src="/assets/register_img.jpg" alt="Banner" class="absolute inset-0 w-full h-full object-cover">
            </div>

            <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col">
                
               <div v-if="currentStep < 4" class="text-center shrink-0">
                    <h2 class="text-3xl font-extrabold text-[#009933] mb-2 border border-[#009933] px-10 rounded-2xl py-3 inline-block">
                        Create an Account
                    </h2>
                    
                    <div class="flex space-x-2 my-6">
                        <div v-for="step in 3" :key="step"
                            class="h-1.5 flex-1 rounded-full transition-all duration-500"
                            :class="currentStep >= step ? 'bg-[#009933]' : 'bg-gray-200'">
                        </div>
                    </div>

                    <div v-if="globalError" class="mb-4 text-red-600 text-sm font-medium bg-red-50 p-3 rounded-lg border-l-4 border-red-500">
                        {{ globalError }}
                    </div>
                </div>

                <div class="flex-grow flex flex-col">
                    
                    <div v-if="currentStep === 1" class="flex-grow flex flex-col animate-in fade-in duration-500">
                        
                        <div class="flex-grow flex flex-col justify-center">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Mobile Number</label>
                                
                                <div class="flex items-center w-full h-12 rounded-xl border border-[#009933] bg-white overflow-hidden shadow-sm">
                                    <div class="px-4 h-full flex items-center justify-center bg-gray-50 border-r border-[#009933] text-[#009933] font-bold">
                                        +63
                                    </div>
                                    <input 
                                        v-model="form.phone" 
                                        type="tel" 
                                        maxlength="10" 
                                        placeholder="9123456789" 
                                        @input="form.phone = form.phone.replace(/[^0-9]/g, '')"
                                        class="w-full h-full px-3 outline-none text-gray-800 bg-transparent"
                                    >
                                </div>
                                <InputError :message="form.errors.phone" class="mt-1" />
                            </div>
                        </div>

                        <div class="mt-auto pt-8 shrink-0 space-y-4">
                            <button @click="registerNow" :disabled="isLoading" class="w-full h-12 bg-[#009933] hover:bg-green-700 text-white font-bold rounded-xl transition-all shadow-md active:scale-95">
                                <span v-if="isLoading">Processing...</span>
                                <span v-else>Next</span>
                            </button>
                            <div class="text-center">
                                <Link href="/login" class="text-sm font-bold text-[#009933] hover:underline">Log in</Link>
                            </div>
                        </div>
                    </div>

                    <div v-if="currentStep === 2" class="flex-grow flex flex-col animate-in fade-in duration-500">
                        
                        <div class="flex-grow flex flex-col justify-center space-y-6">
                            <p class="text-sm text-gray-500 text-center">Enter verification code sent to <br><span class="font-bold text-gray-800">+63 {{ form.phone }}</span></p>
                            
                            <div class="flex justify-between gap-2">
                                <input 
                                    v-for="(digit, index) in otpDigits" :key="index"
                                    :ref="el => { if(el) otpRefs[index] = el as HTMLInputElement }"
                                    v-model="otpDigits[index]"
                                    @input="handleOtpInput($event, index)"
                                    @keydown.delete="handleOtpDelete($event, index)"
                                    type="text" maxlength="1"
                                    class="w-10 h-12 sm:w-12 sm:h-14 text-center text-2xl font-bold text-[#009933] border-2 border-gray-100 rounded-xl focus:border-[#009933] outline-none"
                                >
                            </div>

                            <div class="text-center text-xs">
                                <span v-if="countdown > 0" class="text-gray-400">Resend in {{ formattedTime }}</span>
                                <button v-else @click="resendOtp" class="text-[#009933] font-bold hover:underline">Resend Code</button>
                            </div>
                        </div>

                        <div class="mt-auto pt-8 shrink-0">
                            <button @click="verifyOtp" :disabled="form.otp.length < 6 || isLoading" class="w-full h-12 bg-[#009933] hover:bg-green-700 text-white font-bold rounded-xl shadow-md transition-all">
                                <span v-if="isLoading">Verifying...</span>
                                <span v-else>Next</span>
                            </button>
                        </div>
                    </div>

                    <div v-if="currentStep === 3" class="flex-grow flex flex-col animate-in fade-in duration-500">
                        
                        <div class="flex-grow flex flex-col justify-center space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Set Password</label>
                                <input :type="showPassword ? 'text' : 'password'" v-model="form.password" class="w-full h-12 px-4 border-2 border-gray-100 rounded-xl focus:border-[#009933] outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Retype Password</label>
                                <input :type="showPassword ? 'text' : 'password'" v-model="form.password_confirmation" class="w-full h-12 px-4 border-2 border-gray-100 rounded-xl focus:border-[#009933] outline-none">
                            </div>
                        </div>

                        <div class="mt-auto pt-8 shrink-0">
                            <button @click="createAccount" :disabled="isLoading" class="w-full h-12 bg-[#009933] hover:bg-green-700 text-white font-bold rounded-xl shadow-md transition-all">
                                <span v-if="isLoading">Creating Account...</span>
                                <span v-else>Complete Registration</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="currentStep === 4" class="h-full flex flex-col items-center justify-center animate-in zoom-in duration-500">
                    <div class="flex-grow flex flex-col items-center justify-center w-full">
                        <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mb-6 border-2 border-green-100">
                            <svg class="w-12 h-12 text-[#009933]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-black text-gray-800 mb-2">Success!</h2>
                        <p class="text-gray-500 text-center">Your account is ready.</p>
                    </div>
                    
                    <div class="mt-auto w-full pt-8 shrink-0">
                        <Link href="/login" class="w-full h-12 flex items-center justify-center bg-[#009933] hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition-all">
                            Log in
                        </Link>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>