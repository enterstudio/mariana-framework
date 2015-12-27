/**
 * Created by filipe_2 on 12/25/2015.
 * TODO: Está a fazer o watch mas não está a fazer o parsing de template. tenho de resolver isto.
 */

var $$mariana = function() {
    this.$scope = {};
    this.$body = {};
    this.$documentMapObject = {};
    this.$mutations = {};

    // TEMPLATE
    // =============================================================
    this.$template = function(){
        var allElems=$('body').find('*');
        this.$body = allElems;

        for (i = 0; i < this.$body.length; i++) {
            tagName = this.$body[i].tagName;
            divHtml = this.$body[i].outerHTML;

            if (tagName !== "SCRIPT") {
                if (divHtml.indexOf("{{") > -1) {
                    // do stuff
                    newNode = {
                        "id": i,
                        "tagName": tagName,
                        "original_template": divHtml,
                        "parsed_template": this.$parsingEngine(divHtml, this.$scope, i)
                    }
                    this.$documentMapObject[i] = newNode;
                }
            }
            //  End parsing:..
        }// End For Loop:..
        this.$replaceEngine(this.$documentMapObject, this.$body); //Replace the node by the parsed one
    }// End Template Function:..


    // PARSING ENGINE
    // @Desc: Add flag para fazer ou não watch.
    // @Todo: Add each loop to $scope objects - Logic: if node has mia-repeat="" $.each(pow);
    // =============================================================
    this.$parsingEngine = function (tpl, data , nodeIdentifyer) {

        var re = /{{([^}}]+)?}}/g, match;   // Regex para substituir todos os coisos eh
        while (match = re.exec(tpl)) {  // Enquanto houser matches

            if(typeof (data !== undefined)) {
                if(data[match[1] == undefined]){ // Caso não haja data to match no scope, passa a vazio
                    data[match[1]] = "";
                }
                tpl = tpl.replace(match[0], data[match[1]]); // Replace everything in the template that matches {{}}
            }

            // Add Watcher to the nodes
            if (this.$mutations[match[1]] === undefined) {
                this.$mutations[match[1]] = [];
                this.$mutations[match[1]].push(nodeIdentifyer); // Ex: this.$mutations.name = [1,2,3] -> temos name nestes nódulos
                this.$watcherEngine(match[1], this);
            }else{
                if(this.$mutations[match[1]].indexOf(nodeIdentifyer) > -1){
                    // Existe na array - Fica quieto
                }else{
                    this.$mutations[match[1]].push(nodeIdentifyer);
                    this.$watcherEngine(match[1],this);
                }
            }   // End Watcher
        }   // End While Loop
        return tpl;
    }   // End Parse Engine

    // REPLACE ENGINE
    // Checks witch nodes should be replaced and replaces by the template on them
    //===============================================================================================
    this.$replaceEngine = function(toBeMapped, body){
        console.log(toBeMapped);
        $.each(toBeMapped, function (index, value) {
            i = value.id;
            t = value.parsed_template;
            //console.log(body[i]);
            //console.log(t);
            $(body[i]).replaceWith(t);
        });
    }

    // WATCHER ENGINE
    // Adds watcher to scope variables for changes. On change runs a function
    //===============================================================================================
    this.$watcherEngine = function(name, marianaHerself){

        this.$scope.unwatch(name);    // unwatch all propertys
        this.$scope.watch(name, function (id, oldval, newval) { // watch for changes on a property of the object
            // Explaining:
            // The new Val should be set before replacing all the template.
            try {
                return newval;
            } catch(e) {

            } finally {
                setTimeout(function(){
                    $.each(marianaHerself.$mutations[name], function(index){
                        injectionNode = marianaHerself.$documentMapObject;
                        injectionBody = marianaHerself.$body;
                        marianaHerself.$replaceEngine(injectionNode, injectionBody);
                    });
                },1000);
            }
        });
    }

    // BindingEngine
    // Binds propertys to the scope.
    //===============================================================================================
    this.$bindEngine = function(attr,value) {
        this.$scope[attr] = value;
        console.log("New Attr: " + attr + " - value: " + value);
    }

};

// RUN IT...
var app = new $$mariana();
app.$scope = {
    name    : "pihh",
    age     : "30",
    class   : "nova classe",
    message : "mensagem manipulada pelo nódulo do paragrafo (sem manipular parent)",
    repeat : [{name: "pihh"},{name: "mariana"}]
};

app.$scope.watch('name', function (id, oldval, newval) {
    console.log('name.' + id + ' changed from ' + oldval + ' to ' + newval);
    return newval;
});

$(document).on("keyup","input[mariana-listen]", function(scope){
    var this_input = $(this);
    mutationAttr = $(this).attr("mariana-listen");
    value = this.value;
    app.$bindEngine(mutationAttr,value);
    this_input.focus();
});

$(document).ready(function(){
    app.$template();
});

// TEMPLATE ENGINE - MEDIUM VERSION
