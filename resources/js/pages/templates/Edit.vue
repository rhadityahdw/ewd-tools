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
import { Save } from 'lucide-vue-next';
import type { BreadcrumbItem, Aspect } from '@/types';

// Definisikan tipe data untuk Template
interface Template {
  id: number;
  name: string;
  description: string;
  status: string;
  created_at: string;
  updated_at: string;
  aspects: Aspect[];
}

const props = defineProps<{
  template: Template;
  aspects: Aspect[];
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
    title: 'Edit Template',
    href: route('templates.edit', props.template.id),
  },
];

// Siapkan form dengan data yang sudah ada
const form = useForm({
  name: props.template.name,
  description: props.template.description || '',
  status: props.template.status,
  aspect_ids: props.template.aspects.map(aspect => aspect.id),
});

const submit = () => {
  form.put(route('templates.update', props.template.id), {
    preserveScroll: true,
    onSuccess: () => {
      console.log('Data berhasil diperbarui');
    },
    onError: (errors) => {
      console.error('Error saat memperbarui:', errors);
    }
  });
};

// Cek apakah aspek dipilih
const isAspectSelected = (aspectId: number) => {
  return form.aspect_ids.includes(aspectId);
};

// Toggle aspek yang dipilih
const toggleAspect = (aspectId: number) => {
  const index = form.aspect_ids.indexOf(aspectId);
  if (index === -1) {
    form.aspect_ids.push(aspectId);
  } else {
    form.aspect_ids.splice(index, 1);
  }
};
</script>

<template>
  <Head title="Edit Template" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <Card>
          <CardHeader>
            <CardTitle class="text-xl md:text-2xl font-bold">Edit Template</CardTitle>
          </CardHeader>
          <CardContent>
            <form @submit.prevent="submit" class="space-y-8">
              <!-- Template Information -->
              <div class="space-y-4 border p-4 rounded-lg">
                <h3 class="text-lg font-semibold">Informasi Template</h3>
                
                <div class="grid gap-2">
                  <Label for="name">Nama Template</Label>
                  <Input id="name" v-model="form.name" required />
                  <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                  <Label for="description">Deskripsi</Label>
                  <Textarea id="description" v-model="form.description" rows="3" />
                  <InputError :message="form.errors.description" />
                </div>

                <div class="grid gap-2">
                  <Label for="status">Status</Label>
                  <Select v-model="form.status">
                    <SelectTrigger>
                      <SelectValue placeholder="Pilih status" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="active">Aktif</SelectItem>
                      <SelectItem value="inactive">Tidak Aktif</SelectItem>
                      <SelectItem value="draft">Draft</SelectItem>
                    </SelectContent>
                  </Select>
                  <InputError :message="form.errors.status" />
                </div>
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
                      <Checkbox 
                        :id="`aspect-${aspect.id}`" 
                        :checked="isAspectSelected(aspect.id)"
                        @update:checked="() => toggleAspect(aspect.id)"
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
                  Simpan Perubahan
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>