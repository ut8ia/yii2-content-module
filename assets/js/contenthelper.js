 // handler for "more" click

    $('.more-click').click(function () {
        var destinationClass = $(this).data('destination');
        var sourceClass = $(this).data('source');
        var duration = $(this).data('duration');
        var selfhide = $(this).data('selfhide');

        if (selfhide) {
            $(this).hide();
        }

        var content = $('.' + sourceClass).html();
        $('.' + destinationClass).html(content);
        $('.' + destinationClass).show(1000).fadeIn(2000);

    });
// handler for "less" click

    $('body').on('click','.less-click',function(){
        var destinationClass = $(this).data('destination');
        var sourceClass = $(this).data('source');
        var duration = $(this).data('duration');
        var selfhide = $(this).data('selfhide');
        var showbuttonid = $(this).data('showbuttonid');
        var content ='';

        if (selfhide) {
            $(this).hide();
        }

        if (destinationClass) {
            content = $('.' + sourceClass).html();
        }

        $('.' + destinationClass).html(content);
        $('.' + destinationClass).hide(700).fadeOut(500);

        if(showbuttonid){
            $('#'+showbuttonid).show().fadeIn(300);
        }
    });


