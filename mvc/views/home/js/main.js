/**
 * Created by fsa on 25/01/2016.
 */
//  Start the template engine

//  Set the scope
var scope = {
    paragraph: [
        "The simplest, yet, powerfull framework to build awesome web applications in record time.",
        "Mariana is a PHP MVC framework built having one thing in mind: \"to be the framework with the smallest learning curve ever\". In fact, in order to effectivelly use Mariana Framework you just need to spend 10minutes reading the <a href=\"#\">documentation</a> or watching the <a href=\"#\">video</a> and that's basically it.",
        "Mariana has another cool feature: You can download full systems and just copy and paste them. Every codeyou download from the repository has a video explaining it. This way, you don't have to loose hugeamounts of time trying to understand what's in the code. Feel free to try it on this  <a href=\"https://github.com/pihh/mariana-framework/archive/master.zip\" download>link</a>."
    ],
    signature: "&mdash; Built with <span class=\"red-text\">&#x2661;</span> by \"Pihh\", dedicated to Mia, the real-life <span class=\"heading\">\"Mariana\"</span>",
    link: [{
        first: "#",
        second: "#",
        third: "#"
    }]
};
//  Choose template Id
var template = unescapeHTML(document.getElementById("body").innerHTML);
console.log($closet(template, scope));
document.getElementById("mariana-body").innerHTML = $closet(template, scope);