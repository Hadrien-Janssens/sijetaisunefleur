<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Package } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'articles',
        href: '/articles',
    },
];

defineProps({
    categories: {
        type: Array,
    },
});
</script>

<template>
    <Head title="Articles" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Package class="fixed mt-10 h-screen w-full text-stone-100" />
        <div class="container z-10 mx-auto p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold">Articles</h1>
                <Button variant="teal">Ajouter un article</Button>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="category in categories"
                    :key="category.id"
                    class="w-full bg-gradient-to-br from-white to-gray-50/80 transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg"
                >
                    <CardHeader>
                        <CardTitle class="flex items-start gap-2 text-gray-600">
                            <Package class="h-5 w-5" />
                            <div>
                                <p>{{ category.name }}</p>
                            </div>
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4"> </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
