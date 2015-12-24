<?php


# Defines
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)));
define('VIEW_PATH', ROOT.DS."mvc".DS."views");

# Getting the urls for the MVC
//$url = $_SERVER["REQUEST_URI"];

# including the required filesystem

require_once(ROOT.DS."vendor".DS."autoload.php");
require_once(ROOT.DS."app/app.php");

# BOOT THE APP
#View::render('pages'.DS.'home');



?>
<head>
<script src="https://code.jquery.com/jquery-2.1.4.js"></script>
</head>
<body>

    <div id="div1" class="{{class}}">
        <h1>
            Nome: <small> {{name}} </small>
        </h1>
        <p>"{{message}}"<p>
        <div id="div2">(div para teste de inheritance)
            <span id="span1">(span para teste de inheritance)</span>
        </div>
    </div>
    <p>Paragrafo sem id para manipular data através do map de nodulos</p>
    <span id="span2"> span2</span>

    <input type="text" onkeyup="customBind(this.value,'name')" placeholder="clicar aqui para manipular a view">
    <input type="text" onkeyup="customBind(this.value,'message')" placeholder="clicar aqui para manipular a view">
</body>
<script>
    var c = document.body.children;
    var documentMapObject = {};
    var i;

    var scope = {
        name    : "Pihh",
        age     : "30",
        class   : "nova classe",
        message : "mensagem manipulada pelo nódulo do paragrafo (sem manipular parent)"
    };

    customBind = function(value,scopeAttr){
        scope[scopeAttr] = value;
        angular = TemplateEngine(documentMapObject[0].template, scope);
        //$("#div1").html(angular);
    }

    var TemplateEngine = function(tpl, data) {
        // magic here ...
        var re = /{{([^}}]+)?}}/g, match;
        while(match = re.exec(tpl)) {
            tpl = tpl.replace(match[0], data[match[1]])
        }
        return tpl;
    }

    //var output = input.replace (/\[img\](.*?)\[\/img\]/g, "<img src='$1'/>");


    for (i = 0; i < c.length; i++) {

        tagName = c[i].tagName;
        divHtml = c[i].outerHTML ;
        if(tagName !== "SCRIPT"){
            if(divHtml.contains("{{")) {
                // do stuff
                angular = TemplateEngine(divHtml, scope);

                newNode = {
                    "id" : i,
                    "tagName" : tagName,
                    "template" : divHtml,
                    "angular": angular
                }
                documentMapObject[i] = newNode;
                console.log(i);
            }
        }

    }

    $.each(documentMapObject, function(index, value) {
        console.log(value);

        i = value.id;
        t = value.template;
        a = TemplateEngine(t,scope);
        xx = document.body.children[i];
        $(xx).replaceWith(a);

    });



// TEMPLATE ENGINE - MEDIUM VERSION

</script>

