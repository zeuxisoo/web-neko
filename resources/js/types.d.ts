declare module 'vue-it-bigger' {
    import type { DefineComponent } from 'vue';

    export interface ImageData {
        url: string;
        title?: string;
    }

    export interface Media {
        type: string;
        src: string;
        thumb: string;
        caption: string;
    }

    export interface LightBoxProps {
        media?: Media[];
        showLightBox?: boolean;
        interfaceHideTime?: number;
        showCaption?: boolean;
    }

    interface LightBoxMethods {
        showImage(index: number): void;
    }

    export type LightBoxComponent = DefineComponent<LightBoxProps> & LightBoxMethods;

    const LightBox: LightBoxComponent;
    export default LightBox;
}
