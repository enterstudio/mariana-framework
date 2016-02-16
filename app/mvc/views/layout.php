<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mariana - Framework</title>
    <base href="<?= FRAMEWORK_ROOT ?>">
    <!-- CSS FILES -->
    <link rel="stylesheet" href="app/www/fonts/playfair-display/700i.css" >
    <link rel="stylesheet" href="app/www/fonts/quattrocento-sans/400.css" >
    <link rel="stylesheet" href="app/www/css/style.css" >

</head>
<body>
    <div id="mariana-navbar"></div>
    <div id="mariana-sidebar-left"></div>
    <div id="mariana-body"></div>
    <div id="mariana-sidebar-right"></div>
    <div id="mariana-footer"></div>
    <div id="mariana-templates">
        <?php include_once (VIEW_PATH.DS."home".DS."templates".DS."body.php"); ?>
    </div>
    <div id="mariana-scripts">
        <script src="https://code.jquery.com/jquery-1.12.0.js"></script>
        <script src="app/www/js/mariana-closet-template-engine.js"></script>
        <script src="app/mvc/views/home/js/main.js"></script>
    </div>
</body>
</html>