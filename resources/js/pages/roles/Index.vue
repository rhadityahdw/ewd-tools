<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Edit, Eye, Plus, Trash2 } from 'lucide-vue-next';

interface Permission {
    id: number;
    name: string;
}

interface Role {
    id: number;
    name: string;
    permissions: Permission[];
    users_count: number;
}

const props = defineProps<{
    roles: Role[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Role',
        href: route('roles.index'),
    },
];
</script>

<template>
    <Head title="Daftar Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader class="flex items-center justify-between">
                        <CardTitle class="text-xl font-bold md:text-2xl"> Daftar Role </CardTitle>
                        <Link :href="route('roles.create')">
                            <Button>
                                <Plus class="h-4 w-4" />
                                Tambah Role
                            </Button>
                        </Link>
                    </CardHeader>
                    <CardContent>
                        <div v-if="roles.length === 0" class="py-8 text-center text-gray-500">
                            Belum ada role yang tersedia. Silakan tambahkan role baru.
                        </div>
                        <Table v-else>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Role</TableHead>
                                    <TableHead>Permissions</TableHead>
                                    <TableHead>Jumlah User</TableHead>
                                    <TableHead class="text-right"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="role in roles" :key="role.id">
                                    <TableCell>{{ role.name }}</TableCell>
                                    <TableCell>
                                        <div class="flex flex-wrap gap-1">
                                            <Badge v-for="permission in role.permissions.slice(0, 3)" :key="permission.id" variant="secondary">
                                                {{ permission.name }}
                                            </Badge>
                                            <Badge v-if="role.permissions.length > 3" variant="outline">
                                                +{{ role.permissions.length - 3 }} lainnya
                                            </Badge>
                                        </div>
                                    </TableCell>
                                    <TableCell>{{ role.users_count }}</TableCell>
                                    <TableCell class="flex justify-end space-x-3 text-right">
                                        <Link
                                            :href="route('roles.show', role.id)"
                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300"
                                            title="View"
                                        >
                                            <Eye class="h-5 w-5" />
                                        </Link>
                                        <Link
                                            :href="route('roles.edit', role.id)"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Edit"
                                        >
                                            <Edit class="h-5 w-5" />
                                        </Link>
                                        <Link
                                            :href="route('roles.destroy', role.id)"
                                            method="delete"
                                            as="button"
                                            type="button"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                            onclick="return confirm('Are you sure you want to delete this role?')"
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
