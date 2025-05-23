<script setup lang="ts">
import CreateClientModal from '@/components/CreateClientModal.vue';
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Input from '@/components/ui/input/Input.vue';
import { Label } from '@/components/ui/label';
import { Client } from '@/types';
import { router } from '@inertiajs/vue3';
import { ClipboardList } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';

const props = defineProps<{
    show: boolean;
    clients: Array<Client>;
}>();

const emit = defineEmits(['close', 'validation']);

const searchTerm = ref('');
const selectedClient = ref<null | Client>(null);

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
                <DialogTitle class="flex items-center gap-3 mb-5">
                    <ClipboardList class="text-primary-color" />
                    <div>Sélectionne un client</div>
                </DialogTitle>
                <div class="flex items-baseline gap-4"></div>

                <DialogDescription class="flex flex-col gap-4">
                    <Label for="search">Sélectionne un client pour lui assigner ce ticket</Label>
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
                                class="p-2 duration-300 rounded cursor-pointer hover:bg-gray-100"
                                :class="{ 'bg-primary-color hover:bg-primary-color text-white': selectedClient === client }"
                                @click="selectedClient = client"
                            >
                                <div class="flex justify-between font-medium">
                                    <div class="flex gap-2 font-medium">
                                        <div>{{ client.lastname }}</div>
                                        <div>{{ client.firstname }}</div>
                                    </div>

                                    <div>{{ client.email }}</div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="flex items-center justify-center h-full text-gray-500">Aucun client trouvé</div>
                    </Card>
                </DialogDescription>
            </DialogHeader>

            <div class="flex items-end justify-end gap-3">
                <Button variant="secondary" @click="emit('close')">Annuler</Button>
                <Button variant="teal" @click="emit('validation', selectedClient)">Valider</Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
