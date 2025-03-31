<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import Input from '@/components/ui/input/Input.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { ref } from 'vue';

const props = defineProps<{
    show: boolean;
    categories: any[];
}>();

const emit = defineEmits(['close', 'create']);

const form = ref({
    category_id: '', // Fix typo in property name
    price: 1,
    quantity: 1,
});

const handleSubmit = () => {
    if (!form.value.category_id) {
        return; // Prevent submission if no category is selected
    }

    emit('create', {
        category_id: form.value.category_id,
        price: Number(form.value.price),
        quantity: Number(form.value.quantity),
    });

    form.value = {
        category_id: '',
        price: 1,
        quantity: 1,
    };
};
</script>

<template>
    <Dialog :open="show" @update:open="emit('close')">
        <DialogContent class="sm:max-w-[500px]">
            <DialogHeader>
                <DialogTitle>Ajouter une ligne sur le ticket</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label>Catégorie</label>
                        <Select v-model="form.category_id" class="w-48">
                            <SelectTrigger class="w-48">
                                <SelectValue placeholder="Sélectionner une catégorie" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="flex space-x-4">
                        <div class="space-y-2">
                            <label>Prix</label>
                            <Input type="number" v-model="form.price" required />
                        </div>
                        <div class="space-y-2">
                            <label>Quantité</label>
                            <Input type="number" v-model="form.quantity" required />
                        </div>
                    </div>
                </div>

                <DialogFooter>
                    <Button type="button" variant="outline" @click="emit('close')">Annuler</Button>
                    <Button type="submit" variant="teal">Créer</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
