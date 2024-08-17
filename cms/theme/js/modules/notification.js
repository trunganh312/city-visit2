
(function($){
    var _No = {
        run:function(){
            this.eventListener();
        },
        eventListener:function(){
            $('.collapse-toggle-input').change(function() {
                var parent = $(this).parents('.collapse-wrap');
                var checked = $(this).is(":checked")
                if (checked) {
                    parent.find('.collapse').collapse("show")
                } else {
                    parent.find('.collapse').collapse("hide")
                }
            })
        }
    }
    _No.run();
})(jQuery)