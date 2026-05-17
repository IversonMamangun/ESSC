<script setup lang="ts">
import { ref } from 'vue';
import { Form, Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineProps<{
    status?: string;
    canResetPassword?: boolean;
    canRegister?: boolean;
}>();
</script>

<template>
    <AuthBase title="Welcome back">
        <Head title="Log in" />

        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="login">Email or Phone Number</Label>
                    <Input
                        id="login"
                        type="text"
                        name="login"
                        required
                        autofocus
                        placeholder="juan@example.com or 09123456789"
                    />

                    <InputError :message="errors.login" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Password</Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="text-sm font-medium text-[#009933] hover:text-green-700"
                        >
                            Forgot password?
                        </TextLink>
                    </div>

                    <PasswordInput
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label
                        for="remember"
                        class="flex cursor-pointer items-center space-x-3"
                    >
                        <Checkbox
                            id="remember"
                            name="remember"
                            class="text-[#009933] focus:ring-[#009933]"
                        />

                        <span>Remember me</span>
                    </Label>
                </div>

                <Button
                    type="submit"
                    class="mt-4 w-full bg-[#009933] text-white transition-colors hover:bg-green-700"
                    :disabled="processing"
                >
                    <Spinner v-if="processing" class="mr-2" />

                    Log in
                </Button>
            </div>
        </Form>

        <div
            class="mt-6 text-center text-sm text-muted-foreground"
            v-if="canRegister"
        >
            Don't have an account?
            <TextLink
                :href="register()"
                class="font-bold text-[#009933] hover:text-green-700 hover:underline"
            >
                Sign up
            </TextLink>
        </div>
    </AuthBase>
</template>
