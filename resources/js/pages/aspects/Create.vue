<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { PlusCircle, Save, Trash2 } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Aspek', href: route('aspects.index') },
    { title: 'Tambah Aspek', href: route('aspects.create') },
];

const form = useForm({
    name: '',
    description: '',
    questions: [
        {
            question_text: '',
            weight: 1,
            max_score: 100,
            min_score: 0,
            is_mandatory: false,
            options: [{ option_text: '', score: 0 }],
        },
    ],
});

const addQuestion = () => {
    form.questions.push({
        question_text: '',
        weight: 1,
        max_score: 100,
        min_score: 0,
        is_mandatory: false,
        options: [{ option_text: '', score: 0 }],
    });
};

const addOption = (questionIndex: number) => {
    form.questions[questionIndex].options.push({
        option_text: '',
        score: 0,
    });
};

const removeOption = (questionIndex: number, optionIndex: number) => {
    if (form.questions[questionIndex].options.length > 1) {
        form.questions[questionIndex].options.splice(optionIndex, 1);
    }
};

const removeQuestion = (index: number) => {
    if (form.questions.length > 1) {
        form.questions.splice(index, 1);
    }
};

const submit = () => {
    // Validate total weight
    const totalWeight = form.questions.reduce((sum, q) => sum + q.weight, 0);
    if (Math.abs(totalWeight - 100) > 0.01) {
        alert('Total bobot pertanyaan harus 100%');
        return;
    }

    // Clean up empty options
    form.questions.forEach((question) => {
        question.options = question.options.filter((option) => option.option_text.trim() !== '');
    });

    form.post(route('aspects.store'));
};
</script>

<template>
    <Head title="Buat Aspek" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-xl font-bold md:text-2xl">Tambah Aspek Baru</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-8">
                            <div class="grid gap-2">
                                <Label for="name">Nama Aspek</Label>
                                <Input id="name" v-model="form.name" required />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="description">Deskripsi</Label>
                                <Textarea id="description" v-model="form.description" />
                                <InputError :message="form.errors.description" />
                            </div>

                            <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold">Pertanyaan</h3>
                                    <Button type="button" @click="addQuestion" variant="outline">
                                        <PlusCircle class="mr-2 h-4 w-4" />
                                        Tambah Pertanyaan
                                    </Button>
                                </div>

                                <div v-for="(question, qIndex) in form.questions" :key="qIndex" class="space-y-4 rounded-lg border p-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-medium">Pertanyaan {{ qIndex + 1 }}</h4>
                                        <Button
                                            v-if="form.questions.length > 1"
                                            type="button"
                                            @click="removeQuestion(qIndex)"
                                            variant="destructive"
                                            size="sm"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>

                                    <div class="grid gap-2">
                                        <Label>Teks Pertanyaan</Label>
                                        <Textarea v-model="question.question_text" required />
                                        <InputError :message="(form.errors as any)[`questions.${qIndex}.question_text`]" />
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                                        <div>
                                            <Label>Bobot (%)</Label>
                                            <Input type="number" v-model.number="question.weight" min="0" max="100" step="0.01" />
                                            <InputError :message="(form.errors as any)[`questions.${qIndex}.weight`]" />
                                        </div>
                                        <div>
                                            <Label>Skor Maksimal</Label>
                                            <Input type="number" v-model.number="question.max_score" min="0" />
                                            <InputError :message="(form.errors as any)[`questions.${qIndex}.max_score`]" />
                                        </div>
                                        <div>
                                            <Label>Skor Minimal</Label>
                                            <Input type="number" v-model.number="question.min_score" min="0" />
                                            <InputError :message="(form.errors as any)[`questions.${qIndex}.min_score`]" />
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <Checkbox v-model:checked="question.is_mandatory" />
                                            <Label>Wajib</Label>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <Label>Opsi Jawaban</Label>
                                            <Button type="button" @click="addOption(qIndex)" variant="outline" size="sm">
                                                <PlusCircle class="mr-2 h-4 w-4" />
                                                Tambah Opsi
                                            </Button>
                                        </div>

                                        <div v-for="(option, oIndex) in question.options" :key="oIndex" class="flex items-center space-x-2">
                                            <Input v-model="option.option_text" placeholder="Teks opsi" class="flex-1" />
                                            <Input type="number" v-model.number="option.score" placeholder="Skor" class="w-20" />
                                            <Button
                                                v-if="question.options.length > 1"
                                                type="button"
                                                @click="removeOption(qIndex, oIndex)"
                                                variant="destructive"
                                                size="sm"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <Link :href="route('aspects.index')">
                                    <Button type="button" variant="outline">Batal</Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing">
                                    <Save class="mr-2 h-4 w-4" />
                                    Simpan Aspek
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
