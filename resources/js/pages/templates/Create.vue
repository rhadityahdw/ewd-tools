<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import InputError from '@/components/InputError.vue';
import { Save } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';
import { ref, computed } from 'vue';

interface AspectVersion {
  id: number;
  name: string;
  version: number;
}

interface Aspect {
  id: number;
  name: string;
  description: string;
  aspectVersions: AspectVersion[];
}

const props = defineProps<{
  aspects: Aspect[]
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Dashboard', href: route('dashboard') },
  { title: 'Template', href: route('templates.index') },
  { title: 'Tambah Template', href: route('templates.create') },
];

const form = useForm({
  name: '',
  aspect_weights: [] as Array<{aspect_version_id: number, weight: number}>
});

const selectedAspects = ref<Array<{aspect_version_id: number, weight: number}>>([]);

const totalWeight = computed(() => {
  return selectedAspects.value.reduce((sum, a) => sum + a.weight, 0);
});

const toggleAspect = (aspectVersionId: number) => {
  const index = selectedAspects.value.findIndex(a => a.aspect_version_id === aspectVersionId);
  if (index === -1) {
    selectedAspects.value.push({ aspect_version_id: aspectVersionId, weight: 0 });
  } else {
    selectedAspects.value.splice(index, 1);
  }
};

const isAspectSelected = (aspectVersionId: number) => {
  return selectedAspects.value.some(a => a.aspect_version_id === aspectVersionId);
};

const submit = () => {
  form.aspect_weights = selectedAspects.value.filter(a => a.weight > 0);
  
  if (Math.abs(totalWeight.value - 100) > 0.01) {
    alert('Total bobot harus 100%');
    return;
  }
  
  form.post(route('templates.store'));
};
</script>

<template>
  <Head title="Tambah Template" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <Card>
          <CardHeader>
            <CardTitle class="text-xl md:text-2xl font-bold">Tambah Template Baru</CardTitle>
          </CardHeader>
          <CardContent>
            <form @submit.prevent="submit" class="space-y-8">
              <div class="grid gap-2">
                <Label for="name">Nama Template</Label>
                <Input id="name" v-model="form.name" required />
                <InputError :message="form.errors.name" />
              </div>

              <div class="space-y-4 border p-4 rounded-lg">
                <h3 class="text-lg font-semibold">Aspek yang Termasuk</h3>
                <div class="text-sm text-gray-600 mb-4">
                  Total Bobot: {{ totalWeight }}% (harus 100%)
                </div>
                
                <div class="space-y-4">
                  <div v-for="aspect in aspects" :key="aspect.id" class="border p-4 rounded">
                    <div v-for="version in aspect.aspectVersions" :key="version.id" class="flex items-center space-x-4">
                      <Checkbox 
                        :id="`aspect-${version.id}`"
                        :checked="isAspectSelected(version.id)"
                        @update:checked="toggleAspect(version.id)"
                      />
                      <Label :for="`aspect-${version.id}`" class="flex-1">
                        {{ version.name }} (v{{ version.version }})
                      </Label>
                      <div v-if="isAspectSelected(version.id)" class="flex items-center space-x-2">
                        <Label>Bobot:</Label>
                        <Input 
                          type="number" 
                          min="0" 
                          max="100" 
                          step="0.01"
                          class="w-20"
                          v-model.number="selectedAspects.find(a => a.aspect_version_id === version.id)!.weight"
                        />
                        <span>%</span>
                      </div>
                    </div>
                  </div>
                </div>
                <InputError :message="form.errors.aspect_weights" />
              </div>

              <div class="flex justify-end space-x-4">
                <Link :href="route('templates.index')">
                  <Button type="button" variant="outline">Batal</Button>
                </Link>
                <Button type="submit" :disabled="form.processing">
                  <Save class="w-4 h-4 mr-2" />
                  Simpan Template
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>