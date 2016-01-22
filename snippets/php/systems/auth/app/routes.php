<?php

Router::post("/signin",array(
"controller"    =>  "AuthController",
"method"        =>  "signin"
));

Router::post("/signup",array(
"controller"    =>  "AuthController",
"method"        =>  "signup"
));

Router::post("/signout", array(
"controller"    =>  "AuthController",
"signout"       =>  "signout"
));


# VALID POST REQUESTS
Router::post("/",array(
"controller"    =>  "TestController",
"method"        =>  "post_test"
));

?>