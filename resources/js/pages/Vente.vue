<script setup lang="ts">
import DeleteConfirmationModal from '@/components/DeleteConfirmationModal.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import Tabs from '@/components/ui/tabs/Tabs.vue';
import TabsList from '@/components/ui/tabs/TabsList.vue';
import TabsTrigger from '@/components/ui/tabs/TabsTrigger.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Client, type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Barcode, CalendarDays, ChevronDown, Clock, Flower, Pencil, Printer, Send, ShoppingBag, Ticket, Trash, User } from 'lucide-vue-next';
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
        deleted_at: string | null;
        client: Client | null;
        ticket_rows: Row[];
        is_paid: boolean;
        remise: number;
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
        totalPrice += (row.reduction === 0 ? row.price : row.price - (row.price * row.reduction) / 100) * row.quantity;
    });
    // return (totalPrice - (ticket.remiseAmount ? ticket.remiseAmount : 0)).toFixed(2);
    return totalPrice;
};
const getTicketPriceWithRemise = (ticket: any) => {
    let totalPriceWithRemise = 0;
    ticket.ticket_rows.forEach((row: Row) => {
        totalPriceWithRemise +=
            (row.reduction === 0
                ? ticket.remise === 0
                    ? row.price
                    : row.price - (row.price * ticket.remise) / 100
                : row.price - (row.price * row.reduction) / 100) * row.quantity;
    });
    // return (totalPriceWithRemise - (ticket.remiseAmount ? ticket.remiseAmount : 0)).toFixed(2);
    return totalPriceWithRemise;
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
    window.location.href = route('ticket.export', {
        search: searchQuery.value,
        withInvoice: withInvoice.value,
        start_date: selectedDate.value,
        end_date: selectedEndDate.value,
        actif: activeTab.value,
        time_slot: timeSlot.value,
    });
};

const showTicketDeleteModal = ref(false);
const showConfirmDeleteModal = ref(false);
const ticketToDelete = ref<number | null>(null);

const openDeleteModal = (id: number) => {
    ticketToDelete.value = id;
    showTicketDeleteModal.value = true;
};
const openForceDeleteModal = (id: number) => {
    ticketToDelete.value = id;
    showConfirmDeleteModal.value = true;
};

const confirmDelete = () => {
    if (ticketToDelete.value !== null) {
        router.delete(route('ticket.destroy', ticketToDelete.value));
    }
    showTicketDeleteModal.value = false;
    ticketToDelete.value = null;
};
const forceDelete = () => {
    if (ticketToDelete.value !== null) {
        router.delete(route('ticket.forceDelete', ticketToDelete.value));
    }
    showConfirmDeleteModal.value = false;
    ticketToDelete.value = null;
};

const sendMail = (ticketID: number) => {
    router.post(route('ticket.sendMail', ticketID), {}, { preserveState: true, preserveScroll: true });
};

const loadTicket = (ticketId: number) => {
    // router.get(route('ticket.print', ticketId), {}, { preserveState: true, preserveScroll: true });
    window.open(route('ticket.print', { id: ticketId }), '_blank');
};
</script>
<template>
    <Head title="Ventes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Flower class="text-primary-color fixed mt-10 h-screen w-full opacity-10" />
        <div class="container z-10 mx-auto p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="flex items-center gap-3 text-3xl font-bold">
                    Ventes

                    <button @click="downloadFile" class="text-primary-color hover:text-hover-primary-color"><Printer class="h-6 w-6" /></button>
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
                    <input type="radio" name="timeSlot" id="crenaux" value="crenaux" v-model="timeSlot" class="hidden" />
                    <label
                        for="crenaux"
                        :class="[
                            'cursor-pointer rounded-lg px-4 py-2 font-medium',
                            timeSlot === 'crenaux' ? 'bg-primary-color text-white' : 'hover:bg-gray-100',
                        ]"
                    >
                        Crénaux
                    </label>
                </div>
            </div>

            <div class="mb-2 flex w-full items-center justify-between gap-2">
                <Input
                    type="text"
                    v-model="searchQuery"
                    @input="performSearch"
                    placeholder="Recherche des tickets par client"
                    class="basis-1/2 rounded-md border border-gray-300 p-2"
                />

                <div class="flex items-center gap-2">
                    <Input type="date" lang="fr" v-model="selectedDate" class="rounded-md border border-gray-300 p-2" />

                    <transition name="fade-slide">
                        <Input
                            v-if="timeSlot === 'crenaux'"
                            type="date"
                            lang="fr"
                            v-model="selectedEndDate"
                            class="rounded-md border border-gray-300 p-2"
                        />
                    </transition>
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
                        <div class="flex cursor-pointer items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div>
                                    <p class="flex items-center gap-2 font-medium">
                                        <Barcode class="text-primary-color h-4 w-4" /> N° de ticket {{ ticket.id }}
                                    </p>
                                </div>
                                <div class="text-gray-600">|</div>
                                <div>
                                    <p class="flex items-center gap-2 font-medium">
                                        N° Facture {{ new Date(ticket.created_at).getFullYear() }}-{{ ticket.reference
                                        }}{{ ticket.with_tva ? 'A' : '' }}
                                    </p>
                                </div>
                                <div class="text-gray-600">|</div>

                                <div>
                                    <p v-if="ticket.remise === 0" class="font-medium">
                                        Total : {{ (getTicketPrice(ticket) - ticket.remiseAmount).toFixed(2) }}€
                                    </p>
                                    <p v-else class="font-medium">
                                        Total : {{ (getTicketPriceWithRemise(ticket) - ticket.remiseAmount).toFixed(2) }}€
                                    </p>
                                </div>
                                <Badge v-if="ticket.is_paid" class="bg-green-500">Aquitté</Badge>
                                <Badge v-else class="bg-red-500">à régler</Badge>
                            </div>
                            <div class="flex items-center gap-5">
                                <p class="flex items-center gap-1 text-sm text-gray-600">
                                    <Clock class="h-4 w-4" /> {{ getHour(ticket.created_at) }}
                                </p>
                                <div
                                    class="rounded-full bg-gray-100 p-1 transition-transform duration-300"
                                    :class="{ 'rotate-180': expandedTicketId === ticket.id }"
                                >
                                    <ChevronDown class="h-4 w-4" />
                                </div>
                            </div>
                        </div>
                        <Transition name="expand">
                            <div v-show="expandedTicketId === ticket.id" class="mt-4 overflow-hidden transition-all duration-300 ease-in-out">
                                <div class="rounded-md bg-gray-50 p-4">
                                    <div class="mb-4 grid grid-cols-3 gap-4">
                                        <div>
                                            <p class="flex items-start gap-1 text-sm text-gray-500">
                                                <CalendarDays class="h-4 w-4 text-orange-500" />Date de création
                                            </p>
                                            <p class="font-medium">{{ formatDate(ticket.created_at) }}</p>
                                        </div>
                                        <div>
                                            <p class="flex items-start gap-1 text-sm text-gray-500"><User class="h-4 w-4 text-blue-500" />Client</p>

                                            <Link
                                                v-if="ticket.client"
                                                :href="route('client.edit', ticket.client.id)"
                                                class="hover:text-primary-color font-medium duration-200 hover:cursor-pointer"
                                            >
                                                {{ ticket.client?.firstname }}
                                            </Link>
                                            <p v-else class="font-medium">Client non défini</p>
                                        </div>
                                        <div class="flex items-center justify-end gap-2">
                                            <Link :href="route('ticket.edit', ticket.id)" class="flex items-center gap-2">
                                                <Button
                                                    variant="outline"
                                                    class="bg-cyan-500 text-cyan-100 duration-300 hover:bg-cyan-600 hover:text-cyan-100"
                                                    ><Pencil />Modifier</Button
                                                ></Link
                                            >
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
                                                <ShoppingBag class="text-primary-color h-4 w-4" />
                                                <span>{{ row.category.name || `Article #${index + 1}` }}</span>
                                                <span class="text-sm text-gray-500">{{ row.price }}€ x{{ row.quantity }}</span>
                                                <span
                                                    v-if="row.reduction !== 0"
                                                    class="rounded-full border border-red-500 bg-red-100 px-1 py-0.5 text-xs text-red-500"
                                                >
                                                    -{{ row.reduction }}%
                                                </span>
                                            </div>
                                            <span class="font-medium"
                                                >{{
                                                    (
                                                        (row.reduction === 0 ? row.price : row.price - (row.price * row.reduction) / 100) *
                                                        row.quantity
                                                    ).toFixed(2)
                                                }}€</span
                                            >
                                        </div>
                                    </div>

                                    <div class="mt-4 flex justify-end border-t border-gray-200 pt-4">
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">Total</p>
                                            <p class="text-lg font-bold">{{ getTicketPrice(ticket) }}€</p>
                                            <p class="text-lg font-bold" v-if="ticket.remise === 0">{{ getTicketPrice(ticket).toFixed(2) }}€</p>
                                            <!-- <p v-if="ticket.remiseAmount !== 0">-{{ ticket.remiseAmount }}€</p> -->
                                            <p v-else class="text-lg">
                                                <span class="mr-2 rounded-full border border-red-500 bg-red-100 px-1 py-0.5 text-xs text-red-500">
                                                    -{{ ticket.remise }}%
                                                </span>
                                                <span class="font-bold"> {{ getTicketPriceWithRemise(ticket).toFixed(2) }}€ </span>
                                            </p>
                                            <span class="text-red-500" v-if="ticket.remiseAmount !== 0">
                                                - {{ ticket.remiseAmount.toFixed(2) }} € de remise
                                                <span v-if="ticket.remise === 0" class="font-bold text-black">
                                                    {{ (getTicketPrice(ticket) - ticket.remiseAmount).toFixed(2) }}€</span
                                                >
                                                <span v-else class="font-bold text-black"
                                                    >{{ (getTicketPriceWithRemise(ticket) - ticket.remiseAmount).toFixed(2) }}€</span
                                                >
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="space-x-2">
                                        <Link v-if="ticket.deleted_at" :href="route('ticket.restore', ticket.id)">
                                            <Button variant="outline">Restaurer</Button></Link
                                        >
                                        <Button class="mt-4" variant="outline" @click="loadTicket(ticket.id)">
                                            <Printer class="text-blue-500" />Imprimer le ticket</Button
                                        >
                                        <Button class="mt-4" variant="outline" @click="sendMail(ticket.id)" :disabled="!ticket.client?.email"
                                            ><Send class="text-blue-500" />Envoyer par mail</Button
                                        >
                                    </div>

                                    <div class="mt-4 flex items-center gap-2">
                                        <DeleteConfirmationModal v-model:open="showConfirmDeleteModal" @delete="forceDelete" :hiddenbutton="true" />
                                        <Button v-if="ticket.deleted_at" @click="openForceDeleteModal(ticket.id)" variant="outline">
                                            Supprimer définitivement
                                        </Button>

                                        <DeleteConfirmationModal v-model:open="showTicketDeleteModal" @delete="confirmDelete" :hiddenbutton="true" />

                                        <Button v-if="!ticket.deleted_at" @click="openDeleteModal(ticket.id)" variant="outline"
                                            ><Trash class="text-red-400" /> Supprimer
                                        </Button>
                                        <!-- <Button variant="teal"><Save />Sauvegarder</Button> -->
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

/* Animation de fondu avec décalage */
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.3s ease;
    position: relative;
}

.fade-slide-enter-from {
    opacity: 0;
    transform: translateX(-10px);
}

.fade-slide-leave-to {
    opacity: 0;
    transform: translateX(10px);
}

/* Optionnel : évite le débordement pendant l'animation */
.fade-slide-enter-to,
.fade-slide-leave-from {
    transform: translateX(0);
    opacity: 1;
}
</style>
