jQuery(document).ready( function($){
    // Add rows.
    $( 'button.add_attribute' ).on( 'click', function() {
        var size         = $( '.product_attributes .woocommerce_attribute' ).length;
        var attribute    = $( 'select.attribute_taxonomy' ).val();
        var $wrapper     = $( this ).closest( '#product_attributes' );
        var $attributes  = $wrapper.find( '.product_attributes' );
        var product_type = $( 'select#bt-product-type' ).val();
        var data         = {
            action: 'bytes_add_product_attributes',
            taxonomy: attribute,
            i: size,
        };

        $wrapper.block({
            message: null,
            overlayCSS: {
              background: '#fff',
              opacity: 0.6
            }
        });

        $.post(productattributes.ajax_url, data, function( response ) {
            $attributes.append( response );

            if ( 'variable' !== product_type ) {
              $attributes.find( '.enable_variation' ).hide();
            }

            $( document.body ).trigger( 'wc-enhanced-select-init' );

            attribute_row_indexes();

            $attributes.find( '.woocommerce_attribute' ).last().find( 'h3' ).trigger( 'click' );

            $wrapper.unblock();

            $( document.body ).trigger( 'woocommerce_added_attribute' );

            setTimeout(function(){
              $( '.expand_all' ).closest( '.wc-metaboxes-wrapper' ).find( '.wc-metabox > .wc-metabox-content' ).show();
            }, 500);

        });

        if ( attribute ) {
            $( 'select.attribute_taxonomy' ).find( 'option[value="' + attribute + '"]' ).attr( 'disabled','disabled' );
            $( 'select.attribute_taxonomy' ).val( '' );
        }

        return false;
    });

    // Initial order.
    var woocommerce_attribute_items = $( '.product_attributes' ).find( '.woocommerce_attribute' ).get();

    woocommerce_attribute_items.sort( function( a, b ) {
       var compA = parseInt( $( a ).attr( 'rel' ), 10 );
       var compB = parseInt( $( b ).attr( 'rel' ), 10 );
       return ( compA < compB ) ? -1 : ( compA > compB ) ? 1 : 0;
    });
    $( woocommerce_attribute_items ).each( function( index, el ) {
      $( '.product_attributes' ).append( el );
    });

    function attribute_row_indexes() {
        $( '.product_attributes .woocommerce_attribute' ).each( function( index, el ) {
            $( '.attribute_position', el ).val( parseInt( $( el ).index( '.product_attributes .woocommerce_attribute' ), 10 ) );
        });
    }

    $( '.product_attributes .woocommerce_attribute' ).each( function( index, el ) {
        if ( $( el ).css( 'display' ) !== 'none' && $( el ).is( '.taxonomy' ) ) {
            $( 'select.attribute_taxonomy' ).find( 'option[value="' + $( el ).data( 'taxonomy' ) + '"]' ).attr( 'disabled', 'disabled' );
        }
    });

    $( '.product_attributes' ).on( 'blur', 'input.attribute_name', function() {
        $( this ).closest( '.woocommerce_attribute' ).find( 'strong.attribute_name' ).text( $( this ).val() );
    });

    $( '.product_attributes' ).on( 'click', 'button.select_all_attributes', function() {
        $( this ).closest( 'td' ).find( 'select option' ).prop( 'selected', 'selected' );
        $( this ).closest( 'td' ).find( 'select' ).trigger( 'change' );
        return false;
    });

    $( '.product_attributes' ).on( 'click', 'button.select_no_attributes', function() {
        $( this ).closest( 'td' ).find( 'select option' ).prop( 'selected', false );
        $( this ).closest( 'td' ).find( 'select' ).trigger( 'change' );
        return false;
    });

    $( '.product_attributes' ).on( 'click', '.remove_row', function() {
        if ( window.confirm('Remove this attribute?') ) {
            var $parent = $( this ).parent().parent();
            if ( $parent.is( '.taxonomy' ) ) {
              $parent.find( 'select, input[type=text]' ).val( '' );
              $parent.hide();
              $( 'select.attribute_taxonomy' ).find( 'option[value="' + $parent.data( 'taxonomy' ) + '"]' ).prop( 'disabled', false );
            } else {
              $parent.find( 'select, input[type=text]' ).val( '' );
              $parent.hide();
              attribute_row_indexes();
            }
        }
        return false;
    });

    // Attribute ordering.
    $( '.product_attributes' ).sortable({
      items: '.woocommerce_attribute',
      cursor: 'move',
      axis: 'y',
      handle: 'h3',
      scrollSensitivity: 40,
      forcePlaceholderSize: true,
      helper: 'clone',
      opacity: 0.65,
      placeholder: 'wc-metabox-sortable-placeholder',
      start: function( event, ui ) {
        ui.item.css( 'background-color', '#f6f6f6' );
      },
      stop: function( event, ui ) {
        ui.item.removeAttr( 'style' );
        attribute_row_indexes();
      }
    });

    // Add a new attribute (via ajax).
    $( '.product_attributes' ).on( 'click', 'button.add_new_attribute', function() {
        $( '.product_attributes' ).block({
            message: null,
            overlayCSS: {
              background: '#fff',
              opacity: 0.6
            }
        });

        var $wrapper           = $( this ).closest( '.woocommerce_attribute' );
        var attribute          = $wrapper.data( 'taxonomy' );
        var new_attribute_name = window.prompt( 'Enter a name for the new attribute term:' );

        if ( new_attribute_name ) {
        var data = {
            action:   'bytes_product_add_new_attribute',
            taxonomy: attribute,
            term: new_attribute_name
        };

        $.post( productattributes.ajax_url, data, function( response ) {

          if ( response.error ) {
            // Error.
            window.alert( response.error );
          } else if ( response.slug ) {
            // Success.
            $wrapper.find( 'select.attribute_values' )
              .append( '<option value="' + response.term_id + '" selected="selected">' + response.name + '</option>' );
            $wrapper.find( 'select.attribute_values' ).trigger( 'change' );
          }

          $( '.product_attributes' ).unblock();
        });

        } else {
        $( '.product_attributes' ).unblock();
        }

        return false;
    });

    // Save attributes
    $('.save_attributes').on('click', function(){
        $('.product_attributes').block({
            message: null,
            overlayCSS: {
              background: '#fff',
              opacity: 0.6
            }
        });
        var original_data = $( '.product_attributes' ).find( 'input, select, textarea' );
        var data = {
            post_id     : productattributes.post_id,
            product_type: $( '#bt-product-type' ).val(),
            data        : original_data.serialize(),
            action      : 'bytes_product_save_attributes'
        };

        $.post(productattributes.ajax_url, data, function( response ) {
            if ( response.error ) {
              // Error.
              window.alert( response.error );
            } else if ( response.data ) {
              // Success.
              $( '.product_attributes' ).html( response.data.html );
              $( '.product_attributes' ).unblock();

              // Make sure the dropdown is not disabled for empty value attributes.
              $( 'select.attribute_taxonomy' ).find( 'option' ).prop( 'disabled', false );

              $( '.product_attributes .woocommerce_attribute' ).each( function( index, el ) {
                if ( $( el ).css( 'display' ) !== 'none' && $( el ).is( '.taxonomy' ) ) {
                  $( 'select.attribute_taxonomy' )
                    .find( 'option[value="' + $( el ).data( 'taxonomy' ) + '"]' )
                    .prop( 'disabled', true );
                }
              });
              
              // Hide the 'Used for variations' checkbox if not viewing a variable product
              let product_type = $( 'select#bt-product-type' ).val();
              if ( 'variable' !== product_type ) {
                $( '.enable_variation.show_if_variable' ).hide();
              }

              $( document.body ).trigger( 'wc-enhanced-select-init' );
              $( '.expand_all' ).closest( '.wc-metaboxes-wrapper' ).find( '.wc-metabox > .wc-metabox-content' ).hide();
            }
        });
    });
  });