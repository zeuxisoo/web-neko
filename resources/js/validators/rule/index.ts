// value must be match the rule => pass!
const checkers: Record<string, CheckerFunction> = {

    'required': ({ value }) => {
        if (Array.isArray(value)) {
            return value.length > 0;
        }

        return value !== null && String(value).length > 0;
    },

    'email': ({ value }) => {
        return /\S+@\S+\.\S+/.test(value);
    },

    'min': ({ value, parameters }) => {
        const minimum = parameters[0];

        return value.length >= minimum;
    },

}

class Rule {

    private checker?: CheckerFunction;
    private parameters: string[] = [];
    private attribute: string = "";

    add(name: string, checker: CheckerFunction): this {
        checkers[name] = checker;

        return this;
    }

    create(name: string, parameters: string[], attribute: string): this {
        this.checker    = checkers[name];
        this.parameters = parameters;
        this.attribute  = attribute;

        return this;
    }

    validate(data: Record<string, any>): boolean {
        if (!this.checker) {
            throw new Error('The validate checker is undefined');
        }

        return (this.checker).call(this, {
            value     : data[this.attribute],
            parameters: this.parameters,
            data      : data,
            attribute : this.attribute,
        });
    }

}

export default Rule;
