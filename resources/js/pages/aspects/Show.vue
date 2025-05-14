<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent, CardFooter } from '@/components/ui/card';
import { Head, Link } from '@inertiajs/vue3';
import { BreadcrumbItem, Aspect, Question } from '@/types';
import { ArrowLeft, Edit } from 'lucide-vue-next';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
// import { Badge } from '@/components/ui/badge';

const props = defineProps<{
    aspect: Aspect & { questions: Question[] },
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
                            <p class="text-sm text-gray-500 mt-1">
                                Bobot: {{ aspect.weight }}
                            </p>
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
                                <p class="mt-1">{{ aspect.description || 'Tidak ada deskripsi' }}</p>
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
                        <div v-else class="space-y-4">
                            <div v-for="(question, index) in aspect.questions" :key="question.id" class="border p-4 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium">Pertanyaan {{ index + 1 }}</h4>
                                        <p class="mt-1">{{ question.question_text }}</p>
                                    </div>
                                    <!-- <Badge>Bobot: {{ question.weight }}</Badge> -->
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