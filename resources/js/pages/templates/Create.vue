<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import InputError from '@/components/InputError.vue';
import { Save, PlusCircle, Trash2 } from 'lucide-vue-next';
import type { BreadcrumbItem, Aspect } from '@/types';
import { ref, watch } from 'vue';

const props = defineProps<{
  aspects: Array<{
        id: number,
        name: string,
        description: string,
        weight: number,
        questions: Array<{
            id: number,
            aspect_id: number,
            question_text: string,
        }>
    }>
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: route('dashboard'),
  },
  {
    title: 'Template',
    href: route('templates.index'),
  },
  {
    title: 'Tambah Template',
    href: route('templates.create'),
  },
];

const form = useForm({
  name: '',
  aspect_ids: [] as number[],
});

const selectedAspects = ref<number[]>([]);


const submit = () => {
  form.aspect_ids = [...selectedAspects.value];
  
  if (form.aspect_ids.length === 0) {
    alert('Silakan pilih minimal satu aspek');
    return;
  }
  
  form.post(route('templates.store'), {
    preserveScroll: true,
    onSuccess: () => {
      console.log('Data berhasil disimpan');
    },
    onError: (errors) => {
      console.error('Error saat menyimpan:', errors);
    }
  });
};

const toggleAspect = (aspectId: number) => {
  const index = selectedAspects.value.indexOf(aspectId);
  if (index === -1) {
    selectedAspects.value.push(aspectId);
  } else {
    selectedAspects.value.splice(index, 1);
  }
  console.log('Toggle aspek:', aspectId, 'Selected aspects:', selectedAspects.value);
};

const isAspectSelected = (aspectId: number) => {
  return selectedAspects.value.includes(aspectId);
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

              <!-- Aspek yang Termasuk -->
              <div class="space-y-4 border p-4 rounded-lg">
                <h3 class="text-lg font-semibold">Aspek yang Termasuk</h3>
                
                <div v-if="aspects.length === 0" class="text-center py-4 text-gray-500">
                  Belum ada aspek yang tersedia. Silakan tambahkan aspek terlebih dahulu.
                </div>
                
                <div v-else class="space-y-4">
                  <div v-if="form.errors.aspect_ids" class="text-red-500 text-sm">
                    {{ form.errors.aspect_ids }}
                  </div>
                  
                  <div class="grid gap-4 md:grid-cols-2">
                    <div v-for="aspect in aspects" :key="aspect.id" class="flex items-start space-x-2 border p-3 rounded-md">
                      <input 
                        type="checkbox" 
                        :id="`aspect-${aspect.id}`" 
                        :value="aspect.id"
                        :checked="isAspectSelected(aspect.id)"
                        @change="toggleAspect(aspect.id)"
                        class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                      />
                      <div>
                        <Label :for="`aspect-${aspect.id}`" class="font-medium">{{ aspect.name }}</Label>
                        <p class="text-sm text-gray-500">{{ aspect.description || 'Tidak ada deskripsi' }}</p>
                        <p class="text-xs text-gray-400 mt-1">Bobot: {{ aspect.weight }} | Pertanyaan: {{ aspect.questions?.length || 0 }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="flex justify-end space-x-2">
                <Link :href="route('templates.index')">
                  <Button type="button" variant="outline">
                    Batal
                  </Button>
                </Link>
                <Button type="submit" :disabled="form.processing">
                  <Save class="h-4 w-4 mr-2" />
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