<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const toast = useToast();

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
        title: 'Tambah Divisi',
        href: route('divisions.create'),
    },
];

const form = useForm({
    code: '',
    name: '',
});

const submit = () => {
    form.post(route('divisions.store'), {
        onSuccess: () => {
            toast.success('Divisi berhasil ditambah.');
        },
        onError: () => {
            toast.error('Gagal menambah divisi. Silahkan periksa kembali data Anda.');
        },
    });
};
</script>

<template>
    <Head title="Tambah Divisi" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <CardTitle class="text-xl font-bold md:text-2xl">Tambah Divisi</CardTitle>
                    </CardHeader>
                    <form @submit.prevent="submit" class="space-y-6">
                        <CardContent class="space-y-6">
                            <div class="grid gap-2">
                                <Label for="code">Kode Divisi</Label>
                                <Input id="code" v-model="form.code" placeholder="Masukan kode divisi" />
                                <InputError :message="form.errors.code" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="name">Nama Divisi</Label>
                                <Input id="name" v-model="form.name" placeholder="Masukan nama divisi" />
                                <InputError :message="form.errors.name" />
                            </div>
                        </CardContent>

                        <CardFooter class="flex items-center justify-end gap-4">
                            <Link :href="route('divisions.index')">
                                <Button type="button" variant="outline">Batal</Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing || !form.isDirty">Simpan Data</Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
