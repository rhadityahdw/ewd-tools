<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, CircleDivide, ClipboardList, Clock, FileText, Folder, LayoutGrid, PlusCircle, RollerCoaster, User } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'User',
        href: '/users',
        icon: User,
    },
    {
        title: 'Debitur',
        href: '/borrowers',
        icon: Folder,
    },
    {
        title: 'Template',
        href: '/templates',
        icon: FileText,
    },
    {
        title: 'Aspek',
        href: '/aspects',
        icon: ClipboardList,
    },
    {
        title: 'Periode',
        href: '/periods',
        icon: Clock,
    },
    {
        title: 'Divisi',
        href: '/divisions',
        icon: CircleDivide,
    },
    {
        title: 'Role',
        href: '/roles',
        icon: RollerCoaster,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <div class="px-2 py-1">
                <Link :href="route('forms.index')" class="w-full">
                    <button
                        class="bg-primary hover:bg-primary/90 flex w-full items-center justify-center gap-2 rounded-md px-4 py-2 text-sm font-medium text-white transition-colors dark:text-black"
                    >
                        <PlusCircle class="h-4 w-4" />
                        <span>Tambah Report Baru</span>
                    </button>
                </Link>
            </div>

            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
