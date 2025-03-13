<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import Label from '@/components/ui/label/Label.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { Barcode, Receipt } from 'lucide-vue-next';
import { getHour } from '../lib/utils';

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
}>();

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
    return totalPrice;
};

console.log(props.tickets);
</script>
<template>
    <AppLayout>
        <Receipt class="fixed mt-10 h-screen w-full text-stone-100" />
        <div class="container z-10 mx-auto p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold">Ventes</h1>
                <div class="flex items-center gap-4">
                    <p>Jour</p>
                    <p>Semaine</p>
                    <p>Mois</p>
                    <p>Année</p>
                </div>
            </div>
            <div class="flex items-center justify-between space-x-2">
                <div class="flex items-center space-x-2">
                    <Switch id="invoice" />
                    <Label for="invoice">Avec facture</Label>
                </div>
                <input type="date" class="rounded-md border border-gray-300 p-2" />
            </div>
            <div v-if="tickets?.data?.length">
                <Card
                    class="m-2 w-full bg-gradient-to-br from-white to-gray-50/80 p-2 transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg"
                    v-for="ticket in tickets.data"
                    :key="ticket.id"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="flex gap-2"><Barcode /> N° {{ ticket.id }}</p>
                        </div>
                        <div>
                            <p>{{ getTicketPrice(ticket) }}€</p>
                        </div>
                        <div>
                            <p>à {{ getHour(ticket.created_at) }}</p>
                        </div>
                    </div>
                </Card>
            </div>
            <div v-else class="mt-4 text-center text-gray-500">Aucun ticket disponible</div>
            <!-- Pagination -->
            <div v-if="tickets?.data?.length" class="mt-4">
                <nav class="flex items-center justify-between">
                    <Button variant="outline" :disabled="!tickets.prev_page_url" @click="visit(tickets.prev_page_url)"> Précédent </Button>

                    <span class="text-sm text-gray-700"> Page {{ tickets.current_page }} sur {{ tickets.last_page }} </span>

                    <Button variant="outline" :disabled="!tickets.next_page_url" @click="visit(tickets.next_page_url)"> Suivant </Button>
                </nav>
            </div>
        </div>
    </AppLayout>
</template>
