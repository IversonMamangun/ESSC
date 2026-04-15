<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
</script>

<template>
    <AuthBase
        title="Welcome!"
        description="Enter your mobile number to get started."
    >
        <Head title="Register" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                
                <div class="grid gap-2">
                    <Label for="phone">Mobile Number</Label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50 border border-r-0 border-gray-300 rounded-l-md font-bold">
                            +63
                        </span>
                        <Input
                            id="phone"
                            type="tel"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="tel"
                            name="phone"
                            placeholder="912 345 6789"
                            class="rounded-l-none"
                        />
                    </div>
                    <InputError :message="errors.phone" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <PasswordInput
                        id="password"
                        required
                        :tabindex="2"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Create a password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2 mt-2">
                    <Label>Register as:</Label>
                    <div class="flex items-center gap-6 mt-1 border border-gray-200 rounded-md p-3 bg-gray-50/50">
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="radio" 
                                name="user_type" 
                                value="buyer"
                                checked 
                                class="text-[#009933] focus:ring-[#009933] w-4 h-4 border-gray-300"
                            >
                            <span class="ml-2 text-sm text-gray-700 font-medium">Buyer</span>
                        </label>
                        
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="radio" 
                                name="user_type" 
                                value="seller" 
                                class="text-[#009933] focus:ring-[#009933] w-4 h-4 border-gray-300"
                            >
                            <span class="ml-2 text-sm text-gray-700 font-medium">Seller</span>
                        </label>
                    </div>
                    <InputError :message="errors.user_type" />
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full bg-[#009933] hover:bg-green-700 text-white transition-colors"
                    tabindex="3"
                    :disabled="processing"
                >
                    <Spinner v-if="processing" class="mr-2" />
                    Sign Up
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Already have an account?
                <TextLink
                    :href="login()"
                    class="underline underline-offset-4 text-[#009933] hover:text-green-700 font-bold"
                    :tabindex="4"
                >
                    Log in
                </TextLink>
            </div>
        </Form>
    </AuthBase>
</template>