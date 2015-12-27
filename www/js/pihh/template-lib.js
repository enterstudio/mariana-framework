/**
 * Created by filipe_2 on 12/25/2015.
 */


var $$mariana = {
    name    : "pihh",
    age     : "30",
    class   : "nova classe",
    message : "mensagem manipulada pelo nódulo do paragrafo (sem manipular parent)",
    repeat : [{name: "pihh"},{name: "mariana"}]
};

var $$documentMapObject = {};
var $$mutations = {};
var $$c = {};

$(document).on("keyup","input[mariana-listen]", function(){
    var this_input = $(this);
    scopeAttr = $(this).attr("mariana-listen");
    value = this.value;
    $$mariana[scopeAttr] = value;
    this_input.focus();
});


// Havendo mudanças no tamanho do documento -> corre isto
// Faz o map de td o documento e verifica {{ ao descobrir {{ substitui isto pelo correspondente scope.

$$mariana_template = function() {

    var allElems=$('body').find('*');
    //console.log(allElems);

    $$c = {};
    $$documentMapObject = {};
    $$mutations = {};

    // Reset Body
    //$$c = document.body.children;
    $$c = allElems;

    // if mutations were set, unset them and track again
    // Tracks all body objects and maps every node
    for (i = 0; i < $$c.length; i++) {
        tagName = $$c[i].tagName;
        divHtml = $$c[i].outerHTML;
        if (tagName !== "SCRIPT") {

            if (divHtml.indexOf("{{") > -1) {
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
        //console.log(value);
    });

    console.log($$mutations);
}


//
var $$templateEngine = function (tpl, data , nodeIdentifyer) {
    // Regex para substituir todos os coisos eh
    var re = /{{([^}}]+)?}}/g, match;
    while (match = re.exec(tpl)) {

        // Caso não haja data to match, passa a null
        if(data[match[1]] === undefined){
            data[match[1]] = "";
        }

        // Replace everything in the template that matches {{}}
        tpl = tpl.replace(match[0], data[match[1]]);

        // Track de mutações - no mutation adiciona o local do nódulo e que $$mariana[attr] se refere.
        // Adicion um watcher no $$mariana[attr] que está á escuta de mudanças e caso haja actualiza o template em questão.
        scopeEqv = match[1];
        if($$mutations[scopeEqv] !== undefined) {
            if(!$$mutations[scopeEqv][nodeIdentifyer]) {
                console.log("Mutação " + scopeEqv + " nodulo " + nodeIdentifyer);
                //console.log("Mutation type A: " + scopeEqv + " - " + nodeIdentifyer);
                $$mutations[scopeEqv].push(nodeIdentifyer);
                $$watcherEngine(scopeEqv);
            }else{
                //console.log("Mutation type B: " + scopeEqv + " - " + nodeIdentifyer);
            }
        } else {
            //console.log("Mutation type C: " + scopeEqv + " - " + nodeIdentifyer);
            $$mutations[scopeEqv] = [];
            $$mutations[scopeEqv].push(nodeIdentifyer);
            $$watcherEngine(scopeEqv);
        }
    }
    return tpl;
}


//Função que adiciona novos
$$watcherEngine = function(name){
    // unwatch all propertys
    $$mariana.unwatch(name);
    // watch all propertys
    $$mariana.watch(name, function (id, oldval, newval) {
        try {
            return newval;
        } catch(e) {

        } finally {
            setTimeout(function(){
                $.each($$mutations[id], function (index,value) {
                    var i = value;
                    angular = $$templateEngine($$documentMapObject[ i ].template, $$mariana);
                    xx = document.body.children[i];
                    $(xx).replaceWith(angular);
                });
            },1);
        }
    });
}
/*
$$replaceEngine = function () {
    $.each($("[mariana-repeat]"), function(i,v){
        repeater = $(this).attr("mariana-repeat");
        repeaterTemplate = $(this).prop('outerHTML');
        data = $$mariana;
        // ===============================================
        for (i = 0; i < $$mariana[repeater].length; i++) {
            console.log($$mariana[repeater][i]["name"]);

            var re = /{{([^}}]+)?}}/g, match;
            while (match = re.exec(repeaterTemplate)) {

                //console.log(data[repeater][i][match[1]]);

                // Caso não haja data to match, passa a null
                if (data[match[1]] === undefined) {
                    data[match[1]] = "";
                }
                // Replace everything in the template that matches {{}}
                repeaterTemplate = repeaterTemplate.replace(match[0], data[repeater][i][match[1]]);
                $( repeaterTemplate ).insertAfter( this );
            }
        }
    });
}
*/
$$mariana_template();

// TEMPLATE ENGINE - MEDIUM VERSION
