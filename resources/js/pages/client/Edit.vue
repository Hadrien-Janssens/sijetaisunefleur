<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Building, ContactRound, Hash, Mail, MapPin, Phone } from 'lucide-vue-next';

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
</script>

<template>
    <Head title="Modification d'un client" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container z-10 mx-auto p-6">
            <Button @click="goBack" variant="teal" class="mb-3"><ArrowLeft /> Retour</Button>
            <Card class="w-full bg-gradient-to-br from-white to-gray-50/80">
                <form @submit.prevent="submit">
                    <CardHeader>
                        <CardTitle class="flex items-start gap-2 text-gray-600">
                            <ContactRound class="h-5 w-5" />
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
                            <Mail class="h-4 w-4 text-blue-400" />
                            <Input v-model="form.email" placeholder="Email" class="flex-1" />
                        </div>
                        <div class="flex items-center gap-2">
                            <Phone class="h-4 w-4 text-green-400" />
                            <Input v-model="form.phone" placeholder="Téléphone" class="flex-1" />
                        </div>
                        <div class="flex items-center gap-2">
                            <Building class="h-4 w-4 text-yellow-400" />
                            <Input v-model="form.company" placeholder="Société" class="flex-1" />
                        </div>
                        <div class="flex items-center gap-2">
                            <Hash class="h-4 w-4 text-red-400" />
                            <Input v-model="form.tva_number" placeholder="Numéro de TVA" class="flex-1" />
                        </div>
                        <div class="flex items-start gap-2">
                            <MapPin class="h-4 w-4 shrink-0 text-orange-400" />
                            <div class="flex-1 space-y-2">
                                <Input v-model="form.address" placeholder="Adresse" />
                                <div class="flex gap-2">
                                    <Input v-model="form.city" placeholder="Ville" />
                                    <Input v-model="form.country" placeholder="Pays" />
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <Button variant="teal" type="submit" :disabled="form.processing">Sauvegarder</Button>
                        </div>
                    </CardContent>
                </form>
            </Card>

            <!--  All tickets of this client  -->
        </div>
    </AppLayout>
</template>
