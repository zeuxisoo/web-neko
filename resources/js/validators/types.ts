type CheckerParams = {
    value: any;
    parameters: string[];
    data: Record<string, any>;
    attribute: string;
};

type CheckerFunction = (params: CheckerParams) => boolean;

type ValidatedAttribute = {
    name: string;
    value: string;
};
