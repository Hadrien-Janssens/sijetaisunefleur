<script setup lang="ts">
import CreateClientModal from '@/components/CreateClientModal.vue';
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Input from '@/components/ui/input/Input.vue';
import { Label } from '@/components/ui/label';
import Switch from '@/components/ui/switch/Switch.vue';
import Tabs from '@/components/ui/tabs/Tabs.vue';
import TabsList from '@/components/ui/tabs/TabsList.vue';
import TabsTrigger from '@/components/ui/tabs/TabsTrigger.vue';
import { Client } from '@/types';
import { router } from '@inertiajs/vue3';
import { ClipboardList } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';

import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/components/ui/hover-card';

const props = defineProps<{
    show: boolean;
    clients: Array<Client>;
}>();

const emit = defineEmits(['close', 'validation']);

const searchTerm = ref('');
const selectedClient = ref<null | Client>(null);
const activeTab = ref('saved');
const ClientTVASwitch = ref(true);
const EmailTVASwitch = ref(false);

const selectedEmail = ref('');
const selectedTVA = ref('');

const filteredClients = computed(() => {
    if (!searchTerm.value) return props.clients;

    const search = searchTerm.value.toLowerCase();
    return props.clients.filter(
        (client) =>
            client.firstname?.toLowerCase().includes(search) ||
            client.email?.toLowerCase().includes(search) ||
            client.lastname?.toLowerCase().includes(search) ||
            client.phone?.toLowerCase().includes(search) ||
            client.company?.toLowerCase().includes(search),
    );
});

const isCreateClientModalOpen = ref(false);

const handleCreateClient = (clientData: any) => {
    const form = reactive(clientData);
    router.post(route('client.store'), form);
    isCreateClientModalOpen.value = false;
};
</script>

<template>
    <CreateClientModal :show="isCreateClientModalOpen" @close="isCreateClientModalOpen = false" @create="handleCreateClient" class="z-50" />
    <Dialog :open="show" @update:open="emit('close')">
        <!-- <DialogTrigger> Edit Profile </DialogTrigger> -->
        <DialogContent class="h-[600px]">
            <DialogHeader>
                <DialogTitle class="mb-5 flex items-center gap-3">
                    <ClipboardList class="text-primary-color" />
                    <div>Facture client</div>
                </DialogTitle>
                <div class="flex items-baseline gap-4">
                    <Tabs v-model="activeTab" class="mb-6">
                        <TabsList class="grid w-full grid-cols-2">
                            <TabsTrigger value="saved">Client enregistré</TabsTrigger>
                            <TabsTrigger value="email">Client non-enregistré</TabsTrigger>
                        </TabsList>
                    </Tabs>
                </div>
                <DialogDescription class="flex flex-col gap-4" v-if="activeTab === 'email'">
                    <Label for="email">Envoie une facture sans enregistremment </Label>
                    <div class="flex flex-col gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="text" placeholder="exemple@email.com" v-model="selectedEmail" />
                    </div>
                    <div class="flex flex-col gap-2" v-if="EmailTVASwitch">
                        <Label for="tva">N° TVA</Label>
                        <Input id="tva" type="text" placeholder="BE xxxx xxxx xxxx" v-model="selectedTVA" />
                    </div>
                </DialogDescription>

                <DialogDescription class="flex flex-col gap-4" v-else>
                    <Label for="search">Sélectionne un client pour lui assigner une facture</Label>
                    <div class="flex items-center gap-2">
                        <Input id="search" v-model="searchTerm" class="basis-1/2" placeholder="Recherche" />
                        <Button
                            class="grow"
                            variant="teal"
                            @click="
                                () => {
                                    emit('close');
                                    isCreateClientModalOpen = true;
                                }
                            "
                            >Nouveau</Button
                        >
                    </div>
                    <Card class="h-48 overflow-y-auto">
                        <div v-if="filteredClients.length" class="flex flex-col gap-2 p-3">
                            <div
                                v-for="client in filteredClients"
                                :key="client.id"
                                class="cursor-pointer rounded p-2 duration-300 hover:bg-gray-100"
                                :class="{ 'bg-primary-color hover:bg-primary-color text-white': selectedClient === client }"
                                @click="selectedClient = client"
                            >
                                <div class="flex justify-between font-medium">
                                    <div class="flex flex-col gap-2 font-medium">
                                        <div>{{ client.lastname }}</div>
                                        <div>{{ client.firstname }}</div>
                                    </div>

                                    <div>{{ client.email }}</div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="flex h-full items-center justify-center text-gray-500">Aucun client trouvé</div>
                    </Card>
                </DialogDescription>
                <div class="items- flex justify-end gap-2">
                    <Switch v-if="activeTab == 'saved'" id="withTVA" v-model="ClientTVASwitch" class="ml-10" />
                    <Switch v-else id="withTVA" v-model="EmailTVASwitch" class="ml-10" />

                    <HoverCard>
                        <HoverCardTrigger as-child>
                            <Label for="withTVA" class="text-sm">TVA</Label>
                        </HoverCardTrigger>
                        <HoverCardContent class="w-80">
                            <div class="flex justify-between space-x-4">
                                <!-- <Avatar>
                                        <AvatarImage src="https://github.com/vuejs.png" />
                                        <AvatarFallback>VC</AvatarFallback>
                                    </Avatar> -->
                                <div class="space-y-1">
                                    <h4 class="text-sm font-semibold">Information</h4>
                                    <p class="text-sm">Si c'est coché, tu auras le "A" dans les références de tes factures</p>
                                    <p class="text-sm">Par exemple : 25-054A</p>
                                </div>
                            </div>
                        </HoverCardContent>
                    </HoverCard>
                </div>
            </DialogHeader>

            <div class="flex items-end justify-end gap-3">
                <Button variant="secondary" @click="emit('close')">Annuler</Button>
                <Button v-if="activeTab === 'saved'" variant="teal" @click="emit('validation', selectedClient, ClientTVASwitch)">Valider</Button>
                <Button v-if="activeTab === 'email'" variant="teal" @click="emit('validation', selectedEmail, EmailTVASwitch, selectedTVA)"
                    >Valider</Button
                >
            </div>
        </DialogContent>
    </Dialog>
</template>
