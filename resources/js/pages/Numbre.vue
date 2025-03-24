<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { BadgeEuro, Euro, Flower, ShoppingBag, Store, TrendingUp, Users } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'chiffres',
        href: '/chiffres',
    },
];

const stats = [
    {
        title: 'Chiffre du jour',
        value: '2,350 €',
        change: '+12.5%',
        icon: Euro,
    },
    {
        title: "Clients aujourd'hui",
        value: '24',
        change: '+4.5%',
        icon: Users,
    },
    {
        title: 'Panier moyen',
        value: '89 €',
        change: '+2.1%',
        icon: ShoppingBag,
    },
    {
        title: 'Ventes mensuelles',
        value: '45,250 €',
        change: '+22.4%',
        icon: TrendingUp,
    },
    {
        title: 'Produit le plus vendu',
        value: 'Bouquet Spring',
        secondary: '148 unités',
        icon: Store,
    },
    {
        title: 'Meilleure vente',
        value: '386 €',
        secondary: 'Composition XL',
        icon: BadgeEuro,
    },
];
</script>
<template>
    <Head title="Chiffres" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Flower class="text-primary-color fixed mt-10 h-screen w-full opacity-10" />
        <div class="container mx-auto p-6">
            <div class="mb-8 flex items-center justify-between">
                <h1 class="text-3xl font-bold">Tableau de bord</h1>
                <div class="flex items-center gap-2">
                    <button class="bg-primary-color rounded-lg px-4 py-2 font-medium text-white">Jour</button>
                    <button class="rounded-lg px-4 py-2 font-medium hover:bg-gray-100">Semaine</button>
                    <button class="rounded-lg px-4 py-2 font-medium hover:bg-gray-100">Mois</button>
                    <button class="rounded-lg px-4 py-2 font-medium hover:bg-gray-100">Année</button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="stat in stats"
                    :key="stat.title"
                    class="w-full bg-transparent from-white to-gray-50/80 backdrop-blur-md transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg"
                >
                    <CardContent class="pt-6">
                        <div class="flex items-start justify-between">
                            <div class="space-y-2">
                                <p class="text-sm text-gray-500">{{ stat.title }}</p>
                                <p class="text-2xl font-bold">{{ stat.value }}</p>
                                <div v-if="stat.change" class="flex items-center space-x-1">
                                    <span class="text-primary-color text-sm">{{ stat.change }}</span>
                                </div>
                                <p v-if="stat.secondary" class="text-sm text-gray-500">{{ stat.secondary }}</p>
                            </div>
                            <component :is="stat.icon" class="text-primary-color h-8 w-8" />
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
