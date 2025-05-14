<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import InputError from '@/components/InputError.vue';
import type { BreadcrumbItem, Borrower, Division } from '@/types';

const props = defineProps<{
    borrower: Borrower;
    divisions: Division[]; 
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
        title: 'Edit',
        href: route('borrowers.edit', props.borrower.id),
    },
];

const form = useForm({
    name: props.borrower.name,
    division_id: props.borrower.division_id.toString(),
});

const submit = () => {
    form.put(route('borrowers.update', props.borrower.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Redirect handled by controller
        },
    });
};
</script>

<template>
    <Head title="Edit Borrower"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-xl md:text-2xl font-bold">Edit Borrower</CardTitle>
                        <Link :href="route('borrowers.index')">
                            <Button variant="outline">Back to Borrowers</Button>
                        </Link>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-2">
                                <Label for="name">Nama Debitur</Label>
                                <Input id="name" v-model="form.name" required autocomplete="name" />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="division_id">Divisi</Label>
                                <Select v-model="form.division_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Pilih divisi" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-if="divisions" v-for="division in props.divisions" :key="division.id" :value="division.id.toString()" >
                                            {{ division.code }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.division_id" />
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <Link :href="route('borrowers.index')">
                                    <Button type="button" variant="outline">Cancel</Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing">Update</Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>