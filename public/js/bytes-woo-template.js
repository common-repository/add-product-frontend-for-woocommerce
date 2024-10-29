var allTags = [];
jQuery(document).ready(function($){
    // select product options
    $('.bt-tab-option a').on('click', function(t) {
        t.preventDefault();
        $('.bt-tab-option.active.active').removeClass('active');
        $panel = $(this).attr('href'),
        $('.bt-option-panel').addClass('bt-hide-section');
        $($panel).removeClass('bt-hide-section');
        $(this).parent().addClass('active');
    });

    // show Manage Stock
    $('#bt-manage-stock').click(function() {
        if( $(this).is(':checked')) {
            $(".bt-manage-stock-section").show();
            $('.bt-stock-status-option').hide();
        } else {
            $(".bt-manage-stock-section").hide();
            $('.bt-stock-status-option').show();
        }
    });

    // get product tags on edit product form
    function product_manage_stock(){
      if($('.product_manage_stock').length > 0) {
        if( $('#bt-manage-stock').is(':checked')) {
            $(".bt-manage-stock-section").show();
            $('.bt-stock-status-option').hide();
        }
        else {
            $(".bt-manage-stock-section").hide();
            $('.bt-stock-status-option').show();
        }
      }
    }
    product_manage_stock();

    // selectize multi options
    $('.bt-selectize-multiple').selectize({
        delimiter: ',',
        persist: false,
        create: function(input) {
            return {
                value: input,
                text: input
            }
        }
    });

    // adding tags animations
    $('#bt-add-tag').on('click', function(t) {
        let inputValues = $('#bt-input-values').val();
        if(inputValues == '') {
            return;
        }
        while(inputValues.endsWith(',')) {
            inputValues = inputValues.slice(0,-1);
            if(inputValues == '') {
                return;
            }
        }
        let inputArray = inputValues.split(',');
        $('#bt-input-values').val('');
        inputArray.forEach(element => {
            if(!(allTags.includes(element))){
                $('.bt-tagchecklist').append('<li><button type="button" onclick="btRemoveTag(this, \''+ element +'\');" class="bt-remove-tag-button"><span class="bt-remove-tag-icon" aria-hidden="true"></span></button>'+element+'</li>');
                allTags.push(element);
            }
        });
        $('#bt-input-values-hidden').val(allTags);
    });

    // WP media modal product image
    $(".bt-upload-image-button").on("click",  function (e) {
        e.preventDefault();
        var $link = $(this);
        // Create the media frame.
        var file_frame = wp.media.frames.file_frame = wp.media({
           title: 'Select or upload image',
           library: {
              type: 'image' // specific mime
           },
           button: {
              text: 'Select'
           },
           multiple: false  // Set to true to allow multiple files to be selected
        });
        // When an image is selected, run a callback.
        file_frame.on('select', function () {
           let attachment = file_frame.state().get('selection').first().toJSON();
           $link.siblings('input').val(attachment.id).change();
           $link.siblings('img').prop('src', attachment.url);
           $('.bt-upload-image-button').hide();
           $('.bt-remove-image-button').show();
           $('.bt-image-product-descr').show();
           $('.bt-product-image').show();
        });
        file_frame.open();
     });

    //  Emulating click on '.bt-upload-image-button', on click over image
     $('.bt-product-image').on("click",  function (e) {
        $(".bt-upload-image-button").click();
     });

    // Remove image selected
     $('.bt-remove-image-button').on("click",  function (e) {
        e.preventDefault();
        var $link = $(this);
        $link.siblings('input').val('').change();
        $link.siblings('img').prop('src', '#');
        $('.bt-upload-image-button').show();
        $('.bt-remove-image-button').hide();
        $('.bt-image-product-descr').hide();
        $('.bt-product-image').hide();
    });

    // WP media modal product gallery
    $(".bt-upload-gallery-button").on("click",  function (e) {
        e.preventDefault();
        var $link = $(this);
        // Create the media frame.
        let file_frame = wp.media.frames.file_frame = wp.media({
           title: 'Select or upload image',
           library: {
              type: 'image' // specific mime
           },
           button: {
              text: 'Select'
           },
           multiple: true  // Set to true to allow multiple files to be selected
        });
        // When an image is selected, run a callback.
        file_frame.on('select', function () {
            var gallery_ids = [];
            var attachments = file_frame.state().get('selection').toJSON();
            $('.bt-gallery-imgs').html('');
            attachments.forEach(element => {
                gallery_ids.push(element.id);
                $('.bt-gallery-imgs').append('<li class="bt-image-gallery" data-attachment_id="'+element.id+'" style="background-image: url(\''+element.url+'\'); background-repeat: no-repeat; background-size: cover;"><a onclick="btRemoveGalleryImg(this, '+element.id+');" class="bt-remove-img bt-hide-section"></a></li>');
            });
            $link.siblings('input').val(gallery_ids).change();
        });
        file_frame.open();
    });

    // get product tags on edit product form
    function edit_product_tag(){
      if($('.edit-product-tag').length > 0) {
        let inputValues = $('#bt-input-values').val();
        if(inputValues == '') {
            return;
        }
        while(inputValues.endsWith(',')) {
            inputValues = inputValues.slice(0,-1);
            if(inputValues == '') {
                return;
            }
        }
        let inputArray = inputValues.split(',');
        $('#bt-input-values').val('');
        inputArray.forEach(element => {
            $('.bt-tagchecklist').append('<li><button type="button" onclick="btRemoveTag(this, \''+ element +'\');" class="bt-remove-tag-button"><span class="bt-remove-tag-icon" aria-hidden="true"></span></button>'+element+'</li>');
            allTags.push(element);
        });
        $('#bt-input-values-hidden').val(allTags);
      }
    }
    edit_product_tag();

    // edit product featured image
    function edit_product_featured_image(){
      if($('.edit-product-featured-image').length > 0) {
        let bt_product_image_hidden_val = $('input[name=bt_product_image]').val();
        if(bt_product_image_hidden_val !== ''){
          $('.bt-upload-image-button').hide();
          $('.bt-remove-image-button').show();
          $('.bt-image-product-descr').show();
          $('.bt-product-image').show();
        }
      } 
    }
    edit_product_featured_image();

    // Product type specific options.
    let select_product_type = $( 'select#bt-product-type' ).val();
    if(select_product_type == 'simple'){
        $('.show_simple_product').show();
        $('#virtual_downloadable_product').show();
        $('.show_if_grouped').hide();
        $('.show_if_simple').show();
        $('.hide_if_external').show();
        $( '.show_if_variable' ).hide();
        $('.show_if_simple').show();
        $('.hide_if_grouped').show();
    }
    else if (select_product_type == 'grouped') {
        $( 'input#bt-downloadable' ).prop( 'checked', false );
        $( 'input#bt-virtual' ).prop( 'checked', false );
        $( '#virtual_downloadable_product' ).hide();
        $('.show_if_downloadable').hide();
        $( '.hide_if_grouped' ).hide();
        $( '.show_if_simple' ).hide();
        $( '.show_if_variable' ).hide();
        $( '.show_if_grouped' ).show();
        $( 'ul.bt-wc-tabs li:visible' ).eq( 0 ).find( 'a' ).trigger( 'click' ); // Change to first tab on change product type
    } 
    else if(select_product_type == 'external'){
        $( 'input#bt-downloadable' ).prop( 'checked', false );
        $( 'input#bt-virtual' ).prop( 'checked', false );
        $('.hide_if_grouped').show();
        $( '.hide_if_external' ).hide();
        $( '#virtual_downloadable_product' ).hide();
        $('.show_if_downloadable').hide();
        $('.show_if_simple').hide();
        $( '.show_if_grouped' ).hide();
        $( '.show_if_variable' ).hide();
        $('.show_if_external').show();
    }
    else if(select_product_type == 'variable'){
        $( 'input#bt-downloadable' ).prop( 'checked', false );
        $( 'input#bt-virtual' ).prop( 'checked', false );
        $( '#virtual_downloadable_product' ).hide();
        $('.show_if_downloadable').hide();
        $('.show_if_external').hide();
        $('.show_if_grouped').hide();
        $('.show_if_simple').hide();
        $('.hide_if_external').show();
        $('.hide_if_grouped').show();
        $( '.show_if_variable' ).show();
    }          

    // on change product type
    $('select#bt-product-type').on('change', function(){
        // Get value.
        var select_val = $(this).val();
        if ( 'variable' === select_val ) {
            $( 'input#bt-downloadable' ).prop( 'checked', false );
            $( 'input#bt-virtual' ).prop( 'checked', false );
            $( '#virtual_downloadable_product' ).hide();
            $('.show_if_downloadable').hide();
            $('.show_if_external').hide();
            $('.show_if_grouped').hide();
            $('.show_if_simple').hide();
            $('.hide_if_external').show();
            $('.hide_if_grouped').show();
            $( '.show_if_variable' ).show();
        } 
        else if ( 'grouped' === select_val ) {
            $( 'input#bt-downloadable' ).prop( 'checked', false );
            $( 'input#bt-virtual' ).prop( 'checked', false );
            $( '#virtual_downloadable_product' ).hide();
            $('.show_if_downloadable').hide();
            $( '.hide_if_grouped' ).hide();
            $( '.show_if_simple' ).hide();
            $( '.show_if_variable' ).hide();
            $( '.show_if_grouped' ).show();
        } 
        else if ( 'external' === select_val ) {
            $( 'input#bt-downloadable' ).prop( 'checked', false );
            $( 'input#bt-virtual' ).prop( 'checked', false );
            $('.hide_if_grouped').show();
            $( '.hide_if_external' ).hide();
            $( '#virtual_downloadable_product' ).hide();
            $('.show_if_downloadable').hide();
            $('.show_if_simple').hide();
            $( '.show_if_grouped' ).hide();
            $( '.show_if_variable' ).hide();
            $('.show_if_external').show();
        }
        else if('simple' == select_val){
            $('.show_simple_product').show();
            $('#virtual_downloadable_product').show();
            $('.show_if_external').hide();
            $('.show_if_grouped').hide();
            $( '.show_if_variable' ).hide();
            $('.show_if_simple').show();
            $('.hide_if_external').show();
            $('.hide_if_grouped').show();
        }
        $( 'ul.bt-wc-tabs li:visible' ).eq( 0 ).find( 'a' ).trigger( 'click' ); // Change to first tab on change product type
    });

    // Check virtual product is enable or not
    if($("#bt-virtual").is(':checked')){
        $('.hide_if_virtual').hide();
    }
    $("#bt-virtual").on("click", function (e){
        if($(this).is(':checked')){
            $('.hide_if_virtual').hide(); 
            // If user enables virtual while on shipping tab, switch to general tab.
            if ( $( '.bt-shipping-options' ).hasClass( 'active' ) ) {
                $( '.bt-general-options > a' ).trigger( 'click' );
            }
        }
        else{
            $('.hide_if_virtual').show();
        }
    });

    // Check downloadable product is enable or not
    if($('#bt-downloadable').is(':checked')){
        $('.show_if_downloadable').show();
    }
    else{
        $('.show_if_downloadable').hide();
    }
    $("#bt-downloadable").on("click", function (e){
        if($(this).is(':checked')){
            $('.show_if_downloadable').show();
        }
        else{
            $('.show_if_downloadable').hide();
        }
    });

    // File inputs.
    $( '#downloadable_product' ).on( 'click','.downloadable_files a.insert', function() {
        $( this ).closest( '.downloadable_files' ).find( 'tbody' ).append( $( this ).data( 'row' ) );
        return false;
    });
    $( '#downloadable_product' ).on( 'click','.downloadable_files a.delete',function() {
        $( this ).closest( 'tr' ).remove();
        return false;
    });

    // Download ordering.
    $( '.downloadable_files tbody' ).sortable({
      items: 'tr',
      cursor: 'move',
      axis: 'y',
      handle: 'td.sort',
      scrollSensitivity: 40,
      forcePlaceholderSize: true,
      helper: 'clone',
      opacity: 0.65
    });


});

// Removing tags animations
function btRemoveTag(elem, name) {
    let oldVal = jQuery('#bt-input-values-hidden').val();
    let newVal = oldVal.replace(name, "");
    newVal = newVal.replace(',,', ",");
    jQuery('#bt-input-values-hidden').val(newVal);
    allTags = allTags.filter(function(elem){
        return elem != name; 
    });
    elem.parentElement.remove();
}

// Remove image from gallery
function btRemoveGalleryImg(elem, id) {
    let oldVal = jQuery('#bt-gallery-ids').val().split(',');
    let newVal = jQuery.grep(oldVal, function(value) {
        return value != id;
    });
    jQuery('#bt-gallery-ids').val(newVal)
    elem.parentElement.remove();
}

// Uploading files.
var downloadable_file_frame;
var file_path_field;

jQuery( document).on( 'click', '.upload_file_button', function( event ) {
  var $el = jQuery( this );

  file_path_field = $el.closest( 'tr' ).find( 'td.file_url input' );

  event.preventDefault();

  // If the media frame already exists, reopen it.
  if ( downloadable_file_frame ) {
    downloadable_file_frame.open();
    return;
  }

  var downloadable_file_states = [
    // Main states.
    new wp.media.controller.Library({
      library:   wp.media.query(),
      multiple:  true,
      title:     $el.data('choose'),
      priority:  20,
      filterable: 'uploaded'
    })
  ];

  // Create the media frame.
  downloadable_file_frame = wp.media.frames.downloadable_file = wp.media({
    // Set the title of the modal.
    title: $el.data('choose'),
    library: {
      type: ''
    },
    button: {
      text: $el.data('update')
    },
    multiple: true,
    states: downloadable_file_states
  });

  // When an image is selected, run a callback.
  downloadable_file_frame.on( 'select', function() {
    var file_path = '';
    var selection = downloadable_file_frame.state().get( 'selection' );

    selection.map( function( attachment ) {
      attachment = attachment.toJSON();
      if ( attachment.url ) {
        file_path = attachment.url;
      }
    });

    file_path_field.val( file_path ).trigger( 'change' );
  });

  // Set post to 0 and set our custom type.
  downloadable_file_frame.on( 'ready', function() {
    downloadable_file_frame.uploader.options.uploader.params = {
      type: 'downloadable_product'
    };
  });

  // Finally, open the modal.
  downloadable_file_frame.open();
});