<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'caisse',
        href: '/caisse',
    },
];

const ticket = ref([
    {
        id: 1,
        name: 'Décoration',
        price: 9,
        quantity: 2,
        tva: 21,
    },
    {
        id: 2,
        name: 'Fleurs',
        price: 3,
        quantity: 5,
        tva: 6,
    },
]);

const priceRow = ref<null | number>(null);
const quantityRow = ref(1);
const nameRow = ref('Fleurs');
const setMultiplicatator = ref(false);
const decimal = ref(false);
const centimal = ref(false);
const diff = ref(0);
const isInPaiyment = ref(false);

const calculator = (number: number) => {
    if (setMultiplicatator.value) {
        quantityRow.value = number;
        return;
    }

    if (!priceRow.value) {
        priceRow.value = number;
        return;
    }

    if (decimal.value) {
        priceRow.value = parseFloat((priceRow.value + number / 10).toFixed(2));
        decimal.value = false;
        centimal.value = true;
        return;
    }
    if (centimal.value) {
        priceRow.value = parseFloat((priceRow.value + number / 100).toFixed(2));
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
        ticket.value = [];
        return;
    }
    if (priceRow.value) {
        ticket.value.push({
            id: ticket.value.length + 1,
            name: nameRow.value,
            price: priceRow.value,
            quantity: quantityRow.value,
            tva: tva.value,
        });
        priceRow.value = null;
        quantityRow.value = 1;
        setMultiplicatator.value = false;
    }
};

const deleteCurrentRow = () => {
    priceRow.value = null;
    quantityRow.value = 1;
    setMultiplicatator.value = false;
    isInPaiyment.value = false;
};

const deleteRow = (id: number) => {
    ticket.value = ticket.value.filter((article) => article.id !== id);
};

const tva = computed(() => {
    if (nameRow.value == 'Fleurs') {
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
    diff.value = priceRow.value - total.value;
};

const sendTicket = () => {
    const form = {
        ticket: ticket.value,
    };

    router.post(route('ticket.store'), form);
};
</script>

<template>
    <Head title="Caisse" />

    <AppLayout :breadcrumbs="breadcrumbs" class="h-screen">
        <div class="flex h-full">
            <!-- LEFTSIDE -->
            <div class="flex basis-1/3 flex-col">
                <div class="flex h-20 items-center border-b p-3">
                    <h2>Ticket en cours</h2>
                </div>
                <div class="grow p-3">
                    <div v-for="article in ticket" :key="article.id" class="flex h-20 items-center justify-between border-b">
                        <Trash class="w-5 text-red-400" @click="deleteRow(article.id)" />
                        <p>
                            <span class="mr-3"
                                >{{ article.name }} <span class="text-xs italic">({{ article.tva }}%)</span> :</span
                            >
                            {{ article.price }}€ X {{ article.quantity }} =
                            <span class="font-bold">{{ article.price * article.quantity }} €</span>
                        </p>
                    </div>
                </div>
                <footer class="flex h-20 items-center justify-between border-t">
                    <p class="basis-1/2 text-center">Total : {{ total }}€</p>
                    <div
                        class="flex h-full basis-1/2 items-center justify-center bg-orange-200 text-center font-extrabold text-orange-800 dark:bg-orange-950 dark:text-orange-400"
                        @click="paid"
                    >
                        Payer
                    </div>
                </footer>
            </div>
            <!-- RightSide -->
            <div class="flex basis-2/3 flex-col border-l">
                <div class="flex h-20 w-full items-center border-b">
                    <div class="flex w-full items-center justify-between p-3">
                        <div class="flex items-center space-x-2">
                            <Switch id="invoice" />
                            <Label for="invoice">Facture</Label>
                        </div>

                        <Select defaultValue="flower" v-model="nameRow">
                            <SelectTrigger class="w-[180px]">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem value="Décoration"> Décoration </SelectItem>
                                    <SelectItem value="Fleurs"> Fleurs </SelectItem>
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
                <div class="flex grow flex-col justify-end">
                    <div class="h-full w-full space-y-5 bg-slate-50 p-10 text-end text-6xl">
                        <p v-if="priceRow">
                            {{ priceRow }}€ <span v-if="setMultiplicatator">X</span> <span v-if="quantityRow != 1">{{ quantityRow }}</span>
                        </p>
                        <hr v-if="setMultiplicatator || isInPaiyment" />
                        <p v-if="setMultiplicatator">= {{ priceRow * quantityRow }}€</p>
                        <p v-if="isInPaiyment">à rendre : {{ diff }}€</p>
                    </div>
                    <div class="grid grid-cols-3 border-b border-t">
                        <div class="flex h-20 items-center justify-center border-b bg-sidebar duration-300 hover:bg-slate-100" @click="calculator(9)">
                            9
                        </div>
                        <div
                            class="flex h-20 items-center justify-center border-x border-b bg-sidebar duration-300 hover:bg-slate-100"
                            @click="calculator(8)"
                        >
                            8
                        </div>
                        <div class="flex h-20 items-center justify-center border-b bg-sidebar duration-300 hover:bg-slate-100" @click="calculator(7)">
                            7
                        </div>
                        <div class="flex h-20 items-center justify-center border-b bg-sidebar duration-300 hover:bg-slate-100" @click="calculator(6)">
                            6
                        </div>
                        <div
                            class="flex h-20 items-center justify-center border-x border-b bg-sidebar duration-300 hover:bg-slate-100"
                            @click="calculator(5)"
                        >
                            5
                        </div>
                        <div class="flex h-20 items-center justify-center border-b bg-sidebar duration-300 hover:bg-slate-100" @click="calculator(4)">
                            4
                        </div>
                        <div class="flex h-20 items-center justify-center border-b bg-sidebar duration-300 hover:bg-slate-100" @click="calculator(3)">
                            3
                        </div>
                        <div
                            class="flex h-20 items-center justify-center border-x border-b bg-sidebar duration-300 hover:bg-slate-100"
                            @click="calculator(2)"
                        >
                            2
                        </div>
                        <div class="flex h-20 items-center justify-center border-b bg-sidebar duration-300 hover:bg-slate-100" @click="calculator(1)">
                            1
                        </div>
                        <div
                            class="flex h-20 items-center justify-center bg-sidebar duration-300 hover:bg-slate-100"
                            @click="setMultiplicatator = true"
                        >
                            X
                        </div>
                        <div class="flex h-20 items-center justify-center border-x bg-sidebar duration-300 hover:bg-slate-100" @click="calculator(0)">
                            0
                        </div>
                        <div class="flex h-20 items-center justify-center bg-sidebar duration-300 hover:bg-slate-100" @click="decimal = true">,</div>
                    </div>
                </div>
                <footer class="flex h-20 items-center justify-between border-t">
                    <div
                        class="flex h-full basis-1/3 items-center justify-center bg-red-200 font-extrabold text-red-800 dark:bg-red-950 dark:text-red-400"
                        @click="deleteCurrentRow"
                    >
                        Supprimer
                    </div>
                    <div
                        class="flex h-full basis-2/3 items-center justify-center border-l bg-blue-200 font-extrabold text-blue-800 dark:bg-blue-900 dark:text-teal-400"
                        @click="validation"
                    >
                        Valider
                    </div>
                </footer>
            </div>
        </div>
    </AppLayout>
</template>
