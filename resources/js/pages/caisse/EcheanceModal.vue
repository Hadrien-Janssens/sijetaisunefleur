<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Textarea } from '@/components/ui/textarea';
import { ref } from 'vue';

const emit = defineEmits(['close', 'open', 'validation', 'remove_comment']);

const comment = ref('');
</script>

<template>
    <Dialog @update:open="emit('close')">
        <!-- <DialogTrigger as-child>
            <FilePenLine class="text-primary-color" @click="emit('open')" />
        </DialogTrigger> -->
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Ajoute une échéance</DialogTitle>
                <DialogDescription>Cette échéance apparaîtra sur la facture à la place de "Acquitté"</DialogDescription>
            </DialogHeader>
            <Textarea v-model="comment" />
            <DialogFooter>
                <div class="flex w-full items-center justify-between">
                    <Transition>
                        <Button
                            @click="
                                () => {
                                    comment = '';
                                    emit('remove_comment');
                                }
                            "
                            variant="outline"
                            v-show="comment"
                            >Retirer</Button
                        >
                    </Transition>
                    <div v-show="!comment"></div>

                    <div class="flex gap-2">
                        <Button @click="emit('close')" variant="outline">Annuler</Button>
                        <Button variant="teal" @click="emit('validation', comment)">Valider</Button>
                    </div>
                </div>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
