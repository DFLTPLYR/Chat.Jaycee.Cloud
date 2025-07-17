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

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    profile_photo_url?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    preference: UserPreferrence;
}

export interface UserPreferrence {
    id: number;
    user_id: number;
    text_size: string;
    notifications: boolean;
    created_at: Date;
    updated_at: Date;
}

export type BreadcrumbItemType = BreadcrumbItem;
