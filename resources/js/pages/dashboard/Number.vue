<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Flower } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'dashboard',
        href: '/dashboard',
    },
    {
        title: 'chiffres',
        href: '/chiffres',
    },
];
</script>
<template>
    <Head title="Chiffres" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Flower class="fixed w-full h-screen mt-10 text-primary-color opacity-10" />
        <div class="container p-6 mx-auto">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold">Chiffres</h1>
                <div class="flex items-center gap-2">
                    <button class="px-4 py-2 font-medium text-white rounded-lg bg-primary-color">Jour</button>
                    <button class="px-4 py-2 font-medium rounded-lg hover:bg-gray-100">Semaine</button>
                    <button class="px-4 py-2 font-medium rounded-lg hover:bg-gray-100">Mois</button>
                    <button class="px-4 py-2 font-medium rounded-lg hover:bg-gray-100">Année</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
