$(function() {
    
    /** Gõ search tự động **/
    $('.search_auto').autoComplete({
        minChars: 2,
        source: function(term, response){
            $.getJSON('/module/common/search_auto.php', { q: term, type: $('.search_auto:focus').data('target') }, function(data){ response(data); });
        },
        renderItem: function (item, search){
            return '<div class="autocomplete-suggestion"><span class="img-responsive" data-id="' + item.id + '">' + item.name + '</span></div>';
        },
        onSelect: function(e, term, item){
            e.preventDefault();
            var _module = $('.search_auto:focus').data('target');
            var _type = $('.search_auto:focus').data('type');
            var _name = _module;
            if ($('.search_auto:focus').data('name')) _name = $('.search_auto:focus').data('name');
            //Nếu là search để add nhiều item kiểu như add các địa danh vào tour
            if (_type == 'multi') {
                $('#list_' + _module + ' ul').append('<li><i class="fa fa-times" aria-hidden="true" onclick="remove_item_search(this);"></i>' + item.find('span').text() + '<input type="hidden" name="' + _name + '[]" value="' + item.find('span').data('id') + '"></li>');
            } else {
                //Nếu search 1 item thì put luôn value vào input
                $(_module + '_id').val(item.find('span').data('id'));
                $('#search_' + _module).val(item.find('span').text());
            }
        }
    });
    
    /** --- Sort element --- **/
    $('.sortable').sortable();
    
});

//Xóa destination khỏi list chọn
function remove_item_search(obj) {
    $(obj).parent().remove();
}

/** Gõ search auto check multi **/
function search_multi(type) {
    var loading = $('#search_' + type).parent().find('.img_loading_form');
    var kw = $('#search_' + type).val();
    
    if (kw.length >= 2) {
        loading.show();
        $.get(
            '/module/common/search_multi.php?q=' + kw + '&type=' + type,
            function(data) {
                $('#list_' + type + ' ul').append(data);
            }
        )
        loading.hide();
    } else {
        alert('Từ khóa tìm kiếm phải có ít nhất 2 ký tự');
    }
};