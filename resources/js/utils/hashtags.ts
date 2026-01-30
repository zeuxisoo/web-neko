import { createToken, IToken, Lexer } from 'chevrotain';

// \p{L}: Latin, CJK, etc
// \p{N}: Number
// \p{Emoji_Presentation}: Eomoji (e.g. colored emoji)
// \p{Extended_Pictographic}: modern emoji (e.g. pictographic: ♡, ⌨︎)
// _: underscore
// pattern: /#[\p{L}\p{N}\p{Emoji}\p{Extended_Pictographic}_]+/u,
// pattern: /#[\p{L}\p{N}\p{Emoji_Presentation}\p{Extended_Pictographic}_]+/u
const tag = createToken({
    name: 'Hashtag',
    pattern: (text, offset) => {
        const re = /#[\p{L}\p{N}\p{Emoji}\p{Emoji_Presentation}\p{Extended_Pictographic}_\-]+/gu;
        re.lastIndex = offset;

        const match = re.exec(text);
        if (match && match.index === offset) {
            return match;
        }

        return null;
    },
    line_breaks: false,
});

const sentences = createToken({
    name: 'Sentences',
    pattern: /[^#\s]+/u,
});

const whiteSpace = createToken({
    name: 'WhiteSpace',
    pattern: /\s+/,
    group: Lexer.SKIPPED,
});

const extractHashTags = (text: string) => {
    const lexer = new Lexer([whiteSpace, tag, sentences]);
    const lexingResult = lexer.tokenize(text);

    const tokens = lexingResult.tokens
        .filter((token: IToken) => {
            return token.tokenType.name === 'Hashtag';
        })
        .map((token: IToken) => {
            return token.image;
        });

    return tokens;
};

export default extractHashTags;
