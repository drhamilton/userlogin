(function($){
    var init = function(){
        attachEventHandlers();
    };

    var attachEventHandlers = function(){
        $('#register').on('submit', register);
        $('#login').on('submit', login);
        $('.button--container button').on('click', toggleForm);
    };

    var toggleForm = function(e){
        var button = $(this);

        if (button.hasClass('active')){
            return false;
        }
        else {
            button.siblings().removeClass('active');
            button.toggleClass('active');

            var clickedForm = $(this).data('form');

            var formEl = '#' + clickedForm;
            $('form.active').removeClass('active');
            $(formEl).addClass('active');
        }
    };

    var register = function(e){
        e.preventDefault();

        var data = {},
            form = $(this),
            formData = $(form).serializeArray();

        formData.forEach(function(el){
            data[el.name] = el.value;
        });

        $.ajax('/register', {
            method: 'POST',
            data: data
        }).done(function(response){
            var res = JSON.parse(response);
            console.log(res);
            if (res.err){
                showRegisterError(res.err, form);
            }
            else {
                showRegistered(res.data, form);
            }
        });
    };

    var login = function(e){
        e.preventDefault();

        var data = {},
            form = $(this),
            formData = $(form).serializeArray();

        formData.forEach(function(el){
            data[el.name] = el.value;
        });

        $.ajax('/login', {
            method: 'POST',
            data: data
        }).done(function(response){
            var res = JSON.parse(response);

            if (res.err){
                showLoginError(res.err, form);
            }
            else {
                showLoggedIn(res.data, form);
            }
        });
    };

    var showRegisterError = function(err, form){
        var message = err;
        showMessage(message, form);
    };

    var showRegistered = function(user, form){
        var message = 'Registered!';
        showMessage(message, form);
    };

    var showLoginError = function(err, form){
        var message = err;
        showMessage(message, form);
    };

    var showLoggedIn = function(user, form){
        var message = 'Logged In!';
        showMessage(message, form);
    };

    var showMessage = function(message, form){
        $(form).find('.form--message').text(message);
    };

    init();
}(jQuery));
