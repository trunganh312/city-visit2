var initEditor = function(element_id) {
    CKEDITOR.config.language = 'vi';
    CKEDITOR.config.enterMode         = CKEDITOR.ENTER_DIV;
    CKEDITOR.config.shiftEnterMode    = CKEDITOR.ENTER_BR;
    let height = $(`#${element_id}`).data('height');
    let width = $(`#${element_id}`).data('width');

    CKFinder.setupCKEditor(CKEDITOR.replace(element_id, {
        filebrowserWindowWidth: '1000',
        filebrowserWindowHeight: '700',
        height: height ? height : 300,
        width: width ? width : 'auto'
    }));
};

$(document).ready(()=> {
    $("textarea.text__ckeditor").each((index, item)=> {
        initEditor($(item).attr('id'));
    });
});