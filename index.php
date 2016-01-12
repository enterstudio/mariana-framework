<?php


# Defines
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)));
define('VIEW_PATH', ROOT.DS."mvc".DS."views");

# including the required filesystem and booting the framework
require_once(ROOT.DS."vendor".DS."autoload.php");
require_once(ROOT.DS."app/app.php");

?>
 
<!-- // TESTING STUFF --

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body>
    <div mariana id="app">
        <div mariana-include data-include="partials/nav.html"></div>

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
        <span id="span2" mariana-repeat="repeat">
            repeat - {{name}}
        </span>
        <br>
        <input type="text"  placeholder="clicar aqui para manipular os nomes" value="{{name}}" mariana-listen="name">
        <input type="text"  placeholder="clicar aqui para manipular a mensagem" value="{{message}}" mariana-listen="message">
        <br>
        <button onclick="$$mariana_includes()">Add include file to mariana includes</button>
        <button onclick="console.log(app.$body);console.log(app.$documentMapObject)">replace</button>

        <div mariana-include data-include="partials/include.html"></div>
    </div>
</body>


<script src="https://code.jquery.com/jquery-2.1.4.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script src="www/js/pihh/prototype.js.js"></script>
<!--
<script src="www/js/pihh/template-lib/template-lib4.js"></script>
--
<script src="www/js/pihh/include.js"></script>
<script>
    console.log("x");
    var i = 0;
    while(i <= 18){
        console.log(i);
        console.log($("div").eq(i).text);
        i++;
    }
</script>
-->
