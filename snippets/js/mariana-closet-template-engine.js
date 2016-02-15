/**
 * Created by filipe_2 on 1/16/2016.
 */
var $closet = function(html, options) {

    //re original :  / <%([^%>]+)?%>/g

    var re = /{{\s*([^}]*(?:}(?!})[^}]*)*)}}/g, reExp = /(^( )?(if|for|else|switch|case|break|{|}))(.*)?/g, code = 'var r=[];\n', cursor = 0, match;
    var add = function(line, js) {
        js? (code += line.match(reExp) ? line + '\n' : 'r.push(' + line + ');\n') :
            (code += line != '' ? 'r.push("' + line.replace(/"/g, '\\"') + '");\n' : '');
        return add;
    }
    while(match = re.exec(html)) {
        add(html.slice(cursor, match.index))(match[1], true);
        cursor = match.index + match[0].length;
    }
    add(html.substr(cursor, html.length - cursor));
    code += 'return r.join("");';
    return new Function(code.replace(/[\r\t\n]/g, '')).apply(options);
};

/********************************************/

function makeString(object) {
    if (object == null) return '';
    return String(object);
};

var escapeChars = { lt: '<',
    gt: '>', quot: '"', amp: '&', apos: "'"
};

function unescapeHTML(str) {
    return makeString(str).replace(/\&([^;]+);/g, function(entity, entityCode) {
        var match;

        if (entityCode in escapeChars) {
            return escapeChars[entityCode];
        } else if (match = entityCode.match(/^#x([\da-fA-F]+)$/)) {
            return String.fromCharCode(parseInt(match[1], 16));
        } else if (match = entityCode.match(/^#(\d+)$/)) {
            return String.fromCharCode(~~match[1]);
        } else {
            return entity;
        }
    });
};
