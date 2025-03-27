<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps<{
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

// const handleSubmit = () => {
//     emit('create', formData.value);
// };
const errors = computed(() => usePage().props.errors || {});

const handleSubmit = () => {
    router.post(route('client.store'), formData.value);
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
                        <Input v-model="formData.firstname" :class="{ 'border-red-500': errors?.firstname }" required />
                        <p v-if="errors?.firstname" class="text-sm text-red-500">{{ errors.firstname }}</p>
                    </div>
                    <div class="space-y-2">
                        <label>Nom</label>
                        <Input v-model="formData.lastname" :class="{ 'border-red-500': errors?.lastname }" required />
                        <p v-if="errors?.lastname" class="text-sm text-red-500">{{ errors.lastname }}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <label>Entreprise</label>
                    <Input v-model="formData.company" :class="{ 'border-red-500': errors?.company }" />
                    <p v-if="errors?.company" class="text-sm text-red-500">{{ errors?.company }}</p>
                </div>
                <div class="space-y-2">
                    <label>Email</label>
                    <Input type="email" v-model="formData.email" :class="{ 'border-red-500': errors?.email }" required />
                    <p v-if="errors?.email" class="text-sm text-red-500">{{ errors.email }}</p>
                </div>
                <div class="space-y-2">
                    <label>Téléphone</label>
                    <Input v-model="formData.phone" :class="{ 'border-red-500': errors?.phone }" />
                    <p v-if="errors?.phone" class="text-sm text-red-500">{{ errors.phone }}</p>
                </div>
                <div class="space-y-2">
                    <label>Numéro de TVA</label>
                    <Input v-model="formData.tva_number" :class="{ 'border-red-500': errors?.tva_number }" />
                    <p v-if="errors?.tva_number" class="text-sm text-red-500">{{ errors.tva_number }}</p>
                </div>
                <div class="space-y-2">
                    <label>Adresse</label>
                    <Input v-model="formData.address" :class="{ 'border-red-500': errors?.address }" />
                    <p v-if="errors?.address" class="text-sm text-red-500">{{ errors.address }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label>Ville</label>
                        <Input v-model="formData.city" :class="{ 'border-red-500': errors?.city }" />
                        <p v-if="errors?.city" class="text-sm text-red-500">{{ errors.city }}</p>
                    </div>
                    <div class="space-y-2">
                        <label>Pays</label>
                        <Input v-model="formData.country" :class="{ 'border-red-500': errors?.country }" />
                        <p v-if="errors?.country" class="text-sm text-red-500">{{ errors.country }}</p>
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
