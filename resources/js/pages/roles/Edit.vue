<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import InputError from '@/components/InputError.vue';

interface Permission {
    id: number;
    name: string;
}

interface PermissionGroup {
    [key: string]: Permission[];
}

interface Role {
    id: number;
    name: string;
    permissions: Permission[];
}

const props = defineProps<{
    role: Role;
    permissions: PermissionGroup;
}>();

const form = useForm({
    name: props.role.name,
    permissions: props.role.permissions.map(p => p.id)
});

const submit = () => {
    form.put(route('roles.update', props.role.id));
};

const togglePermission = (permissionId: number) => {
    const index = form.permissions.indexOf(permissionId);
    if (index > -1) {
        form.permissions.splice(index, 1);
    } else {
        form.permissions.push(permissionId);
    }
};

const selectAllInGroup = (groupPermissions: Permission[]) => {
    const allSelected = groupPermissions.every(p => form.permissions.includes(p.id));
    
    if (allSelected) {
        groupPermissions.forEach(p => {
            const index = form.permissions.indexOf(p.id);
            if (index > -1) {
                form.permissions.splice(index, 1);
            }
        });
    } else {
        groupPermissions.forEach(p => {
            if (!form.permissions.includes(p.id)) {
                form.permissions.push(p.id);
            }
        });
    }
};
</script>

<template>
    <Head title="Edit Role" />

    <AppLayout>
        <div class="container mx-auto px-4 py-8">
            <Card>
                <CardHeader>
                    <CardTitle class="text-xl md:text-2xl font-bold">Edit Role: {{ role.name }}</CardTitle>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="name">Nama Role</Label>
                            <Input id="name" v-model="form.name" required />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="grid gap-4">
                            <Label>Permissions</Label>
                            <div v-for="(groupPermissions, groupName) in permissions" :key="groupName" class="border rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-semibold capitalize">{{ groupName }}</h3>
                                    <Button 
                                        type="button" 
                                        variant="outline" 
                                        size="sm"
                                        @click="selectAllInGroup(groupPermissions)"
                                    >
                                        {{ groupPermissions.every(p => form.permissions.includes(p.id)) ? 'Deselect All' : 'Select All' }}
                                    </Button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div v-for="permission in groupPermissions" :key="permission.id" class="flex items-center space-x-2">
                                        <Checkbox 
                                            :id="`permission-${permission.id}`"
                                            :checked="form.permissions.includes(permission.id)"
                                            @update:checked="() => togglePermission(permission.id)"
                                        />
                                        <Label :for="`permission-${permission.id}`" class="text-sm">{{ permission.name }}</Label>
                                    </div>
                                </div>
                            </div>
                            <InputError :message="form.errors.permissions" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <Link :href="route('roles.index')">
                                <Button type="button" variant="outline">Cancel</Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Mengupdate...' : 'Update' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>