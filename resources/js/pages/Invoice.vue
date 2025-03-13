<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ClipboardList } from 'lucide-vue-next';
</script>
<template>
    <AppLayout>
        <ClipboardList class="fixed mt-10 h-screen w-full text-stone-100" />
        <div class="container z-10 mx-auto p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold">Factures</h1>
                <div class="flex items-center gap-4">
                    <p>Jour</p>
                    <p>Semaine</p>
                    <p>Mois</p>
                    <p>Année</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
