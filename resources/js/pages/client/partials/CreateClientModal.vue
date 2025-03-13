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
    firstname: '',
    lastname: '',
    company: '',
    email: '',
    phone: '',
    tva_number: '',
    address: '',
    city: '',
    country: 'Belgique',
});

const handleSubmit = () => {
    emit('create', formData.value);
    formData.value = {
        firstname: '',
        lastname: '',
        company: '',
        email: '',
        phone: '',
        tva_number: '',
        address: '',
        city: '',
        country: '',
    };
};
</script>

<template>
    <Dialog :open="show" @update:open="emit('close')">
        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle>Ajouter un client</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label>Prénom</label>
                        <Input v-model="formData.firstname" required />
                    </div>
                    <div class="space-y-2">
                        <label>Nom</label>
                        <Input v-model="formData.lastname" required />
                    </div>
                </div>
                <div class="space-y-2">
                    <label>Entreprise</label>
                    <Input v-model="formData.company" />
                </div>
                <div class="space-y-2">
                    <label>Email</label>
                    <Input type="email" v-model="formData.email" required />
                </div>
                <div class="space-y-2">
                    <label>Téléphone</label>
                    <Input v-model="formData.phone" required />
                </div>
                <div class="space-y-2">
                    <label>Numéro de TVA</label>
                    <Input v-model="formData.tva_number" />
                </div>
                <div class="space-y-2">
                    <label>Adresse</label>
                    <Input v-model="formData.address" required />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label>Ville</label>
                        <Input v-model="formData.city" required />
                    </div>
                    <div class="space-y-2">
                        <label>Pays</label>
                        <Input v-model="formData.country" required />
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
