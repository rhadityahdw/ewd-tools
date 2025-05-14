<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import type { BreadcrumbItem, Division, Role } from '@/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import InputError from '@/components/InputError.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

defineProps<{
    roles: Role[],
    divisions: Division[],
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Users',
        href: route('users.index'),
    },
    {
        title: 'Create',
        href: route('users.create'),
    },
];

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_id: '',
    division_id: '', 
});

const submit = () => {
    form.post(route('users.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Create User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-xl md:text-2xl font-bold">Create User</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-2">
                                <Label for="name">Name</Label>
                                <Input id="name" v-model="form.name" autocomplete="name" />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="email">Email</Label>
                                <Input id="email" type="email" v-model="form.email" autocomplete="email" />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password">Password</Label>
                                <Input id="password" type="password" v-model="form.password" autocomplete="new-password" />
                                <InputError :message="form.errors.password" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password_confirmation">Confirm Password</Label>
                                <Input id="password_confirmation" type="password" v-model="form.password_confirmation" autocomplete="new-password" />
                                <InputError :message="form.errors.password_confirmation" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="role_id">Role</Label>
                                <Select v-model="form.role_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select a role" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-if="roles" v-for="role in roles" :key="role.id" :value="role.id" >
                                            {{ role.name.toUpperCase() }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.role_id" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="division_id">Division</Label>
                                <Select v-model="form.division_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select a division (if needed)" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-if="divisions" v-for="division in divisions" :key="division.id" :value="division.id" >
                                            {{ division.code }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.division_id" />
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <Link :href="route('users.index')">
                                    <Button type="button" variant="outline">Cancel</Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing">Submit</Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>