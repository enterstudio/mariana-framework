'use strict';

describe("Scope", function(){
    it("Can be constructed and used as an object", function(){
        var scope = new Scope();
        scope.aProperty = 1;
        expect(scope.aProperty).toBe(1);
    });

    it("Can add a watch", function(){
        var scope = new Scope();

        scope.$watch(function (){}, function(){} );

        expect(scope.$$watchers).toBeDefined();
        expect(scope.$$watchers.length).toBe(1);
    });
});

describe("$digest", function(){
    it("Runs all the watchers when runned", function(){
        var scope = new Scope;
        var data = {};

        scope.$watch(function(){ data.watch1 = "Hello World" },function(){});
        scope.$watch(function(){ data.watch2 = "Hello World" },function(){});

        scope.$digest();

        expect(data.watch1).toBe("Hello World");
        expect(data.watch2).toBe("Hello World");
    });

    it("Runs the watcher listener when the watcher changes it's value", function(){
            var scope = new Scope;
            var data = {};

            scope.$watch(function(){
                return "Hello World";
            }, function(){
               data.value = "Hello World";
            });

            scope.$digest();

            expect(data.value).toBe("Hello World");
        });

    it("Corre todos os watchers pelo menos uma vez", function(){
        //Ao arrancar compara os watchers todos aquela função dont let n sei que
            var scope = new Scope;
            var data = {};

            scope.$watch(function(){return null;}, function(){data.value = "Hello World";});

            scope.$digest();

            expect(data.value).toBe("Hello World");
        });

    it("Corre todos os watchers pelo menos uma vez", function(){
        //Ao arrancar compara os watchers todos aquela função dont let n sei que
        var scope = new Scope;
        var data = {};

        //scope.$watch(function(){return null;}, function(){data.value = "Hello World";});
        scope.$watch(function(){return "Hello World";}, function(oldValue,newValue,scope){
            data ={
                newValue: newValue,
                oldValue: oldValue,
                scope: scope
            }
        });

        scope.$digest();

        expect(data.newValue).toBe("Hello World");
        expect(data.oldValue).toBe("Hello World");
        expect(data.scope).toBe(scope);
    });

    it("Keeps digesting when a watched value is changed  in the digest", function(){
        var scope = new Scope;
        var runCount = 0;

        scope.$watch(function(scope){ return scope.myValue; }, function(newValue,oldValue,scope){runCount++});
        scope.$watch(function(scope){ return scope.myValue; }, function(newValue,oldValue,scope){scope.myValue = "Hello World"});

        scope.$digest();

        expect(runCount).toBe(2);
    });

    it("Throws if digest loop keeps going for more than 5 times", function(){
        // Native were 10 times
        var scope = new Scope;

        scope.$watch(function(scope){return scope.myValue;}, function(newValue, oldValue, scope){
            scope.myValue = new Date();
        });

        expect(function(){scope.$digest();}).toThrow();
    });

    it("Only keeps digesting until the last dirty reporting watcher returns not dirty", function(){
        var scope = new Scope;
        var values = [0,1,2];
        var watcherCount = 0;

        scope.$watch(function(scope){watcherCount++ ; return values[0];}, function(){});
        scope.$watch(function(scope){watcherCount++ ; return values[1];}, function(){});
        scope.$watch(function(scope){watcherCount++ ; return values[2];}, function(){});

        scope.$digest();
        expect(watcherCount).toBe(6);

        values[0] = 1;
        scope.$digest();
        expect(watcherCount).toBe(10);
    });

    it("Does value equality comparison if requested", function(){
        // The object only changes when the scpe object has changed (a propety is not enough)
        var scope = new Scope;
        scope.value = {
            value1: "Hello",
            value2: "World"
        };
        scope.count = 0;

        scope.$watch(function(scope){ return scope.value},function(newValue, oldValue, scope){scope++},true);
            // I want to watch scope.value ^
            // Adiciona-se o true para garantir que não quremos comparações de referencias queremos comparar o objecto todo.

        scope.$digest();

        scope.value.value2 = "TechEd";
        scope.$digest();

        expect(scope.count).toBe(2);

    });
});