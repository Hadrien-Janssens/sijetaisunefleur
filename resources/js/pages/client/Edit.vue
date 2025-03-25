<script setup lang="ts">
import DeleteConfirmationModal from '@/components/DeleteConfirmationModal.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Building, ContactRound, Hash, Mail, MapPin, Phone } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    client: {
        id: number;
        firstname: string;
        lastname: string;
        email: string;
        phone: string;
        company: string;
        tva_number: string;
        address: string;
        city: string;
        country: string;
        deleted_at: string | null;
    };
}>();

const form = useForm({
    firstname: props.client.firstname,
    lastname: props.client.lastname,
    email: props.client.email,
    phone: props.client.phone,
    company: props.client.company,
    tva_number: props.client.tva_number,
    address: props.client.address,
    city: props.client.city,
    country: props.client.country,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'clients',
        href: route('client.index'),
    },
    {
        title: 'Modification',
        href: '/edit',
    },
];

const submit = () => {
    form.put(route('client.update', props.client.id));
};

const goBack = () => {
    window.history.back();
};

const showDeleteModal = ref(false);

const confirmDelete = () => {
    form.delete(route('client.destroy', props.client.id));
    showDeleteModal.value = false;
};
</script>

<template>
    <Head title="Modification d'un client" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container z-10 p-6 mx-auto">
            <Button @click="goBack" variant="teal" class="mb-3"><ArrowLeft /> Retour</Button>
            <Card class="w-full bg-gradient-to-br from-white to-gray-50/80">
                <form @submit.prevent="submit">
                    <CardHeader>
                        <CardTitle class="flex items-start gap-2 text-gray-600">
                            <ContactRound class="w-5 h-5" />
                            <div class="w-full space-y-4">
                                <div class="flex gap-4">
                                    <Input v-model="form.firstname" placeholder="Prénom" />
                                    <Input v-model="form.lastname" placeholder="Nom" />
                                </div>
                            </div>
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-center gap-2">
                            <Mail class="w-4 h-4 text-blue-400" />
                            <Input v-model="form.email" placeholder="Email" class="flex-1" />
                        </div>
                        <div class="flex items-center gap-2">
                            <Phone class="w-4 h-4 text-green-400" />
                            <Input v-model="form.phone" placeholder="Téléphone" class="flex-1" />
                        </div>
                        <div class="flex items-center gap-2">
                            <Building class="w-4 h-4 text-yellow-400" />
                            <Input v-model="form.company" placeholder="Société" class="flex-1" />
                        </div>
                        <div class="flex items-center gap-2">
                            <Hash class="w-4 h-4 text-red-400" />
                            <Input v-model="form.tva_number" placeholder="Numéro de TVA" class="flex-1" />
                        </div>
                        <div class="flex items-start gap-2">
                            <MapPin class="w-4 h-4 text-orange-400 shrink-0" />
                            <div class="flex-1 space-y-2">
                                <Input v-model="form.address" placeholder="Adresse" />
                                <div class="flex gap-2">
                                    <Input v-model="form.city" placeholder="Ville" />
                                    <Input v-model="form.country" placeholder="Pays" />
                                </div>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter class="flex items-center justify-between gap-3 m-3" :class="{ 'justify-end': !client.deleted_at }">
                        <Link v-if="client.deleted_at" :href="route('client.restore', client.id)"> <Button variant="outline">Restaurer</Button></Link>
                        <div class="flex items-center gap-3">
                            <DeleteConfirmationModal v-model:open="showDeleteModal" @delete="confirmDelete" />
                            <div class="flex justify-end">
                                <Button variant="teal" type="submit" :disabled="form.processing">Sauvegarder</Button>
                            </div>
                        </div>
                    </CardFooter>
                </form>
            </Card>

            <!--  All tickets of this client  -->
        </div>
    </AppLayout>
</template>
