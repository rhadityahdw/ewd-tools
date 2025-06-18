<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { ref } from 'vue';

const formData = ref({
    borrowerName: 'BARASENTOSA LESTARI',
    period: 'IV 2024',
    aspects: [
        { id: 'A', name: 'HUKUM', classification: 'SAFE' },
        { id: 'B', name: 'MANAJEMEN', classification: 'SAFE' },
        { id: 'C', name: 'AGUNAN/JAMINAN', classification: 'WARNING' },
        { id: 'D', name: 'KEUANGAN', classification: 'WARNING' },
        { id: 'E', name: 'PEMASARAN', classification: 'SAFE' },
        { id: 'F', name: 'AMDAL', classification: 'WARNING' },
        { id: 'G', name: 'LAINNYA', classification: 'SAFE' },
    ],
    businessNotes: '',
    override: false,
    collectibilityIndicator: 0,
    reviewerNotes: '',
});
</script>

<template>
    <div class="bg-[#2e3192] p-4 text-white shadow-md dark:bg-[#1a1d68] dark:text-gray-200">
        <Label class="pl-2 text-2xl font-bold">Summary Early Warning</Label>
    </div>

    <!-- Borrower Information -->

    <!-- Aspect Table -->
</template>
