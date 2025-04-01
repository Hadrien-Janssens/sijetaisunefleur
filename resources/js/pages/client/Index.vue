<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Building, ContactRound, Flower, Hash, Mail, MapPin, Phone, Printer } from 'lucide-vue-next';
import { reactive, ref, watch } from 'vue';
import CreateClientModal from '../../components/CreateClientModal.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'clients',
        href: '/client',
    },
];

const props = defineProps<{
    clients: {
        type: object;
        required: true;
    };
    filters: {
        search: string;
        actif?: string;
    };
}>();
console.log(props);

const searchQuery = ref(props.filters.search || '');
const activeTab = ref(props.filters.actif || 'active');

const performSearch = () => {
    router.get(
        '/client',
        {
            search: searchQuery.value,
            actif: activeTab.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

watch(
    [searchQuery, activeTab],
    () => {
        performSearch();
    },
    { immediate: true },
);

const isModalOpen = ref(false);

const handleCreateClient = (clientData: any) => {
    const form = reactive(clientData);
    router.post(route('client.store'), form);
    isModalOpen.value = false;
};
const downloadFile = () => {
    window.location.href = route('client.export', { tab: activeTab.value });
};

const visit = (url: string | null) => {
    if (!url) return;

    // Ajouter les paramètres de filtre actuels à l'URL
    const urlObj = new URL(url);
    urlObj.searchParams.set('search', searchQuery.value);
    urlObj.searchParams.set('actif', activeTab.value);

    router.visit(urlObj.toString(), {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Chiffres" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <CreateClientModal :show="isModalOpen" @close="isModalOpen = false" @create="handleCreateClient" />
        <Flower class="fixed w-full h-screen mt-10 text-primary-color opacity-10" />
        <div class="container z-10 p-6 mx-auto">
            <div class="z-50 flex items-center justify-between mb-6">
                <h1 class="flex items-center gap-3 text-3xl font-bold">
                    Clients

                    <button @click="downloadFile" class="text-primary-color hover:text-hover-primary-color"><Printer class="w-6 h-6" /></button>
                </h1>
                <Button variant="teal" @click="isModalOpen = true">Ajouter un client</Button>
            </div>

            <div class="flex items-center gap-2 mb-2 grow">
                <Input v-model="searchQuery" placeholder="Rechercher un client..." />
            </div>
            <Tabs v-model="activeTab" class="mb-6">
                <TabsList class="grid w-full grid-cols-4">
                    <TabsTrigger value="active">Clients actifs</TabsTrigger>
                    <TabsTrigger value="with_tva">Clients assujettis</TabsTrigger>
                    <TabsTrigger value="without_tva">Clients non-assujettis </TabsTrigger>
                    <TabsTrigger value="deleted">Clients supprimés</TabsTrigger>
                </TabsList>
            </Tabs>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="client in clients.data"
                    :key="client.id"
                    class="w-full transition-all duration-300 ease-in-out bg-transparent from-white to-gray-50/80 backdrop-blur-md hover:-translate-y-1 hover:shadow-lg"
                >
                    <Link :href="route('client.edit', client.id)">
                        <CardHeader>
                            <CardTitle class="flex items-start gap-2 text-gray-600">
                                <ContactRound class="w-5 h-5" />
                                <div>
                                    <p>{{ client.firstname }}</p>
                                    <p>{{ client.lastname }}</p>
                                </div>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center gap-2 text-sm">
                                <Mail class="w-4 h-4 text-blue-400" />
                                <span>{{ client.email }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <Phone class="w-4 h-4 text-green-400" />
                                <span>{{ client.phone }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <Building class="w-4 h-4 text-yellow-400" />
                                <span>{{ client.company }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <Hash class="w-4 h-4 text-red-400" />
                                <span>TVA: {{ client.tva_number }}</span>
                            </div>
                            <div class="flex items-start gap-2 text-sm">
                                <MapPin class="w-4 h-4 text-orange-400 shrink-0" />
                                <div>
                                    <p>{{ client.address }}</p>
                                    <p>{{ client.city }}, {{ client.country }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Link>
                </Card>
            </div>
            <!-- PAGINATION -->
            <div v-if="clients?.data?.length" class="mt-6">
                <nav class="flex items-center justify-between">
                    <Button variant="outline" :disabled="!clients.prev_page_url" @click="visit(clients.prev_page_url)"> Précédent </Button>

                    <span class="text-sm text-gray-700"> Page {{ clients.current_page }} sur {{ clients.last_page }} </span>

                    <Button variant="outline" :disabled="!clients.next_page_url" @click="visit(clients.next_page_url)"> Suivant </Button>
                </nav>
            </div>
        </div>
    </AppLayout>
</template>
