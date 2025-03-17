<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Input from '@/components/ui/input/Input.vue';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { Switch } from '@/components/ui/switch';
import { router } from '@inertiajs/vue3';
import { ClipboardList } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';
import CreateClientModal from '../components/CreateClientModal.vue';

const props = defineProps<{
    show: boolean;
    clients: Array<{
        id: number;
        name: string;
        email: string;
        phone?: string;
    }>;
}>();

const emit = defineEmits(['close', 'validation']);

const searchTerm = ref('');
const selectedClient = ref(null);
const withTVA = ref(false);

const filteredClients = computed(() => {
    if (!searchTerm.value) return props.clients;

    const search = searchTerm.value.toLowerCase();
    return props.clients.filter(
        (client) =>
            client.firstname.toLowerCase().includes(search) ||
            client.email.toLowerCase().includes(search) ||
            client.lastname.toLowerCase().includes(search),
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
        <DialogContent>
            <DialogHeader>
                <DialogTitle class="mb-5 flex items-center gap-3">
                    <ClipboardList class="text-teal-600" />
                    <div>Facture client</div>
                    <Switch id="withTVA" v-model="withTVA" class="ml-10" />
                    <Label for="withTVA" class="text-sm">TVA</Label>
                </DialogTitle>
                <DialogDescription class="flex flex-col gap-4">
                    <Label for="email">Envoie une facture sans enregistremment </Label>
                    <Input id="email" type="text" placeholder="exemple@email.com" />
                </DialogDescription>

                <Separator class="my-4" label="Ou" />
                <DialogDescription class="flex flex-col gap-4">
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
                                :class="{ 'bg-teal-600 text-white hover:bg-teal-600': selectedClient === client }"
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
                        <div v-else class="flex h-full items-center justify-center text-gray-500">Aucun client trouvé</div>
                    </Card>
                </DialogDescription>
            </DialogHeader>

            <DialogFooter>
                <Button variant="secondary" @click="emit('close')">Annuler</Button>
                <Button variant="teal" @click="emit('validation', selectedClient)">Valider</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
