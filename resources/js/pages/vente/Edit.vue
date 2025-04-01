<script setup lang="ts">
import DeleteConfirmationModal from '@/components/DeleteConfirmationModal.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { Client, Row, type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, ListPlus, ShoppingBag, Ticket, Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import ChangeClientModal from './ChangeClientModal.vue';
import CreateRowModal from './CreateRowModal.vue';

const props = defineProps<{
    ticket: {
        id: number;
        reference: number;
        is_sent: boolean;
        is_paid: boolean;
        with_tva: boolean;
        comment: string;
        client: Client;
        created_at: string;
        remise: number;
        deleted_at: string | null;
        ticket_rows: {
            id: number;
            price: number;
            quantity: number;
            category_id: number;
            reduction: number;
        }[];
    };
    clients: Client[];
    category: {
        id: number;
        name: string;
    }[];
}>();

const form = useForm({
    reference: props.ticket.reference,
    is_sent: props.ticket.is_sent,
    is_paid: props.ticket.is_paid,
    with_tva: props.ticket.with_tva,
    comment: props.ticket.comment,
    date: new Date(props.ticket.created_at).toISOString().split('T')[0],
    client: props.ticket.client?.id || null,
    ticket_rows: props.ticket.ticket_rows,
    remise: props.ticket.remise,
});

console.log(props.ticket.ticket_rows);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'tickets',
        href: route('vente.index'),
    },
    {
        title: 'Modification',
        href: '/edit',
    },
];

const submit = () => {
    form.put(route('ticket.update', props.ticket.id));
};

const goBack = () => {
    window.history.back();
};

const showDeleteModal = ref(false);

const confirmDelete = () => {
    form.delete(route('ticket.destroy', props.ticket.id));
    showDeleteModal.value = false;
};

const forceDelete = () => {
    form.delete(route('ticket.forceDelete', props.ticket.id));
    showDeleteModal.value = false;
};

const isModalOpen = ref(false);

const setSelectedClient = (client: Client) => {
    form.client = client.id;
    isModalOpen.value = false;
};

const clientName = computed(() => {
    const client = form.client ? props.clients.find((c) => c.id === form.client) : null;

    return client ? `${client.firstname} ${client.lastname}` : 'Aucun client défini';
});

// const getTicketPrice = (ticket: any) => {
//     let totalPrice = 0;

//     ticket.ticket_rows.forEach((row: Row) => {
//         totalPrice += row.price * row.quantity;
//     });
//     return totalPrice.toFixed(2);
// };

const getTicketPrice = (ticket: any) => {
    let totalPrice = 0;
    ticket.ticket_rows.forEach((row: Row) => {
        totalPrice += (row.reduction === 0 ? row.price : row.price - (row.price * row.reduction) / 100) * row.quantity;
    });
    return totalPrice.toFixed(2);
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
    return totalPriceWithRemise.toFixed(2);
};

const openCreateRowModal = ref(false);

const createRow = (rowData: any) => {
    form.ticket_rows.push({
        id: Date.now(), // Temporary ID for new rows
        category_id: rowData.category_id,
        price: rowData.price,
        quantity: rowData.quantity,
        reduction: rowData.reduction,
    });
    openCreateRowModal.value = false;
};

const deleteRow = (index: number) => {
    form.ticket_rows.splice(index, 1);
};
</script>

<template>
    <Head title="Modification d'un ticket" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container z-10 p-6 mx-auto">
            <Button @click="goBack" variant="teal" class="mb-3"><ArrowLeft /> Retour</Button>
            <Card class="w-full p-5 bg-gradient-to-br from-white to-gray-50/80">
                <form @submit.prevent="submit">
                    <CardHeader class="w-full">
                        <CardTitle class="flex items-center w-full gap-2">
                            <div class="flex items-center justify-between w-full gap-3 mb-4">
                                <div class="flex items-center gap-3">
                                    <Ticket class="w-10 h-8 text-primary-color" />
                                    <p class="text-lg">Modification ticket du</p>
                                    <Input v-model="form.date" type="date" class="max-w-36" />
                                </div>
                                <div class="flex items-center gap-3">
                                    <Switch v-model="form.is_paid" />
                                    <label class="font-semibold shrink-0">Facture payée</label>
                                </div>
                            </div>
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-5">
                        <div class="flex flex-col justify-between gap-3 mb-4 lg:flex-row lg:items-center">
                            <div class="flex items-center gap-4 basis-1/2">
                                <div class="flex items-center gap-4">
                                    <label class="font-semibold">Référence</label>
                                    <Input v-model="form.reference" type="text" placeholder="Référence" class="max-w-36" />
                                </div>
                                <div class="flex items-center gap-3">
                                    <Switch v-model="form.with_tva" />
                                    <label class="font-semibold shrink-0">Avec TVA</label>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <label class="font-semibold" for="client">Client</label>
                                <div
                                    class="px-2 py-1 font-bold duration-300 bg-gray-100 border rounded-sm shadow-sm hover:cursor-pointer hover:bg-gray-200"
                                    @click="isModalOpen = true"
                                >
                                    <p :class="{ 'text-gray-500': !form.client }">
                                        {{ clientName }}
                                    </p>
                                </div>
                                <ChangeClientModal
                                    :show="isModalOpen"
                                    :clients="clients"
                                    @close="isModalOpen = false"
                                    @validation="setSelectedClient"
                                />
                            </div>
                        </div>
                        <div class="flex flex-col items-start gap-2">
                            <label for="comment" class="font-semibold">Commentaire</label>
                            <Textarea id="comment" v-model="form.comment" placeholder="Commentaire" class="flex-1" />
                        </div>

                        <div class="flex gap-2 pb-2 mb-2 border-b border-gray-200">
                            <p class="font-semibold">Détails des articles</p>

                            <ListPlus class="text-blue-500 hover:cursor-pointer" @click="openCreateRowModal = true" />
                            <CreateRowModal
                                :show="openCreateRowModal"
                                @close="openCreateRowModal = false"
                                @create="createRow"
                                :categories="category"
                            />
                        </div>

                        <div class="space-y-2">
                            <div
                                v-for="(row, index) in form.ticket_rows"
                                :key="row.id"
                                class="flex items-center justify-between p-2 bg-white rounded-md"
                            >
                                <div class="flex items-center gap-2">
                                    <ShoppingBag class="w-4 h-4 text-primary-color" />
                                    <Select v-model="row.category_id" class="w-48">
                                        <SelectTrigger class="w-48">
                                            <SelectValue :placeholder="row.category?.name || `Article #${index + 1}`" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="(category, i) in props.category" :key="i" :value="category.id">
                                                {{ category.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <span class="flex items-center gap-3 text-sm text-gray-500">
                                        <Input type="text" class="w-28" v-model="row.price" />
                                        <p class="shrink-0">€ x</p>
                                        <Input type="number" class="w-28" v-model="row.quantity" :min="1" />
                                    </span>
                                </div>
                                <span class="font-medium"
                                    >{{
                                        ((row.reduction === 0 ? row.price : row.price - (row.price * row.reduction) / 100) * row.quantity).toFixed(2)
                                    }}€</span
                                >
                                <div class="flex items-center gap-1">
                                    <span>Réduction :</span>
                                    <Input
                                        type="number"
                                        v-model="row.reduction"
                                        placeholder="0"
                                        class="w-16"
                                        :disabled="form.ticket_rows.length === 0"
                                    />%
                                </div>
                                <Trash class="text-red-500 hover:cursor-pointer" @click="deleteRow(index)" />
                            </div>
                        </div>

                        <div class="flex items-end justify-between pt-4 mt-4 border-t border-gray-200">
                            <div class="flex items-center gap-3">
                                <p class="font-semibold">Remise</p>
                                <Input type="number" v-model="form.remise" placeholder="0" class="w-28" :disabled="form.ticket_rows.length === 0" />%
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Total</p>
                                <p class="text-lg font-bold" v-if="ticket.remise !== 0">{{ getTicketPrice(form) }}€</p>
                                <p class="text-lg font-bold" v-else>{{ getTicketPriceWithRemise(form) }}€</p>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter class="flex items-center justify-between gap-3 m-3" :class="{ 'justify-end': !ticket.deleted_at }">
                        <Link v-if="ticket.deleted_at" :href="route('ticket.restore', ticket.id)">
                            <Button variant="outline">Restaurer</Button>
                        </Link>
                        <div class="flex items-center gap-3">
                            <DeleteConfirmationModal
                                v-if="ticket.deleted_at"
                                v-model:open="showDeleteModal"
                                @delete="forceDelete"
                                text="Supprimer définitivement"
                            />
                            <DeleteConfirmationModal v-else v-model:open="showDeleteModal" @delete="confirmDelete" />

                            <div class="flex justify-end">
                                <Button variant="teal" type="submit" :disabled="form.processing">Sauvegarder</Button>
                            </div>
                        </div>
                    </CardFooter>
                </form>
            </Card>
        </div>
    </AppLayout>
</template>
