$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if ($('.btn-show-pupup-login').is(':visible')) {
        setTimeout(function() {
            $('#modalLogin').modal('show');
        }, 1000);
    }
    
    $('#navbarDropdownProfile').dropdown();
    
    // use regex in selector
    $.expr[':'].regex = function (elem, index, match) {
        var matchParams = match[3].split(','),
            validLabels = /^(data|css):/,
            attr = {
                method: matchParams[0].match(validLabels) ?
                    matchParams[0].split(':')[0] : 'attr',
                property: matchParams.shift().replace(validLabels, '')
            },
            regexFlags = 'ig',
            regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g, ''), regexFlags);

        return regex.test(jQuery(elem)[attr.method](attr.property));
    }

    $(this).scrollTop(0);
    $('#loader').delay(1000).slideUp(1300);

    $(window).on('scroll', function() {
        if ($(window).scrollTop() > 80 ) {
            $('#header').addClass('header-shrink');
        } else {
            $('#header').removeClass('header-shrink');
        }
    });

    $('#change-avatar').click(function(e) {
        e.preventDefault();
        $("#upload-avatar:hidden").trigger('click');

        return false;
    });

    $('#upload-avatar').change(function(e) {
        $('.browse-your-computer').css('display', 'none');
        $('.name-picture-profile').text($('#upload-avatar').val().split('\\').pop());
        $('.submit-image-profile').show(300);
    });

    if ($('.datepicker').length) {
        var id = $('.calendar').attr('id');
        var locale = 'mm/dd/yyyy';

        if (id == 'vn') {
            locale = 'dd/mm/yyyy';
        }

        $('.datepicker').datepicker({
            format: locale,
        });
    }

    $('.alert-message').delay(3000).slideUp(300);

    $('.counter').each(function() {
        var $this = $(this);
        var countTo = $this.attr('data-count');

        $({countNum: $this.text()}).animate({
            countNum: countTo
        }, {
            duration: 3000,
            easing:'linear',
            step: function() {
                $this.text(Math.floor(this.countNum));
            },
            complete: function() {
                $this.text(this.countNum);
            }
        });
    });

    // languages
    $('.nav-link').click(function() {
        $('.select-options').hide();
        $('.select-styled').removeClass('active');
    });

    $('.select-styled').click(function(e) {
        e.stopPropagation();
        $('.dropdown-menu').each(function() {
            if ($(this).hasClass('show')) {
                $(this).removeClass('show');
            }
        })
        $('div.select-styled.active').not(this).each(function() {
            $(this).removeClass('active').next('ul.select-options').hide();
        });
        $(this).toggleClass('active').next('ul.select-options').toggle();
    });

    $('.select-options li').click(function(e) {
        e.stopPropagation();
        $('div.select-styled').html($(this).html()).removeClass('active');
        $('.select-options').hide();
        $('.select-styled').removeClass('active');
    });

    $(document).click(function() {
        $('.select-styled').removeClass('active');
        $('.select-options').hide();
    });

    $('.select-options li').click(function (e) {
        var url = $('.select-language').data('url');
        var locale = $(this).attr('rel');
        $.ajax({
            method: 'GET',
            url: url,
            dataType: 'json',
            data: {
                'locale': locale
            }
        })
        .done(function (response) {
            if (response.success) {
                window.location.href = response.urlBack;
            }
        });
    });

    // create survey complete page
    $(document).on('click', '.complete-content .copy-link-survey', function () {
        // copy link
        var $temp = $('<input>');
        $('body').append($temp);
        $temp.val($('.complete-content .link-survey').attr('href')).select();
        document.execCommand('copy');
        $temp.remove();

        // show message tooltip
        $(this).find('.tooltiptext').text(Lang.get('lang.copied_link'));

        return false;
    });

    $(document).on('mouseover', '.complete-content .copy-link-survey', function () {
        $(this).find('.tooltiptext').text(Lang.get('lang.copy_link'));
    });

    $(document).on('click', '.complete-content .copy-link-manage', function () {
        // copy link
        var $temp = $('<input>');
        $('body').append($temp);
        $temp.val($('.complete-content .link-manage').attr('href')).select();
        document.execCommand('copy');
        $temp.remove();
        
        // show message tooltip
        $(this).find('.tooltiptext').text(Lang.get('lang.copied_link'));

        return false;
    });

    $(document).on('mouseover', '.complete-content .copy-link-manage', function () {
        $(this).find('.tooltiptext').text(Lang.get('lang.copy_link'));
    });

    $(document).on('submit', '#form-feedback', function (e) {
        e.preventDefault();
        $.ajax({
            method: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json'
        })
        .done(function (data) {
            if (data.success) {
                $('#modal-feedback').modal('hide');
                alertSuccess({message: data.message}, function () {
                    location.reload();
                });
            } else {
                alertDanger({message: data.message});
            }
        })
        .fail(function (data) {
            var errors = data.responseJSON;
            $('.feedback-name-messages').text(errors.name);
            $('.feedback-email-messages').text(errors.email);
            $('.feedback-content-messages').text(errors.content);
        });
    });

    $('.modal-body').on('focus', 'input.validate, textarea.validate', function () {
        $(this).parent().find('.help-block').text('');
    });
});
