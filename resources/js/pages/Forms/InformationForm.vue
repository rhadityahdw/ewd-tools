<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useFormStore } from '@/stores/formStore';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useToast } from 'vue-toastification';

const toast = useToast();
const formStore = useFormStore();

const props = defineProps({
    borrowers: Object,
    borrower_id: {
        type: Number,
        default: null,
    },
});

const borrowers = props.borrowers?.data;

console.log('Borrowers:', borrowers);

const selectedBorrower: any = ref(null);

const initialInformationBorrower = {
    borrowerId: formStore.informationBorrower.borrowerId || props.borrower_id,
    borrowerGroup: formStore.informationBorrower.borrowerGroup || '',
    purpose: formStore.informationBorrower.purpose || '',
    economicSector: formStore.informationBorrower.economicSector || '',
    businessField: formStore.informationBorrower.businessField || '',
    borrowerBusiness: formStore.informationBorrower.borrowerBusiness || '',
    collectibility: formStore.informationBorrower.collectibility || 1,
    restructuring: formStore.informationBorrower.restructuring || false,
};

const form = useForm({
    borrower_id: initialInformationBorrower.borrowerId,
    borrower_group: initialInformationBorrower.borrowerGroup,
    purpose: initialInformationBorrower.purpose,
    economic_sector: initialInformationBorrower.economicSector,
    business_field: initialInformationBorrower.businessField,
    borrower_business: initialInformationBorrower.borrowerBusiness,
    collectibility: initialInformationBorrower.collectibility,
    restructuring: initialInformationBorrower.restructuring,
});

watch(
    () => form.data(),
    (newData, oldData) => {
        if (newData.borrower_id !== oldData?.borrower_id) {
            if (newData.borrower_id) {
                const foundBorrower = borrowers?.find((borrower: any) => borrower.id === newData.borrower_id);
                if (foundBorrower) {
                    selectedBorrower.value = foundBorrower;
                    formStore.existingBorrowerId = newData.borrower_id;
                    formStore.existingBorrowerName = foundBorrower.name || '';
                } else {
                    selectedBorrower.value = null;
                    formStore.existingBorrowerId = null;
                    formStore.existingBorrowerName = '';
                }
            } else {
                selectedBorrower.value = null;
                formStore.existingBorrowerId = null;
                formStore.existingBorrowerName = '';
            }
        }

        formStore.updateInformationBorrower({
            borrowerId: newData.borrower_id,
            borrowerGroup: newData.borrower_group,
            purpose: newData.purpose,
            economicSector: newData.economic_sector,
            businessField: newData.business_field,
            borrowerBusiness: newData.borrower_business,
            collectibility: newData.collectibility,
            restructuring: newData.restructuring,
        });
    },
    { deep: true, immediate: true },
);

const purposeOptions = [
    { value: 'kie', label: 'KIE (Kredit Investasi dan Ekspansi)' },
    { value: 'kmke', label: 'KMKE (Kredit Modal Kerja Ekspor)' },
    { value: 'both', label: 'Keduanya' },
];

const collectibilityOptions = [
    { value: 1, label: '1 - Lancar' },
    { value: 2, label: '2 - Dalam Perhatian Khusus' },
    { value: 3, label: '3 - Kurang Lancar' },
    { value: 4, label: '4 - Diragukan' },
    { value: 5, label: '5 - Macet' },
];

const selectedBorrowerName = computed(() => {
    return selectedBorrower.value?.name || 'Belum dipilih';
});

defineExpose({
    form,
    submitForm: () => {
        return form.post(route('borrower-details.store'), {
            onSuccess: () => {
                toast.success('Data informasi debitur berhasil disimpan');
            },
            onError: (errors) => {
                console.error('Form errors:', errors);
                toast.error('Terjadi kesalahan saat menyimpan data');
            },
        });
    },
});
</script>

<template>
    <Head title="Informasi Debitur" />

    <div class="space-y-6 bg-red-500">
        <Card>
            <CardHeader>
                <CardTitle class="text-xl font-semibold text-gray-900"> Informasi Debitur </CardTitle>
                <p class="text-sm text-gray-600">Pilih debitur dan lengkapi informasi detail untuk memulai penilaian.</p>
            </CardHeader>
            <CardContent class="space-y-6">
                <!-- Borrower Selection -->
                <div class="space-y-2">
                    <Label for="borrower_id" class="text-sm font-medium text-gray-700"> Pilih Debitur <span class="text-red-500">*</span> </Label>
                    <Select v-model="form.borrower_id">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Pilih debitur..." />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="borrower in borrowers" :key="borrower.id" :value="borrower.id.toString()">
                                {{ borrower.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.borrower_id" class="text-sm text-red-600">
                        {{ form.errors.borrower_id }}
                    </p>
                </div>

                <!-- Borrower Group -->
                <div class="space-y-2">
                    <Label for="borrower_group" class="text-sm font-medium text-gray-700"> Grup Debitur </Label>
                    <Input id="borrower_group" v-model="form.borrower_group" type="text" placeholder="Masukkan grup debitur..." class="w-full" />
                    <p v-if="form.errors.borrower_group" class="text-sm text-red-600">
                        {{ form.errors.borrower_group }}
                    </p>
                </div>

                <!-- Purpose -->
                <div class="space-y-2">
                    <Label for="purpose" class="text-sm font-medium text-gray-700"> Tujuan Kredit <span class="text-red-500">*</span> </Label>
                    <Select v-model="form.purpose">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Pilih tujuan kredit..." />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="option in purposeOptions" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.purpose" class="text-sm text-red-600">
                        {{ form.errors.purpose }}
                    </p>
                </div>

                <!-- Economic Sector -->
                <div class="space-y-2">
                    <Label for="economic_sector" class="text-sm font-medium text-gray-700"> Sektor Ekonomi </Label>
                    <Input id="economic_sector" v-model="form.economic_sector" type="text" placeholder="Masukkan sektor ekonomi..." class="w-full" />
                    <p v-if="form.errors.economic_sector" class="text-sm text-red-600">
                        {{ form.errors.economic_sector }}
                    </p>
                </div>

                <!-- Business Field -->
                <div class="space-y-2">
                    <Label for="business_field" class="text-sm font-medium text-gray-700"> Bidang Usaha </Label>
                    <Input id="business_field" v-model="form.business_field" type="text" placeholder="Masukkan bidang usaha..." class="w-full" />
                    <p v-if="form.errors.business_field" class="text-sm text-red-600">
                        {{ form.errors.business_field }}
                    </p>
                </div>

                <!-- Borrower Business -->
                <div class="space-y-2">
                    <Label for="borrower_business" class="text-sm font-medium text-gray-700"> Jenis Usaha Debitur </Label>
                    <Input
                        id="borrower_business"
                        v-model="form.borrower_business"
                        type="text"
                        placeholder="Masukkan jenis usaha debitur..."
                        class="w-full"
                    />
                    <p v-if="form.errors.borrower_business" class="text-sm text-red-600">
                        {{ form.errors.borrower_business }}
                    </p>
                </div>

                <!-- Collectibility -->
                <div class="space-y-2">
                    <Label for="collectibility" class="text-sm font-medium text-gray-700"> Kolektibilitas <span class="text-red-500">*</span> </Label>
                    <Select v-model="form.collectibility">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Pilih kolektibilitas..." />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="option in collectibilityOptions" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.collectibility" class="text-sm text-red-600">
                        {{ form.errors.collectibility }}
                    </p>
                </div>

                <!-- Restructuring -->
                <div class="flex items-center space-x-2">
                    <Checkbox id="restructuring" v-model="form.restructuring" />
                    <Label for="restructuring" class="text-sm font-medium text-gray-700"> Restrukturisasi </Label>
                </div>
                <p v-if="form.errors.restructuring" class="text-sm text-red-600">
                    {{ form.errors.restructuring }}
                </p>
            </CardContent>
        </Card>
    </div>
</template>
