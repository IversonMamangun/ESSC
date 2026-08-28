<script setup lang="ts">
import { Head, useForm, useHttp } from '@inertiajs/vue3';
import { UserIcon, CameraIcon, CheckCircle2Icon } from 'lucide-vue-next';
import { computed, watch, ref } from 'vue';
import UserAccountSidebar from '@/components/accounts/UserAccountSidebar.vue';
import Footer from '@/components/sections/Footer.vue';
import Navbar from '@/components/sections/Navbar.vue';
import TopBar from '@/components/sections/TopBar.vue';
import InputError from '@/components/InputError.vue';
import shop from '@/routes/shop';
import type { User, SendOtpResponse, VerifyOtpResponse } from '@/types';
import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
} from '@/components/ui/dialog';
import {
  InputOTP,
  InputOTPGroup,
  InputOTPSlot,
} from '@/components/ui/input-otp';

const props = defineProps<{
  user: User;
}>();

const normalizePhone = (phone: string) =>
  phone.startsWith('0') ? phone.slice(1) : phone;

const originalEmail = props.user.email ?? '';
const originalPhone = normalizePhone(props.user.phone ?? '');

// Local input only ever shows/accepts the raw 10-digit number (no "0" prefix,
// no "+63"); form.phone stores exactly that same raw value.
const localPhone = computed({
  get: () => form.phone,
  set: (value: string) => {
    form.phone = value.replace(/[^0-9]/g, '');
  },
});
const internationalPhone = computed(() => `63${form.phone}`);

const form = useForm({
  _method: 'PATCH',
  name: props.user.name ?? '',
  email: originalEmail,
  phone: originalPhone,
  avatar: null as File | null,
  verification_token: '',
});

form.transform((data) => ({
  ...data,
  phone: internationalPhone.value,
}));

const avatarPreview = computed(() =>
  form.avatar
    ? URL.createObjectURL(form.avatar)
    : props.user.avatar
      ? `/storage/${props.user.avatar}`
      : null,
);

const needsVerification = computed(
  () => form.email !== originalEmail || form.phone !== originalPhone,
);
const purpose = computed(() =>
  form.phone !== originalPhone ? 'change_phone' : 'change_email',
);

watch([() => form.email, () => form.phone], () => {
  form.verification_token = '';
});

const handleAvatarChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files?.length) form.avatar = target.files[0];
};

// Pre-flight validation, run through useHttp (raw XHR) rather than
// form.post(), since this endpoint returns plain JSON, not an Inertia
// page response — running it through a page-visit would misbehave.
const validateHttp = useHttp({ name: '', email: '', phone: '' });

const submitProfile = () => {
  if (needsVerification.value && !form.verification_token) {
    validateHttp.name = form.name;
    validateHttp.email = form.email;
    validateHttp.phone = internationalPhone.value;

    validateHttp
      .post(shop.account.profile.validate.url())
      .then(() => openOtpDialog())
      .catch(() => {
        Object.keys(form.errors).forEach((key) => {
          delete (form.errors as Record<string, string>)[key];
        });
        Object.assign(form.errors, validateHttp.errors);
      });

    return;
  }

  form.post(shop.account.profile.update.url(), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      form.verification_token = '';
    },
  });
};

// --- OTP send/verify ---
const sendHttp = useHttp<{ purpose: string }, SendOtpResponse>({ purpose: '' });
const verifyHttp = useHttp<{ purpose: string; otp: string }, VerifyOtpResponse>(
  {
    purpose: '',
    otp: '',
  },
);

const otpOpen = ref(false);
const maskedTarget = ref('');
const sendError = ref('');
const verifyError = ref('');
const sendMessage = ref('');

const countdown = ref(0);
let timerId: ReturnType<typeof setInterval>;

const startTimer = (seconds: number) => {
  countdown.value = Math.max(0, seconds);
  clearInterval(timerId);
  timerId = setInterval(() => {
    if (countdown.value > 0) countdown.value--;
    else clearInterval(timerId);
  }, 1000);
};

const formattedTime = computed(() => {
  const m = String(Math.floor(countdown.value / 60)).padStart(2, '0');
  const s = String(countdown.value % 60).padStart(2, '0');
  return `${m}:${s}`;
});

const openOtpDialog = () => {
  verifyHttp.otp = '';
  verifyError.value = '';
  otpOpen.value = true;
  sendOtp();
};

function extractErrorMessage(e: any, fallback: string): string {
  const status = e?.response?.status;
  const safeStatuses = [401, 403, 404, 422, 429];

  if (!safeStatuses.includes(status)) {
    return fallback;
  }

  const raw = e?.response?.data;
  let parsed = raw;

  if (typeof raw === 'string') {
    try {
      parsed = JSON.parse(raw);
    } catch {
      return fallback;
    }
  }

  return parsed?.message ?? fallback;
}

const sendOtp = () => {
  sendError.value = '';
  sendHttp.purpose = purpose.value;
  verifyHttp.clearErrors();

  sendHttp
    .post(shop.account.profile.otp.send.url())
    .then((data) => {
      maskedTarget.value = data.target;
      sendMessage.value = data.message;
      startTimer(data.expires_in ?? 300);
    })
    .catch((e: any) => {
      if (Object.keys(sendHttp.errors).length === 0) {
        sendError.value = extractErrorMessage(e, 'Failed to send OTP.');
      }
    });
};

const verifyOtp = () => {
  verifyError.value = '';
  verifyHttp.purpose = purpose.value;
  verifyHttp.clearErrors();

  verifyHttp
    .post(shop.account.profile.otp.verify.url())
    .then((data) => {
      form.verification_token = data.verification_token;
      otpOpen.value = false;
      submitProfile();
    })
    .catch((e: any) => {
      if (Object.keys(verifyHttp.errors).length === 0) {
        verifyError.value = extractErrorMessage(e, 'Verification failed.');
      }
    });
};
</script>

<template>
  <Head title="My Profile" />

  <div class="flex min-h-screen flex-col transition-colors duration-300">
    <TopBar />
    <div class="sticky top-0 z-50 mt-8">
      <Navbar />
    </div>

    <main
      class="mx-auto w-full max-w-7xl flex-grow px-4 py-8 sm:px-6 md:py-12 lg:px-8"
    >
      <div class="flex flex-col gap-8 lg:flex-row">
        <UserAccountSidebar :name="user.name" :avatar="user.avatar" />

        <div class="min-w-0 flex-1">
          <div
            class="rounded-3xl border border-zinc-200 bg-zinc-50 p-6 shadow-sm transition-colors md:p-10 dark:border-zinc-800 dark:bg-zinc-900"
          >
            <div
              class="mb-8 border-b border-zinc-200 pb-6 dark:border-zinc-800"
            >
              <h1 class="text-2xl font-black text-zinc-900 dark:text-white">
                My Profile
              </h1>
              <p class="mt-1 text-zinc-500 dark:text-zinc-400">
                Manage your basic information
              </p>
            </div>

            <form
              @submit.prevent="submitProfile"
              class="flex flex-col-reverse gap-12 lg:flex-row"
            >
              <div class="flex-1 space-y-6">
                <div>
                  <label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Full Name</label
                  >
                  <input
                    type="text"
                    v-model="form.name"
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-zinc-900 transition-all outline-none focus:border-[#009933] focus:ring-2 focus:ring-[#009933]/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                </div>
                <div>
                  <label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Email Address</label
                  >
                  <input
                    type="email"
                    v-model="form.email"
                    placeholder="bM8v0@example.com"
                    class="w-full rounded-xl border border-zinc-200 bg-white px-4 py-3 text-zinc-900 transition-all outline-none focus:border-[#009933] focus:ring-2 focus:ring-[#009933]/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  />
                  <p
                    v-if="form.email !== originalEmail"
                    class="mt-1 text-xs text-zinc-500"
                  >
                    You'll need to verify this via SMS OTP.
                  </p>
                  <InputError :message="form.errors.email" />
                </div>
                <div>
                  <label
                    class="mb-2 block text-sm font-bold text-zinc-700 dark:text-zinc-300"
                    >Phone Number</label
                  >
                  <div
                    class="flex h-12 w-full items-center overflow-hidden rounded-xl border border-zinc-200 bg-white px-4 py-3 text-zinc-900 transition-all outline-none focus-within:border-[#009933] focus-within:ring-2 focus-within:ring-[#009933]/20 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                  >
                    <div
                      class="flex h-full items-center justify-center border-r border-zinc-300 pe-4 font-bold text-zinc-700 dark:border-zinc-600 dark:text-zinc-300"
                    >
                      +63
                    </div>

                    <input
                      id="phone"
                      v-model="localPhone"
                      type="tel"
                      maxlength="10"
                      placeholder="9123456789"
                      class="h-full w-full bg-transparent px-3 outline-none"
                    />
                  </div>
                  <p
                    v-if="form.phone !== originalPhone"
                    class="mt-1 text-xs text-zinc-500"
                  >
                    You'll need to verify this via SMS OTP.
                  </p>
                  <InputError :message="form.errors.phone" />
                </div>

                <button
                  type="submit"
                  :disabled="form.processing || !form.isDirty"
                  class="mt-4 flex cursor-pointer items-center gap-2 rounded-xl bg-[#009933] px-8 py-3.5 font-black text-white shadow-md transition-all hover:bg-green-700 active:scale-95 disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-[#009933] disabled:active:scale-100"
                >
                  <CheckCircle2Icon class="h-5 w-5" />
                  {{
                    needsVerification && !form.verification_token
                      ? 'Verify & Save'
                      : 'Save Profile'
                  }}
                </button>
                <p
                  v-if="form.recentlySuccessful"
                  class="mt-2 text-sm font-bold text-[#009933]"
                >
                  Saved successfully!
                </p>
              </div>

              <div
                class="flex flex-col items-center border-l border-zinc-200 lg:w-1/3 lg:pl-12 dark:border-zinc-800"
              >
                <div class="group relative mb-6 cursor-pointer">
                  <div
                    class="flex h-32 w-32 items-center justify-center overflow-hidden rounded-full border-4 border-white bg-zinc-100 shadow-lg dark:border-zinc-900 dark:bg-zinc-800"
                  >
                    <img
                      v-if="avatarPreview"
                      :src="avatarPreview"
                      class="h-full w-full object-cover"
                    />
                    <UserIcon v-else class="h-16 w-16 text-zinc-400" />
                  </div>
                  <label
                    for="avatar"
                    class="absolute inset-0 flex cursor-pointer flex-col items-center justify-center rounded-full bg-black/50 text-white opacity-0 transition-opacity group-hover:opacity-100"
                  >
                    <CameraIcon class="mb-1 h-6 w-6" />
                    <span class="text-xs font-bold">Edit</span>
                    <input
                      id="avatar"
                      type="file"
                      class="hidden"
                      @change="handleAvatarChange"
                    />
                  </label>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
    <Footer />

    <Dialog v-model:open="otpOpen">
      <DialogContent class="sm:max-w-md">
        <DialogHeader>
          <DialogTitle>Verify it's you</DialogTitle>
          <DialogDescription>
            To confirm it's you, enter the 6-digit code sent to your registered
            number
            {{ maskedTarget || 'on file' }}.
          </DialogDescription>
        </DialogHeader>

        <div class="flex justify-center py-2">
          <InputOTP
            v-model="verifyHttp.otp"
            :maxlength="6"
            @complete="verifyOtp"
          >
            <InputOTPGroup>
              <InputOTPSlot :index="0" />
              <InputOTPSlot :index="1" />
              <InputOTPSlot :index="2" />
              <InputOTPSlot :index="3" />
              <InputOTPSlot :index="4" />
              <InputOTPSlot :index="5" />
            </InputOTPGroup>
          </InputOTP>
        </div>

        <p v-if="sendMessage" class="text-center text-xs text-zinc-500">
          {{ sendMessage }}
        </p>

        <InputError :message="verifyHttp.errors.otp" class="text-center" />
        <p
          v-if="sendError"
          class="text-center text-sm font-medium text-red-600"
        >
          {{ sendError }}
        </p>
        <p
          v-if="verifyError"
          class="text-center text-sm font-medium text-red-600"
        >
          {{ verifyError }}
        </p>

        <div class="flex items-center justify-between pt-2">
          <button
            type="button"
            :disabled="sendHttp.processing || countdown > 0"
            @click="sendOtp"
            class="text-sm font-bold text-[#009933] disabled:opacity-50"
          >
            <span v-if="sendHttp.processing">Sending…</span>
            <span v-else-if="countdown > 0">Resend in {{ formattedTime }}</span>
            <span v-else>Resend code</span>
          </button>
          <Button
            :disabled="verifyHttp.processing || verifyHttp.otp.length !== 6"
            @click="verifyOtp"
          >
            {{ verifyHttp.processing ? 'Verifying…' : 'Verify' }}
          </Button>
        </div>
      </DialogContent>
    </Dialog>
  </div>
</template>
