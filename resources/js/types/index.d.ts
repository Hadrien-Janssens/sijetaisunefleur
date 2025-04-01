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
    color?: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Row {
    id: number;
    price: number;
    quantity: number;
    reduction: number;
    category: {
        name: string;
    };
}

export interface Client {
    id: number;
    firstname: string;
    lastname: string;
    email: string;
    phone: string;
    company: string;
    tva_number: string;
    address: string;
    city: string;
    country: string;
    created_at: string;
    updated_at: string;
}

export interface Ticket {
    id: number;
    reference: number;
    is_sent: boolean;
    is_paid: boolean;
    with_tva: boolean;
    comment: string;
    client: number;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Category {
    id: number;
    name: string;
    tva: number;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
