<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Trash } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'caisse',
        href: '/caisse',
    },
];
</script>

<template>
    <Head title="Caisse" />

    <AppLayout :breadcrumbs="breadcrumbs" class="h-screen">
        <div class="flex h-full">
            <!-- LEFTSIDE -->
            <div class="flex basis-1/3 flex-col">
                <div class="flex h-20 items-center border-b p-3">
                    <h2>Ticket en cours</h2>
                </div>
                <div class="grow p-3">
                    <div class="flex h-20 items-center justify-between border-b">
                        <Trash class="w-5 text-red-400" />
                        <p>Décoration : 9€ X 2</p>
                    </div>
                    <div class="flex h-20 items-center justify-between border-b">
                        <Trash class="w-5 text-red-400" />
                        <p>Décoration : 9€ X 2</p>
                    </div>
                </div>
                <footer class="flex h-20 items-center justify-between border-t">
                    <p class="basis-1/2 text-center">Total : 22,5€</p>
                    <div
                        class="flex h-full basis-1/2 items-center justify-center bg-orange-300 text-center font-extrabold text-orange-600 dark:bg-orange-950 dark:text-orange-400"
                    >
                        Payer
                    </div>
                </footer>
            </div>
            <!-- RightSide -->
            <div class="flex basis-2/3 flex-col border-l">
                <div class="flex h-20 w-full items-center border-b">
                    <div class="flex w-full items-center justify-between p-3">
                        <div class="flex items-center space-x-2">
                            <Switch id="invoice" />
                            <Label for="invoice">Facture</Label>
                        </div>

                        <Select defaultValue="deco">
                            <SelectTrigger class="w-[180px]">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem value="deco"> Décoration </SelectItem>
                                    <SelectItem value="flower"> Fleur </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>

                        <div class="flex items-center space-x-2">
                            <Switch id="r1" />
                            <Label for="r1">6%</Label>
                            <Switch id="r2" />
                            <Label for="r2">21%</Label>
                        </div>
                    </div>
                </div>
                <div class="flex grow flex-col justify-end">
                    <div class="grid grid-cols-3 border-b border-t">
                        <div class="flex h-20 items-center justify-center border-b bg-sidebar">9</div>
                        <div class="flex h-20 items-center justify-center border-x border-b bg-sidebar">8</div>
                        <div class="flex h-20 items-center justify-center border-b bg-sidebar">7</div>
                        <div class="flex h-20 items-center justify-center border-b bg-sidebar">6</div>
                        <div class="flex h-20 items-center justify-center border-x border-b bg-sidebar">5</div>
                        <div class="flex h-20 items-center justify-center border-b bg-sidebar">4</div>
                        <div class="flex h-20 items-center justify-center border-b bg-sidebar">3</div>
                        <div class="flex h-20 items-center justify-center border-x border-b bg-sidebar">2</div>
                        <div class="flex h-20 items-center justify-center border-b bg-sidebar">1</div>
                        <div class="flex h-20 items-center justify-center bg-sidebar">X</div>
                        <div class="flex h-20 items-center justify-center border-x bg-sidebar">0</div>
                        <div class="flex h-20 items-center justify-center bg-sidebar">,</div>
                    </div>
                </div>
                <footer class="flex h-20 items-center justify-between border-t">
                    <div
                        class="flex h-full basis-1/3 items-center justify-center bg-red-300 font-extrabold text-red-600 dark:bg-red-950 dark:text-red-400"
                    >
                        supprimer
                    </div>
                    <div
                        class="flex h-full basis-2/3 items-center justify-center border-l bg-green-300 font-extrabold text-green-600 dark:bg-green-900 dark:text-green-400"
                    >
                        valider
                    </div>
                </footer>
            </div>
        </div>
    </AppLayout>
</template>
