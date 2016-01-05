var $$mariana_includes = function(){
    app.$undoEngine(app);
    var includes = $('div[mariana-include]');
    jQuery.each(includes, function(){
        var file = 'mvc/views/' + $(this).data('include');
        $(this).load(file);
    }).promise().done( function(){
        setTimeout(function(){
            app = new $$mariana();
            app.$scope = scope;
            app.$template();
        },100);
    });
};

