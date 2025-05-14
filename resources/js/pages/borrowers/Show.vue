<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    Card, 
    CardContent, 
    CardHeader, 
    CardTitle 
} from '@/components/ui/card';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    borrower: {
        id: number;
        name: string;
        division: {
            id: number;
            name: string;
        };
        created_at: string;
        updated_at: string;
    };
}>();
</script>

<template>
    <Head title="Borrower Details"/>

    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-xl md:text-2xl font-bold">Borrower Details</CardTitle>
                        <div class="flex space-x-2">
                            <Link :href="route('borrowers.edit', borrower.id)">
                                <Button variant="outline">Edit</Button>
                            </Link>
                            <Link :href="route('borrowers.index')">
                                <Button variant="outline">Back to Borrowers</Button>
                            </Link>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ borrower.name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Division</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ borrower.division?.name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ new Date(borrower.created_at).toLocaleString() }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Updated At</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ new Date(borrower.updated_at).toLocaleString() }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>