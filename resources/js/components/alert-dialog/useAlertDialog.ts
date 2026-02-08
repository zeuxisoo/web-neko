import { createTemplatePromise } from '@vueuse/core';

type DialogResult = 'ok' | 'cancel';

type DialogProps = {
    title: string;
    description: string;
    labelOk?: string;
    labelCancel?: string;
};

// crate dialog template in gloabl scope first
const dialogTemplate = createTemplatePromise<DialogResult, [DialogProps]>();

const useAlertDialog = () => dialogTemplate;

export { useAlertDialog };
