
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(typeof __floating_labels !== 'function'){
    function __floating_labels(){
        if(jQuery('.__floating-labels > textarea').length){
            jQuery('.__floating-labels > textarea').each(function(){
                __floating_labels_textarea(this);
            });
        }
        if(jQuery('.__floating-labels > select').length){
            jQuery('.__floating-labels > select').each(function(){
                __floating_labels_select(this);
            });
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(typeof __floating_labels_select !== 'function'){
    function __floating_labels_select(select){
        if(jQuery(select).val() == ''){
            jQuery(select).removeClass('__placeholder-hidden');
        } else {
            jQuery(select).addClass('__placeholder-hidden');
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(typeof __floating_labels_textarea !== 'function'){
    function __floating_labels_textarea(textarea){
        jQuery(textarea).height(parseInt(jQuery(textarea).data('element'))).height(textarea.scrollHeight - parseInt(jQuery(textarea).data('padding')));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

jQuery(function($){
    if($('.__floating-labels > textarea').length){
        $('.__floating-labels > textarea').each(function(){
            $(this).data({
                'border': $(this).outerHeight() - $(this).innerHeight(),
                'element': $(this).height(),
                'padding': $(this).innerHeight() - $(this).height(),
            });
        });
    }
    __floating_labels();
    if($('.__floating-labels > textarea').length){
        $('.__floating-labels > textarea').on('input propertychange', function(){
            __floating_labels_textarea(this);
        });
    }
    if($('.__floating-labels > select').length){
        $('.__floating-labels > select').on('change', function(){
            __floating_labels_select(this);
        });
    }
    $(document).on(__page_visibility_event(), __floating_labels);
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
