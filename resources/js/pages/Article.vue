<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Package } from 'lucide-vue-next';

defineProps({
    categories: {
        type: Array,
    },
});
</script>

<template>
    <AppLayout>
        <div class="container mx-auto p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold">Articles</h1>
                <Button class="bg-green-600 font-bold text-green-100 hover:cursor-pointer hover:bg-green-700">Ajouter un article</Button>
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
