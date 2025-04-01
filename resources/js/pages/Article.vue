<script setup lang="ts">
import CreateCategoryModal from '@/components/CreateCategoryModal.vue';
import DeleteConfirmationModal from '@/components/DeleteConfirmationModal.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { Category, type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Flower, Package } from 'lucide-vue-next';
import { reactive, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'articles',
        href: '/articles',
    },
];

defineProps({
    categories: {
        type: Array<Category>,
    },
});

const openCategoryModal = ref(false);

const createCategory = (clientData: any) => {
    const form = reactive(clientData);
    router.post(route('article.store'), form);
    openCategoryModal.value = false;
};

// Remplacer la variable showDeleteModal simple par un Map
const deleteModalStates = ref(new Map());

const toggleDeleteModal = (id: number, value: boolean) => {
    deleteModalStates.value.set(id, value);
};

const confirmDelete = (id: number) => {
    router.delete(route('article.destroy', id));
    toggleDeleteModal(id, false);
};
</script>

<template>
    <Head title="Articles" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Flower class="fixed w-full h-screen mt-10 text-primary-color opacity-10" />
        <div class="container z-10 p-6 mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold">Articles</h1>
                <CreateCategoryModal :show="openCategoryModal" @close="openCategoryModal = false" @create="createCategory" />
                <Button variant="teal" @click="openCategoryModal = true">Ajouter un article</Button>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card
                    v-for="category in categories"
                    :key="category.id"
                    class="w-full transition-all duration-300 ease-in-out bg-transparent from-white to-gray-50/80 backdrop-blur-md hover:-translate-y-1 hover:shadow-lg"
                >
                    <CardHeader>
                        <CardTitle class="flex items-start gap-2 text-gray-600">
                            <Package class="w-5 h-5" />
                            <div>
                                <p>
                                    {{ category.name }} <span class="text-sm italic text-gray-500">({{ category.tva }}%)</span>
                                </p>
                            </div>
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="flex justify-end w-full space-y-4">
                        <DeleteConfirmationModal
                            :open="deleteModalStates.get(category.id) || false"
                            @update:open="(value) => toggleDeleteModal(category.id, value)"
                            @delete="confirmDelete(category.id)"
                        />
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
