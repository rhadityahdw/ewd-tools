<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import InputError from '@/components/InputError.vue';
import type { BreadcrumbItem, User, Role, Division } from '@/types';

const props = defineProps<{
    user: User;
    roles: Role[];
    divisions: Division[]; 
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
        title: 'Edit',
        href: route('users.edit', props.user.id),
    },
];

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role_id: props.user.role_id.toString(),
});

const submit = () => {
    form.put(route('users.update', props.user.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Edit User ({{ props.user.name }})</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
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
                                            {{ role.name.toUpperCase() }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.role_id" />
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <Link :href="route('users.index')">
                                    <Button type="button" variant="outline">Cancel</Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing">Update</Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>