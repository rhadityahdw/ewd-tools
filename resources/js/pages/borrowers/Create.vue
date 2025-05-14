<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'; 
import InputError from '@/components/InputError.vue';
import { Division, BreadcrumbItem } from '@/types';

defineProps<{
    divisions: Division[]
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Debitur',
        href: route('borrowers.index'),
    },
    {
        title: 'Tambah Debitur',
        href: route('borrowers.create'),
    },
];

const form = useForm({
    name: '',
    division_id: null,
});

const canSubmit = () => {
    return form.name.trim().length > 0 && form.division_id !== null;
};

const submit = () => {
    if (!form.division_id) {
        form.errors.division_id = 'Division is required';
        return;
    }
    
    form.post(route('borrowers.store'), {
        preserveScroll: true,
    });
};

const resetForm = () => {
    form.reset('name', 'division_id');
}
</script>

<template>
    <Head title="Tambah Debitur" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-xl md:text-2xl font-bold">Tambah Debitur</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-2">
                                <Label for="name">Nama Debitur</Label>
                                <Input id="name" v-model="form.name" autocomplete="name" placeholder="Masukan nama debitur" />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="division_id">Divisi</Label>
                                <Select v-model="form.division_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Pilih divisi" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-if="divisions" v-for="division in divisions" :key="division.id" :value="division.id" >
                                            {{ division.code }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.division_id" />
                            </div>

                            <div class="flex justify-end space-x-2">
                                <Button 
                                    type="button" 
                                    variant="outline" 
                                    @click="resetForm"
                                    v-if="form.name || form.division_id"
                                >
                                    Reset
                                </Button>
                                <Button 
                                    type="submit" 
                                    :disabled="form.processing || !canSubmit"
                                >
                                    Simpan Data
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>