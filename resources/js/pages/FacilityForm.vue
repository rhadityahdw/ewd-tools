<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent } from '@/components/ui/card';
import { Table, TableHeader, TableRow, TableHead, TableCell, TableBody } from '@/components/ui/table';
import { Plus, Trash2 } from 'lucide-vue-next';
import { ref, computed } from 'vue';
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
    }
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
    }
]);

const addKieRow = () => {
    const newIndex = kieRows.value.length + 1
    kieRows.value.push({
        name: `KIE ${newIndex}`,
        limit: 0,
        outstanding: 0,
        interestRate: 0,
        principalArrears: 0,
        interestArrears: 0,
        pdo: 0,
        dueDate: 0
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
    const newIndex = kmkeRows.value.length + 1
    kmkeRows.value.push({
        name: `KMKE ${newIndex}`,
        limit: 0,
        outstanding: 0,
        interestRate: 0,
        principalArrears: 0,
        interestArrears: 0,
        pdo: 0,
        dueDate: 0
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
                closeOnClick: true
            });
        },
        onError: (errors) => {
            isSubmitting.value = false;
            toast.error('Terjadi kesalahan saat menyimpan data', {
                timeout: 3000,
                position: POSITION.TOP_RIGHT,
                closeOnClick: true
            });
            console.error(errors);
        }
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
})

</script>

<template>
    <Head title="Fasilitas Debitur" />

    <div class="min-h-screen">
        <!-- Form -->
        <form>
                        <div class="overflow-x-auto -mx-4 sm:-mx-6">
                            <div class="inline-block min-w-full align-middle">
                                <Table class="min-w-full border border-gray-200">
                                    <TableHeader class="bg-muted">
                                        <TableRow class="border-b border-gray-200">
                                            <TableHead class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-2" rowspan="2">Rincian Fasilitas</TableHead>
                                            <TableHead class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-2" rowspan="2">Limit</TableHead>
                                            <TableHead class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-2" rowspan="2">Outstanding</TableHead>
                                            <TableHead class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-2" rowspan="2">Suku Bunga Efektif (%)</TableHead>
                                            <TableHead class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-2" colspan="2">Tunggakan</TableHead>
                                            <TableHead class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-2" rowspan="2">PDO (Hari)</TableHead>
                                            <TableHead class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-2" rowspan="2">Jatuh Tempo</TableHead>
                                            <TableHead class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-2" rowspan="2">Aksi</TableHead>
                                        </TableRow>
                                        <TableRow class="border-b border-gray-200">
                                            <TableHead class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-2">Pokok</TableHead>
                                            <TableHead class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-2">Bunga</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        <TableRow>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3 font-medium">KIE (IDR Juta)</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ totalKieLimit }}</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ totalKieOutstanding }}</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ avgKieInterestRate }}%</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ totalKiePrincipalArrears }}</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ totalKieInterestArrears }}</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ avgKiePdo }}</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ avgKieInterestRate }}</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">
                                                <button type="button" @click="addKieRow" class="px-2 py-1 sm:px-3 sm:py-1.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors shadow-none">
                                                    <Plus class="w-3 h-3 sm:w-4 sm:h-4"/>
                                                </button>
                                            </TableCell>
                                        </TableRow>
                                        <TableRow v-for="(row, index) in kieRows" :key="`kie-${index}`">
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">
                                                <Input type="text" v-model="row.name" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.limit" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.outstanding" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.interestRate" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.principalArrears" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.interestArrears" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.pdo" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.dueDate" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <button type="button" @click="removeKieRow(index)" class="px-2 py-1 sm:px-3 sm:py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors shadow-none">
                                                    <Trash2 class="w-3 h-3 sm:w-4 sm:h-4"/>
                                                </button>
                                            </TableCell>
                                        </TableRow>
                                        <TableRow>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3 font-medium">KMKE (IDR Juta)</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ totalKmkeLimit }}</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ totalKmkeOutstanding }}</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ avgKmkeInterestRate }}</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ totalKmkePrincipalArrears }}</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ totalKmkeInterestArrears }}</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">{{ avgKmkePdo }}</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">5.000</TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <button type="button" @click="addKmkeRow" class="px-2 py-1 sm:px-3 sm:py-1.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors shadow-none">
                                                    <Plus class="w-3 h-3 sm:w-4 sm:h-4"/>
                                                </button>
                                            </TableCell>
                                        </TableRow>
                                        <TableRow v-for="(row, index) in kmkeRows" :key="`kmke-${index}`">
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 text-xs sm:text-sm md:text-base md:px-4 md:py-3">
                                                <Input type="text" v-model="row.name" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.limit" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.outstanding" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.interestRate" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.principalArrears" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.interestArrears" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.pdo" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <Input type="number" v-model="row.dueDate" class="w-full text-xs sm:text-sm md:text-base"/>
                                            </TableCell>
                                            <TableCell class="px-2 py-2 text-center border border-gray-200 md:px-4 md:py-3">
                                                <button type="button" @click="removeKmkeRow(index)" class="px-2 py-1 sm:px-3 sm:py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors shadow-none">
                                                    <Trash2 class="w-3 h-3 sm:w-4 sm:h-4"/>
                                                </button>
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-4">
                            <Button type="button" variant="outline" as-child class="text-xs sm:text-sm">
                                <Link :href="route('forms.borrower')">Sebelumnya</Link>
                            </Button>
                            <Button type="button" @click="submit" :disabled="isSubmitting" class="bg-[#2E3192] hover:bg-[#232578] text-xs sm:text-sm">
                                <span v-if="isSubmitting">Menyimpan...</span>
                                <span v-else>Berikutnya</span>
                            </Button>
                        </div>
                    </form>
    </div>
</template>

<style scoped>
/* Tambahkan gaya untuk memperjelas baris total */
:deep(tr.bg-blue-50), :deep(tr.bg-indigo-50) {
    font-weight: 600;
}

/* Gaya untuk input */
:deep(.input) {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem;
    background-color: white;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

:deep(.input:focus) {
    outline: none;
    border-color: #2E3192;
    box-shadow: 0 0 0 2px rgba(46, 49, 146, 0.2);
}

/* Gaya untuk tabel pada perangkat mobile */
@media (max-width: 640px) {
    :deep(.input) {
        padding: 0.375rem;
        font-size: 0.875rem;
    }
}
</style>