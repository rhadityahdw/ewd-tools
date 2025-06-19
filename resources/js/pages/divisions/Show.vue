<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Division } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { FileTextIcon, PencilIcon } from 'lucide-vue-next';

const props = defineProps<{
    division: Division;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Divisi',
        href: route('divisions.index'),
    },
    {
        title: props.division.code,
        href: route('divisions.show', props.division.id),
    },
];
</script>

<template>
    <Head title="Tambah Divisi" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader class="flex items-center justify-between">
                        <CardTitle class="text-xl font-bold md:text-2xl">Divisi {{ props.division.code }}</CardTitle>
                        <Link :href="route('divisions.edit', division.id)">
                            <Button>
                                <PencilIcon class="h-4 w-4" />
                                Edit Unit
                            </Button>
                        </Link>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                            <div class="space-y-2">
                                <div class="text-sm text-gray-500">Kode Divisi</div>
                                <div class="flex items-center font-medium">
                                    <FileTextIcon class="text-primary mr-2 h-4 w-4" />
                                    {{ division.code }}
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="text-sm text-gray-500">Nama Divisi</div>
                                <div class="flex items-center font-medium">
                                    <!-- <UniversityIcon class="text-primary mr-2 h-4 w-4" /> -->
                                    {{ division.name }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
