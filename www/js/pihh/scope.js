
'use strict'

var dontLetFirstLastToBeNull = function(){}; // Em javacript uma função pode ser comparada a kk outra coisa, logo o last nunca será este bjecto

function Scope(){
    this.$$watchers = [];   // Array de coisas que vão estar á escuta

};

Scope.prototype.$watch = function(watcher,listener,valueEq){

    this.$$watchers.push({
        watcher: watcher,
        listener: listener || function(){},
        last: dontLetFirstLastToBeNull,  // null é um mau valor para last porque o novo val pode ser null
        valueEq: !!valueEq // Compara igualdade de valores ( se é um obj por ex, compara o obj inteiro com todas as refs )
                    // !! -> true or false
    });
    this.$$lastDirtyWatch = null; // Resets value when ever some watcher is pushed
};

// Básicamente faz o loop por todos os watchers e verifica 1 a 1 se este mudou
Scope.prototype.$digest = function(){
    var self = this;
    var dirty;
    var ttl = 10; // Time to live ( num de vezes que corre o loop )
    self.$$lastDirtyWatch = null; //

    do{
        dirty = doDigest(this.$$watchers);
        ttl--;
        if(dirty && !ttl){
            throw "Digestion exception";
        }
    }while (dirty);

    function doDigest() {
        // Esta função corre o loop. Caso alguma variavel retorne dirty isto manda um sinal a dizer eH tá sujo, faz alguma coisa
        var isDirty = false;
        for (var i = 0, len = self.$$watchers.length; i < len; i++) {
            //Conf que o runs all the Watchers
            // self.$$watchers[i].watcher(self); // Por cada watcher -> Faz a tua avaliação. -> Mete-se a scope aqui para avaliar se a scope mudou ou não

            var oldValue = self.$$watchers[i].last;
            var newValue = self.$$watchers[i].watcher(self);

            if (newValue !== oldValue) {
                //self.$$watchers[i].listener();
                self.$$watchers[i].listener(newValue, oldValue == dontLetFirstLastToBeNull ? newValue : oldValue, self);
                self.$$watchers[i].last = newValue;
                isDirty = true;

                self.$$lastDirtyWatch = self.$$watchers[i];
            }else if(self.$$watchers[i] === self.$$lastDirtyWatch){
                return false;
            }
        }
        return isDirty;
    }

    // Function que compara a igualdade de valores (helper)
    function areEqual(newValue, oldValue, valueEq){
        if(valueEq){

            var aProps = Object.getOwnPropertyNames(newValue);
            var bProps = Object.getOwnPropertyNames(oldValue);
            if (aProps.length != bProps.length) {
                return false;
            }
            for (var i = 0; i < aProps.length; i++) {
                var propName = aProps[i];
                if (a[propName] !== b[propName]) {
                    return false;
                }
            }
            return true;

        }else{
            return newValue === oldValue || typeof newValue === 'number' && typeof oldValue === 'number' && isNaN(newValue) && !isNaN(oldValue);
        }
    }


};