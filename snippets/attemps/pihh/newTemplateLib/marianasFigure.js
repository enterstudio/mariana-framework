
/**
 * Created by fsa on 11/01/2016.
 * Stages of this project
 *
 * Tokenizer - breaks template in string and list of tokens
 * Parser - recognizes language constructons
 * Evaluator - gets Abstract Sitnax Tree produced by the parser, evaluates and returns the ending result
 *
 */


/**
 *  Tokenizer:
 *      @Exemplo: O regex abaixo definido parte uma string em uam array de vários componentes.
 *      @Regex: "([0-9]+)|(for\b)|([a-zA-Z]+)|(\\.)|(,)|(;)|([\x09\x0A\x0D\x20]+)"
 *      @Result:
 *          - Integer numbers [0-9]+
 *          - Reserved word "for"
 *          - Identifiers [a-zA-Z]+
 *          - Punctuators ".", ",", ";"
 *          - White space characters [\x09\x0A\x0D\x20]+
 */
/*
var input ='123';
var regExp = new RegExp('(1)|(2)|(3)','g');
var match = regExp.exec(input);
while (match) {
    for (var c = 1; c < match.length; c++) {
        if (!match[c]) continue;
        alert('matching group is: ' + (c -1));
        break;
    }
    match = regExp.exec(input);
}
*/
function Tokenizer() {
    this.input = '';
    this.tokens = {};
    this.tokenExpr = null;
    this.tokenNames = [];
}

Tokenizer.prototype.addToken = function(name, expression) {
    this.tokens[name] = expression;
};

Tokenizer.prototype.tokenize = function(input) {
    this.input = input;
    var tokenExpr = [];
    for (var tokenName in this.tokens) {
        this.tokenNames.push(tokenName);
        tokenExpr.push('('+this.tokens[tokenName]+')');
    }
    this.tokenExpr = new RegExp(tokenExpr.join('|'), 'g');
};

Tokenizer.prototype.getToken = function() {
    var match = this.tokenExpr.exec(this.input);
    if (!match) return null;
    for (var c = 1; c < match.length; c++) {
        if (!match[c]) continue;
        return {
            name: this.tokenNames[c - 1],
            pos: match.index,
            data: match[c]
        };
    }
};

var tokenizer = new Tokenizer();
tokenizer.addToken('number', '[0-9]+');
tokenizer.addToken('for', 'for\\b');
tokenizer.addToken('identifier', '[a-zA-Z]+');
tokenizer.addToken('dot', '\\.');
tokenizer.addToken('comma', ',');
tokenizer.addToken('semicolon', ';');
tokenizer.addToken('whitespaces', '[\x09\x0A\x0D\x20]+');

tokenizer.tokenize('.for 123 foobar for .,;');

var token;
while (token = tokenizer.getToken()) {
    console.info(token);
}