<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { ref } from 'vue';

const emit = defineEmits(['close', 'open', 'comment_validation']);

const comment = ref('');

defineProps<{
    title: string;
    trigger: string;
    description: string;
}>();
</script>

<template>
    <Dialog @update:open="emit('close')">
        <DialogTrigger as-child>
            <div
                @click="emit('open')"
                class="flex items-center justify-center h-full font-extrabold text-center text-blue-100 bg-blue-500 rounded-lg basis-1/2 dark:bg-orange-950 dark:text-orange-400"
            >
                {{ trigger }}
            </div>
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription>{{ description }}</DialogDescription>
            </DialogHeader>

            <RadioGroup default-value="30" :orientation="'vertical'">
                <div class="flex items-center space-x-2">
                    <RadioGroupItem id="reduc1" value="10" />
                    <Label for="reduc1">10%</Label>
                </div>
                <div class="flex items-center space-x-2">
                    <RadioGroupItem id="reduc2" value="20" />
                    <Label for="reduc2">20%</Label>
                </div>
                <div class="flex items-center space-x-2">
                    <RadioGroupItem id="reduc3" value="30" />
                    <Label for="reduc3">30%</Label>
                </div>
                <div class="flex items-center space-x-2">
                    <RadioGroupItem id="reduc4" value="50" />
                    <Label for="reduc4">50%</Label>
                </div>
            </RadioGroup>

            <DialogFooter>
                <Button @click="emit('close')" variant="outline">Annuler</Button>
                <Button variant="teal" @click="emit('comment_validation', comment)">Valider</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
