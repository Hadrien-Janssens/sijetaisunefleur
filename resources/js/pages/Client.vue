<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Building, ContactRound, Hash, Mail, MapPin, Phone } from 'lucide-vue-next';

defineProps({
    clients: {
        type: Array,
    },
});
</script>

<template>
    <AppLayout>
        <div class="container mx-auto p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold">Clients</h1>
                <Button class="bg-green-600 font-bold text-green-100 hover:cursor-pointer hover:bg-green-700">Ajouter un client</Button>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="client in clients"
                    :key="client.id"
                    class="w-full bg-gradient-to-br from-white to-gray-50/80 transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg"
                >
                    <CardHeader>
                        <CardTitle class="flex items-start gap-2 text-gray-600">
                            <ContactRound class="h-5 w-5" />
                            <div>
                                <p>{{ client.firstname }}</p>
                                <p>{{ client.lastname }}</p>
                            </div>
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-center gap-2 text-sm">
                            <Mail class="h-4 w-4 text-blue-400" />
                            <span>{{ client.email }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <Phone class="h-4 w-4 text-green-400" />
                            <span>{{ client.phone }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <Building class="h-4 w-4 text-yellow-400" />
                            <span>{{ client.company }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <Hash class="h-4 w-4 text-red-400" />
                            <span>TVA: {{ client.tva_number }}</span>
                        </div>
                        <div class="flex items-start gap-2 text-sm">
                            <MapPin class="h-4 w-4 shrink-0 text-orange-400" />
                            <div>
                                <p>{{ client.address }}</p>
                                <p>{{ client.city }}, {{ client.country }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
