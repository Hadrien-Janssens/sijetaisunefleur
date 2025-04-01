<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { BadgeEuro, Euro, Flower, ShoppingBag, Store, TrendingUp, Users } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'dashboard',
        href: '/dashboard',
    },
];

const stats = [
    {
        title: 'Chiffre du jour',
        value: '2,350 €',
        change: '+12.5%',
        icon: Euro,
        link: 'number',
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
        <Flower class="fixed w-full h-screen mt-10 text-primary-color opacity-10" />
        <div class="container p-6 mx-auto">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold">Tableau de bord</h1>
                <div class="flex items-center gap-2">
                    <button class="px-4 py-2 font-medium text-white rounded-lg bg-primary-color">Jour</button>
                    <button class="px-4 py-2 font-medium rounded-lg hover:bg-gray-100">Semaine</button>
                    <button class="px-4 py-2 font-medium rounded-lg hover:bg-gray-100">Mois</button>
                    <button class="px-4 py-2 font-medium rounded-lg hover:bg-gray-100">Année</button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="stat in stats"
                    :key="stat.title"
                    :href="stat.link ? route(stat.link) : undefined"
                    class="w-full transition-all duration-300 ease-in-out bg-transparent from-white to-gray-50/80 backdrop-blur-md hover:-translate-y-1 hover:shadow-lg"
                >
                    <Card
                        class="w-full transition-all duration-300 ease-in-out bg-transparent from-white to-gray-50/80 backdrop-blur-md hover:-translate-y-1 hover:shadow-lg"
                    >
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
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
