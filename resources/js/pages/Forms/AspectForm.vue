<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Head, useForm } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

const showReport = ref(false);

// Data dummy untuk aspek-aspek
const aspectGroups = reactive([
    {
        id: 'A',
        name: 'Aspek Hukum',
        aspects: [
            {
                id: 'A.1',
                question: 'Apakah legalitas dan perijinan nasabah masih berlaku dan sesuai ketentuan?',
                value: 'Ya',
                notes: '',
            },
            {
                id: 'A.2',
                question:
                    'Apakah ada tuntutan/masalah hukum dari internal/eksternal/pihak ketiga yang berdampak signifikan terhadap kondisi keuangan perusahaan?',
                value: 'Tidak',
                notes: '',
            },
            {
                id: 'A.3',
                question:
                    'Apakah ada permasalahan (pemogokan, perselisihan tenaga kerja, pengurangan tenaga kerja, dll) antara manajemen/perusahaan dengan tenaga kerja yang berpotensi menggangu kelangsungan usaha debitur?',
                value: 'Tidak',
                notes: '',
            },
            {
                id: 'A.4',
                question: 'Apakah debitur terindikasi memulai usaha baru diluar core bisnis yang belum dikuasai?',
                value: 'Tidak',
                notes: '',
            },
            {
                id: 'A.5',
                question: 'Apakah justifikasi ekspornya masih terpenuhi sesuai dengan ketentuan?',
                value: 'Ya',
                notes: '',
            },
            {
                id: 'A.6',
                question: 'Apakah terdapat perikatan perjanjian yang masih belum sempurna?',
                value: 'Tidak',
                notes: '',
            },
            {
                id: 'A.7',
                question: 'Apakah debitur tidak dapat memenuhi kesepakatan atau pemenuhan covenant dalam Perjanjian Kredit?',
                value: 'Tidak',
                notes: '',
            },
            {
                id: 'A.8',
                question:
                    'Apakah debitur menggunakan KAP / KJPP / Asuransi / Notaris yang bukan Rekanan atau terdapat pembekuan izin/pengenaan sanksi dari regulator atau tidak mendapat izin OJK dan Kemenkeu atau QR tidak sesuai?',
                value: 'Ya',
                notes: '',
            },
        ],
    },
    {
        id: 'B',
        name: 'Aspek Manajemen',
        aspects: [
            {
                id: 'B.1',
                question: 'Apakah terdapat perubahan manajemen/pemegang saham yang berpotensi mempengaruhi kinerja perusahaan?',
                value: 'Tidak',
                notes: '',
            },
            {
                id: 'B.2',
                question: 'Apakah terdapat indikasi fraud yang dilakukan oleh manajemen/pemegang saham?',
                value: 'Tidak',
                notes: '',
            },
        ],
    },
]);

// Opsi untuk dropdown
const nilaiOptions = ['Ya', 'Tidak'];

// Form untuk submit data
const form = useForm({
    aspects: aspectGroups,
});

// Fungsi untuk submit form
const submitForm = () => {
    form.post(route('aspects.store'), {
        onSuccess: () => {
            alert('Data berhasil disimpan');
        },
    });
};

// Mendeteksi ukuran layar untuk responsivitas
const isMobile = ref(false);

// Fungsi untuk mengecek ukuran layar
const checkScreenSize = () => {
    isMobile.value = window.innerWidth < 768;
};

// Menjalankan pengecekan ukuran layar saat komponen dimount
if (typeof window !== 'undefined') {
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);
}

// Methods
const generateReport = () => {
    showReport.value = true;
};

const closeReport = () => {
    showReport.value = false;
};
</script>

<template>
    <Head title="Input Aspek" />

        <!-- Form -->
        <div class="container mx-auto px-4 py-8">
            <Card>
                <CardContent>
                    <form @submit.prevent="submitForm">
                        <div v-for="group in aspectGroups" :key="group.id" class="mb-8">
                            <!-- Judul Kelompok Aspek -->
                            <div class="mb-4 bg-gray-200 p-2">
                                <h2 class="font-bold">{{ group.id }}. {{ group.name }}</h2>
                            </div>

                            <!-- Tampilan Desktop -->
                            <div v-if="!isMobile" class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-blue-600 text-white">
                                            <th class="w-16 border border-gray-300 p-2 text-center">NO</th>
                                            <th class="border border-gray-300 p-2 text-left">ASPEK</th>
                                            <th class="w-32 border border-gray-300 p-2 text-center">NILAI</th>
                                            <th class="w-64 border border-gray-300 p-2 text-center">KETERANGAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="aspect in group.aspects" :key="aspect.id" class="hover:bg-gray-50">
                                            <td class="border border-gray-300 p-2 text-center">{{ aspect.id }}</td>
                                            <td class="border border-gray-300 p-2">{{ aspect.question }}</td>
                                            <td class="border border-gray-300 p-2">
                                                <Select v-model="aspect.value">
                                                    <SelectTrigger class="w-full">
                                                        <SelectValue :placeholder="aspect.value" />
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        <SelectItem v-for="option in nilaiOptions" :key="option" :value="option">
                                                            {{ option }}
                                                        </SelectItem>
                                                    </SelectContent>
                                                </Select>
                                            </td>
                                            <td class="border border-gray-300 p-2">
                                                <Textarea v-model="aspect.notes" placeholder="Masukkan keterangan..." class="min-h-[60px]" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Tampilan Mobile -->
                            <div v-else class="space-y-6">
                                <div v-for="aspect in group.aspects" :key="aspect.id" class="rounded-md border border-gray-300 bg-white p-4">
                                    <div class="mb-2 font-semibold text-blue-600">{{ aspect.id }}</div>
                                    <div class="mb-3">{{ aspect.question }}</div>

                                    <div class="mb-3">
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Nilai:</label>
                                        <Select v-model="aspect.value">
                                            <SelectTrigger class="w-full">
                                                <SelectValue :placeholder="aspect.value" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem v-for="option in nilaiOptions" :key="option" :value="option">
                                                    {{ option }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Keterangan:</label>
                                        <Textarea v-model="aspect.notes" placeholder="Masukkan keterangan..." class="min-h-[80px] w-full" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="mt-6 flex justify-end">
                            <Button @click="generateReport" class="w-full bg-blue-600 hover:bg-blue-700 md:w-auto">Simpan Data</Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>

        <!-- Modal -->
        <div v-if="showReport" class="fixed inset-0 z-10 overflow-y-auto" role="dialog" aria-labelledby="modal-title" aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pt-4 text-center sm:block sm:p-0">
                <!-- Background Overlay -->
                <div @click="closeReport" class="fixed inset-0 bg-black/50  backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

                <!-- Modal Content -->
                <div
                    class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl sm:align-middle"
                >
                    <div class="bg-white p-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 w-full text-center sm:mt-0 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium" id="modal-title">Hasil Penilaian Aspek</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Ringkasan hasil penilaian dari semua pertanyaan telah dijawab.</p>
                                </div>

                                <div class="mt-4 max-h-[60vh] overflow-y-auto pr-2">
                                    <div v-for="(item, index) in aspectGroups[0].aspects" :key="`report-${index}`" class="mb-6 last:mb-0">
                                        <div class="mb-2 flex items-center justify-between">
                                            <h4>{{ item.id }}</h4>
                                            <span
                                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                                :class="{
                                                    'bg-green-100 text-green-800': item.value === 'Ya',
                                                    'bg-red-100 text-red-800': item.value === 'Tidak',
                                                    'bg-gray-100 text-gray-800': !item.value,
                                                }"
                                                >{{ item.value || 'Belum dinilai' }}</span
                                            >
                                        </div>
                                        <p class="mb-2 text-sm">{{ item.question }}</p>
                                        <div v-if="item.notes" class="rounded-md bg-gray-100 p-3 text-sm">
                                            <span class="font-medium">Keterangan:</span> {{ item.notes }}
                                        </div>
                                        <hr class="my-4" v-if="index < aspectGroups[0].aspects.length - 1" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button
                            type="button"
                            @click="closeReport"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
</template>

<style scoped>
table {
    border-spacing: 0;
}

@media (max-width: 768px) {
    .container {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
}
</style>
