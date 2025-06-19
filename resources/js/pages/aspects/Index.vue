<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Head, Link } from '@inertiajs/vue3';
import { BreadcrumbItem } from '@/types';
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

interface QuestionOption {
    id: number;
    option_text: string;
    score: number;
}

interface Question {
    id: number;
    question_text: string;
    weight: number;
    max_score: number;
    min_score: number;
    is_mandatory: boolean;
    options: QuestionOption[];
}

interface AspectData {
    id: number;
    name: string;
    code: string;
    description: string;
    version: number;
    questions_count: number;
    questions: Question[];
}

const props = defineProps<{
    aspects: AspectData[]
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
                                    <TableHead>Kode</TableHead>
                                    <TableHead>Versi</TableHead>
                                    <TableHead>Jumlah Pertanyaan</TableHead>
                                    <TableHead>Deskripsi</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="aspect in aspects" :key="aspect.id">
                                    <TableCell class="font-medium">{{ aspect.name }}</TableCell>
                                    <TableCell>
                                        <Badge variant="outline">{{ aspect.code }}</Badge>
                                    </TableCell>
                                    <TableCell>v{{ aspect.version }}</TableCell>
                                    <TableCell>{{ aspect.questions_count }}</TableCell>
                                    <TableCell class="max-w-xs truncate">{{ aspect.description || 'Tidak ada deskripsi' }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex space-x-2 justify-end">
                                            <Link :href="route('aspects.show', aspect.id)" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="Lihat">
                                                <Eye class="h-4 w-4" />
                                            </Link>
                                            <Link :href="route('aspects.edit', aspect.id)" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Edit">
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                            <Link 
                                                :href="route('aspects.destroy', aspect.id)" 
                                                method="delete" 
                                                as="button" 
                                                type="button"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus aspek ini?')"
                                                title="Hapus"
                                            >
                                                <Trash class="h-4 w-4" />
                                            </Link>
                                        </div>
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