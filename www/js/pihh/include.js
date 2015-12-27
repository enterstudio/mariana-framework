var $$mariana_includes = function(){
    var includes = $('div[mariana-include]');
    jQuery.each(includes, function(){
        var file = 'mvc/views/' + $(this).data('include');
        $(this).load(file);
    }).promise().done( function(){
        setTimeout($$mariana_template(),1000);
    });
};

