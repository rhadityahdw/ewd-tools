<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table/index';
import type { BreadcrumbItem, User } from '@/types';

defineProps<{
    users: User[];
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
];

const getRoleBadgeClass = (roleName: string) => {
    switch(roleName) {
        case 'admin': return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
        case 'rm': return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        case 'ero': return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }
};
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader class="flex justify-between items-center mb-6">
                        <CardTitle class="text-xl md:text-2xl font-bold">Daftar Pengguna</CardTitle>
                        <Link :href="route('users.create')">
                            <Button>Create User</Button>
                        </Link>
                    </CardHeader>
                    <CardContent>
                        <div v-if="users.length === 0" class="text-center py-8 text-gray-500">
                            Belum ada pengguna yang terdaftar. Silakan tambahkan pengguna baru.
                        </div>
                        <Table v-else>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Role</TableHead>
                                    <TableHead>Division</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="user in users" :key="user.id">
                                    <TableCell>{{ user.name }}</TableCell>
                                    <TableCell>{{ user.email }}</TableCell>
                                    <TableCell>
                                        <span :class="['px-2 py-1 text-xs font-medium rounded-full', getRoleBadgeClass(user.role.name)]">
                                            {{ user.role?.name.toUpperCase() }}
                                        </span>
                                    </TableCell>
                                    <TableCell>{{ user.division?.code }}</TableCell>
                                    <TableCell class="text-right flex space-x-3 justify-end">
                                        <Link :href="route('users.edit', user.id)" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15.1l-3.414.586.586-3.414 9.586-9.586z" />
                                                </svg>
                                            </Link>
                                            <Link :href="route('users.show', user.id)" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
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
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
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