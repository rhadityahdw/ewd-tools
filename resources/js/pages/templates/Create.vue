<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useToast } from 'vue-toastification';

const toast = useToast();

interface AspectVersion {
    id: number;
    name: string;
    version: number;
}

interface Aspect {
    id: number;
    name: string;
    description: string;
    aspect_versions: AspectVersion[];
}

const props = defineProps<{
    aspects: Aspect[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Template', href: route('templates.index') },
    { title: 'Tambah Template', href: route('templates.create') },
];

const form = useForm({
    name: '',
    aspect_weights: [] as Array<{ aspect_version_id: number; weight: number }>,
});

const selectedAspects = ref<Array<{ aspect_version_id: number; weight: number }>>([]);

const totalWeight = computed(() => {
    return selectedAspects.value.reduce((sum, a) => sum + a.weight, 0);
});

const toggleAspect = (aspectVersionId: number) => {
    const index = selectedAspects.value.findIndex((a) => a.aspect_version_id === aspectVersionId);
    if (index === -1) {
        selectedAspects.value.push({ aspect_version_id: aspectVersionId, weight: 0 });
    } else {
        selectedAspects.value.splice(index, 1);
    }
};

const isAspectSelected = (aspectVersionId: number) => {
    return selectedAspects.value.some((a) => a.aspect_version_id === aspectVersionId);
};

const submit = () => {
    console.log(form);
    form.aspect_weights = selectedAspects.value.filter((a) => a.weight > 0);

    if (Math.abs(totalWeight.value - 100) > 0.01) {
        alert('Total bobot harus 100%');
        return;
    }

    form.post(route('templates.store'));
};

const resetForm = () => {
    form.reset();
};
</script>

<template>
    <Head title="Tambah Template" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <CardTitle class="text-xl font-bold md:text-2xl">Tambah Template</CardTitle>
                        <Link :href="route('templates.index')">
                            <Button type="button" variant="outline">Kembali</Button>
                        </Link>
                    </CardHeader>
                    <form @submit.prevent="submit" class="space-y-6">
                        <CardContent class="space-y-6">
                            <div class="grid gap-2">
                                <Label for="name">Nama Template</Label>
                                <Input id="name" v-model="form.name" placeholder="Masukan nama template" />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="space-y-4 rounded-lg border p-4">
                                <h3 class="text-lg font-semibold">Aspek yang Termasuk</h3>
                                <div class="mb-4 text-sm text-gray-600">Total Bobot: {{ totalWeight }}% (harus 100%)</div>

                                <div class="space-y-4">
                                    <div v-for="aspect in aspects" :key="aspect.id" class="rounded border p-4">
                                        <div v-for="version in aspect.aspect_versions" :key="version.id" class="flex items-center space-x-4">
                                            <Checkbox
                                                :id="`aspect-${version.id}`"
                                                :checked="isAspectSelected(version.id)"
                                                @update:checked="toggleAspect(version.id)"
                                            />
                                            <Label :for="`aspect-${version.id}`" class="flex-1"> {{ version.name }} (v{{ version.version }}) </Label>
                                            <div v-if="isAspectSelected(version.id)" class="flex items-center space-x-2">
                                                <Label>Bobot:</Label>
                                                <Input
                                                    type="number"
                                                    min="0"
                                                    max="100"
                                                    step="0.01"
                                                    class="w-20"
                                                    v-model.number="selectedAspects.find((a) => a.aspect_version_id === version.id)!.weight"
                                                />
                                                <span>%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <InputError :message="form.errors.aspect_weights" />
                            </div>
                        </CardContent>

                        <CardFooter class="flex justify-end space-x-4">
                            <Button type="button" variant="outline" @click="resetForm" v-if="form.isDirty">Reset</Button>
                            <Button type="submit" :disabled="form.processing || !form.isDirty">Simpan Template</Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
