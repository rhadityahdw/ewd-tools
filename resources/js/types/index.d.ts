import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface Role {
    id: number;
    name: string;
}

export interface Division {
    id: number,
    code: string,
    name: string
}

export interface User {
    id: number;
    name: string;
    email: string;
    password: string;
    role_id: number;
    role: Role[];
    division_id?: number;
    division?: Division;
    created_at?: string;
    updated_at?: string;
}

export interface Borrower {
    id: number;
    name: string;
    division_id: number;
    division: Division;
    details: BorrowerDetail;
    facilities: BorrowerFacility;
}

export interface BorrowerDetail {
    id: number;
    borrower_id: number;
    borrower: Borrower;
    borrower_group?: string;
    purpose: string;
    economic_sector: string;
    business_field: string;
    borrower_business: string;
    collectibility: number;
    restructuring: boolean;
    created_at?: string;
    updated_at?: string;
}

export interface BorrowerFacility {
    id: number;
    borrower_id: number;
    facility_name: string;
    limit: number;
    outstanding: number;
    interest_rate: number;
    principal_arrears: number;
    interest_arrears: number;
    pdo_days: number;
    maturity_date: string;
    created_at?: string;
    updated_at?: string;
}

export interface Period {
    id: number;
    name: string;
    start_date: Date;
    end_date: Date;
    status: string;
    created_at?: string;
    updated_at?: string;
}

export interface Aspect {
    id: number;
    name: string;
    description?: string;
    weight: number;
    created_at?: string;
    updated_at?: string;
}

export interface Question {
    id: number;
    aspect_id: number;
    aspect: Aspect;
    question_text: string;
    weight: number;
    created_at?: string;
    updated_at?: string;
}

export interface Answer {
    id: number;
    question_id: number;
    question: Question;
    answer_value: string;
    answer_description?: string;
    created_at?: string;
    updated_at?: string; 
}

export type BreadcrumbItemType = BreadcrumbItem;
