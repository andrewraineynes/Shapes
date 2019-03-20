jQuery(function( $ ){

    $(".nav-primary").stick_in_parent();

    $('#trigger-overlay').click(function(event){
        event.preventDefault();
        $('.overlay .search-form input[type="search"]').focus();
    })

});