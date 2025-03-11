<script setup lang="ts">
import Label from '@/components/ui/label/Label.vue';
import Switch from '@/components/ui/switch/Switch.vue';
import AppLayout from '@/layouts/AppLayout.vue';
</script>
<template>
    <AppLayout>
        <div class="container mx-auto p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold">Ventes</h1>
                <div class="flex items-center gap-4">
                    <p>Jour</p>
                    <p>Semaine</p>
                    <p>Mois</p>
                    <p>Année</p>
                </div>
            </div>
            <div class="flex items-center justify-between space-x-2">
                <div class="flex items-center space-x-2">
                    <Switch id="invoice" />
                    <Label for="invoice">Avec facture</Label>
                </div>
                <input type="date" class="rounded-md border border-gray-300 p-2" />
            </div>
        </div>
    </AppLayout>
</template>
