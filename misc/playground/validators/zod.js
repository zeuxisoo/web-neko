import z from 'https://esm.sh/zod';

// abortEarly option commented
// - https://github.com/colinhacks/zod/issues/3884
// - https://github.com/colinhacks/zod/blob/main/packages/zod/src/v4/core/schemas.ts#L24
const schema = z.object({
    name: z.string(),
    age: z.number(),
    birth_year: z.number().optional(),
    ip_address: z.object({
        v4: z.ipv4(),
        v6: z.ipv6(),
    }),
    cars: z.array(
        z.object({
            name: z.string(),
            year: z.number('car.year is not number'),
        }),
    ),
});

console.log(
    schema.parse({
        name: 'don',
        age: 18,
        ip_address: {
            v4: '127.0.0.1',
            v6: '::1',
        },
        cars: [
            { name: 'a', year: 1 },
            { name: 'b', year: 9 },
        ],
    }),
);
