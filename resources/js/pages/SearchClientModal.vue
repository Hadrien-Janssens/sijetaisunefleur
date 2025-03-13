<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Input from '@/components/ui/input/Input.vue';

defineProps<{
    show: boolean;
    clients: Array<{
        id: number;
        name: string;
        email: string;
        phone?: string;
    }>;
}>();

const emit = defineEmits(['close']);
</script>

<template>
    <Dialog :open="show" @update:open="emit('close')">
        <!-- <DialogTrigger> Edit Profile </DialogTrigger> -->
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Facture client</DialogTitle>
                <DialogDescription class="flex flex-col gap-4">
                    <p>Sélectionne un client pour lui assigner une facture</p>
                    <div class="flex items-center gap-2">
                        <Input class="basis-1/2" placeholder="Recherche" />
                        <Button class="grow" variant="teal" @click="emit('close')">Nouveau</Button>
                    </div>
                    <Card class="h-48 overflow-y-auto">
                        <div v-if="clients.length" class="flex flex-col gap-2 p-3">
                            <div v-for="client in clients" :key="client.id" class="cursor-pointer rounded p-2 hover:bg-gray-100">
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
                <Button variant="teal" @click="emit('close')">Valider</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
