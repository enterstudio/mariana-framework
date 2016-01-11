/**
 * Created by fsa on 11/01/2016.
 *
 * ModularJs apps devem ser escritas em layers de forma a que se consiga bastante abstração
 *
 * BASE: Baselibs como jquery por ex
 * CORE: Core functionality e abstacts baselayer for the above layers
 * SANDBOX: API or interface for the forth layer
 * MODULES: Chunks of code - independent - if one module blows the otyhers are ok
 *
 *
 * Começamos a escrever pelo módulo pois através daqui decidimos as dependencias do projecto.
 */

//Sb = Sandbox;

CORE.create.module("searchBox", function(sb){
    var input, button, reset;

    return {
        init: function(){
            input = sb.query("#search_input")[0]; // Como tamos a retornar um objecto, mete-se desta forma para só trazer o DOM
        },
        destroy: function(){}
    }

});