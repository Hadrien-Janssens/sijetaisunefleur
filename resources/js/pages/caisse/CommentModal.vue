<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Textarea } from '@/components/ui/textarea';
import { FilePenLine } from 'lucide-vue-next';
import { ref } from 'vue';

const emit = defineEmits(['close', 'open', 'comment_validation']);

const comment = ref('');
</script>

<template>
    <Dialog @update:open="emit('close')">
        <DialogTrigger as-child>
            <FilePenLine class="text-primary-color" @click="emit('open')" />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Ajoute un commentaire</DialogTitle>
                <DialogDescription>Ce commentaire apparaîtra sur la facture</DialogDescription>
            </DialogHeader>
            <Textarea v-model="comment" />
            <DialogFooter>
                <Button @click="emit('close')" variant="outline">Annuler</Button>
                <Button variant="teal" @click="emit('comment_validation', comment)">Valider</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
