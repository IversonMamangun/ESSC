<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DeleteUser from '@/components/DeleteUser.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import type { BreadcrumbItem } from '@/types';

interface User {
    name: string;
    email: string;
    phone?: string;
    address?: string;
    city?: string;
    province?: string;
    zip?: string;
    email_verified_at?: string | null;
}

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: edit(),
    },
];

const page = usePage();

const user = computed(() => page.props.auth.user as User);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile settings" />

        <h1 class="sr-only">Profile settings</h1>

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <Heading
                    variant="small"
                    title="Profile information"
                    description="Update your name, phone, complete address, and email"
                />

                <Form
                    v-bind="ProfileController.update.form()"
                    class="space-y-6"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" class="mt-1 block w-full" name="name" :default-value="user.name" required autocomplete="name" placeholder="Full name" />
                        <InputError class="mt-2" :message="errors?.name" /> 
                    </div>

                    <div class="grid gap-2">
                        <Label for="phone">Phone Number</Label>
                        <Input id="phone" type="tel" class="mt-1 block w-full" name="phone" :default-value="user.phone" autocomplete="tel" placeholder="09123456789" />
                        <InputError class="mt-2" :message="errors?.phone" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="address">Street Address / Bldg / Barangay</Label>
                        <Input id="address" type="text" class="mt-1 block w-full" name="address" :default-value="user.address" placeholder="House No., Street Name, Barangay" />
                        <InputError class="mt-2" :message="errors?.address" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="grid gap-2">
                            <Label for="city">City</Label>
                            <Input id="city" type="text" class="mt-1 block w-full" name="city" :default-value="user.city" />
                            <InputError class="mt-2" :message="errors?.city" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="province">Province</Label>
                            <Input id="province" type="text" class="mt-1 block w-full" name="province" :default-value="user.province" />
                            <InputError class="mt-2" :message="errors?.province" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="zip">Zip Code</Label>
                            <Input id="zip" type="text" class="mt-1 block w-full" name="zip" :default-value="user.zip" />
                            <InputError class="mt-2" :message="errors?.zip" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input id="email" type="email" class="mt-1 block w-full" name="email" :default-value="user.email" required autocomplete="username" placeholder="Email address" />
                        <InputError class="mt-2" :message="errors?.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link :href="send()" as="button" class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500">
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="processing" data-test="update-profile-button">
                            Save
                        </Button>

                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="recentlySuccessful" class="text-sm text-neutral-600">
                                Saved.
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>