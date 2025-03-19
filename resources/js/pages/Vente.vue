<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import { Input } from '@/components/ui/input';
import Label from '@/components/ui/label/Label.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Barcode, CalendarDays, ChevronDown, ChevronUp, Clock, Flower, Printer, ShoppingBag, Ticket, User } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { getHour } from '../lib/utils';

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
}

const props = defineProps<{
    tickets: Ticket | null;
    filters: {
        search: string;
        withInvoice?: boolean;
        date?: string;
    };
}>();

console.log(props.tickets);

const searchQuery = ref(props.filters.search || '');
const withInvoice = ref(props.filters.withInvoice || false);
const selectedDate = ref(props.filters.date || new Date().toISOString().split('T')[0]);

const performSearch = () => {
    router.get(
        '/vente',
        {
            search: searchQuery.value,
            withInvoice: withInvoice.value,
            date: selectedDate.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

watch(
    [searchQuery, withInvoice, selectedDate],
    () => {
        performSearch();
    },
    { immediate: true },
);

const visit = (url: string) => {
    router.visit(url, {
        preserveState: true,
        preserveScroll: true,
    });
};

const getTicketPrice = (ticket: any) => {
    let totalPrice = 0;
    ticket.ticket_rows.forEach((row: object) => {
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
</script>
<template>
    <Head title="Ventes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Flower class="fixed mt-10 h-screen w-full text-teal-600 opacity-5" />
        <div class="container z-10 mx-auto p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="flex items-center gap-3 text-3xl font-bold">
                    Ventes

                    <button @click="downloadFile" class="text-teal-600 hover:text-teal-700"><Printer class="h-6 w-6" /></button>
                </h1>

                <div class="flex items-center gap-2">
                    <button class="rounded-lg bg-teal-600 px-4 py-2 font-medium text-white">Jour</button>
                    <button class="rounded-lg px-4 py-2 font-medium hover:bg-gray-100">Semaine</button>
                    <button class="rounded-lg px-4 py-2 font-medium hover:bg-gray-100">Mois</button>
                    <button class="rounded-lg px-4 py-2 font-medium hover:bg-gray-100">Année</button>
                </div>
            </div>
            <!-- SEARCHBAR -->
            <Input
                type="text"
                v-model="searchQuery"
                @input="performSearch"
                placeholder="Recherche des tickets par client"
                class="mb-3 rounded-md border border-gray-300 p-2"
            />

            <div class="flex items-center justify-between space-x-2">
                <div class="flex items-center space-x-2">
                    <Switch id="invoice" v-model="withInvoice" />
                    <Label for="invoice">Avec facture</Label>
                </div>
                <input type="date" lang="fr" v-model="selectedDate" class="rounded-md border border-gray-300 p-2" />
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
                        <div class="flex cursor-pointer items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div>
                                    <p class="flex items-center gap-2 font-medium"><Barcode class="h-4 w-4 text-teal-600" /> N° {{ ticket.id }}</p>
                                </div>
                                <div class="text-gray-600">|</div>

                                <div>
                                    <p class="font-medium">Total : {{ getTicketPrice(ticket) }}€</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-5">
                                <p class="flex items-center gap-1 text-sm text-gray-600">
                                    <Clock class="h-4 w-4" /> {{ getHour(ticket.created_at) }}
                                </p>
                                <div
                                    class="rounded-full bg-gray-100 p-1 transition-transform duration-300"
                                    :class="{ 'rotate-180': expandedTicketId === ticket.id }"
                                >
                                    <ChevronDown v-if="expandedTicketId !== ticket.id" class="h-4 w-4" />
                                    <ChevronUp v-else class="h-4 w-4" />
                                </div>
                            </div>
                        </div>
                        <Transition name="expand">
                            <div v-show="expandedTicketId === ticket.id" class="mt-4 overflow-hidden transition-all duration-300 ease-in-out">
                                <div class="rounded-md bg-gray-50 p-4">
                                    <div class="mb-4 grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="flex items-start gap-1 text-sm text-gray-500">
                                                <CalendarDays class="h-4 w-4" />Date de création
                                            </p>
                                            <p class="font-medium">{{ formatDate(ticket.created_at) }}</p>
                                        </div>
                                        <div>
                                            <p class="flex items-start gap-1 text-sm text-gray-500"><User class="h-4 w-4" />Client</p>

                                            <Link
                                                v-if="ticket.client"
                                                :href="route('client.edit', ticket.client.id)"
                                                class="font-medium duration-200 hover:cursor-pointer hover:text-teal-600"
                                            >
                                                {{ ticket.client?.firstname }}
                                            </Link>
                                            <p v-else class="font-medium">Client non défini</p>
                                        </div>
                                    </div>

                                    <div class="mb-2 border-b border-gray-200 pb-2">
                                        <p class="font-semibold">Détails des articles</p>
                                    </div>

                                    <div class="space-y-2">
                                        <div
                                            v-for="(row, index) in ticket.ticket_rows"
                                            :key="index"
                                            class="flex items-center justify-between rounded-md bg-white p-2"
                                        >
                                            <div class="flex items-center gap-2">
                                                <ShoppingBag class="h-4 w-4 text-gray-500" />
                                                <span>{{ row.category.name || `Article #${index + 1}` }}</span>
                                                <span class="text-sm text-gray-500">{{ row.price }}€ x{{ row.quantity }}</span>
                                            </div>
                                            <span class="font-medium">{{ (row.price * row.quantity).toFixed(2) }}€</span>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex justify-end border-t border-gray-200 pt-4">
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">Total</p>
                                            <p class="text-lg font-bold">{{ getTicketPrice(ticket) }}€</p>
                                        </div>
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
