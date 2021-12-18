$(document).ready(function(){
    let currPage = 1, times = 1, value = '#section-1';
    if(totalPage){
        showData(currPage, value, times, totalPage);
    
        $(window).on('scroll', function(){
            if($(window).scrollTop() + $(window).height() > $(document).height() - 100){
                if(currPage <= 4 && currPage != totalPage){
                    currPage = currPage + 1 <= totalPage ? currPage + 1 : currPage;
                    showData(currPage, value, totalPage);
                }
            }
        })

        $('body').on('click', '#loadMore', function(){
            currPage = currPage + 1 <= totalPage ? currPage + 1 : currPage;
            showData(currPage, value, totalPage);
        })

        function showData(currPage, value, totalPage){
            if(totalPage){
                $.ajax({
                    type: 'POST',
                    url: 'function/indexAjax/infiniteScroll.php',
                    data:{
                        currPage: currPage  
                    }, success: function(data){
                        if(currPage == totalPage){
                            $('#loadMore').addClass('hide');
                        }else if(currPage > 4){
                            $('#loadMore').removeClass('hide');
                        }
                        $(value).append(data);
                    }
                })
            }
        }
    }
})


