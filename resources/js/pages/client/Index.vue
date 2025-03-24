<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Building, ContactRound, Flower, Hash, Mail, MapPin, Phone, Printer } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';
import CreateClientModal from '../../components/CreateClientModal.vue';

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

const activeTab = ref('active');

const filteredClients = computed(() => {
    const filtered = props.clients.filter((client: any) => {
        const isDeleted = client.deleted_at !== null;
        return activeTab.value === 'deleted' ? isDeleted : !isDeleted;
    });

    if (!searchQuery.value) return filtered;

    const query = searchQuery.value.toLowerCase();
    return filtered.filter(
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
const downloadFile = () => {
    window.location.href = route('ticket.export');
};
</script>

<template>
    <Head title="Chiffres" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <CreateClientModal :show="isModalOpen" @close="isModalOpen = false" @create="handleCreateClient" />
        <Flower class="text-primary-color fixed mt-10 h-screen w-full opacity-10" />
        <div class="container z-10 mx-auto p-6">
            <div class="z-50 mb-6 flex items-center justify-between">
                <h1 class="flex items-center gap-3 text-3xl font-bold">
                    Clients

                    <button @click="downloadFile" class="text-primary-color hover:text-hover-primary-color"><Printer class="h-6 w-6" /></button>
                </h1>
                <Button variant="teal" @click="isModalOpen = true">Ajouter un client</Button>
            </div>

            <div class="mb-6 flex grow items-center gap-2">
                <Input v-model="searchQuery" placeholder="Rechercher un client..." />
            </div>
            <Tabs v-model="activeTab" class="mb-6">
                <TabsList class="grid w-full max-w-[400px] grid-cols-2">
                    <TabsTrigger value="active">Clients actifs</TabsTrigger>
                    <TabsTrigger value="deleted">Clients supprimés</TabsTrigger>
                </TabsList>
            </Tabs>

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
        </div>
    </AppLayout>
</template>
