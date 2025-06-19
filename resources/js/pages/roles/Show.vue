<script setup lang="ts">
import { Badge } from '@/components/ui/badge/index';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Edit } from 'lucide-vue-next';

interface Permission {
    id: number;
    name: string;
}

interface User {
    id: number;
    name: string;
    email: string;
}

interface Role {
    id: number;
    name: string;
    permissions: Permission[];
    users: User[];
}

const props = defineProps<{
    role: Role;
}>();
</script>

<template>
    <Head :title="`Role: ${role.name}`" />

    <AppLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="space-y-6">
                <!-- Role Info -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle class="text-xl font-bold md:text-2xl">{{ role.name }}</CardTitle>
                            <p class="mt-1 text-gray-600">{{ role.permissions.length }} permissions, {{ role.users.length }} users</p>
                        </div>
                        <div class="flex gap-2">
                            <Link :href="route('roles.edit', role.id)">
                                <Button>
                                    <Edit class="mr-2 h-4 w-4" />
                                    Edit Role
                                </Button>
                            </Link>
                            <Link :href="route('roles.index')">
                                <Button variant="outline">
                                    <ArrowLeft class="mr-2 h-4 w-4" />
                                    Kembali
                                </Button>
                            </Link>
                        </div>
                    </CardHeader>
                </Card>

                <!-- Permissions -->
                <Card>
                    <CardHeader>
                        <CardTitle>Permissions ({{ role.permissions.length }})</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="role.permissions.length === 0" class="py-4 text-center text-gray-500">Role ini belum memiliki permission.</div>
                        <div v-else class="flex flex-wrap gap-2">
                            <Badge v-for="permission in role.permissions" :key="permission.id" variant="secondary">
                                {{ permission.name }}
                            </Badge>
                        </div>
                    </CardContent>
                </Card>

                <!-- Users -->
                <Card>
                    <CardHeader>
                        <CardTitle>Users dengan Role Ini ({{ role.users.length }})</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="role.users.length === 0" class="py-4 text-center text-gray-500">Belum ada user yang menggunakan role ini.</div>
                        <Table v-else>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Nama</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="user in role.users" :key="user.id">
                                    <TableCell class="font-medium">{{ user.name }}</TableCell>
                                    <TableCell>{{ user.email }}</TableCell>
                                    <TableCell class="text-right">
                                        <Link :href="route('users.show', user.id)" class="text-blue-600 hover:text-blue-800"> Lihat Detail </Link>
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
