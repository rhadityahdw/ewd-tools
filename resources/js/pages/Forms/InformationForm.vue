<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Borrower } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useToast } from 'vue-toastification';

const props = defineProps<{
    borrowers: Borrower[];
    borrower_id: number;
}>();

const form = useForm({
    borrower_id: props.borrower_id ?? '',
    borrower_group: '',
    purpose: '',
    economic_sector: '',
    business_field: '',
    borrower_business: '',
    collectibility: 0,
    restructuring: false,
});

const toast = useToast();
const isSubmitting = ref(false);

const submit = () => {
    form.post(route('forms.borrower'), {
        onSuccess: () => {
            isSubmitting.value = false;
            toast.success('Data berhasil disimpan');
        },
        onError: () => {
            isSubmitting.value = false;
            toast.error('Terjadi kesalahan saat menyimpan data');
        },
    });
    isSubmitting.value = true;
};

const purposes = ref([
    { value: 'kie', label: 'KIE' },
    { value: 'kmke', label: 'KMKE' },
    { value: 'both', label: 'KIE & KMKE' },
]);

const economicSectors = ref([
    { value: 'pertanian', label: 'Pertanian' },
    { value: 'manufaktur', label: 'Manufaktur' },
    { value: 'jasa', label: 'Jasa' },
]);

const businessFields = ref([
    { value: 'retail', label: 'Retail' },
    { value: 'grosir', label: 'Grosir' },
    { value: 'ekspor', label: 'Ekspor' },
]);

const businessTypes = ref([
    { value: 'mikro', label: 'Mikro' },
    { value: 'kecil', label: 'Kecil' },
    { value: 'menengah', label: 'Menengah' },
    { value: 'besar', label: 'Besar' },
]);
</script>

<template>
    <Head title="Informasi Debitur" />

    <div class="container mx-auto px-4 py-8">
        <Card>
            <CardContent>
                <form @submit.prevent="submit">
                    <div class="space-y-4">
                        <!-- Nama Debitur -->
                        <div class="sm:grid-cols-[200px, 1fr] grid grid-cols-1 items-start gap-2 p-2 transition-colors sm:items-center sm:gap-3">
                            <Label for="borrower_name" class="text-base font-medium">Nama Debitur</Label>
                            <Select v-model="form.borrower_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Pilih debitur" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-if="borrowers" v-for="borrower in borrowers" :key="borrower.id" :value="borrower.id">
                                            {{ borrower.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.borrower_id" />
                        </div>

                        <!-- Grup Usaha -->
                        <div class="sm:grid-cols-[200px, 1fr] grid grid-cols-1 items-start gap-2 p-2 transition-colors sm:items-center sm:gap-3">
                            <Label for="borrower_group" class="text-base font-medium">Grup Usaha</Label>
                            <Input
                                id="borrower_group"
                                v-model="form.borrower_group"
                                type="text"
                                placeholder="Masukan grup usaha"
                                class="w-full text-sm"
                            />
                            <InputError :message="form.errors.borrower_group" />
                        </div>

                        <!-- Tujuan -->
                        <div class="sm:grid-cols-[200px, 1fr] grid grid-cols-1 items-start gap-2 p-2 transition-colors sm:items-center sm:gap-3">
                            <Label for="purpose" class="text-base font-medium">Tujuan</Label>
                            <Select v-model="form.purpose">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Pilih tujuan" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="purpose in purposes" :key="purpose.value" :value="purpose.value">
                                            {{ purpose.label }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.purpose" />
                        </div>

                        <!-- Sektor Ekonomi -->
                        <div class="sm:grid-cols-[200px, 1fr] grid grid-cols-1 items-start gap-2 p-2 transition-colors sm:items-center sm:gap-3">
                            <Label for="economic_sector" class="text-base font-medium">Sektor Ekonomi</Label>
                            <Select v-model="form.economic_sector">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Pilih sektor ekonomi" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="sector in economicSectors" :key="sector.value" :value="sector.value">
                                            {{ sector.label }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.economic_sector" />
                        </div>

                        <!-- Bidang Usaha -->
                        <div class="sm:grid-cols-[200px, 1fr] grid grid-cols-1 items-start gap-2 p-2 transition-colors sm:items-center sm:gap-3">
                            <Label for="business_field" class="text-base font-medium">Bidang Usaha</Label>
                            <Select v-model="form.business_field">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Pilih bidang usaha" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="field in businessFields" :key="field.value" :value="field.value">
                                            {{ field.label }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.business_field" />
                        </div>

                        <!-- Bisnis Debitur -->
                        <div class="sm:grid-cols-[200px, 1fr] grid grid-cols-1 items-start gap-2 p-2 transition-colors sm:items-center sm:gap-3">
                            <Label for="borrower_business" class="text-base font-medium">Bisnis Debitur</Label>
                            <Select v-model="form.borrower_business">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Pilih bisnis debitur" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem v-for="type in businessTypes" :key="type.value" :value="type.value">
                                            {{ type.label }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.borrower_business" />
                        </div>

                        <!-- Kolektabilitas -->
                        <div class="sm:grid-cols-[200px, 1fr] grid grid-cols-1 items-start gap-2 p-2 transition-colors sm:items-center sm:gap-3">
                            <Label for="collectibility" class="text-base font-medium">Kolektabilitas</Label>
                            <Input
                                id="collectibility"
                                v-model="form.collectibility"
                                type="number"
                                placeholder="Masukan kolektabilitas"
                                min="1"
                                max="5"
                                class="w-full text-sm"
                            />
                            <InputError :message="form.errors.collectibility" />
                        </div>

                        <!-- Restrukturisasi -->
                        <div class="sm:grid-cols-[200px, 1fr] grid grid-cols-1 items-start gap-2 p-2 transition-colors sm:items-center sm:gap-3">
                            <Label for="restructuring" class="text-base font-medium">Restrukturisasi</Label>
                            <div class="flex items-center space-x-2">
                                <Checkbox id="restructuring" v-model:checked="form.restructuring" />
                                <Label for="restructuring" class="text-sm font-normal">Ya</Label>
                            </div>
                            <InputError :message="form.errors.restructuring" />
                        </div>
                    </div>
                </form>
            </CardContent>
        </Card>
    </div>
</template>
