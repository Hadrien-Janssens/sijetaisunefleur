<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';

defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>();
</script>

<template>
    <header
        class="flex shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex w-full items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs.length > 0">
                <div class="flex h-12 w-full items-center justify-between">
                    <div>
                        <Breadcrumbs :breadcrumbs="breadcrumbs" />
                    </div>
                    <div>
                        <p>
                            Aujourd'hui : <span class="font-bold"> {{ $page.props.dailyAmount.toFixed(2) }}€</span>
                        </p>
                    </div>
                </div>
            </template>
        </div>
    </header>
</template>
