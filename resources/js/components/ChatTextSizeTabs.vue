<script setup lang="ts">
import { useUserPreference } from '@/composables/usePreferences';

const { currentTextSize, updateUserPreference } = useUserPreference();

const textSizes = [
    { value: 'text-xs', label: 'Extra Small' },
    { value: 'text-sm', label: 'Small' },
    { value: 'text-base', label: 'Base' },
    { value: 'text-lg', label: 'Large' },
    { value: 'text-xl', label: 'Extra Large' },
    { value: 'text-2xl', label: '2x Large' },
    { value: 'text-3xl', label: '3x Large' },
    { value: 'text-4xl', label: '4x Large' },
    { value: 'text-5xl', label: '5x Large' },
    { value: 'text-6xl', label: '6x Large' },
    { value: 'text-7xl', label: '7x Large' },
    { value: 'text-8xl', label: '8x Large' },
    { value: 'text-9xl', label: '9x Large' },
] as const;

</script>

<template>
    <div class="flex flex-wrap gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800">
        <button v-for="{ value, label } in textSizes" :key="value" @click="updateUserPreference(value)" :class="[
            'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
            currentTextSize === value
                ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
        ]">
            <span class="ml-1.5" :class="value">{{ label }}</span>
        </button>
    </div>
</template>
