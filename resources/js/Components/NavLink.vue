<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'text-sm font-medium px-3 py-2 rounded-md hover:bg-gray-100 bg-gray-100'
        : 'text-sm font-medium px-3 py-2 rounded-md hover:bg-gray-100'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
