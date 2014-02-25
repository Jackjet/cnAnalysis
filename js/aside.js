$(document).ready(function(){
    $("dd:not(:first)").hide();
    $("dt a").click(function(){
        $("dd:visible").slideUp("slow");
        $(this).parent().next().slideDown("slow");
        return false;
    });

    var ul_li = $('.aside dl ul a'),
        layout = $('.layout');
    $('.layout:not(:first-child)').hide();
    ul_li.click(function() {
        layout.hide();
        var li_index = ul_li.index(this) + 1;
        $('#layout' + li_index).show();
        return false;
    });

    $('#layout1 tr:odd').addClass('odd');
    $('#layout1 tr:even').addClass('even');
    $('#layout2 tr:odd').addClass('odd');
    $('#layout2 tr:even').addClass('even');
});