<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Line } from '@unovis/ts';
import { VisAxis, VisLine, VisTooltip, VisXYContainer } from '@unovis/vue';
import { Flower } from 'lucide-vue-next';
import { ref } from 'vue';

type DataRecord = { x: number; y: number };

// Configuration des breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'dashboard', href: '/dashboard' },
    { title: 'chiffres', href: '/chiffres' },
];

// Données et configuration du graphique
const data = ref<DataRecord[]>([
    { x: 0, y: 0 },
    { x: 1, y: 2 },
    { x: 2, y: 1 },
]);

const triggers = {
    [Line.selectors.point]: (d: DataRecord) => `
        <div class="p-2 bg-white border rounded-lg shadow-md">
            <b>Valeur:</b> ${d.y}
        </div>
    `,
};
</script>

<template>
    <Head title="Chiffres" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Flower class="fixed w-full h-screen mt-10 text-primary-color opacity-10" />
        <div class="container p-6 mx-auto">
            <!-- En-tête -->
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold">Chiffres</h1>
                <div class="flex items-center gap-2">
                    <button class="px-4 py-2 font-medium text-white rounded-lg bg-primary-color">Jour</button>
                    <button class="px-4 py-2 font-medium rounded-lg hover:bg-gray-100">Semaine</button>
                    <button class="px-4 py-2 font-medium rounded-lg hover:bg-gray-100">Mois</button>
                    <button class="px-4 py-2 font-medium rounded-lg hover:bg-gray-100">Année</button>
                </div>
            </div>

            <!-- Graphique -->
            <div class="mx-auto w-[500px] p-6">
                <VisXYContainer :data="data">
                    <VisLine color="hsl(90deg 64% 54%)" :lineWidth="5" :x="(d: DataRecord) => d.x" :y="(d: DataRecord) => d.y" :pointSize="6" />
                    <VisTooltip
                        :triggers="triggers"
                        :followCursor="false"
                        verticalPlacement="top"
                        class="[--vis-tooltip-background-color:#fff;--vis-tooltip-text-color:#333]"
                    />
                    <VisAxis type="x" />
                    <VisAxis type="y" />
                </VisXYContainer>
            </div>
        </div>
    </AppLayout>
</template>
