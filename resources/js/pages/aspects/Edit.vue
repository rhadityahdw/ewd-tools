<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import InputError from '@/components/InputError.vue';
import { PlusCircle, Trash2, Save } from 'lucide-vue-next';
import type { BreadcrumbItem, Aspect, Question } from '@/types';

const props = defineProps<{
    aspect: Aspect & {
        questions: Question[]
    }
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
        title: 'Edit Aspek',
        href: route('aspects.edit', props.aspect.id),
    },
];

// Siapkan form dengan data yang sudah ada
const form = useForm({
    name: props.aspect.name,
    description: props.aspect.description || '',
    weight: props.aspect.weight,
    questions: props.aspect.questions.map(q => ({
        id: q.id,
        question_text: q.question_text,
        weight: q.weight
    }))
});

const addQuestion = () => {
    form.questions.push({
        id: props.aspect.questions.length + 1,
        question_text: '',
        weight: 1,
    });
};

const removeQuestion = (index: number) => {
    form.questions.splice(index, 1);
};

const submit = () => {
    // Pastikan nilai numerik dikonversi dengan benar
    const formData = {
        ...form.data(),
        weight: Number(form.weight),
        questions: form.questions.map(q => ({
            ...q,
            weight: Number(q.weight)
        }))
    };
    
    form.put(route('aspects.update', props.aspect.id), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Data berhasil diperbarui');
        },
        onError: (errors) => {
            console.error('Error saat memperbarui:', errors);
        }
    });
};
</script>

<template>
    <Head title="Edit Aspek" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-xl md:text-2xl font-bold">Edit Aspek</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-8">
                            <!-- Aspect Information -->
                            <div class="space-y-4 border p-4 rounded-lg">
                                <h3 class="text-lg font-semibold">Informasi Aspek</h3>
                                
                                <div class="grid gap-2">
                                    <Label for="name">Nama Aspek</Label>
                                    <Input id="name" v-model="form.name" required />
                                    <InputError :message="form.errors.name" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="description">Deskripsi</Label>
                                    <Textarea id="description" v-model="form.description" rows="3" />
                                    <InputError :message="form.errors.description" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="weight">Bobot</Label>
                                    <Input id="weight" v-model="form.weight" type="number" min="0" step="0.01" required />
                                    <InputError :message="form.errors.weight" />
                                </div>
                            </div>

                            <!-- Questions -->
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold">Pertanyaan</h3>
                                    <Button type="button" variant="outline" size="sm" @click="addQuestion">
                                        <PlusCircle class="h-4 w-4 mr-2" />
                                        Tambah Pertanyaan
                                    </Button>
                                </div>

                                <div v-if="form.errors.questions" class="text-red-500 text-sm">
                                    {{ form.errors.questions }}
                                </div>

                                <div v-for="(question, index) in form.questions" :key="index" class="border p-4 rounded-lg space-y-4">
                                    <div class="flex justify-between items-center">
                                        <h4 class="font-medium">Pertanyaan {{ index + 1 }}</h4>
                                        <Button type="button" variant="destructive" size="sm" @click="removeQuestion(index)" v-if="form.questions.length > 1">
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>

                                    <div class="grid gap-2">
                                        <Label :for="`question-text-${index}`">Teks Pertanyaan</Label>
                                        <Textarea :id="`question-text-${index}`" v-model="question.question_text" rows="2" required />
                                        <InputError :message="(form.errors as any)[`questions.${index}.question_text`]" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label :for="`question-weight-${index}`">Bobot</Label>
                                        <Input :id="`question-weight-${index}`" v-model="question.weight" type="number" min="0" step="0.01" required />
                                        <InputError :message="(form.errors as any)[`questions.${index}.weight`]" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <Link :href="route('aspects.index')">
                                    <Button type="button" variant="outline">
                                        Batal
                                    </Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing">
                                    <Save class="h-4 w-4 mr-2" />
                                    Simpan Perubahan
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>