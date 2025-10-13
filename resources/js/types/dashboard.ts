import { LucideProps } from 'lucide-vue-next';
import { FunctionalComponent } from 'vue';

export type NavItem = {
    kind: 'group' | 'single';
    title: string;
    url: string;
    icon: FunctionalComponent<LucideProps, {}, any, {}>;
    isActive?: boolean;
    items?: {
        title: string;
        url: string;
    }[];
};
