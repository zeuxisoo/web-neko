import { isObject } from 'es-toolkit/compat';
import { sprintf } from 'sprintf-js';
import Rule from './rule';
import { ValidateError } from './error';

class Validator {

    private regs: Record<string, string[]>; // regulations
    private locales: Record<string, string>;
    private rule: Rule;

    constructor() {
        this.regs = this.rules();
        this.locales = this.messages();

        this.rule = new Rule();

        for (let { name, checker } of this.checkers()) {
            this.rule.add(name, checker);
        }
    }

    addChecker(name: string, checker: CheckerFunction): this {
        this.rule.add(name, checker);

        return this;
    }

    addChain(name: string, regs: string[]): this {
        if (name in this.regs) {
            this.regs[name] = this.regs[name].concat(regs);
        } else {
            this.regs[name] = regs;
        }

        return this;
    }

    addMessage(key: string, message: string): this {
        this.locales[key] = message;

        return this;
    }

    validate(formData: Record<string, any>): Record<string, any> {
        const validated: Record<string, any> = {};

        for (const [attribute, rules] of Object.entries(this.regs)) {
            if (rules.length <= 0) {
                continue;
            }

            const firstAsteriskIndex = attribute.indexOf('*');

            if (firstAsteriskIndex === -1) {
                const validAttribute = this.validateNormalized(formData, attribute, rules);

                validated[validAttribute.name] = validAttribute.value;
            } else {
                const validAttribute = this.validateNested(formData, attribute, rules);

                validated[validAttribute.name] = validAttribute.value;
            }
        }

        return validated;
    }

    validateNormalized(formData: Record<string, any>, attribute: string, rules: string[]): ValidatedAttribute {
        for (const rule of rules) {
            const { isValid, ruleName, ruleParameters } = this.triggerValidate(rule, attribute, formData);

            if (!isValid) {
                const message = sprintf(this.locales[`${attribute}.${ruleName}`], { args: ruleParameters });

                throw new ValidateError(message);
            }
        }

        return {
            name: attribute,
            value: formData[attribute],
        };
    }

    validateNested(formData: Record<string, any>, attribute: string, rules: string[]): ValidatedAttribute {
        // formData:
        // - {
        //     postings: [
        //       {
        //         account : 0,
        //         price   : { amount: 0, currency: 0 }
        //         exchange: { amount: 0, currency: 0 }
        //       }
        //     ]
        //   }
        // attribute:
        // - postings.*.account
        // - postings.*.price.amount
        // - postings.*.exhange.amount

        // parse attribute
        // - parent  : postings
        // - asterisk: *
        // - childs  : account | price.amount | exchange.amount
        const [parentAttributeName, _asterisk, ...childAttributeNames] = attribute.split('.');

        // get parent attribute data from data:
        // - [{
        //      account : 0,
        //      price   : { amount: 0, currency: 0 },
        //      exchange: { amount: 0, currency: 0 },
        //   }]
        const parentAttributeRows = formData[parentAttributeName];

        // Get and remove the first child from the childs array
        // - ['acount'] -> acount
        // - ['price', 'amount'] -> price
        const childAttributeName = childAttributeNames.shift();

        if (!childAttributeName) {
            throw new ValidateError('The child attribute name is undefined');
        }

        // loop for validate each attribute data inside parent attribute
        // - row: { account, price, exchange }
        for (let [index, attributeRow] of parentAttributeRows.entries()) {
            for (const rule of rules) {
                // copy the attribute row and child name for non object, otherwise jump into object to destruct
                // if the child is object then pick the first child again without remove them
                // - postings.*.acount -> posting['account'] -> (!object) "" -> nothing
                // - postings.*.price.amount -> posting['price'] -> (object) { amount: 0, price: 0 } -> pick ['amount'] without remove again
                let attributeLine = attributeRow;
                let attributeName = childAttributeName;

                if (isObject(attributeRow[childAttributeName])) {
                    attributeLine = attributeRow[childAttributeName];
                    attributeName = childAttributeNames[0];
                }

                // validate it
                const { isValid, ruleName, ruleParameters } = this.triggerValidate(rule, attributeName, attributeLine);

                if (!isValid) {
                    const message = sprintf(this.locales[`${attribute}.${ruleName}`], {
                        args: ruleParameters,
                        index: index,
                    });

                    throw new ValidateError(message);
                }
            }
        }

        return {
            name: parentAttributeName,
            value: formData[parentAttributeName],
        };
    }

    triggerValidate(rule: string, attribute: string, formData: Record<string, any>) {
        // try to split rule to rule and rule parameters by colon
        // 1. colon is not exists -> only set rule
        // 2. colon is exists -> set rule and rule parameters
        const firstColonIndex = rule.indexOf(':');

        let ruleName: string;
        let ruleParameter: string | undefined;

        if (firstColonIndex === -1) {
            ruleName = rule;
            ruleParameter = undefined;
        } else {
            ruleName = rule.slice(0, firstColonIndex);
            ruleParameter = rule.slice(firstColonIndex + 1);
        }

        // split the rule parameters into array when is not undefined
        const ruleParameters = ruleParameter?.split(',') ?? [];

        // validate format data
        const isValid = this.rule.create(ruleName, ruleParameters, attribute).validate(formData);

        return {
            isValid,
            ruleName,
            ruleParameters,
        };
    }

    rules(): Record<string, string[]> {
        throw new ValidateError('Method rules() is not implemented');
    }

    messages(): Record<string, string> {
        throw new ValidateError('Method locales() is not implemented');
    }

    checkers() {
        // keep it empty when no custom expressions
        return [
            // {
            //     name   : 'custom',
            //     checker: item => { console.log(item); return false; },
            // }
        ];
    }

}

export default Validator;
