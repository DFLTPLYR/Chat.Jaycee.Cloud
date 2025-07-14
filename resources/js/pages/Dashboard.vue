<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { Send } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex flex-col items-center justify-center">
                    <PlaceholderPattern />
                    <h1>Current Users</h1>
                    <p class="sm:text-6xl">0</p>
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                    <h1>Current Users</h1>
                    <p class="sm:text-6xl">0</p>
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                    <h1>Current Users</h1>
                    <p class="sm:text-6xl">0</p>
                </div>
            </div>
            <div
                class="relative h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border grid grid-rows-10 p-2">
                <PlaceholderPattern />
                <section class="row-span-9 h-[calc(100vh-48vh)] overflow-y-auto">
                    <h1 v-for="(e, index) in Array(100)" :key="index"> test {{ index }}</h1>
                </section>
                <div class="row-span-1 p-2 gap-2 rounded-2xl bg-blue-300/5 flex flex-row items-center justify-center">
                    <Input placeholder="Send Message here" />
                    <button class="p-2 rounded-2xl bg-white/5 hover:bg-white/50 transition-all duration-300">
                        <Send />
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
