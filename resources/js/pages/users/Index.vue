<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table/index';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Edit, Eye, PlusIcon, Trash2 } from 'lucide-vue-next';

const props = defineProps({
    users: Object,
});

const users = props.users?.data;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Users',
        href: route('users.index'),
    },
];

const getRoleBadgeClass = (roleName: string) => {
    switch (roleName) {
        case 'admin':
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
        case 'rm':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        case 'ero':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }
};
</script>

<template>
    <Head title="Daftar Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader class="flex items-center justify-between">
                        <CardTitle class="text-xl font-bold md:text-2xl">Daftar User</CardTitle>
                        <Link :href="route('users.create')">
                            <Button>
                                <PlusIcon class="h-4 w-4" />
                                Tambah User
                            </Button>
                        </Link>
                    </CardHeader>
                    <CardContent>
                        <div v-if="users.length === 0" class="py-8 text-center text-gray-500">
                            Belum ada user yang terdaftar. Silakan tambahkan user baru.
                        </div>
                        <Table v-else>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Nama User</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Role</TableHead>
                                    <TableHead>Divisi</TableHead>
                                    <TableHead class="text-right"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="user in users" :key="user.id">
                                    <TableCell>{{ user.name }}</TableCell>
                                    <TableCell>{{ user.email }}</TableCell>
                                    <TableCell>
                                        <span :class="['rounded-full px-2 py-1 text-xs font-medium', getRoleBadgeClass(user.roles?.[0]?.name)]">
                                            {{ user.role.name || 'NO ROLE' }}
                                        </span>
                                    </TableCell>
                                    <TableCell>{{ user.division?.code }}</TableCell>
                                    <TableCell class="flex justify-end space-x-3 text-right">
                                        <Link
                                            :href="route('users.show', user.id)"
                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300"
                                            title="View"
                                        >
                                            <Eye class="h-5 w-5" />
                                        </Link>
                                        <Link
                                            :href="route('users.edit', user.id)"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Edit"
                                        >
                                            <Edit class="h-5 w-5" />
                                        </Link>
                                        <Link
                                            :href="route('users.destroy', user.id)"
                                            method="delete"
                                            as="button"
                                            type="button"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                            onclick="return confirm('Are you sure you want to delete this user?')"
                                            title="Delete"
                                        >
                                            <Trash2 class="h-5 w-5" />
                                        </Link>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
