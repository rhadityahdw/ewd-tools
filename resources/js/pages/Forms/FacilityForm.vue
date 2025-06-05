<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Head, useForm } from '@inertiajs/vue3';
import { Plus, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { POSITION, useToast } from 'vue-toastification';

const toast = useToast();

const facilityTypes = ref<string[]>([]);
const borrowerDetail = ref({
    purpose: 'both',
});

const isSubmitting = ref(false);

const kieRows = ref([
    {
        name: 'KIE 1',
        limit: 0,
        outstanding: 0,
        interestRate: 0,
        principalArrears: 0,
        interestArrears: 0,
        pdo: 0,
        dueDate: 0,
    },
]);

const kmkeRows = ref([
    {
        name: 'KMKE 1',
        limit: 0,
        outstanding: 0,
        interestRate: 0,
        principalArrears: 0,
        interestArrears: 0,
        pdo: 0,
        dueDate: 0,
    },
]);

const addKieRow = () => {
    const newIndex = kieRows.value.length + 1;
    kieRows.value.push({
        name: `KIE ${newIndex}`,
        limit: 0,
        outstanding: 0,
        interestRate: 0,
        principalArrears: 0,
        interestArrears: 0,
        pdo: 0,
        dueDate: 0,
    });
};

const removeKieRow = (index: number) => {
    if (kieRows.value.length > 1) {
        kieRows.value.splice(index, 1);
        kieRows.value.forEach((row, idx) => {
            row.name = `KIE ${idx + 1}`;
        });
    }
};

const addKmkeRow = () => {
    const newIndex = kmkeRows.value.length + 1;
    kmkeRows.value.push({
        name: `KMKE ${newIndex}`,
        limit: 0,
        outstanding: 0,
        interestRate: 0,
        principalArrears: 0,
        interestArrears: 0,
        pdo: 0,
        dueDate: 0,
    });
};

const removeKmkeRow = (index: number) => {
    if (kmkeRows.value.length > 1) {
        kmkeRows.value.splice(index, 1);
        kmkeRows.value.forEach((row, idx) => {
            row.name = `KMKE ${idx + 1}`;
        });
    }
};

const submit = () => {
    isSubmitting.value = true;

    // Mengambil data dari fasilitas yang dipilih (misalnya KIE pertama)
    if (kieRows.value.length > 0) {
        const selectedFacility = kieRows.value[0];

        // Mengisi form dengan data dari fasilitas
        form.facility_name = selectedFacility.name;
        form.limit = selectedFacility.limit;
        form.outstanding = selectedFacility.outstanding;
        form.interest_rate = selectedFacility.interestRate;
        form.principal_arrears = selectedFacility.principalArrears;
        form.interest_arrears = selectedFacility.interestArrears;
        form.pdo_days = selectedFacility.pdo;
        form.maturity_date = new Date().toISOString().split('T')[0]; // Format tanggal YYYY-MM-DD
    }

    // Kirim data ke endpoint menggunakan Inertia
    form.post(route('forms.facility'), {
        onSuccess: () => {
            isSubmitting.value = false;
            toast.success('Data fasilitas berhasil disimpan', {
                timeout: 3000,
                position: POSITION.TOP_RIGHT,
                closeOnClick: true,
            });
        },
        onError: (errors) => {
            isSubmitting.value = false;
            toast.error('Terjadi kesalahan saat menyimpan data', {
                timeout: 3000,
                position: POSITION.TOP_RIGHT,
                closeOnClick: true,
            });
            console.error(errors);
        },
    });
};

// Menghitung total limit KIE
const totalKieLimit = computed(() => {
    return kieRows.value.reduce((sum, row) => sum + Number(row.limit), 0);
});

// Menghitung total outstanding KIE
const totalKieOutstanding = computed(() => {
    return kieRows.value.reduce((sum, row) => sum + Number(row.outstanding), 0);
});

// Menghitung total interest rate KIE (rata-rata)
const avgKieInterestRate = computed(() => {
    if (kieRows.value.length === 0) return 0;
    const total = kieRows.value.reduce((sum, row) => sum + Number(row.interestRate), 0);
    return (total / kieRows.value.length).toFixed(2);
});

// Menghitung total principal arrears KIE
const totalKiePrincipalArrears = computed(() => {
    return kieRows.value.reduce((sum, row) => sum + Number(row.principalArrears), 0);
});

// Menghitung total interest arrears KIE
const totalKieInterestArrears = computed(() => {
    return kieRows.value.reduce((sum, row) => sum + Number(row.interestArrears), 0);
});

// Menghitung total PDO KIE (rata-rata)
const avgKiePdo = computed(() => {
    if (kieRows.value.length === 0) return 0;
    const total = kieRows.value.reduce((sum, row) => sum + Number(row.pdo), 0);
    return Math.round(total / kieRows.value.length);
});

// Menghitung total limit KMKE
const totalKmkeLimit = computed(() => {
    return kmkeRows.value.reduce((sum, row) => sum + Number(row.limit), 0);
});

// Menghitung total outstanding KMKE
const totalKmkeOutstanding = computed(() => {
    return kmkeRows.value.reduce((sum, row) => sum + Number(row.outstanding), 0);
});

// Menghitung total interest rate KMKE (rata-rata)
const avgKmkeInterestRate = computed(() => {
    if (kmkeRows.value.length === 0) return 0;
    const total = kmkeRows.value.reduce((sum, row) => sum + Number(row.interestRate), 0);
    return (total / kmkeRows.value.length).toFixed(2);
});

// Menghitung total principal arrears KMKE
const totalKmkePrincipalArrears = computed(() => {
    return kmkeRows.value.reduce((sum, row) => sum + Number(row.principalArrears), 0);
});

// Menghitung total interest arrears KMKE
const totalKmkeInterestArrears = computed(() => {
    return kmkeRows.value.reduce((sum, row) => sum + Number(row.interestArrears), 0);
});

// Menghitung total PDO KMKE (rata-rata)
const avgKmkePdo = computed(() => {
    if (kmkeRows.value.length === 0) return 0;
    const total = kmkeRows.value.reduce((sum, row) => sum + Number(row.pdo), 0);
    return Math.round(total / kmkeRows.value.length);
});

const form = useForm({
    facility_name: '',
    limit: 0,
    outstanding: 0,
    interest_rate: 0,
    principal_arrears: 0,
    interest_arrears: 0,
    pdo_days: 0,
    maturity_date: '',
});

const handleSubmit = () => {
    form.post(route('forms.facility'), {
        onSuccess: () => {
            isSubmitting.value = false;
        },
    });
};
</script>

<template>
    <Head title="Fasilitas Debitur" />

    <div class="container mx-auto px-4 py-8">
        <form @submiy.prevent="handleSubmit">
            <div class="mx-4 overflow-x-auto sm:-mx-6">
                <div class="inline-block min-w-full align-middle">
                    <Table class="min-w-full border border-gray-200">
                        <TableHeader class="bg-muted">
                            <TableRow class="border-b border-gray-200">
                                <TableHead
                                    class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-2 md:text-base"
                                    rowspan="2"
                                    >Rincian Fasilitas</TableHead
                                >
                                <TableHead
                                    class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-2 md:text-base"
                                    rowspan="2"
                                    >Limit</TableHead
                                >
                                <TableHead
                                    class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-2 md:text-base"
                                    rowspan="2"
                                    >Outstanding</TableHead
                                >
                                <TableHead
                                    class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-2 md:text-base"
                                    rowspan="2"
                                    >Suku Bunga Efektif (%)</TableHead
                                >
                                <TableHead
                                    class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-2 md:text-base"
                                    colspan="2"
                                    >Tunggakan</TableHead
                                >
                                <TableHead
                                    class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-2 md:text-base"
                                    rowspan="2"
                                    >PDO (Hari)</TableHead
                                >
                                <TableHead
                                    class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-2 md:text-base"
                                    rowspan="2"
                                    >Jatuh Tempo</TableHead
                                >
                                <TableHead
                                    class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-2 md:text-base"
                                    rowspan="2"
                                    >Aksi</TableHead
                                >
                            </TableRow>
                            <TableRow class="border-b border-gray-200">
                                <TableHead class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-2 md:text-base"
                                    >Pokok</TableHead
                                >
                                <TableHead class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-2 md:text-base"
                                    >Bunga</TableHead
                                >
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow>
                                <TableCell
                                    class="border border-gray-200 px-2 py-2 text-center text-xs font-medium sm:text-sm md:px-4 md:py-3 md:text-base"
                                    >KIE (IDR Juta)</TableCell
                                >
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">{{
                                    totalKieLimit
                                }}</TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">{{
                                    totalKieOutstanding
                                }}</TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base"
                                    >{{ avgKieInterestRate }}%</TableCell
                                >
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">{{
                                    totalKiePrincipalArrears
                                }}</TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">{{
                                    totalKieInterestArrears
                                }}</TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">{{
                                    avgKiePdo
                                }}</TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">{{
                                    avgKieInterestRate
                                }}</TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">
                                    <button
                                        type="button"
                                        @click="addKieRow"
                                        class="rounded-md bg-green-600 px-2 py-1 text-white shadow-none transition-colors hover:bg-green-700 sm:px-3 sm:py-1.5"
                                    >
                                        <Plus class="h-3 w-3 sm:h-4 sm:w-4" />
                                    </button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-for="(row, index) in kieRows" :key="`kie-${index}`">
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">
                                    <Input type="text" v-model="row.name" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.limit" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.outstanding" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.interestRate" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.principalArrears" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.interestArrears" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.pdo" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.dueDate" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <button
                                        type="button"
                                        @click="removeKieRow(index)"
                                        class="rounded-md bg-red-600 px-2 py-1 text-white shadow-none transition-colors hover:bg-red-700 sm:px-3 sm:py-1.5"
                                    >
                                        <Trash2 class="h-3 w-3 sm:h-4 sm:w-4" />
                                    </button>
                                </TableCell>
                            </TableRow>
                            <TableRow>
                                <TableCell
                                    class="border border-gray-200 px-2 py-2 text-center text-xs font-medium sm:text-sm md:px-4 md:py-3 md:text-base"
                                    >KMKE (IDR Juta)</TableCell
                                >
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">{{
                                    totalKmkeLimit
                                }}</TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">{{
                                    totalKmkeOutstanding
                                }}</TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">{{
                                    avgKmkeInterestRate
                                }}</TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">{{
                                    totalKmkePrincipalArrears
                                }}</TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">{{
                                    totalKmkeInterestArrears
                                }}</TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">{{
                                    avgKmkePdo
                                }}</TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base"
                                    >5.000</TableCell
                                >
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <button
                                        type="button"
                                        @click="addKmkeRow"
                                        class="rounded-md bg-green-600 px-2 py-1 text-white shadow-none transition-colors hover:bg-green-700 sm:px-3 sm:py-1.5"
                                    >
                                        <Plus class="h-3 w-3 sm:h-4 sm:w-4" />
                                    </button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-for="(row, index) in kmkeRows" :key="`kmke-${index}`">
                                <TableCell class="border border-gray-200 px-2 py-2 text-center text-xs sm:text-sm md:px-4 md:py-3 md:text-base">
                                    <Input type="text" v-model="row.name" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.limit" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.outstanding" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.interestRate" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.principalArrears" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.interestArrears" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.pdo" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <Input type="number" v-model="row.dueDate" class="w-full text-xs sm:text-sm md:text-base" />
                                </TableCell>
                                <TableCell class="border border-gray-200 px-2 py-2 text-center md:px-4 md:py-3">
                                    <button
                                        type="button"
                                        @click="removeKmkeRow(index)"
                                        class="rounded-md bg-red-600 px-2 py-1 text-white shadow-none transition-colors hover:bg-red-700 sm:px-3 sm:py-1.5"
                                    >
                                        <Trash2 class="h-3 w-3 sm:h-4 sm:w-4" />
                                    </button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </form>
    </div>
</template>
