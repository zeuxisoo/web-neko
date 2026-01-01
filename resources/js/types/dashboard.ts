import { LucideProps } from 'lucide-vue-next';
import { FunctionalComponent } from 'vue';
import { RouterLinkProps } from 'vue-router';

type NavItemTo = RouterLinkProps['to'];

export type NavItem = {
    kind: 'group' | 'single';
    title: string;
    to: NavItemTo;
    icon: FunctionalComponent<LucideProps, {}, any, {}>;
    isActive?: boolean;
    items?: {
        title: string;
        to: NavItemTo;
    }[];
};
