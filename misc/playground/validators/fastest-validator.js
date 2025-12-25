import Validator from 'https://esm.sh/fastest-validator';

// Halt on first
// - https://github.com/icebob/fastest-validator?tab=readme-ov-file#halting
// const v = new Validator({ haltOnFirstError: true });
const v = new Validator();

const schema = {
    name: {
        type: 'string',
    },
    age: {
        type: 'number',
        messages: {
            required: '{field} is required :(',
            number: 'Age must type of number :(',
        },
    },
    birthday_year: {
        type: 'number',
        optional: true,
    },
    ip_address: {
        // shorthand: https://github.com/icebob/fastest-validator/issues/348#issuecomment-2383809557
        $$type: 'object',
        v4: { type: 'string' },
        v6: { type: 'string' },
    },
    cars: {
        type: 'array',
        items: {
            type: 'object',
            props: {
                name: { type: 'string' },
                year: { type: 'number' },
            },
        },
    },
};

const check = v.compile(schema);

console.log(
    check({
        name: 'cat',
        age: 18,
        ip_address: {
            v4: '',
            v6: '',
        },
        cars: [{ name: '', year: 0 }],
    }),
);
