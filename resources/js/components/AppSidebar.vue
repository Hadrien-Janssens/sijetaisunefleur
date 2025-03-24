<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { LineChart, Package, Receipt, ShoppingCart, Users } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Caisse',
        href: '/caisse',
        icon: ShoppingCart,
        color: 'text-yellow-500',
    },
    {
        title: 'Chiffres',
        href: '/numbre',
        icon: LineChart,
        color: 'text-red-500',
    },
    {
        title: 'Ventes',
        href: '/vente',
        icon: Receipt,
        color: 'text-green-500',
    },
    // {
    //     title: 'Factures',
    //     href: '/invoice',
    //     icon: ClipboardList,
    //     color: 'text-orange-500',
    // },

    {
        title: 'Clients',
        href: '/client',
        icon: Users,
        color: 'text-blue-500',
    },
    {
        title: 'Articles',
        href: '/article',
        icon: Package,
        color: 'text-amber-700',
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('caisse')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
            <!-- <Button variant="teal" class="h-16 text-teal-100 duration-300">Fin de jour</Button> -->
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
