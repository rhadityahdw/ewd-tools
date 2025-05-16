<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import DatePicker from '@/components/DatePicker.vue';
import { parseDate } from '@internationalized/date';
import { BreadcrumbItem, Period } from '@/types';
import { useToast } from 'vue-toastification';
import { ref, watch } from 'vue';

const props = defineProps<{
    period: Period;
}>();

const startDate = ref(parseDate(props.period.start_date.toString()));
const endDate = ref(parseDate(props.period.end_date.toString()));

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Periode',
        href: route('periods.index'),
    },
    {
        title: 'Edit Periode',
        href: route('periods.edit', props.period.id),
    },
];

const form = useForm({
    name: props.period.name,
    start_date: props.period.start_date,
    end_date: props.period.end_date,
    status: props.period.status
});

console.log(form);

const toast = useToast();

watch(startDate, (val) => {
  form.start_date = val ? new Date(val.toString()) : new Date();
});
watch(endDate, (val) => {
  form.end_date = val ? new Date(val.toString()) : new Date();
});

const submit = () => {
    form.put(route('periods.update', props.period.id), {
        onSuccess: () => {
            toast.success('Periode berhasil diperbarui');
        },
        onError: (errors) => {
            toast.error("Terjadi kesalahan saat memperbarui periode.");
            form.errors = errors;
        }
    });
};
</script>

<template>
    <Head title="Edit Periode" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-xl md:text-2xl font-bold">Edit Periode</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-2">
                                <Label for="name">Nama Periode</Label>
                                <Input id="name" v-model="form.name" placeholder="Masukan nama periode" />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="start_date">Tanggal Mulai</Label>
                                <DatePicker id="start_date" v-model="form.start_date" />
                                <InputError :message="form.errors.start_date" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="end_date">Tanggal Selesai</Label>
                                <DatePicker id="end_date" v-model="form.end_date" />
                                <InputError :message="form.errors.end_date" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="status">Status</Label>
                                <select id="status" v-model="form.status" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                    <option value="draft">Draft</option>
                                    <option value="active">Active</option>
                                    <option value="ended">Ended</option>
                                    <option value="expired">Expired</option>
                                </select>
                                <InputError :message="form.errors.status" />
                            </div>
                            <div class="flex justify-end space-x-2">
                                <Link :href="route('periods.index')">
                                    <Button type="button" variant="outline">Batal</Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing">Simpan Perubahan</Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>