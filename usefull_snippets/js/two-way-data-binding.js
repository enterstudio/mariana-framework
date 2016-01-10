/**
 * Created by fsa on 10/01/2016.
 */

//Got this great piece of code from https://gist.github.com/384583
Object.defineProperty(Object.prototype, "__watch", {
    enumerable: false,
    configurable: true,
    writable: false,
    value: function (prop, handler) {
        var val = this[prop],
            getter = function () {
                return val;
            },
            setter = function (newval) {
                val = newval;
                handler.call(this, prop, newval);
                return newval;
            };

        if (delete this[prop]) { // can't watch constants
            Object.defineProperty(this, prop, {
                get: getter,
                set: setter,
                enumerable: true,
                configurable: true
            });
        }
    }
});

Object.defineProperty(Object.prototype, "__watchCallback", {
    enumerable: false,
    configurable: true,
    writable: false,
    value: function (prop, handlers) {
        var val = this[prop],
            getter = function () {
                return val;
            },
            setter = function (newval) {
                val = newval;
                for(var i=0;i<handlers.length;i++){
                    handlers[i].call(this, prop, newval);
                }
                return newval;
            };

        if (delete this[prop]) { // can't watch constants
            Object.defineProperty(this, prop, {
                get: getter,
                set: setter,
                enumerable: true,
                configurable: true
            });
        }
    }
});

var DataBinder = function () {
    //The property is changed whenever the dom element changes value
    //TODO add a callback ?
    this._bind = function (DOMelement, propertyName) {
        //The next line is commented because priority is given to the model
        //this[propertyName] = $(DOMelement).val();
        var _ctrl = this;
        $(DOMelement).on("change input propertyChange", function (e) {
            e.preventDefault();
            _ctrl[propertyName] = DOMelement.val();
        });
    };
    var watchList = [];
    //The dom element changes values when the propertyName is setted
    this._watch = function (DOMelement, propertyName) {
        watchList[propertyName] = watchList[propertyName] || [];
        var list = watchList[propertyName];
        if (list.indexOf(DOMelement) > 0) {
            return;
        }
        list.push(DOMelement);
        var handler = function (propertyName, value) {
            for (var i = 0; i < list.length; i++) {
                var watch = list[i];
                $(watch).val(value);
                $(watch).html(value);
            }
        };
        this.__watch(propertyName, handler);
    };

    this.watch = function (DOMelement, propertyName) {
        var _binder = this;
        $(DOMelement).on("change input propertyChange", function(e) {
            e.preventDefault();
            _binder[propertyName] = DOMelement.val();
        });
    };

    var callbackList = [];
    this.addCallback= function (propertyName, callback) {
        callbackList[propertyName] = callbackList[propertyName] || [];
        var list = callbackList[propertyName];
        list.push(callback);
        this.__watchCallback(propertyName, list);
    };
};

var viewBinder = new DataBinder();
viewBinder.watch($('#textt1'), 'value');
viewBinder.addCallback('value', function(p,v){
    $('#textt2').val(v);
});
viewBinder.addCallback('value',function(p,v){
    $('#textt3').val(v);
});
viewBinder.addCallback('value', function(p,v){
    if(v>1000){
        $('#output2').addClass('important');
    }else{
        $('#output2').removeClass('important');
    }
});
