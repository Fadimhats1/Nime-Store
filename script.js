$('.next').click(()=>{
    $('.title').css('opacity', '0');
    $('.back').css('opacity', '1');
    $('.uName').addClass('right').removeClass('now');
    $('.pass').addClass('now').removeClass('left');
    $('.next').addClass('hide').removeClass('show');
    $('.submit').addClass('show').removeClass('hide');
})

$('.back').click(()=>{
    $('.back').css('opacity', '0');
    $('.title').css('opacity', '1');
    $('.uName').addClass('now').removeClass('right');
    $('.pass').addClass('left').removeClass('now');
    $('.next').addClass('show').removeClass('hide');
    $('.submit').addClass('hide').removeClass('show');
})

$('.btn-close').click(()=>{
    $('.back').css('opacity', '0');
    $('.title').css('opacity', '1');
    $('.uName').addClass('now').removeClass('right');
    $('.pass').addClass('left').removeClass('now');
    $('.next').addClass('show').removeClass('hide');
    $('.submit').addClass('hide').removeClass('show');
})

$('#eyes').click(() =>{
    if($('#pass').attr('type') === 'password'){
        $('#pass').attr('type', 'text');
        $('#eyes').addClass('fa-eye-slash');
        $('#eyes').removeClass('fa-eye');
    }else{
        $('#pass').attr('type', 'password');
        $('#eyes').addClass('fa-eye');
        $('#eyes').removeClass('fa-eye-slash');
    }
})

function paginator(items, current_page = 1, per_page_items = 10) {
    let page = current_page,
    per_page = per_page_items,
    offset = (page - 1) * per_page,

    paginatedItems = items.slice(offset).slice(0, per_page_items),
    total_pages = Math.ceil(items.length / per_page);

    return { // return object
        page: page,
        per_page: per_page,
        pre_page: page - 1 ? page - 1 : null,
        next_page: (total_pages > page) ? page + 1 : null,
        total: items.length,
        total_pages: total_pages,
        data: paginatedItems
    };
}

$('.btn-plus, .btn-minus').on('click', function(e) {
    const isNegative = $(e.target).closest('.btn-minus').is('.btn-minus');
    const input = $(e.target).closest('.input-group').find('input');
    if (input.is('input')) {
        input[0][isNegative ? 'stepDown' : 'stepUp']()
    }
})