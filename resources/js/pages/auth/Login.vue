<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="flex min-h-svh flex-col gap-1">
        <img src="/logo.avif" alt="logo" class="w-full" />
        <AuthBase title="Si j'étais une fleur 🌼 " description="Salut 👋, entre tes identifiants de fleuriste en herbe 🪴">
            <Head title="Se connecter" />

            <!-- <div v-if="status" class="mb-4 text-sm font-medium text-center text-green-600">
            {{ status }}
        </div> -->

            <form @submit.prevent="submit" class="flex flex-col gap-6">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <Label for="email">Ton email</Label>
                        <Input
                            id="email"
                            type="email"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="email"
                            v-model="form.email"
                            placeholder="email@example.com"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <div class="flex items-center justify-between">
                            <Label for="password">Ton mot de passe</Label>
                            <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm" :tabindex="5">
                                Mot de passe oublié ?
                            </TextLink>
                        </div>
                        <Input
                            id="password"
                            type="password"
                            required
                            :tabindex="2"
                            autocomplete="current-password"
                            v-model="form.password"
                            placeholder="Mot de passe"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="flex items-center justify-between" :tabindex="3">
                        <Label for="remember" class="flex items-center space-x-3">
                            <Checkbox id="remember" v-model:checked="form.remember" :tabindex="4" />
                            <span>Se souvenir de moi</span>
                        </Label>
                    </div>

                    <Button type="submit" variant="teal" :tabindex="4" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Me connecter
                    </Button>
                </div>
            </form>
        </AuthBase>
    </div>
</template>
