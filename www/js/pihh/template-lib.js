/**
 * Created by filipe_2 on 12/25/2015.
 */


var $$mariana = {
    name    : "Pihh",
    age     : "30",
    class   : "nova classe",
    message : "mensagem manipulada pelo nódulo do paragrafo (sem manipular parent)"
};


var $$c = document.body.children;


var $$documentMapObject = {};
var $$mutations = {};

$(document).on("keyup","input[mariana-listen]", function(){

    var this_input = $(this);
    scopeAttr = $(this).attr("mariana-listen");
    value = this.value;
    $$mariana[scopeAttr] = value;

    $.each($$mutations[scopeAttr], function (index,value) {
        var i = value;
        angular = $$templateEngine($$documentMapObject[ i ].template, $$mariana);
        xx = document.body.children[i];
        $(xx).html(angular);

    });
    this_input.focus();
});

var $$templateEngine = function (tpl, data , nodeIdentifyer) {
    // Regex para substituir todos os coisos eh
    var re = /{{([^}}]+)?}}/g, match;
    while (match = re.exec(tpl)) {
        tpl = tpl.replace(match[0], data[match[1]]);

        // Track de mutações.
        scopeEqv = match[1];

        if($$mutations[scopeEqv] != undefined) {
            if(!$$mutations[scopeEqv][nodeIdentifyer] && nodeIdentifyer !== undefined ) {
                $$mutations[scopeEqv].push(nodeIdentifyer);
            }
        } else {
            $$mutations[scopeEqv] = [];
            $$mutations[scopeEqv].push(nodeIdentifyer);
        }
    }
    return tpl;
}

$$mariana_template = function() {

    // if mutations were set, unset them and track again
    $$mutations = {};
    // Tracks all body objects and maps every node
    for (i = 0; i < $$c.length; i++) {
        tagName = $$c[i].tagName;
        divHtml = $$c[i].outerHTML;
        if (tagName !== "SCRIPT") {
            if (divHtml.contains("{{")) {
                // do stuff
                angular = $$templateEngine(divHtml, $$mariana, i);

                newNode = {
                    "id": i,
                    "tagName": tagName,
                    "template": divHtml,
                    "angular": angular
                }
                $$documentMapObject[i] = newNode;
            }
        }
    }

    $.each($$documentMapObject, function (index, value) {
        i = value.id;
        t = value.template;
        a = $$templateEngine(t, $$mariana,i);
        xx = document.body.children[i];
        $(xx).replaceWith(a);

    });
}

$$mariana_template();
// TEMPLATE ENGINE - MEDIUM VERSION
