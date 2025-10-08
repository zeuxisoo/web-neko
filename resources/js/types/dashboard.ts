export type NavItem = {
    kind: 'group' | 'single';
    title: string;
    url: string;
    icon: string;
    isActive?: boolean;
    items?: {
        title: string;
        url: string;
    }[];
};
