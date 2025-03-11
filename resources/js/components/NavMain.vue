<script setup lang="ts">
import { SidebarGroup, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
}>();

const page = usePage<SharedData>();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarMenu class="mt-5">
            <SidebarMenuItem v-for="item in items" :key="item.title" class="py-2">
                <SidebarMenuButton as-child :is-active="item.href === page.url">
                    <Link :href="item.href" class="py-5">
                        <component :is="item.icon" :class="item.color ? item.color : ''" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
