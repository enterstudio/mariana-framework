var mariana_includes = function(){
    var includes = $('div[mariana-include]');
    var include = 0;
    jQuery.each(includes, function(){
        var file = 'mvc/views/' + $(this).data('include') + '.html';
        $(this).load(file);
        include++;
    });
    if(include > 0){
        pihh_template();
    }
};

