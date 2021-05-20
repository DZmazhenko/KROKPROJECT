$(function (){
    $('.test-data').find('div:first').show();

    $('.pagination a').on('click', function () {
        if ($(this).attr('class') == 'nav-active') return false;

        var link =$(this).attr('href'); // link на запрошеную вкладку
        var prevActive = $('.pagination > a.nav-active').attr('href'); // link на активную вкладку

        $('.pagination > a.nav-active').removeClass('nav-active'); // Удаление активного класа
        $(this).addClass('nav-active'); // Добавить клас активной вкладке

        //Скрыть и показать вопросы

        $(prevActive).fadeOut(100, function (){
            $(link).fadeIn(100);
        });

        //****

        return false;
    });

    $('#btn').click(function (){
        var test = $('#test-id').text();
        var res = {'test':test};

        $('.question').each(function (){
           var id = $(this).data('id');
           res[id] = $('input[name=question-' + id + ']:checked').val();
        });
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: res,
            success: function (html){
                $('.content_tests').html(html);
            },
            error: function (){
                alert('Error!');
            }
        });
    });
});