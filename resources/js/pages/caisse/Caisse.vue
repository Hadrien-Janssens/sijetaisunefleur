<script setup lang="ts">
import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/components/ui/hover-card';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Client } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Building, Hash, Mail, MapPin, Phone, Ticket, Trash, UserCheck, UserRoundX } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import CommentModal from './CommentModal.vue';
import SearchClientModal from './SearchClientModal.vue';
import TicketReductionModal from './TicketReductionModal.vue';

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
        tva: number;
    }>;
}>();

interface Ticket {
    id: number;
    category_id: number;
    price: number;
    quantity: number;
    tva: number;
    reduction: number;
}

const ticket = ref<Array<Ticket>>([]);

const priceRow = ref<null | number>(null);
const quantityRow = ref<number | null>(null);

const setMultiplicatator = ref(false);
const decimal = ref(false);
const centimal = ref(false);
const diff = ref(0);
const isInPaiyment = ref(false);
const selectedClient = ref<null | Client>(null);
const selectedEmail = ref('');
const selectedTva = ref(false);
const TVANumber = ref('');
const isTicketReductionModalOpen = ref(false);
const isArticleReductionModalOpen = ref(false);
const isCommentModalOpen = ref(false);
const comment = ref('');
// c'est la reduction exemaple 10%
const totalReduction = ref(0);

const reductionRow = ref(0);
const credit = ref(false);

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
        return;
    }
    if (priceRow.value) {
        ticket.value.push({
            id: ticket.value.length + 1,
            category_id: selectedCategoryId.value,
            price: priceRow.value,
            reduction: reductionRow.value,
            quantity: quantityRow.value ? quantityRow.value : 1,
            tva: tva.value,
        });
        priceRow.value = null;
        reductionRow.value = 0;
        quantityRow.value = null;
        setMultiplicatator.value = false;
    }
};

const deleteCurrentRow = () => {
    priceRow.value = null;
    reductionRow.value = 0;
    quantityRow.value = null;
    setMultiplicatator.value = false;
    isInPaiyment.value = false;
    decimal.value = false;
    centimal.value = false;
};

const deleteRow = (id: number) => {
    ticket.value = ticket.value.filter((article) => article.id !== id);
};

const total = computed(() => {
    if (ticket.value.length === 0) {
        return 0;
    }

    return ticket.value.reduce((acc, row) => {
        const price = row.reduction !== 0 ? row.price - (row.price * row.reduction) / 100 : row.price;
        return acc + price * row.quantity;
    }, 0);
});

const totalWithReduction = computed(() => {
    if (ticket.value.length === 0) {
        return 0;
    }

    return ticket.value.reduce((acc, row) => {
        const price =
            row.reduction !== 0
                ? row.price - (row.price * row.reduction) / 100
                : totalReduction.value !== 0
                  ? row.price - (row.price * totalReduction.value) / 100
                  : row.price;
        return acc + price * row.quantity;
    }, 0);
});

const paid = () => {
    if (priceRow.value === null) {
        priceRow.value = 0;
    }
    isInPaiyment.value = true;

    diff.value = priceRow.value - (totalReduction.value !== 0 ? totalWithReduction.value : total.value);
};

const sendTicket = () => {
    const form = {
        ticket: ticket.value,
        client_id: selectedClient.value?.id || null,
        email: selectedEmail.value || null,
        with_tva: selectedTva.value,
        remise: totalReduction.value,
        tva_number: TVANumber.value,
        is_paid: !credit.value,
        comment: comment.value || null,
    };
    selectedClient.value = null;
    selectedEmail.value = '';
    selectedTva.value = false;
    ticket.value = [];
    TVANumber.value = '';
    totalReduction.value = 0;
    comment.value = '';
    priceRow.value = null;
    quantityRow.value = null;
    setMultiplicatator.value = false;
    decimal.value = false;
    centimal.value = false;
    isInPaiyment.value = false;
    credit.value = false;

    router.post(route('ticket.store'), form);
};

const isModalOpen = ref(false);

const setSelectedClient = (client: any, tva: boolean, tvaNumber = '') => {
    console.log(client, tva);

    if (typeof client === 'string') {
        selectedEmail.value = client;
        isModalOpen.value = false;
    } else {
        selectedClient.value = client;
        isModalOpen.value = false;
    }
    selectedTva.value = tva;
    TVANumber.value = tvaNumber;
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

const reductionTicketValidation = (value: number) => {
    totalReduction.value = value;
    isTicketReductionModalOpen.value = false;
};
const reductionRowValidation = (value: number) => {
    reductionRow.value = value;
    isArticleReductionModalOpen.value = false;
};
const commentValidation = (value: string) => {
    comment.value = value;
    isCommentModalOpen.value = false;
};

const tva = ref(6);
const filterCategories = computed(() => {
    return props.categories.filter((category) => category.tva === tva.value);
});

const selectedCategoryId = ref(filterCategories.value[0].id);

const toggleTva = (value: number) => {
    tva.value = value;
};

watch(tva, (newVal) => {
    const newCategories = props.categories.filter((c) => c.tva === newVal);
    if (newCategories.length > 0) {
        selectedCategoryId.value = newCategories[0].id;
    }
});
</script>

<template>
    <Head title="Caisse" />

    <AppLayout :breadcrumbs="breadcrumbs" class="h-screen">
        <div class="flex h-full">
            <!-- LEFTSIDE -->
            <div class="flex flex-col basis-1/3">
                <div class="flex flex-col h-20 gap-2 p-3 border-b">
                    <div class="flex items-center justify-between">
                        <h2 class="flex items-center gap-2"><Ticket class="text-primary-color" />Ticket en cours</h2>
                        <CommentModal
                            v-if="selectedClient || selectedEmail"
                            :open="isCommentModalOpen"
                            @close="isCommentModalOpen = false"
                            @open="isCommentModalOpen = true"
                            @comment_validation="commentValidation"
                        />
                    </div>
                    <HoverCard v-if="selectedClient">
                        <HoverCardTrigger as-child>
                            <Link :href="route('client.edit', selectedClient.id)">
                                <p class="flex items-center gap-2">
                                    <UserCheck class="text-blue-500" /> {{ selectedClient.lastname }} {{ selectedClient.firstname }}
                                </p>
                            </Link>
                        </HoverCardTrigger>
                        <HoverCardContent class="space-y-3 w-80">
                            <p class="flex items-center gap-2">
                                <UserCheck class="text-blue-500" /> {{ selectedClient.lastname }} {{ selectedClient.firstname }}
                            </p>

                            <p class="flex items-center gap-2"><Mail class="text-blue-500 shrink-0" /> {{ selectedClient.email }}</p>
                            <p class="flex items-center gap-2"><Phone class="text-blue-500 shrink-0" /> {{ selectedClient.phone }}</p>
                            <p class="flex items-center gap-2"><Building class="text-blue-500 shrink-0" /> {{ selectedClient.company }}</p>
                            <p class="flex items-center gap-2"><Hash class="text-blue-500 shrink-0" /> {{ selectedClient.tva_number }}</p>
                            <p class="flex items-center gap-2">
                                <MapPin class="text-blue-500 shrink-0" /> {{ selectedClient.address }} {{ selectedClient.city }}
                                {{ selectedClient.country }}
                            </p>
                            <div v-if="comment">
                                <p class="font-bold">Commentaire :</p>
                                <p>{{ comment }}</p>
                            </div>
                        </HoverCardContent>
                    </HoverCard>

                    <p v-else-if="selectedEmail" class="flex items-center gap-2"><Mail class="text-blue-500" /> {{ selectedEmail }}</p>
                    <p v-else class="flex items-center gap-2"><UserRoundX class="text-orange-500" /> Pas de client</p>
                </div>
                <div class="overflow-scroll overflow-y-auto grow">
                    <p v-if="ticket.length === 0" class="mt-20 text-xl font-extrabold text-center text-gray-400">Aucun achat sur ce ticket</p>
                    <TransitionGroup tag="ul" name="v">
                        <li v-for="article in ticket" :key="article.id" class="flex flex-col p-3 border-b min-h-20">
                            <div class="flex items-center justify-between">
                                <Trash class="w-5 text-red-400" @click="deleteRow(article.id)" />
                                <p>
                                    <span class="mr-3"
                                        >{{ getNameCategory(article.category_id) }} <span class="text-xs italic">({{ article.tva }}%)</span> :</span
                                    >
                                    {{ article.price.toFixed(2) }}€ X {{ article.quantity }} =
                                    <span class="font-bold">{{ (article.price * article.quantity).toFixed(2) }} €</span>
                                </p>
                            </div>
                            <p v-if="article.reduction !== 0" class="self-end">
                                <span class="rounded-full border border-red-500 bg-red-100 p-0.5 px-1 text-xs text-red-500">
                                    - {{ article.reduction }}%</span
                                >
                                <span class="ml-3 font-bold"
                                    >{{
                                        (article.price * article.quantity - (article.price * article.quantity * article.reduction) / 100).toFixed(2)
                                    }}
                                    €</span
                                >
                            </p>
                        </li>
                    </TransitionGroup>
                </div>
                <footer class="flex items-center justify-between h-20 p-1 border-t">
                    <div class="text-center basis-1/2">
                        <p>
                            Total :
                            <span class="font-bold" :class="totalReduction !== 0 ? 'text-red-500 line-through' : ''">{{ total.toFixed(2) }}€ </span
                            ><span
                                class="relative -top-3 rounded-full border border-red-500 bg-red-100 p-0.5 px-1 text-xs text-red-500"
                                v-if="totalReduction !== 0"
                                >- {{ totalReduction }}%</span
                            >
                        </p>
                        <p v-if="totalReduction !== 0" class="font-bold">{{ totalWithReduction.toFixed(2) }}€</p>
                    </div>

                    <TicketReductionModal
                        title="Séléctionne la réduction"
                        trigger="Réduction ticket"
                        description="Cette réduction sera appliquée sur l'ensemble du ticket"
                        :open="isTicketReductionModalOpen"
                        @close="isTicketReductionModalOpen = false"
                        @open="isTicketReductionModalOpen = true"
                        @validation="reductionTicketValidation"
                    />
                </footer>
            </div>
            <!-- RightSide -->
            <div class="flex flex-col border-l basis-2/3">
                <div class="flex items-center w-full h-20 border-b">
                    <div class="flex items-center justify-between w-full p-3">
                        <SearchClientModal :show="isModalOpen" :clients="clients" @close="isModalOpen = false" @validation="setSelectedClient" />

                        <div class="flex items-center space-x-2">
                            <Switch id="invoice" v-model="hasSelectedClient" />
                            <Label for="invoice">Facture</Label>
                        </div>
                        <div class="flex items-center space-x-2">
                            <Switch id="credit" v-model="credit" />
                            <Label for="credit">Crédit</Label>
                        </div>

                        <Select v-model="selectedCategoryId">
                            <SelectTrigger class="w-[180px]">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="category in filterCategories" :value="category.id" :key="category.id">
                                        {{ category.name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>

                        <div class="flex items-center space-x-2">
                            <Switch id="r1" @click="toggleTva(6)" :model-value="tva == 6" />
                            <Label for="r1">6%</Label>
                            <Switch id="r2" @click="toggleTva(21)" :model-value="tva == 21" />
                            <Label for="r2">21%</Label>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-end grow">
                    <div class="flex justify-end w-full h-full gap-10 px-5 pt-3 text-4xl bg-slate-50 text-end">
                        <p v-if="priceRow" class="text-4xl">
                            {{ priceRow.toFixed(2) }}€ <span class="text-4xl" v-if="setMultiplicatator">X</span>
                            <span v-if="quantityRow" class="text-4xl">{{ quantityRow }}</span>
                        </p>
                        <span v-if="reductionRow !== 0" class="self-start text-4xl text-red-500">-{{ reductionRow }}%</span>

                        <!-- <p v-if="setMultiplicatator && quantityRow">= {{ (priceRow * quantityRow).toFixed(2) }}€</p> -->
                        <p v-if="isInPaiyment">à rendre : {{ diff.toFixed(2) }}€</p>
                    </div>
                    <div class="grid grid-cols-4 gap-1 p-1 font-extrabold border-t border-b">
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b rounded-lg bg-sidebar hover:bg-slate-100"
                            @click="calculator(9)"
                        >
                            9
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b rounded-lg border-x bg-sidebar hover:bg-slate-100"
                            @click="calculator(8)"
                        >
                            8
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b rounded-lg bg-sidebar hover:bg-slate-100"
                            @click="calculator(7)"
                        >
                            7
                        </div>
                        <div
                            class="flex items-center justify-center h-full row-span-2 font-extrabold text-green-100 border-l rounded-lg bg-primary-color basis-2/3"
                            @click="validation"
                        >
                            Valider
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b rounded-lg bg-sidebar hover:bg-slate-100"
                            @click="calculator(6)"
                        >
                            6
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b rounded-lg border-x bg-sidebar hover:bg-slate-100"
                            @click="calculator(5)"
                        >
                            5
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b rounded-lg bg-sidebar hover:bg-slate-100"
                            @click="calculator(4)"
                        >
                            4
                        </div>

                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b rounded-lg bg-sidebar hover:bg-slate-100"
                            @click="calculator(3)"
                        >
                            3
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b rounded-lg border-x bg-sidebar hover:bg-slate-100"
                            @click="calculator(2)"
                        >
                            2
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 border-b rounded-lg bg-sidebar hover:bg-slate-100"
                            @click="calculator(1)"
                        >
                            1
                        </div>

                        <TicketReductionModal
                            title="Séléctionne la réduction"
                            trigger="Réduction article"
                            description="Cette réduction sera appliquée sur l'article"
                            :open="isArticleReductionModalOpen"
                            @close="isArticleReductionModalOpen = false"
                            @open="isArticleReductionModalOpen = true"
                            @validation="reductionRowValidation"
                        />

                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 rounded-lg bg-sidebar hover:bg-slate-100"
                            @click="setMultiplicatator = true"
                        >
                            X
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 rounded-lg border-x bg-sidebar hover:bg-slate-100"
                            @click="calculator(0)"
                        >
                            0
                        </div>
                        <div
                            class="flex items-center justify-center h-20 text-4xl duration-300 rounded-lg bg-sidebar hover:bg-slate-100"
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
                        <div
                            class="flex items-center justify-center h-full text-4xl font-extrabold text-red-100 bg-red-400 rounded-lg basis-1/3"
                            @click="deleteCurrentRow"
                        >
                            C
                        </div>
                    </div>
                </div>
                <footer class="flex items-center justify-between h-20 p-1 border-t">
                    <div
                        class="flex items-center justify-center w-full h-full font-extrabold text-center text-green-100 rounded-lg bg-primary-color dark:bg-orange-950 dark:text-orange-400"
                        @click="paid"
                    >
                        Payer
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
