<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent, CardFooter } from '@/components/ui/card';
import { Head, Link } from '@inertiajs/vue3';
import { BreadcrumbItem } from '@/types';
import { ArrowLeft, Edit, Trash } from 'lucide-vue-next';
// import { Badge } from '@/components/ui/badge';

// Definisikan tipe data untuk Template
interface Template {
  id: number;
  name: string;
  description: string;
  status: string;
  created_at: string;
  updated_at: string;
}

const props = defineProps<{
  template: Template;
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
    title: props.template.name,
    href: route('templates.show', props.template.id),
  }
];

// Fungsi untuk menghapus template
const deleteTemplate = () => {
  if (confirm('Apakah Anda yakin ingin menghapus template ini?')) {
    // Gunakan Inertia untuk mengirim permintaan delete
    window.Inertia.delete(route('templates.destroy', props.template.id), {
      onSuccess: () => {
        // Redirect ke halaman index setelah berhasil menghapus
        window.Inertia.visit(route('templates.index'));
      }
    });
  }
};

// Fungsi untuk mendapatkan label status
const getStatusLabel = (status: string) => {
  switch (status) {
    case 'active':
      return 'Aktif';
    case 'inactive':
      return 'Tidak Aktif';
    case 'draft':
      return 'Draft';
    default:
      return status;
  }
};

// Fungsi untuk mendapatkan warna badge status
const getStatusVariant = (status: string) => {
  switch (status) {
    case 'active':
      return 'success';
    case 'inactive':
      return 'destructive';
    case 'draft':
      return 'secondary';
    default:
      return 'default';
  }
};
</script>

<template>
  <Head :title="`Template: ${template.name}`" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Detail Template -->
        <Card class="mb-8">
          <CardHeader class="flex flex-row items-center justify-between">
            <div>
              <CardTitle class="text-xl md:text-2xl font-bold">
                {{ template.name }}
              </CardTitle>
              <p class="text-sm text-gray-500 mt-1">
                <Badge :variant="getStatusVariant(template.status)">
                  {{ getStatusLabel(template.status) }}
                </Badge>
              </p>
            </div>
            <div class="flex space-x-2">
              <Link :href="route('templates.edit', template.id)">
                <Button variant="outline">
                  <Edit class="w-4 h-4 mr-2" />
                  Edit
                </Button>
              </Link>
              <Button variant="destructive" @click="deleteTemplate">
                <Trash class="w-4 h-4 mr-2" />
                Hapus
              </Button>
            </div>
          </CardHeader>
          <CardContent>
            <div class="space-y-4">
              <div>
                <h3 class="text-lg font-medium">Deskripsi</h3>
                <p class="mt-1">{{ template.description || 'Tidak ada deskripsi' }}</p>
              </div>
              
              <div>
                <h3 class="text-lg font-medium">Informasi Tambahan</h3>
                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <p class="text-sm text-gray-500">Tanggal Dibuat</p>
                    <p>{{ new Date(template.created_at).toLocaleString('id-ID') }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-gray-500">Terakhir Diperbarui</p>
                    <p>{{ new Date(template.updated_at).toLocaleString('id-ID') }}</p>
                  </div>
                </div>
              </div>
            </div>
          </CardContent>
          <CardFooter>
            <div class="flex justify-between w-full">
              <Link :href="route('templates.index')">
                <Button variant="outline">
                  <ArrowLeft class="w-4 h-4 mr-2" />
                  Kembali
                </Button>
              </Link>
            </div>
          </CardFooter>
        </Card>

        <!-- Bagian untuk menampilkan aspek-aspek -->
    <Card class="mt-6">
      <CardHeader>
        <CardTitle class="text-lg font-semibold">Aspek yang Termasuk</CardTitle>
      </CardHeader>
      <CardContent>
        <div v-if="template.aspects.length === 0" class="text-center py-4 text-gray-500">
          Tidak ada aspek yang terkait dengan template ini.
        </div>
        <div v-else class="grid gap-4 md:grid-cols-2">
          <div v-for="aspect in template.aspects" :key="aspect.id" class="border p-3 rounded-md">
            <div class="font-medium">{{ aspect.name }}</div>
            <p class="text-sm text-gray-500">{{ aspect.description || 'Tidak ada deskripsi' }}</p>
            <p class="text-xs text-gray-400 mt-1">Bobot: {{ aspect.weight }} | Pertanyaan: {{ aspect.questions?.length || 0 }}</p>
          </div>
        </div>
      </CardContent>
    </Card>
      </div>
    </div>
    
  </AppLayout>
</template>