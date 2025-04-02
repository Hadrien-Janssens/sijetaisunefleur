<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Line } from '@unovis/ts';
import { VisAxis, VisLine, VisTooltip, VisXYContainer } from '@unovis/vue';
import { BadgeEuro, Euro, Flower, Store, TrendingUp, Users } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'dashboard',
        href: '/dashboard',
    },
];

const stats = ref([
    {
        title: "Chiffre d'affaires",
        value: '2,350 €',
        change: '+12.5%',
        icon: Euro,
        link: 'number',
        expended: false,
    },
    {
        title: 'Nombre de clients ',
        value: '24',
        change: '+4.5%',
        icon: Users,
        expended: false,
    },

    {
        title: 'Ventes mensuelles',
        value: '45,250 €',
        change: '+22.4%',
        icon: TrendingUp,
        expended: false,
    },
    {
        title: 'Produit le plus vendu',
        value: 'Bouquet Spring',
        secondary: '148 unités',
        icon: Store,
        expended: false,
    },
    {
        title: 'Meilleure vente',
        value: '386 €',
        secondary: 'Composition XL',
        icon: BadgeEuro,
        expended: false,
    },
]);
type DataRecord = { x: number; y: number };
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

const expended = (title: string) => {
    //   find stat
    const stat = stats.value.find((stat) => stat.title === title);
    if (stat) {
        //   toggle expended
        stat.expended = !stat.expended;
        console.log('coucou');
    }
};

const timeSlot = ref('day');
</script>
<template>
    <Head title="Chiffres" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Flower class="fixed w-full h-screen mt-10 text-primary-color opacity-10" />
        <div class="container p-6 mx-auto space-y-10">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold">Tableau de bord</h1>
                <div class="flex items-center gap-2">
                    <input type="radio" name="timeSlot" id="day" value="day" v-model="timeSlot" class="hidden" />
                    <label
                        for="day"
                        :class="[
                            'cursor-pointer rounded-lg px-4 py-2 font-medium',
                            timeSlot === 'day' ? 'bg-primary-color text-white' : 'hover:bg-gray-100',
                        ]"
                    >
                        Jour
                    </label>

                    <input type="radio" name="timeSlot" id="week" value="week" v-model="timeSlot" class="hidden" />
                    <label
                        for="week"
                        :class="[
                            'cursor-pointer rounded-lg px-4 py-2 font-medium',
                            timeSlot === 'week' ? 'bg-primary-color text-white' : 'hover:bg-gray-100',
                        ]"
                    >
                        Semaine
                    </label>

                    <input type="radio" name="timeSlot" id="month" value="month" v-model="timeSlot" class="hidden" />
                    <label
                        for="month"
                        :class="[
                            'cursor-pointer rounded-lg px-4 py-2 font-medium',
                            timeSlot === 'month' ? 'bg-primary-color text-white' : 'hover:bg-gray-100',
                        ]"
                    >
                        Mois
                    </label>

                    <input type="radio" name="timeSlot" id="year" value="year" v-model="timeSlot" class="hidden" />
                    <label
                        for="year"
                        :class="[
                            'cursor-pointer rounded-lg px-4 py-2 font-medium',
                            timeSlot === 'year' ? 'bg-primary-color text-white' : 'hover:bg-gray-100',
                        ]"
                    >
                        Année
                    </label>
                    <input type="radio" name="timeSlot" id="crenaux" value="crenaux" v-model="timeSlot" class="hidden" />
                    <label
                        for="crenaux"
                        :class="[
                            'cursor-pointer rounded-lg px-4 py-2 font-medium',
                            timeSlot === 'crenaux' ? 'bg-primary-color text-white' : 'hover:bg-gray-100',
                        ]"
                    >
                        Crénaux
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Graphique -->
                <div class="col-span-2 col-start-1 row-span-2 row-start-1">
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
                <div
                    v-for="stat in stats"
                    :key="stat.title"
                    @click="expended(stat.title)"
                    class="w-full transition-all duration-300 ease-in-out bg-transparent from-white to-gray-50/80 backdrop-blur-md hover:-translate-y-1 hover:shadow-lg"
                    :class="{
                        '': !stat.expended,
                        'col-span-3 col-start-1 row-span-5 row-start-1': stat.expended,
                    }"
                >
                    <Card class="h-full transition-all duration-500 ease-in-out">
                        <CardContent class="pt-6">
                            <div class="flex items-start justify-between">
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">{{ stat.title }}</p>
                                    <p class="text-2xl font-bold">{{ stat.value }}</p>
                                    <div v-if="stat.change" class="flex items-center space-x-1">
                                        <span class="text-sm text-primary-color">{{ stat.change }}</span>
                                    </div>
                                    <p v-if="stat.secondary" class="text-sm text-gray-500">{{ stat.secondary }}</p>
                                </div>
                                <component :is="stat.icon" class="w-8 h-8 text-primary-color" />
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
