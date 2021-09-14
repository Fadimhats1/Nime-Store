$(document).ready(function(){
    $('select').select2({
        placeholder: 'Sub-Category',
        tags: 'true',
        allowClear: 'true',
        tokenSeparators: [',','/'], 
    });
});