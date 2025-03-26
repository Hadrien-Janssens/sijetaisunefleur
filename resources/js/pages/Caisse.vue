<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import AppLayout from '@/layouts/AppLayout.vue';
import { Client, type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Building, Hash, Mail, MapPin, Phone, Ticket, Trash, UserCheck, UserRoundX } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import SearchClientModal from './SearchClientModal.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'caisse',
        href: '/caisse',
    },
];

const props = defineProps<{
    clients: Array<Client>;
    categories: Array<{
        id: number;
        name: string;
    }>;
}>();

const ticket = ref([]);

const priceRow = ref<null | number>(null);
const quantityRow = ref<number | null>(null);
const category_idRow = ref(2);
const setMultiplicatator = ref(false);
const decimal = ref(false);
const centimal = ref(false);
const diff = ref(0);
const isInPaiyment = ref(false);
const selectedClient = ref<null | Client>(null);
const selectedEmail = ref('');

const getNameCategory = (id: number) => {
    return props.categories.find((category) => category.id === id)?.name;
};

const calculator = (number: number) => {
    if (setMultiplicatator.value) {
        if (quantityRow.value) {
            quantityRow.value = quantityRow.value * 10 + number;
        } else {
            quantityRow.value = number;
        }
        return;
    }

    if (!priceRow.value) {
        priceRow.value = number;
        return;
    }

    if (decimal.value) {
        priceRow.value = priceRow.value + number / 10;
        decimal.value = false;
        centimal.value = true;
        return;
    }
    if (centimal.value) {
        priceRow.value = priceRow.value + number / 100;
        centimal.value = false;
        return;
    }
    if (priceRow.value) {
        priceRow.value = priceRow.value * 10 + number;
    }
};

const validation = () => {
    if (isInPaiyment.value) {
        deleteCurrentRow();
        isInPaiyment.value = false;

        sendTicket();
        selectedClient.value = null;
        ticket.value = [];
        return;
    }
    if (priceRow.value) {
        ticket.value.push({
            id: ticket.value.length + 1,
            category_id: category_idRow.value,
            price: priceRow.value,
            quantity: quantityRow.value ? quantityRow.value : 1,
            tva: tva.value,
        });
        priceRow.value = null;
        quantityRow.value = null;
        setMultiplicatator.value = false;
    }
};

const deleteCurrentRow = () => {
    priceRow.value = null;
    quantityRow.value = null;
    setMultiplicatator.value = false;
    isInPaiyment.value = false;
    decimal.value = false;
    centimal.value = false;
};

const deleteRow = (id: number) => {
    ticket.value = ticket.value.filter((article) => article.id !== id);
};

const tva = computed(() => {
    if (category_idRow.value == 2) {
        return 6;
    } else {
        return 21;
    }
});

const total = computed(() => {
    return ticket.value.reduce((acc, article) => acc + article.price * article.quantity, 0).toFixed(2);
});

const paid = () => {
    isInPaiyment.value = true;
    diff.value = (priceRow.value - total.value).toFixed(2);
};

const sendTicket = () => {
    const form = {
        ticket: ticket.value,
        client_id: selectedClient.value?.id || null,
        email: selectedEmail.value || null,
        with_tva: selectedClient.value?.tva_number ? true : false,
    };

    router.post(route('ticket.store'), form);
};

const isModalOpen = ref(false);

const setSelectedClient = (client: any) => {
    if (typeof client === 'string') {
        selectedEmail.value = client;
        isModalOpen.value = false;
    } else {
        selectedClient.value = client;
        isModalOpen.value = false;
    }
};

const hasSelectedClient = computed({
    get() {
        return selectedClient.value !== null || selectedEmail.value !== '';
    },
    set(value) {
        if (!value) {
            selectedClient.value = null;
            selectedEmail.value = '';
        } else if (selectedClient.value === null && selectedEmail.value === '') {
            isModalOpen.value = true;
        }
    },
});
</script>

<template>
    <Head title="Caisse" />

    <AppLayout :breadcrumbs="breadcrumbs" class="h-screen">
        <div class="flex h-full">
            <!-- LEFTSIDE -->
            <div class="flex flex-col basis-1/3">
                <div class="flex flex-col gap-2 p-3 border-b">
                    <h2 class="flex items-center gap-2"><Ticket class="text-primary-color" />Ticket en cours</h2>
                    <Link :href="route('client.edit', selectedClient.id)" v-if="selectedClient">
                        <p class="flex items-center gap-2">
                            <UserCheck class="text-blue-500" /> {{ selectedClient.lastname }} {{ selectedClient.firstname }}
                        </p>

                        <p class="flex items-center gap-2"><Mail class="text-blue-500" /> {{ selectedClient.email }}</p>
                        <p class="flex items-center gap-2"><Phone class="text-blue-500" /> {{ selectedClient.phone }}</p>
                        <p class="flex items-center gap-2"><Building class="text-blue-500" /> {{ selectedClient.company }}</p>
                        <p class="flex items-center gap-2"><Hash class="text-blue-500" /> {{ selectedClient.tva_number }}</p>
                        <p class="flex items-center gap-2">
                            <MapPin class="text-blue-500" /> {{ selectedClient.address }} {{ selectedClient.city }} {{ selectedClient.country }}
                        </p>
                    </Link>
                    <p v-else-if="selectedEmail" class="flex items-center gap-2"><Mail class="text-blue-500" /> {{ selectedEmail }}</p>
                    <p v-else class="flex items-center gap-2"><UserRoundX class="text-orange-500" /> Pas de client</p>
                </div>
                <div class="p-3 overflow-scroll overflow-y-auto grow">
                    <p v-if="ticket.length === 0" class="mt-20 text-xl font-extrabold text-center text-gray-400">Aucun achat sur ce ticket</p>
                    <TransitionGroup tag="ul" name="v">
                        <li v-for="article in ticket" :key="article.id" class="flex items-center justify-between h-20 border-b">
                            <Trash class="w-5 text-red-400" @click="deleteRow(article.id)" />
                            <p>
                                <span class="mr-3"
                                    >{{ getNameCategory(article.category_id) }} <span class="text-xs italic">({{ article.tva }}%)</span> :</span
                                >
                                {{ article.price.toFixed(2) }}€ X {{ article.quantity }} =
                                <span class="font-bold">{{ (article.price * article.quantity).toFixed(2) }} €</span>
                            </p>
                        </li>
                    </TransitionGroup>
                </div>
                <footer class="flex items-center justify-between h-20 border-t">
                    <p class="text-center basis-1/2">
                        Total : <span class="font-bold">{{ total }}€</span>
                    </p>
                    <div
                        class="flex items-center justify-center h-full font-extrabold text-center text-blue-100 bg-blue-500 basis-1/2 dark:bg-orange-950 dark:text-orange-400"
                        @click="paid"
                    >
                        Payer
                    </div>
                </footer>
            </div>
            <!-- RightSide -->
            <div class="flex flex-col border-l basis-2/3">
                <div class="flex items-center w-full h-20 border-b">
                    <div class="flex items-center justify-between w-full p-3">
                        <SearchClientModal
                            :show="isModalOpen"
                            :clients="clients"
                            @close="isModalOpen = false"
                            @validation="setSelectedClient($event)"
                        />

                        <div class="flex items-center space-x-2">
                            <Switch id="invoice" v-model="hasSelectedClient" />
                            <Label for="invoice">Facture</Label>
                        </div>

                        <Select defaultValue="Fleurs" v-model="category_idRow">
                            <SelectTrigger class="w-[180px]">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="category in categories" :value="category.id" :key="category.id">
                                        {{ category.name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>

                        <div class="flex items-center space-x-2">
                            <Switch id="r1" :model-value="tva == 6" @click="tva = 6" />
                            <Label for="r1">6%</Label>
                            <Switch id="r2" :model-value="tva == 21" @click="tva = 21" />
                            <Label for="r2">21%</Label>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-end grow">
                    <div class="w-full h-full pt-3 pr-3 text-4xl bg-slate-50 text-end">
                        <p v-if="priceRow" class="text-4xl">
                            {{ priceRow.toFixed(2) }}€ <span class="text-4xl" v-if="setMultiplicatator">X</span>
                            <span v-if="quantityRow" class="text-4xl">{{ quantityRow }}</span>
                        </p>
                        <!-- <p v-if="setMultiplicatator && quantityRow">= {{ (priceRow * quantityRow).toFixed(2) }}€</p> -->
                        <p v-if="isInPaiyment">à rendre : {{ diff }}€</p>
                    </div>
                    <div class="grid grid-cols-3 font-extrabold border-t border-b">
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b bg-sidebar hover:bg-slate-100"
                            @click="calculator(9)"
                        >
                            9
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b border-x bg-sidebar hover:bg-slate-100"
                            @click="calculator(8)"
                        >
                            8
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b bg-sidebar hover:bg-slate-100"
                            @click="calculator(7)"
                        >
                            7
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b bg-sidebar hover:bg-slate-100"
                            @click="calculator(6)"
                        >
                            6
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b border-x bg-sidebar hover:bg-slate-100"
                            @click="calculator(5)"
                        >
                            5
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b bg-sidebar hover:bg-slate-100"
                            @click="calculator(4)"
                        >
                            4
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b bg-sidebar hover:bg-slate-100"
                            @click="calculator(3)"
                        >
                            3
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b border-x bg-sidebar hover:bg-slate-100"
                            @click="calculator(2)"
                        >
                            2
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b bg-sidebar hover:bg-slate-100"
                            @click="calculator(1)"
                        >
                            1
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 bg-sidebar hover:bg-slate-100"
                            @click="setMultiplicatator = true"
                        >
                            X
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-x bg-sidebar hover:bg-slate-100"
                            @click="calculator(0)"
                        >
                            0
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 bg-sidebar hover:bg-slate-100"
                            @click="
                                () => {
                                    if (priceRow) {
                                        decimal = true;
                                    }
                                }
                            "
                        >
                            ,
                        </div>
                    </div>
                </div>
                <footer class="flex items-center justify-between h-20 border-t">
                    <div
                        class="flex items-center justify-center h-full font-extrabold text-red-100 bg-red-500 basis-1/3 dark:bg-red-950 dark:text-red-400"
                        @click="deleteCurrentRow"
                    >
                        Supprimer
                    </div>
                    <div
                        class="flex items-center justify-center h-full font-extrabold text-blue-100 border-l bg-primary-color basis-2/3 dark:bg-blue-900 dark:text-teal-400"
                        @click="validation"
                    >
                        Valider
                    </div>
                </footer>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.v-move,
.v-enter-active,
.v-leave-active {
    transition: all 0.3s ease-out;
    opacity: 1;
}
.v-leave-active {
    position: absolute;
}

.v-enter-from,
.v-leave-to {
    transform: translateX(50px);
    opacity: 0;
}
</style>
