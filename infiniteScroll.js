$(document).ready(function(){
    let currPage = 1, times = 0, value = '#section-1';

    showData(currPage, value, times, totalPage);

    $(window).on('scroll', function(){
        if($(window).scrollTop() + $(window).height() > $(document).height() - 100){
            if(times < 4){
                ++times;
                showData(++currPage, value, times, totalPage);
            }
        }
    })
    $('body').on('click', '#loadMore', function(){
        showData(++currPage, value, times, totalPage);
    })

})

function showData(currPage, value, times, totalPage){
    $.ajax({
        type: 'POST',
        url: 'function/indexAjax/infiniteScroll.php',
        data:{
            currPage: currPage
        }, beforeSend: function(){
            $('#loadMore').removeClass('hide');
        }, success: function(data){
            if(times < 4 || currPage == totalPage){
                $('#loadMore').addClass('hide');
            }
            $(value).append(data);
        }
    })
}
