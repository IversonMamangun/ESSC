<script setup lang="ts">
import { useHttp, Head, router } from '@inertiajs/vue3';
import { Eye, EyeOff, LoaderCircle } from 'lucide-vue-next';
import { ref, computed, nextTick } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { store } from '@/routes/register';
import { send, verify } from '@/routes/verifications';

// Single useHttp instance carries every field across all 3 steps.
const http = useHttp({
  name: '',
  email: '',
  phone: '',
  user_type_id: '3',
  purpose: 'registration',
  otp: '',
  password: '',
  password_confirmation: '',
  verification_token: '',
});

// UI only ever shows the 10 digits after "+63"; http.phone always holds the
const localPhone = computed({
  get: () => http.phone.replace(/^63/, ''),
  set: (value: string) => {
    http.phone = `63${value.replace(/[^0-9]/g, '')}`;
  },
});

// Step / UI state
const currentStep = ref<1 | 2 | 3 | 4>(1);
const globalError = ref('');
const showPassword = ref(false);
const successRedirect = ref('/');

const hasFieldErrors = () => Object.keys(http.errors).length > 0;

// OTP countdown
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

// 6-box OTP input
const otpDigits = ref(['', '', '', '', '', '']);
const otpRefs = ref<HTMLInputElement[]>([]);

const handleOtpInput = (event: Event, index: number) => {
  const input = event.target as HTMLInputElement;
  const value = input.value.replace(/[^0-9]/g, '');
  otpDigits.value[index] = value;

  if (value && index < 5) {
    nextTick(() => otpRefs.value[index + 1]?.focus());
  }

  http.otp = otpDigits.value.join('');
};

const handleOtpDelete = (event: KeyboardEvent, index: number) => {
  if (!otpDigits.value[index] && index > 0) {
    nextTick(() => otpRefs.value[index - 1]?.focus());
  }
};

const resetOtpBoxes = () => {
  otpDigits.value = ['', '', '', '', '', ''];
  http.otp = '';
};

// useHttp posts the hook's own reactive data (http.name,
// http.phone, etc.) and auto-populates http.errors on a 422 response
const requestOtp = async () => {
  globalError.value = '';

  try {
    await http.post(send().url);
  } catch {
    globalError.value = 'Failed to send OTP.';

    return;
  }

  if (hasFieldErrors()) {
return;
}

  currentStep.value = 2;
  startTimer();
  nextTick(() => otpRefs.value[0]?.focus());
};

const verifyOtp = async () => {
  let data: { verification_token?: string } | undefined;

  try {
    data = (await http.post(verify().url)) as { verification_token?: string };
  } catch {
    globalError.value = 'Verification failed.';

    return;
  }

  if (hasFieldErrors()) {
return;
}

  http.verification_token = data?.verification_token ?? '';
  currentStep.value = 3;
};

const resendOtp = async () => {
  globalError.value = '';

  try {
    await http.post(send().url);
  } catch {
    globalError.value = 'Failed to resend OTP.';

    return;
  }

  if (hasFieldErrors()) {
return;
}

  startTimer();
  resetOtpBoxes();
  nextTick(() => otpRefs.value[0]?.focus());
};

const createAccount = async () => {
  globalError.value = '';
  let data: { redirect?: string } | undefined;

  try {
    data = (await http.post(store().url)) as { redirect?: string };
  } catch {
    globalError.value = 'Failed to create account.';

    return;
  }

  if (hasFieldErrors()) {
return;
}

  successRedirect.value = data?.redirect ?? '/';
  currentStep.value = 4;
};
</script>

<template>
  <Head title="Create an Account" />

  <div
    class="relative flex min-h-screen items-center justify-center overflow-hidden bg-gray-50 px-4 py-10"
  >
    <div
      class="absolute top-0 left-0 z-0 h-[35vh] w-full scale-x-110 rounded-b-[40%] bg-[#009933] shadow-lg"
    />

    <div
      class="relative z-10 flex min-h-150 w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl md:flex-row"
    >
      <div class="relative hidden bg-gray-100 md:block md:w-1/2">
        <img
          src="/assets/register_img.jpg"
          alt="Banner"
          class="absolute inset-0 h-full w-full object-cover"
        />
      </div>

      <div class="flex w-full flex-col p-8 sm:p-12 md:w-1/2">
        <div v-if="currentStep < 4" class="shrink-0 text-center">
          <h2
            class="mb-2 inline-block rounded-2xl border border-[#009933] px-10 py-3 text-3xl font-extrabold text-[#009933]"
          >
            Create an Account
          </h2>

          <div class="my-6 flex space-x-2">
            <div
              v-for="step in 3"
              :key="step"
              class="h-1.5 flex-1 rounded-full transition-all duration-500"
              :class="currentStep >= step ? 'bg-[#009933]' : 'bg-gray-200'"
            />
          </div>

          <div
            v-if="globalError"
            class="mb-4 rounded-lg border-l-4 border-red-500 bg-red-50 p-3 text-sm font-medium text-red-600"
          >
            {{ globalError }}
          </div>
        </div>

        <div class="flex grow flex-col">
          <!-- Step 1: account details -->
          <form
            v-if="currentStep === 1"
            class="flex grow flex-col"
            @submit.prevent="requestOtp"
          >
            <div class="flex grow flex-col justify-center gap-4">
              <div>
                <Label class="text-zinc-500">I'm registering as</Label>
                <RadioGroup
                  v-model="http.user_type_id"
                  class="mt-2 grid grid-cols-2 gap-3"
                >
                  <div>
                    <RadioGroupItem
                      id="customer"
                      value="3"
                      class="peer sr-only"
                    />
                    <Label
                      for="customer"
                      class="flex h-11 cursor-pointer items-center justify-center rounded-xl border-2 border-gray-200 font-semibold text-gray-500 peer-data-[state=checked]:border-[#009933] peer-data-[state=checked]:text-[#009933]"
                    >
                      Buyer
                    </Label>
                  </div>
                  <div>
                    <RadioGroupItem
                      id="seller"
                      value="2"
                      class="peer sr-only"
                    />
                    <Label
                      for="seller"
                      class="flex h-11 cursor-pointer items-center justify-center rounded-xl border-2 border-gray-200 font-semibold text-gray-500 peer-data-[state=checked]:border-[#009933] peer-data-[state=checked]:text-[#009933]"
                    >
                      Seller
                    </Label>
                  </div>
                </RadioGroup>
              </div>

              <div>
                <Label for="name" class="text-zinc-500">Full Name</Label>
                <Input
                  id="name"
                  v-model="http.name"
                  type="text"
                  placeholder="John Doe"
                  class="mt-1 flex h-11 w-full items-center justify-center overflow-hidden rounded-xl border border-r border-[#009933] bg-white px-4 font-medium text-gray-800 shadow-sm focus-visible:border-[#009933] focus-visible:ring-0 dark:bg-white dark:text-gray-800"
                  autocomplete="name"
                  required
                />
                <InputError :message="http.errors.name" class="mt-1" />
              </div>

              <div>
                <Label for="email" class="text-zinc-500">Email</Label>
                <Input
                  id="email"
                  v-model="http.email"
                  type="email"
                  placeholder="bM8v0@example.com"
                  class="mt-1 flex h-11 w-full items-center justify-center overflow-hidden rounded-xl border border-r border-[#009933] bg-white px-4 font-medium text-gray-800 shadow-sm focus-visible:border-[#009933] focus-visible:ring-0 dark:bg-white dark:text-gray-800"
                  autocomplete="email"
                  required
                />
                <InputError :message="http.errors.email" class="mt-1" />
              </div>

              <div>
                <Label for="phone" class="text-zinc-500">Mobile Number</Label>
                <div
                  class="mt-1 flex h-11 w-full items-center overflow-hidden rounded-xl border border-[#009933] bg-white shadow-sm"
                >
                  <div
                    class="flex h-full items-center justify-center border-r border-[#009933] bg-gray-50 px-4 font-bold text-[#009933]"
                  >
                    +63
                  </div>
                  <input
                    id="phone"
                    v-model="localPhone"
                    type="tel"
                    maxlength="10"
                    placeholder="9123456789"
                    class="h-full w-full bg-transparent px-3 text-gray-800 outline-none"
                  />
                </div>
                <InputError :message="http.errors.phone" class="mt-1" />
              </div>
            </div>

            <div class="mt-auto shrink-0 pt-8">
              <Button
                type="submit"
                :disabled="http.processing"
                class="h-11 w-full bg-[#009933] font-bold hover:bg-green-700"
              >
                <LoaderCircle
                  v-if="http.processing"
                  class="h-4 w-4 animate-spin"
                />
                <span v-else class="text-white">Next</span>
              </Button>

              <!-- <div class="relative flex items-center py-2">
                    <div class="grow border-t border-gray-200"></div>
                    <span class="shrink-0 px-4 text-xs font-bold text-gray-400 uppercase">Or connect with</span>
                    <div class="grow border-t border-gray-200"></div>
                </div>

                <a href="/auth/google/redirect" class="w-full h-12 flex items-center justify-center bg-white border-2 border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-xl transition-all shadow-sm active:scale-95">
                    <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Google
                </a> -->

              <p class="pt-4 text-center text-sm text-gray-500">
                Already have an account?
                <a
                  href="/login"
                  class="font-bold text-[#009933] hover:underline"
                  >Log in</a
                >
              </p>
            </div>
          </form>

          <!-- Step 2: OTP -->
          <form
            v-if="currentStep === 2"
            class="flex grow flex-col"
            @submit.prevent="verifyOtp"
          >
            <div class="flex grow flex-col justify-center space-y-6">
              <p class="text-center text-sm text-gray-500">
                Enter verification code sent to <br />
                <span class="font-bold text-gray-800"
                  >+63 {{ localPhone }}</span
                >
              </p>

              <div class="flex justify-between gap-2">
                <input
                  v-for="(digit, index) in otpDigits"
                  :key="index"
                  :ref="
                    (el) => {
                      if (el) otpRefs[index] = el as HTMLInputElement;
                    }
                  "
                  v-model="otpDigits[index]"
                  @input="handleOtpInput($event, index)"
                  @keydown.delete="handleOtpDelete($event, index)"
                  type="text"
                  inputmode="numeric"
                  maxlength="1"
                  class="h-12 w-10 rounded-xl border-2 border-gray-100 text-center text-2xl font-bold text-[#009933] outline-none focus:border-[#009933] sm:h-14 sm:w-12"
                />
              </div>
              <InputError :message="http.errors.otp" class="text-center" />

              <div class="text-center text-xs">
                <span v-if="countdown > 0" class="text-gray-400"
                  >Resend in {{ formattedTime }}</span
                >
                <button
                  v-else
                  type="button"
                  @click="resendOtp"
                  class="font-bold text-[#009933] hover:underline"
                >
                  Resend Code
                </button>
              </div>
            </div>

            <div class="mt-auto shrink-0 pt-8">
              <Button
                type="submit"
                :disabled="http.otp.length < 6 || http.processing"
                class="h-11 w-full bg-[#009933] font-bold hover:bg-green-700"
              >
                <LoaderCircle
                  v-if="http.processing"
                  class="h-4 w-4 animate-spin"
                />
                <span v-else>Next</span>
              </Button>
            </div>
          </form>

          <!-- Step 3: password -->
          <form
            v-if="currentStep === 3"
            class="flex grow flex-col"
            @submit.prevent="createAccount"
          >
            <div class="flex grow flex-col justify-center gap-4">
              <div>
                <Label for="password">Set Password</Label>
                <div class="relative mt-1">
                  <Input
                    :id="'password'"
                    v-model="http.password"
                    :type="showPassword ? 'text' : 'password'"
                  />
                  <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-400"
                  >
                    <EyeOff v-if="showPassword" class="h-4 w-4" />
                    <Eye v-else class="h-4 w-4" />
                  </button>
                </div>
                <InputError :message="http.errors.password" class="mt-1" />
              </div>

              <div>
                <Label for="password_confirmation">Retype Password</Label>
                <Input
                  id="password_confirmation"
                  v-model="http.password_confirmation"
                  :type="showPassword ? 'text' : 'password'"
                  class="mt-1"
                />
                <InputError
                  :message="http.errors.password_confirmation"
                  class="mt-1"
                />
              </div>
            </div>

            <div class="mt-auto shrink-0 pt-8">
              <Button
                type="submit"
                :disabled="http.processing"
                class="h-11 w-full bg-[#009933] font-bold hover:bg-green-700"
              >
                <LoaderCircle
                  v-if="http.processing"
                  class="h-4 w-4 animate-spin"
                />
                <span v-else>Complete Registration</span>
              </Button>
            </div>
          </form>
        </div>

        <!-- Step 4: success -->
        <div
          v-if="currentStep === 4"
          class="flex h-full flex-col items-center justify-center"
        >
          <div class="flex w-full grow flex-col items-center justify-center">
            <div
              class="mb-6 flex h-20 w-20 items-center justify-center rounded-full border-2 border-green-100 bg-green-50"
            >
              <svg
                class="h-12 w-12 text-[#009933]"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="3"
                  d="M5 13l4 4L19 7"
                />
              </svg>
            </div>
            <h2 class="mb-2 text-3xl font-black text-gray-800">Success!</h2>
            <p class="text-center text-gray-500">Your account is ready.</p>
          </div>

          <div class="mt-auto w-full shrink-0 pt-8">
            <Button
              class="h-11 w-full bg-[#009933] font-bold hover:bg-green-700"
              @click="router.visit(successRedirect)"
            >
              Continue
            </Button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
