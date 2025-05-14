<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItem, User, Role, Division } from '@/types';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';

// Define props to receive user data from the controller
const props = defineProps<{
    user: User & { role: Role, division?: Division | null };
}>();

console.log(props.user)

const userRole = props.user.role?.name;
const userDivision = props.user.division?.name;

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
        title: props.user.name,
        href: route('users.show', props.user.id),
    },
];
</script>

<template>
    <Head :title="`User: ${props.user.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-xl md:text-2xl font-bold">User Details</CardTitle>
                        <div class="flex space-x-2">
                            <Link :href="route('users.edit', props.user.id)">
                                <Button variant="outline">Edit User</Button>
                            </Link>
                            <Link :href="route('users.index')">
                                <Button variant="outline">Back to Users</Button>
                            </Link>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <h2 class="text-lg font-medium">Name</h2>
                                    <p>{{ props.user.name }}</p>
                                </div>
                                
                                <div>
                                    <h2 class="text-lg font-medium">Email</h2>
                                    <p>{{ props.user.email }}</p>
                                </div>
                                
                                <div>
                                    <h2 class="text-lg font-medium">Role</h2>
                                    <p>{{ userRole ? userRole.toUpperCase() : 'No Role' }}</p>
                                </div>

                                <div>
                                    <h2 class="text-lg font-medium">Division</h2>
                                    <p>{{ userDivision ? userDivision : 'No Division' }}</p>
                                </div>
                                
                                <div>
                                    <h2 class="text-lg font-medium">Created At</h2>
                                    <!-- <p>{{ new Date(props.user.created_at).toLocaleString() }}</p> -->
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>