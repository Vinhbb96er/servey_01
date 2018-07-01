$(document).ready(function () {
    var url = $('.url_onwer').val();
    $('.list-survey-ajax').click(function (e) {
        e.preventDefault();
        url = $(this).attr('data-url');
        $('.list-survey-ajax').removeClass('active');
        $(this).addClass('active');
        listSurvey(url, 'list-survey-ajax');
    });

    reloadPagination();

    $(document).on('click', '.list-survey-owner-pagi li a', function (e) {
        e.preventDefault();
        var page = $(this).text().trim();
        var page_current = parseInt($('.page_current').val());

        if ($(this).parent('li').is(':last-child')) {
            page = page_current + 1;
        }

        if ($(this).parent('li').is(':first-child')) {
            page = page_current - 1;
        }

        urlPaginate = url + '?page=' + page;
        listSurvey(urlPaginate);
    });

    $(document).on('click', '.btn-search-survey', function (e) {
        listSurvey(url);
    });

    $(document).on('submit', '.form-search-list-survey', function (e) {
        e.preventDefault();
        listSurvey(url);
    });

    $(document).on('click', '.delete-feedback-btn', function (e) {
        e.preventDefault();
        var element = $(this);

        confirmWarning({message: Lang.get('lang.confirm_delete_feedback')}, function () {
            element.next('.delete-feedback-form').submit();
        })
    });

    $(document).on('click', '.feedback-detail-btn', function (e) {
        e.preventDefault();
        var modal = $('#modal-feedback-detail');

        modal.find('input.feedback-detail-name').val($(this).closest('tr').find('.feedback-name').attr('val'));
        modal.find('input.feedback-detail-email').val($(this).closest('tr').find('.feedback-email').attr('val'));
        modal.find('textarea.feedback-detail-content').val($(this).closest('tr').find('.feedback-content').attr('val'));
        modal.modal('show');
    });
});

function listSurvey(url, flag = 'form-search') {
    $.ajax({
        method: 'GET',
        url: url,
        data: $('.form-search-list-survey').serialize(),
        dataType: 'json',
        success: function (data) {
            $('#show-list-surveys').html(data.html).promise().done(function () {
                if (flag == 'list-survey-ajax') {
                    $('.element-search-survey').val('');
                }

                reloadPagination();
            });
        }
    });
}

function reloadPagination() {
    $('#show-list-surveys .pagination').addClass('list-survey-owner-pagi');
    $('#show-list-surveys .pagination li').addClass('page-item').find('span').addClass('page-link');
    $('#show-list-surveys .pagination li a').addClass('page-link').attr('href', 'javascript:void(0)');
}
