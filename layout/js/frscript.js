$(function () {

    // Run SelectBoxIt Plugin
    $("select").selectBoxIt({
        autoWidth: false,
        showEffect: 'fadeIn',
        hideEffect: 'fadeOut'
    });

    // Toggle Placeholder Text
    $('[placeholder]').on('focus', function () {
        $place = $(this).attr('placeholder');
        $(this).attr('placeholder', '');
    }).on('blur', function () {
        $(this).attr('placeholder', $place);
    });

    // Add Span For Asterisk After Each Input Has Attribute Required
    $('input, textarea').each(function () { // use each to check for each inputs
        if ($(this).attr('required') === 'required') {
            $(this).after("<span class='asterisk'>*</span>");
        }
    });

    // Warn The Admin Before He Delete A Member
    $('.confirm, .warnme').click(function () {

        return confirm("Are You Sure You Want To Delete This Element ?");

    });
    // Toggle Login Form And Sign-up Form
    $('.login-link').on('click', function () {

        $('.form-container .form-background').hide();
        $('.form-container .form-background-sign-up').show();

    });

    $('.sign-up-link').on('click', function () {

        $('.form-container .form-background-sign-up').hide();
        $('.form-container .form-background').show();

    });

    $('.ad-form #name').on('keyup', function () {

        $('.ad-thumbnail h3').text($(this).val());

    });

    $('.ad-form #description').on('keyup', function () {

        $('.ad-thumbnail p').text($(this).val());

    });

    $('.ad-form #price').on('keyup', function () {

        $('.ad-thumbnail .price').text( "$" + $(this).val());

    });

});


