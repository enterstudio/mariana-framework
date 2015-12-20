<html>
<head>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <style>
        .row{clear:both;}
        .col {float:left;width:150px;}
        .template {display:none}
        .grey .col{background-color:grey}
        .white .col{background-color:white}
    </style>
</head>
<body>



<div class="result"></div>

<script type="template" id="template">
    <h2>
        <a href="{{href}}">
            {{title}}
        </a>
    </h2>
    <img src="{{imgSrc}}" alt="{{title}}">
</script>
<script>
    (function () {
        // simulates AJAX request
        var data = [{
                title: "Create a Sticky Note Effect in 5 Easy Steps with CSS3 and HTML5",
                href: "http://net.tutsplus.com/tutorials/html-css-techniques/create-a-sticky-note-effect-in-5-easy-steps-with-css3-and-html5/",
                imgSrc: "https://d2o0t5hpnwv4c1.cloudfront.net/771_sticky/sticky_notes.jpg"
            }, {
                title: "Nettuts+ Quiz #8",
                href: "http://net.tutsplus.com/articles/quizzes/nettuts-quiz-8-abbreviations-darth-sidious-edition/",
                imgSrc: "https://d2o0t5hpnwv4c1.cloudfront.net/989_quiz2jquerybasics/quiz.jpg"
            }, {
                title: "WordPress Plugin Development Essentials",
                href: "http://net.tutsplus.com/tutorials/wordpress/wordpress-plugin-development-essentials/",
                imgSrc: "https://d2o0t5hpnwv4c1.cloudfront.net/1101_wpPlugins/wpplugincourse.png"
            }],
            template = document.querySelector('#template').innerHTML,
            result = document.querySelector('.result'),
            attachTemplateToData;


        // Accepts a template and data. Searches through the
        // data, and replaces each key in the template, accordingly.
        attachTemplateToData = function(template, data) {
            var i = 0,
                len = data.length,
                fragment = '';

            // For each item in the object, make the necessary replacement
            function replace(obj) {
                var t, key, reg;

                for (key in obj) {
                    reg = new RegExp('{{' + key + '}}', 'ig');
                    t = (t || template).replace(reg, obj[key]);
                }

                return t;
            }

            for (; i < len; i++) {
                fragment += replace(data[i]);
            }

            return fragment;
        };

        result.innerHTML = attachTemplateToData(template, data);

    })();
</script>