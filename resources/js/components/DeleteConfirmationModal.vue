<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';

const props = defineProps<{
    open: boolean;
    hiddenbutton?: boolean;
}>();

const emit = defineEmits(['update:open', 'delete']);

const handleClose = () => {
    emit('update:open', false);
};

const handleDelete = () => {
    emit('delete');
    handleClose();
};
</script>

<template>
    <Dialog :open="open" @update:open="(value) => emit('update:open', value)">
        <DialogTrigger asChild v-if="!hiddenbutton">
            <Button variant="outline">Supprimer</Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Confirmation de suppression</DialogTitle>
                <DialogDescription> T'es sûr de vouloir supprimer ? <br /> </DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <Button variant="ghost" @click="handleClose">Annuler</Button>
                <Button variant="destructive" @click="handleDelete">Supprimer</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
