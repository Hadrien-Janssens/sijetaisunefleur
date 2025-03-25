<script setup lang="ts">
import DeleteConfirmationModal from '@/components/DeleteConfirmationModal.vue';
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import { Input } from '@/components/ui/input';
import Label from '@/components/ui/label/Label.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import Tabs from '@/components/ui/tabs/Tabs.vue';
import TabsList from '@/components/ui/tabs/TabsList.vue';
import TabsTrigger from '@/components/ui/tabs/TabsTrigger.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Barcode, CalendarDays, ChevronDown, Clock, Flower, Printer, ShoppingBag, Ticket, User } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { getHour } from '../lib/utils';
import { Row } from '../types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'ventes',
        href: '/ventes',
    },
];

interface Ticket {
    id: number;
    created_at: string;
    prev_page_url: string | null;
    next_page_url: string | null;
    current_page: number;
    last_page: number;
    data: Array<{
        id: number;
        created_at: string;
        client: {
            id: number;
            firstname: string;
        };
        ticket_rows: Row[];
    }>;
}

const props = defineProps<{
    tickets: Ticket | null;
    filters: {
        search: string;
        withInvoice?: boolean;
        start_date?: string;
        end_date?: string;
        actif?: string;
        time_slot?: string;
    };
}>();

const searchQuery = ref(props.filters.search || '');
const withInvoice = ref(props.filters.withInvoice || false);
const selectedDate = ref(props.filters.start_date || new Date().toISOString().split('T')[0]);
const selectedEndDate = ref(props.filters.end_date || new Date().toISOString().split('T')[0]);
const activeTab = ref(props.filters.actif || 'active');
const timeSlot = ref(props.filters.time_slot || 'day');

const performSearch = () => {
    router.get(
        '/vente',
        {
            search: searchQuery.value,
            withInvoice: withInvoice.value,
            start_date: selectedDate.value,
            end_date: selectedEndDate.value,
            actif: activeTab.value,
            time_slot: timeSlot.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

watch(
    [searchQuery, withInvoice, selectedDate, activeTab, selectedEndDate, timeSlot],
    () => {
        performSearch();
    },
    { immediate: true },
);

const visit = (url: string | null) => {
    if (!url) return;
    router.visit(url, {
        preserveState: true,
        preserveScroll: true,
    });
};

const getTicketPrice = (ticket: any) => {
    let totalPrice = 0;
    ticket.ticket_rows.forEach((row: Row) => {
        totalPrice += row.price * row.quantity;
    });
    return totalPrice.toFixed(2);
};

const expandedTicketId = ref<number | null>(null);

const toggleTicket = (ticketId: number) => {
    if (expandedTicketId.value === ticketId) {
        expandedTicketId.value = null;
    } else {
        expandedTicketId.value = ticketId;
    }
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
const downloadFile = () => {
    window.location.href = route('ticket.export');
};

const showDeleteModal = ref(false);
const ticketToDelete = ref<number | null>(null);

const openDeleteModal = (id: number) => {
    ticketToDelete.value = id;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (ticketToDelete.value !== null) {
        router.delete(route('ticket.destroy', ticketToDelete.value));
    }
    showDeleteModal.value = false;
    ticketToDelete.value = null;
};
</script>
<template>
    <Head title="Ventes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Flower class="fixed w-full h-screen mt-10 text-primary-color opacity-10" />
        <div class="container z-10 p-6 mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="flex items-center gap-3 text-3xl font-bold">
                    Ventes

                    <button @click="downloadFile" class="text-primary-color hover:text-hover-primary-color"><Printer class="w-6 h-6" /></button>
                </h1>

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
                </div>
            </div>
            <!-- SEARCHBAR -->
            <div class="flex items-center justify-between gap-2 mb-2">
                <Input
                    type="text"
                    v-model="searchQuery"
                    @input="performSearch"
                    placeholder="Recherche des tickets par client"
                    class="p-2 border border-gray-300 rounded-md"
                />
                <div class="flex gap-2 items-cente grow">
                    <Input type="date" lang="fr" v-model="selectedDate" class="p-2 border border-gray-300 rounded-md" />
                    <Input type="date" lang="fr" v-model="selectedEndDate" class="p-2 border border-gray-300 rounded-md" />
                </div>
            </div>
            <div class="flex items-center justify-between">
                <Tabs v-model="activeTab">
                    <TabsList class="grid w-full max-w-[400px] grid-cols-2">
                        <TabsTrigger value="active">Tickets actifs</TabsTrigger>
                        <TabsTrigger value="deleted">Tickets supprimés</TabsTrigger>
                    </TabsList>
                </Tabs>
                <div class="flex items-center gap-2">
                    <Switch id="invoice" v-model="withInvoice" />
                    <Label for="invoice">Avec facture</Label>
                </div>
            </div>

            <div v-if="tickets?.data?.length" class="mt-4 space-y-3">
                <div v-for="ticket in tickets.data" :key="ticket.id" class="transition-all duration-300 ease-in-out">
                    <Card
                        :class="[
                            'w-full bg-transparent from-white to-gray-50/80 p-4 shadow transition-all duration-300 ease-in-out',
                            expandedTicketId === ticket.id ? 'scale-102 shadow-lg' : 'backdrop-blur-md hover:-translate-y-1 hover:shadow-md',
                        ]"
                        @click="toggleTicket(ticket.id)"
                    >
                        <div class="flex items-center justify-between cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div>
                                    <p class="flex items-center gap-2 font-medium">
                                        <Barcode class="w-4 h-4 text-primary-color" /> N° {{ ticket.id }}
                                    </p>
                                </div>
                                <div class="text-gray-600">|</div>

                                <div>
                                    <p class="font-medium">Total : {{ getTicketPrice(ticket) }}€</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-5">
                                <p class="flex items-center gap-1 text-sm text-gray-600">
                                    <Clock class="w-4 h-4" /> {{ getHour(ticket.created_at) }}
                                </p>
                                <div
                                    class="p-1 transition-transform duration-300 bg-gray-100 rounded-full"
                                    :class="{ 'rotate-180': expandedTicketId === ticket.id }"
                                >
                                    <ChevronDown class="w-4 h-4" />
                                </div>
                            </div>
                        </div>
                        <Transition name="expand">
                            <div v-show="expandedTicketId === ticket.id" class="mt-4 overflow-hidden transition-all duration-300 ease-in-out">
                                <div class="p-4 rounded-md bg-gray-50">
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <p class="flex items-start gap-1 text-sm text-gray-500">
                                                <CalendarDays class="w-4 h-4" />Date de création
                                            </p>
                                            <p class="font-medium">{{ formatDate(ticket.created_at) }}</p>
                                        </div>
                                        <div>
                                            <p class="flex items-start gap-1 text-sm text-gray-500"><User class="w-4 h-4" />Client</p>

                                            <Link
                                                v-if="ticket.client"
                                                :href="route('client.edit', ticket.client.id)"
                                                class="font-medium duration-200 hover:text-primary-color hover:cursor-pointer"
                                            >
                                                {{ ticket.client?.firstname }}
                                            </Link>
                                            <p v-else class="font-medium">Client non défini</p>
                                        </div>
                                    </div>

                                    <div class="pb-2 mb-2 border-b border-gray-200">
                                        <p class="font-semibold">Détails des articles</p>
                                    </div>

                                    <div class="space-y-2">
                                        <div
                                            v-for="(row, index) in ticket.ticket_rows"
                                            :key="index"
                                            class="flex items-center justify-between p-2 bg-white rounded-md"
                                        >
                                            <div class="flex items-center gap-2">
                                                <ShoppingBag class="w-4 h-4 text-gray-500" />
                                                <span>{{ row.category.name || `Article #${index + 1}` }}</span>
                                                <span class="text-sm text-gray-500">{{ row.price }}€ x{{ row.quantity }}</span>
                                            </div>
                                            <span class="font-medium">{{ (row.price * row.quantity).toFixed(2) }}€</span>
                                        </div>
                                    </div>

                                    <div class="flex justify-end pt-4 mt-4 border-t border-gray-200">
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">Total</p>
                                            <p class="text-lg font-bold">{{ getTicketPrice(ticket) }}€</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="space-x-2">
                                        <Button class="mt-4" variant="outline">Imprimer le ticket</Button>
                                        <Button class="mt-4" variant="outline">Envoyer par mail</Button>
                                    </div>
                                    <div class="space-x-2">
                                        <DeleteConfirmationModal v-model:open="showDeleteModal" @delete="confirmDelete()" :hiddenbutton="true" />

                                        <Button @click="openDeleteModal(ticket.id)" variant="outline"> Supprimer </Button>
                                        <Button class="mt-4" variant="teal" @click="console.log(ticket.id)">Sauvegarder</Button>
                                    </div>
                                </div>
                            </div>
                        </Transition>
                    </Card>
                </div>
            </div>
            <div v-else class="mt-4 text-center text-gray-500">Aucun ticket disponible</div>
            <!-- Pagination -->
            <div v-if="tickets?.data?.length" class="mt-6">
                <nav class="flex items-center justify-between">
                    <Button variant="outline" :disabled="!tickets.prev_page_url" @click="visit(tickets.prev_page_url)"> Précédent </Button>

                    <span class="text-sm text-gray-700"> Page {{ tickets.current_page }} sur {{ tickets.last_page }} </span>

                    <Button variant="outline" :disabled="!tickets.next_page_url" @click="visit(tickets.next_page_url)"> Suivant </Button>
                </nav>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.expand-enter-active,
.expand-leave-active {
    transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    max-height: 500px;
    opacity: 1;
    margin-top: 1rem;
}

.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    opacity: 0;
    margin-top: 0;
}

.scale-102 {
    transform: scale(1.02);
}
</style>
