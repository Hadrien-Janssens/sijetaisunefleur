<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { useToast } from '@/components/ui/toast';
import Toaster from '@/components/ui/toast/Toaster.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { Building, ContactRound, Flower, Hash, Mail, MapPin, Phone, Search } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';
import CreateClientModal from '../../components/CreateClientModal.vue';

const { toast } = useToast();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'clients',
        href: '/client',
    },
];

const props = defineProps({
    clients: {
        type: Array,
        required: true,
    },
});

const searchQuery = ref('');

const filteredClients = computed(() => {
    if (!searchQuery.value) return props.clients;
    const query = searchQuery.value.toLowerCase();
    return props.clients.filter(
        (client: any) =>
            client.firstname.toLowerCase().includes(query) ||
            client.lastname.toLowerCase().includes(query) ||
            client.company.toLowerCase().includes(query) ||
            client.email.toLowerCase().includes(query),
    );
});

const isModalOpen = ref(false);

const handleCreateClient = (clientData: any) => {
    const form = reactive(clientData);
    router.post(route('client.store'), form);
    isModalOpen.value = false;
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <CreateClientModal :show="isModalOpen" @close="isModalOpen = false" @create="handleCreateClient" />
        <!-- <Users class="fixed w-full h-screen mt-10 text-stone-100" /> -->
        <Flower class="fixed mt-10 h-screen w-full text-teal-600 opacity-10" />
        <div class="container z-10 mx-auto p-6">
            <div class="z-50 mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold">Clients</h1>
                <Button variant="teal" @click="isModalOpen = true">Ajouter un client</Button>
            </div>

            <div class="mb-6 flex items-center gap-2">
                <Search class="h-4 w-4 text-gray-500" />
                <Input v-model="searchQuery" placeholder="Rechercher un client..." class="max-w-sm" />
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="client in filteredClients"
                    :key="client.id"
                    class="w-full bg-transparent from-white to-gray-50/80 backdrop-blur-md transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg"
                >
                    <Link :href="route('client.edit', client.id)">
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
                    </Link>
                </Card>
            </div>
            <button
                @click="
                    () => {
                        toast({
                            title: 'Scheduled: Catch up',
                            description: 'Friday, February 10, 2023 at 5:57 PM',
                        });
                    }
                "
            >
                Test Log
            </button>
            <Toaster />
        </div>
    </AppLayout>
</template>
