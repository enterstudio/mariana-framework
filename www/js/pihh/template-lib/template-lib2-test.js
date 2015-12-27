'use strict';

describe("$$mariana", function(){
    it("Can be constructed and used as an object", function(){
        var $mariana = new $$mariana();
        $mariana.aProperty = 1;
        expect($mariana.aProperty).toBe(1);
    });

    it("Can add array to it's scope", function(){

        var $mariana = new $$mariana();
        $mariana.$scope = [{
            id:"1",
            name:"mia"
        }];

        expect($mariana.$scope.length).toBe(1);
    });

    it("$$mariana.$template is a function that checks all the nodes of the current page", function(){
        /*
                confirm it by running index.html with some content and:
                     -  var app = new $$mariana();
                        app.$template();
                        console.log(app.$body);

         */
    });
});
