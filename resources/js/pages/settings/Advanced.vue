<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Paramètres Avancés',
        href: '/settings/appearance',
    },
];

const deleteHoldTickets = () => {
    if (confirm('Es-tu sûr de vouloir supprimer les tickets vieux de plus de 5 ans ?')) {
        router.get(
            '/settings/advanced/delete-hold-tickets',
            {},
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Paramètre avancés" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Paramètre avancés" description="" />

                <p>
                    🚨 Ton application commence à ralentir ? Tu peux supprimer les tickets vieux de plus de 5 ans en cliquant sur ce bouton, mais
                    contact quelqu'un pour sauvegarder les données avant, on est pas à l'abri d'un bug pour ce genre de tâches. Il vaut donc mieux
                    procéder à une sauvegarde.
                </p>

                <Button variant="destructive" @click="deleteHoldTickets">Supprimer les anciens tickets</Button>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
