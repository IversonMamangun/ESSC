<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase
        title="Create an Account"
        description="Sign up to start shopping."
    >
        <Head title="Register" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                
                <div class="grid gap-2">
                    <Label for="name">Full Name</Label>
                    <Input
                        id="name"
                        type="text"
                        v-model="form.name"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        placeholder="Juan Dela Cruz"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email Address</Label>
                    <Input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        placeholder="juan@example.com"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="phone">Phone Number</Label>
                    <Input
                        id="phone"
                        type="text"
                        v-model="form.phone"
                        required
                        :tabindex="3"
                        autocomplete="tel"
                        placeholder="09123456789"
                    />
                    <InputError :message="form.errors.phone" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <PasswordInput
                        id="password"
                        v-model="form.password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        placeholder="Create a password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm Password</Label>
                    <PasswordInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        required
                        :tabindex="5"
                        autocomplete="new-password"
                        placeholder="Confirm your password"
                    />
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full bg-[#009933] hover:bg-green-700 text-white transition-colors"
                    :tabindex="6"
                    :disabled="form.processing"
                >
                    <Spinner v-if="form.processing" class="mr-2" />
                    Sign Up
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink
                    href="/login"
                    class="underline underline-offset-4 text-[#009933] hover:text-green-700 font-bold"
                    :tabindex="7"
                >
                    Log in
                </TextLink>
            </div>
        </form>
    </AuthBase>
</template>