<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Head, Link } from '@inertiajs/vue3';
import { BreadcrumbItem, Aspect, Question } from '@/types';
import { Plus, Eye, Edit, Trash } from 'lucide-vue-next';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';

const props = defineProps<{
    aspects: Array<{
        id: number,
        name: string,
        description: string,
        weight: number,
        questions: Array<{
            id: number,
            aspect_id: number,
            question_text: string,
        }>
    }>
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Aspek',
        href: route('aspects.index'),
    }
];

const questionsCount = (aspectId: number) => {
    return props.aspects.find(a => a.id === aspectId)?.questions.length ?? 0;
}
</script>

<template>
    <Head title="List Aspek" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <CardTitle class="text-xl md:text-2xl font-bold">
                            Daftar Aspek
                        </CardTitle>
                        <Link :href="route('aspects.create')">
                            <Button>
                                <Plus class="w-4 h-4 mr-2" />
                                Tambah Aspek
                            </Button>
                        </Link>
                    </CardHeader>
                    <CardContent>
                        <div v-if="aspects.length === 0" class="text-center py-8 text-gray-500">
                            Belum ada aspek yang tersedia. Silakan tambahkan aspek baru.
                        </div>
                        <Table v-else>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Nama Aspek</TableHead>
                                    <TableHead>Bobot</TableHead>
                                    <TableHead>Jumlah Pertanyaan</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="aspect in aspects" :key="aspect.id">
                                    <TableCell class="font-medium">{{ aspect.name }}</TableCell>
                                    <TableCell>{{ aspect.weight }}</TableCell>
                                    <TableCell>{{ questionsCount(aspect.id) }}</TableCell>
                                    <TableCell class="text-right flex space-x-3 justify-end">
                                        <Link :href="route('aspects.edit', aspect.id)" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15.1l-3.414.586.586-3.414 9.586-9.586z" />
                                            </svg>
                                        </Link>
                                        <Link :href="route('aspects.show', aspect.id)" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <Link 
                                            :href="route('aspects.destroy', aspect.id)" 
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