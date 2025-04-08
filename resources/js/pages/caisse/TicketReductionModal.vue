<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import Input from '@/components/ui/input/Input.vue';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import Tabs from '@/components/ui/tabs/Tabs.vue';
import TabsList from '@/components/ui/tabs/TabsList.vue';
import TabsTrigger from '@/components/ui/tabs/TabsTrigger.vue';
import { ref } from 'vue';

const emit = defineEmits(['close', 'open', 'pourcent_validation', 'amount_validation']);

defineProps<{
    title: string;
    trigger: string;
    description: string;
}>();
const value = ref(0);
const activeTab = ref('pourcent');
</script>

<template>
    <Dialog @update:open="emit('close')">
        <DialogTrigger as-child>
            <div
                @click="emit('open')"
                class="flex h-full basis-1/2 items-center justify-center rounded-lg bg-cyan-500 text-center font-extrabold text-cyan-100"
            >
                {{ trigger }}
            </div>
        </DialogTrigger>
        <DialogContent class="flex h-[600px] flex-col justify-between sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>{{ description }}</DialogDescription>
            </DialogHeader>

            <Tabs v-model="activeTab" class="mb-6">
                <TabsList class="grid w-full grid-cols-2">
                    <TabsTrigger value="pourcent">%</TabsTrigger>
                    <TabsTrigger value="amount">€</TabsTrigger>
                </TabsList>
            </Tabs>
            <div v-if="activeTab === 'pourcent'">
                <Label for="customredcu">Pourcentage personnalisé</Label>
                <Input id="customredcu" type="number" v-model="value" />
            </div>
            <RadioGroup :default-value="value" :orientation="'vertical'" class="grow" v-model="value" v-if="activeTab === 'pourcent'">
                <div class="flex items-center space-x-2">
                    <RadioGroupItem id="reduc0" :value="0" />
                    <Label for="reduc0" class="text-lg">Aucune</Label>
                </div>
                <div class="flex items-center space-x-2">
                    <RadioGroupItem id="reduc1" :value="10" />
                    <Label for="reduc1" class="text-lg">10%</Label>
                </div>
                <div class="flex items-center space-x-2">
                    <RadioGroupItem id="reduc2" :value="20" />
                    <Label for="reduc2" class="text-lg">20%</Label>
                </div>
                <div class="flex items-center space-x-2">
                    <RadioGroupItem id="reduc3" :value="30" />
                    <Label for="reduc3" class="text-lg">30%</Label>
                </div>
                <div class="flex items-center space-x-2">
                    <RadioGroupItem id="reduc4" :value="50" />
                    <Label for="reduc4" class="text-lg">50%</Label>
                </div>
            </RadioGroup>

            <div v-if="activeTab !== 'pourcent'" class="grow space-y-3">
                <Label for="reduction" class="text-lg">Montant de la réduction</Label>
                <Input id="reduction" type="number" class="w-full" v-model="value" />
            </div>

            <DialogFooter>
                <Button @click="emit('close')" variant="outline">Annuler</Button>
                <Button variant="teal" @click="emit('pourcent_validation', value)" v-if="activeTab === 'pourcent'">Valider</Button>
                <Button variant="teal" @click="emit('amount_validation', value)" v-if="activeTab === 'amount'">Valider</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
