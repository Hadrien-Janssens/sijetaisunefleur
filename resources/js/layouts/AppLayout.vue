<script setup lang="ts">
import { useToast } from '@/components/ui/toast';
import Toaster from '@/components/ui/toast/Toaster.vue';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { defineProps, nextTick, watch, withDefaults } from 'vue';

const { toast } = useToast();
const page = usePage();

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
watch(
    () => page.props.flash?.success,
    (message) => {
        if (message) {
            message = message + '  ✅ ';
            nextTick(() => {
                toast({
                    description: message,
                });
            });
        }
    },
    { deep: true, immediate: true },
);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <Toaster />
    </AppLayout>
</template>
