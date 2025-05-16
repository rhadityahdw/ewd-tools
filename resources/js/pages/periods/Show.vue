<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent, CardFooter } from '@/components/ui/card';
import { Head, Link } from '@inertiajs/vue3';
import { BreadcrumbItem, Period } from '@/types';
import { ArrowLeft, Edit } from 'lucide-vue-next';

const props = defineProps<{
    period: Period;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Periode',
        href: route('periods.index'),
    },
    {
        title: props.period.name,
        href: route('periods.show', props.period.id),
    }
];

const getStatusBadgeClass = (status: string) => {
    switch(status) {
        case 'draft': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
        case 'active': return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        case 'completed': return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }
};

const getStatusLabel = (status: string) => {
    switch(status) {
        case 'draft': return 'Draft';
        case 'active': return 'Aktif';
        case 'completed': return 'Selesai';
        default: return status;
    }
};
</script>

<template>
    <Head :title="`Periode: ${period.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle class="text-xl md:text-2xl font-bold">
                                {{ period.name }}
                            </CardTitle>
                            <p class="text-sm text-muted-foreground mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="getStatusBadgeClass(period.status)">
                                    {{ getStatusLabel(period.status) }}
                                </span>
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <Link :href="route('periods.edit', period.id)">
                                <Button variant="outline" class="flex items-center">
                                    <Edit class="h-4 w-4 mr-2" />
                                    Edit
                                </Button>
                            </Link>
                            <Link :href="route('periods.index')">
                                <Button variant="outline" class="flex items-center">
                                    <ArrowLeft class="h-4 w-4 mr-2" />
                                    Kembali
                                </Button>
                            </Link>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Periode</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ period.name }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="getStatusBadgeClass(period.status)">
                                        {{ getStatusLabel(period.status) }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ period.start_date }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</h3>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ period.end_date }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>