//
function renderTemplate(template: string, context: Record<string, any>): string {
    return template.replace(/\{\{(\w+)\}\}/g, (_, key) => {
        return key in context ? String(context[key]) : '';
    });
}

const variable1 = 'Password';
const variable2 = 8;

const message = renderTemplate('{{variable1}} letters must be more than {{variable2}}', { variable1, variable2 });
console.log(message); // Output: "Password letters must be more than 8"

//
function renderTemplate2(message: string, data: Record<string, any>): string {
    const dataKey = Object.keys(data);
    const dataVal = Object.values(data);

    const result = new Function(...dataKey, `return \`${message}\`;`)(...dataVal);

    return result;
}

const message2 = renderTemplate2('this is ${name} in ${ip.v4}', {
    name: 'cat',
    ip: {
        v4: '127.0.0.1',
    },
});

console.log(message2); // Output: "this is cat in 127.0.0.1"
