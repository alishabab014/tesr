(function ($) {

  'use strict';



  function imageUploader() {

    $(document).off('click', '.rt-add-image');

    $(document).on('click', '.rt-add-image' , addImage);

    $(document).on('click', '.rt-remove-image', removeImage);

  }



  function addImage(event) {

    event.preventDefault();

    event.stopPropagation();

    var el = this;

    var file_frame = 0;

    var product_variation_id = $(this).data('product_variation_id');

    var loop = $(this).data('product_variation_loop');



    if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {

      if (file_frame) {

        file_frame.open();

        return;

      }



      file_frame = wp.media.frames.select_image = wp.media({

        title: woocommerce_admin_meta_boxes_variations.choose_image,

        button: {

          text: woocommerce_admin_meta_boxes_variations.add_image

        },

        library: {

          type: ['image']

        },

        multiple: true

      });

      file_frame.on('select', function () {

        var images = file_frame.state().get('selection').toJSON();

        var html = images.map(function (image) {

          if (image.type === 'image') {

            var id = image.id,

                image_sizes = image.sizes;

            image_sizes = image_sizes === undefined ? {} : image_sizes;

            var thumbnail = image_sizes.thumbnail,

                full = image_sizes.full;

            var url = thumbnail ? thumbnail.url : full.url;

            var template = wp.template('rt-image');

            return template({

              id: id,

              url: url,

              product_variation_id: product_variation_id,

              loop: loop,

            });

          }

        }).join('');

        $(el).parent().prev().find('.rt-images').append(html);

        sortableGallery();

        variationChanged(el);

      });

      file_frame.open();

    }

  }



  function removeImage(event){

    event.preventDefault();

    event.stopPropagation();



    var el = this;

    variationChanged(el);

    $(el).parent().remove();

  }



  function sortableGallery() {

    var $gallery = $('.rt-images');

    $gallery.each(function(){

      var $this = $(this);

      $this.sortable({

        items: 'li.image',

        cursor: 'move',

        scrollSensitivity: 40,

        forcePlaceholderSize: true,

        forceHelperSize: false,

        helper: 'clone',

        opacity: 0.65,

        placeholder: 'wc-metabox-sortable-placeholder',

        start: function (event, ui) {

          ui.item.css('background-color', '#f6f6f6');

        },

        stop: function (event, ui) {

          ui.item.removeAttr('style');

        },

        update: function () {

          var attachment_ids = '';



          $gallery.find('li.image').each(function () {

            var attachment_id = $(this).attr('data-attachment_id');

            attachment_ids = attachment_ids + attachment_id + ',';

          });



          $this.parents('.woocommerce_variation').eq(0).addClass('variation-needs-update');

          $('#variable_product_options').find('input').eq(0).change();

        }

      });

    })

    

  }



  function variationChanged(element) {

    $(element).closest('.woocommerce_variation').addClass('variation-needs-update');

    $('button.cancel-variation-changes, button.save-variation-changes').removeAttr('disabled');

    $('#variable_product_options').trigger('woocommerce_variations_input_changed');

  }



  $('#woocommerce-product-data').on('woocommerce_variations_loaded', function () {

    imageUploader();

    sortableGallery();

  });

  $('#variable_product_options').on('woocommerce_variations_added', function () {

    imageUploader();

    sortableGallery();

  });



})(jQuery)