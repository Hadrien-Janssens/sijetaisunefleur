<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import Input from '@/components/ui/input/Input.vue';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { ref } from 'vue';

const emit = defineEmits(['close', 'open', 'validation']);

defineProps<{
    title: string;
    trigger: string;
    description: string;
}>();
const value = ref(0);
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

            <div>
                <Label for="customredcu">Pourcentage personnalisé</Label>
                <Input id="customredcu" type="number" v-model="value" />
            </div>

            <RadioGroup :default-value="value" :orientation="'vertical'" class="grow space-y-4" v-model="value">
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

            <DialogFooter>
                <Button @click="emit('close')" variant="outline">Annuler</Button>
                <Button variant="teal" @click="emit('validation', value)">Valider</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
