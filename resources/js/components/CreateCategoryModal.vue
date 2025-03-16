<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { ref } from 'vue';

defineProps<{
    show: boolean;
}>();

const emit = defineEmits(['close', 'create']);

const formData = ref({
    name: '',
});

const handleSubmit = () => {
    emit('create', formData.value);
    formData.value = {
        name: '',
    };
};
</script>

<template>
    <Dialog :open="show" @update:open="emit('close')">
        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle>Ajouter une catégorie</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label>Nom</label>
                        <Input v-model="formData.name" required />
                    </div>
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="emit('close')">Annuler</Button>
                    <Button type="submit" variant="teal">Créer</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
