<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardAction, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Head } from '@inertiajs/vue3';
import { BreadcrumbItem, Period } from '@/types';

defineProps<{
    periods: Period[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Periode',
        href: route('periods.index'),
    }
];

const startPeriod = () => {
    router.post(route('periods.open'));
}

const stopPeriod = (periodId: number) => {
    router.post(route('periods.close', periodId));
}
</script>

<template>
    <Head title="Periode" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-xl md:text-2xl font-bold">Periode</CardTitle>
                        <div>
                            <Button  
                                variant="default" 
                                @click="startPeriod"
                            >
                                Mulai Sesi Baru
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div v-if="periods.length === 0" class="text-center py-8 text-gray-500">
                            Belum ada periode yang terdaftar. Silakan mulai periode baru.
                        </div>
                        <Table v-else>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Nama</TableHead>
                                    <TableHead>Tanggal Mulai</TableHead>
                                    <TableHead>Tanggal Selesai</TableHead>
                                    <TableHead class="text-right"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                    <TableRow v-for="(period, index) in periods" :key="period.id">
                                        <TableCell>{{ period.name || `Periode ${index + 1}` }}</TableCell>
                                        <TableCell>{{ period.start_date }}</TableCell>
                                        <TableCell>{{ period.end_date || '-' }}</TableCell>
                                        <TableCell>
                                        <span v-if="period.status === 'open'" class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            Aktif
                                        </span>
                                        <span v-else class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/20">
                                            Tidak Aktif
                                        </span>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <Button 
                                            v-if="period.status === 'open'" 
                                            variant="destructive" 
                                            size="sm" 
                                            @click="stopPeriod(period.id)"
                                        >
                                            Hentikan Sesi
                                        </Button>
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