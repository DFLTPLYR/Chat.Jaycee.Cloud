<script setup lang="ts">
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type User } from '@/types';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { computed } from 'vue';
import { getInitials } from '@/composables/useInitials';
import { Camera } from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const page = usePage();
const user = page.props.auth.user as User;
const auth = computed(() => page.props.auth);
const imageInput = ref();
const imagePreview = ref<string>('');
const handleClick = () => {
    imageInput.value?.click();
};

const onFileSelected = (event: Event) => {
    const MaxFileSize = 5 * 1024 * 1024;
    const file = (event.target as HTMLInputElement).files?.[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        alert('Please select a valid image file.');
        return;
    }

    if (file.size > MaxFileSize) {
        alert('File is too large. Please select an image under 5MB.');
        return;
    }

    const reader = new FileReader();
    reader.onload = () => {
        if (typeof reader.result === 'string') {
            imagePreview.value = reader.result;
        }
    };
    reader.readAsDataURL(file);
};


const form = useForm({
    name: user.name,
    email: user.email,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};

const removePreview = () => {
    imagePreview.value = ''
}

const updateAvatar = () => {
    const image = imageInput.value.files?.[0];

    const form = new FormData();
    form.append('avatar', image);

    router.post(route('profile.avatar.update'),
        form,
        {
            forceFormData: true,
            onSuccess: () => {
                console.log('Avatar uploaded successfully');
            },
            onError: (errors) => {
                console.error('Upload failed:', errors);
            },
        })
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Avatar" description="Update your account's Avatar" />
                <div class="relative inline-block">
                    <Avatar class="size-40 overflow-hidden rounded-full" v-if="imagePreview">
                        <AvatarImage v-if="imagePreview" :src="imagePreview" :alt="auth.user.name" />
                        <AvatarFallback
                            class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white">
                            {{ getInitials(auth.user?.name) }}
                        </AvatarFallback>
                    </Avatar>
                    <Avatar class="size-40 overflow-hidden rounded-full" v-else>
                        <AvatarImage v-if="auth.user.profile_photo_url" :src="auth.user.profile_photo_url"
                            :alt="auth.user.name" />
                        <AvatarFallback
                            class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-neutral-700 dark:text-white">
                            {{ getInitials(auth.user?.name) }}
                        </AvatarFallback>
                    </Avatar>
                    <div class="absolute bottom-2 right-2 z-50 bg-white/50 hover:bg-white/90 transition-all duration-300 p-1 rounded-full shadow"
                        @click="handleClick">
                        <input ref="imageInput" type="file" accept="image/*" class="hidden" @change="onFileSelected" />
                        <Camera class="w-4 h-4 text-black" />
                    </div>
                </div>
                <Transition name="options">
                    <div v-if="imagePreview" class="mt-2 flex gap-2">
                        <Button variant="default" @click="updateAvatar">
                            Confirm
                        </Button>
                        <Button variant="destructive" @click="removePreview">
                            Remove
                        </Button>
                    </div>
                </Transition>
            </div>

            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name"
                            placeholder="Full name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                            autocomplete="username" placeholder="Email address" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link :href="route('verification.send')" method="post" as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500">
                            Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save</Button>

                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>

<style scoped>
.options-enter-from {
    opacity: 0;
    transform: translateY(10px);
}

.options-enter-active {
    transition: all 0.3s ease;
}

.options-enter-to {
    opacity: 1;
    transform: translateY(0);
}

.options-leave-from {
    opacity: 1;
    transform: translateY(0);
}

.options-leave-active {
    transition: all 0.2s ease;
}

.options-leave-to {
    opacity: 0;
    transform: translateY(10px);
}
</style>
