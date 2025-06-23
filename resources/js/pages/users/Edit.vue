<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem, Division, Role } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const toast = useToast();

const props = defineProps({
    user: Object,
    roles: Array<Role>,
    divisions: Array<Division>,
});

const user = props.user?.data;

console.log('User data:', user);

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
        title: 'Edit User',
        href: route('users.edit', user.id),
    },
];

const form = useForm({
    name: user.name,
    email: user.email,
    password: '',
    password_confirmation: '',
    role_id: user.role_id.toString(),
    division_id: user.division_id?.toString() ?? '',
});

const submit = () => {
    form.put(route('users.update', user.id), {
        onSuccess: () => {
            toast.success('User berhasil diperbarui.');
        },
        onError: () => {
            toast.error('Gagal memperbarui user. Silakan periksa kembali data Anda.');
        },
        onFinish: () => {
            resetForm();
        },
    });
};

const resetForm = () => {
    form.reset();
};
</script>

<template>
    <Head title="Edit User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <CardTitle class="text-xl font-bold md:text-2xl">Edit User</CardTitle>
                        <Link :href="route('users.index')">
                            <Button type="button" variant="outline">Kembali</Button>
                        </Link>
                    </CardHeader>
                    <form @submit.prevent="submit" class="space-y-6">
                        <CardContent class="space-y-6">
                            <div class="grid gap-2">
                                <Label for="name">Name</Label>
                                <Input id="name" v-model="form.name" required autocomplete="name" />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="email">Email</Label>
                                <Input id="email" type="email" v-model="form.email" required autocomplete="email" />
                                <InputError :message="form.errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password">Password (leave blank to keep current)</Label>
                                <Input id="password" v-model="form.password" autocomplete="new-password" />
                                <InputError :message="form.errors.password" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password_confirmation">Confirm Password</Label>
                                <Input id="password_confirmation" type="password" v-model="form.password_confirmation" autocomplete="new-password" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="role_id">Role</Label>
                                <Select v-model="form.role_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select a role" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="role in props.roles" :key="role.id" :value="role.id.toString()">
                                            {{ role.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.role_id" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="division_id">Division</Label>
                                <Select v-model="form.division_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select a division" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="division in props.divisions" :key="division.id" :value="division.id.toString()">
                                            {{ division.code }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.division_id" />
                            </div>
                        </CardContent>

                        <CardFooter class="flex items-center justify-end gap-4">
                            <Button type="button" variant="outline" @click="resetForm" v-if="form.isDirty">Reset</Button>
                            <Button type="submit" :disabled="form.processing || !form.isDirty">Update</Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
