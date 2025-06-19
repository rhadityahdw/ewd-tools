<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent, CardFooter } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Head, Link } from '@inertiajs/vue3';
import { BreadcrumbItem } from '@/types';
import { ArrowLeft, Edit, CheckCircle, XCircle } from 'lucide-vue-next';

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
    version_number?: number;
    options: QuestionOption[];
}

interface AspectData {
    id: number;
    name: string;
    code: string;
    description: string;
    version: number;
    questions: Question[];
}

const props = defineProps<{
    aspect: AspectData
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Aspek',
        href: route('aspects.index'),
    },
    {
        title: props.aspect.name,
        href: route('aspects.show', props.aspect.id),
    }
];
</script>

<template>
    <Head :title="`Aspek: ${aspect.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Detail Aspek -->
                <Card class="mb-8">
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle class="text-xl md:text-2xl font-bold">
                                {{ aspect.name }}
                            </CardTitle>
                            <div class="flex items-center gap-2 mt-2">
                                <Badge variant="outline">{{ aspect.code }}</Badge>
                                <Badge variant="secondary">v{{ aspect.version }}</Badge>
                            </div>
                        </div>
                        <Link :href="route('aspects.edit', aspect.id)">
                            <Button variant="outline">
                                <Edit class="w-4 h-4 mr-2" />
                                Edit Aspek
                            </Button>
                        </Link>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-medium">Deskripsi</h3>
                                <p class="mt-1 text-gray-600">{{ aspect.description || 'Tidak ada deskripsi' }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Daftar Pertanyaan -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg font-bold">
                            Daftar Pertanyaan ({{ aspect.questions.length }})
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="aspect.questions.length === 0" class="text-center py-8 text-gray-500">
                            Belum ada pertanyaan untuk aspek ini.
                        </div>
                        <div v-else class="space-y-6">
                            <div v-for="(question, index) in aspect.questions" :key="question.id" class="border p-6 rounded-lg bg-gray-50 dark:bg-gray-800">
                                <div class="space-y-4">
                                    <!-- Question Header -->
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h4 class="font-semibold text-lg">Pertanyaan {{ index + 1 }}</h4>
                                                <Badge v-if="question.is_mandatory" variant="destructive" class="text-xs">
                                                    <CheckCircle class="w-3 h-3 mr-1" />
                                                    Wajib
                                                </Badge>
                                                <Badge v-else variant="secondary" class="text-xs">
                                                    <XCircle class="w-3 h-3 mr-1" />
                                                    Opsional
                                                </Badge>
                                            </div>
                                            <p class="text-gray-700 dark:text-gray-300 mb-3">{{ question.question_text }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Question Details -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div class="bg-white dark:bg-gray-700 p-3 rounded border">
                                            <span class="font-medium text-gray-500">Bobot:</span>
                                            <span class="ml-2 font-semibold">{{ question.weight }}</span>
                                        </div>
                                        <div class="bg-white dark:bg-gray-700 p-3 rounded border">
                                            <span class="font-medium text-gray-500">Skor Maksimal:</span>
                                            <span class="ml-2 font-semibold">{{ question.max_score }}</span>
                                        </div>
                                        <div class="bg-white dark:bg-gray-700 p-3 rounded border">
                                            <span class="font-medium text-gray-500">Skor Minimal:</span>
                                            <span class="ml-2 font-semibold">{{ question.min_score }}</span>
                                        </div>
                                    </div>

                                    <!-- Question Options -->
                                    <div v-if="question.options && question.options.length > 0" class="mt-4">
                                        <h5 class="font-medium text-gray-700 dark:text-gray-300 mb-3">Pilihan Jawaban:</h5>
                                        <div class="grid gap-2">
                                            <div v-for="(option, optionIndex) in question.options" :key="option.id" 
                                                 class="flex justify-between items-center p-3 bg-white dark:bg-gray-700 rounded border">
                                                <div class="flex items-center">
                                                    <span class="w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full flex items-center justify-center text-xs font-medium mr-3">
                                                        {{ String.fromCharCode(65 + optionIndex) }}
                                                    </span>
                                                    <span>{{ option.option_text }}</span>
                                                </div>
                                                <Badge variant="outline" class="ml-2">
                                                    {{ option.score }} poin
                                                </Badge>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <div class="flex justify-between w-full">
                            <Link :href="route('aspects.index')">
                                <Button variant="outline">
                                    <ArrowLeft class="w-4 h-4 mr-2" />
                                    Kembali
                                </Button>
                            </Link>
                        </div>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>