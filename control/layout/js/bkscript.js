$(function () {

    // Run SelectBoxIt Plugin
    $("select").selectBoxIt({
        autoWidth:false,
        showEffect:'fadeIn',
        hideEffect:'fadeOut'
    });

    // Tags Box
    $("#tags").tagit({
        tags: "skills"
    });

    // Toggle Class Selected
    $('.toggle-info').click(function () {

        $(this).toggleClass('selected').parent().next('.panel-body').slideToggle(200);

        if ($(this).hasClass('selected')) {

            $(this).html("<i class='fa fa-minus'></i>");

        } else {

            $(this).html("<i class='fa fa-plus'></i>");

        }

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

    // Show Password On Hover In show-me icon
    let passEle= $('.password');
    $(".show-me").hover(function () {
        passEle.attr('type', 'text');
        $(this).css('color', '#000');
    }, function () {
        passEle.attr('type', 'password');
        $(this).css('color', '#b1b1b1');
    });

    // Warn The Admin Before He Delete A Member
    $('.confirm, .warnme').click(function () {

        return confirm("Are You Sure You Want To Delete This Element ?");

    });

    // On Click In h3 Toggle full-view Class
    $(".cat h3").click(function () {

        $(this).siblings('.full-view').fadeToggle();

    });

    // toggle Class
    let mydiv = $(".option div");

    mydiv.click(function () {

        $(this).toggleClass('active').siblings('div').removeClass('active');

    });


});











