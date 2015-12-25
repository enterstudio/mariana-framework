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

</head>
<body>

    <!--    WORKS
        <div class="pihh-include" data-include="/mvc/views/partials/importedPartial.html"></div>
    -->
<div mariana>

    <div  class="{{class}}">
        <h1>
            Nome: <small> {{name}} </small>
        </h1>
        <p>"{{message}}"<p>
        <div id="div2">(div para teste de inheritance)
            <span id="span1">(span para teste de inheritance)</span>
        </div>
    </div>
    <p>Paragrafo sem id para manipular data através do map de nodulos - {{name}}</p>
    <span id="span2"> span2 - {{message}}</span>
    </div>
    <div mariana-include data-include="include"></div>
    <input type="text"  placeholder="clicar aqui para manipular os nomes" value="{{name}}" mariana-listen="name">
    <input type="text"  placeholder="clicar aqui para manipular a mensagem" value="{{message}}" mariana-listen="message">
</body>

<script src="https://code.jquery.com/jquery-2.1.4.js"></script>
<script src="www/js/pihh/include.js"></script>
<script src="www/js/pihh/template-lib.js"></script>

