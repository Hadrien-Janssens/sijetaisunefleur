<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Apparence',
        href: '/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Appearence settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="🎨 Réglage du thème" description="Modifie l'apparance de ton App" />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
