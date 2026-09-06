(function($) {

	$(document).on('click', '.rdt-start-import', function (e) {
        e.preventDefault();
        
        var $this = $(this);

        var popup = $('.rdt-popup-import');
        popup.html('');
        popup.addClass('loading');
        $('.rdt-popup-overlay').addClass('active');
        var data = {
            action: 'rdt_ajax_import_popup',
            demo : $this.data('demo'),
            name : $this.data('demo-name')
        };

        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: data,
            success: function (data) {
                popup.append(data);
                popup.addClass('active').removeClass('loading');
            },
            complete: function () {
            }
        });
    });

    $(document).on('click', '.rdt-import', function (e) {
        e.preventDefault();
        callbacks['install_content']();
        return false;
    });
    $(document).on('click', '.rdt-btn-childtheme', function (e) {
        e.preventDefault();
        callbacks['install_childtheme']();
        return false;
    });
    $(document).on('click', '.rdt-btn-childtheme-next', function (e) {
        e.preventDefault();
        $('.rdt-popup-import__childtheme').hide();
        $('.rdt-popup-import__plugins').show();
    });
    $(document).on('click', '.rdt-btn-skip', function (e) {
        e.preventDefault();
        $('.rdt-popup-import__childtheme').hide();
        $('.rdt-popup-import__plugins').show();
    });
    $(document).on('click', '.rdt-btn-plugins', function (e) {
        e.preventDefault();
        callbacks['install_plugins']();
        return false;
    });
    $(document).on('click', '.rdt-btn-next', function (e) {
        e.preventDefault();
        $('.rdt-popup-import__plugins').hide();
        $('.rdt-popup-import__content').show();
    });
    $(document).on('click', '.rdt-btn-close', function (e) {
        if (!$('.rdt-popup-import').hasClass('completed') && !confirm('Your import process will be lost if you close the import process.')){
            e.preventDefault();
            return;
        }
        $('.rdt-popup-import').removeClass('active');
        $('.rdt-popup-overlay').removeClass('active');
        $('.rdt-popup-import').html('');
    });
    var callbacks = {
        install_childtheme: function(){
            var chiltheme = new ChildTheme();
            chiltheme.init();
        },
        install_plugins: function(){
            var plugins = new PluginManager();
            plugins.init();
        },
        install_content: function(){
            var content = new ContentManager();
            content.init();
        }
    };

    function ChildTheme() {
        var notice    = $(".childtheme-notes");

        function ajax_callback(r) {

            if (typeof r.done !== "undefined") {
                setTimeout(function(){
                    notice.addClass("lead");
                },0);
                $('.icon--checkmark').show();

                complete();
            } else {
                notice.addClass("lead error");
                notice.html(r.error);
            }
        }

        function do_ajax() {
            jQuery.post(ajaxurl, {
                action: "rdt_child_theme",
            }, ajax_callback).fail(ajax_callback);
        }

        return {
            init: function(btn) {
                complete = function() {
                    $('.rdt-btn-childtheme').removeClass('loading');
                    setTimeout(function(){
                        $('.icon--checkmark').hide();
                        $('.rdt-popup-import__childtheme').hide();
                        $('.rdt-popup-import__plugins').show();
                    },2000);
                };
                $('.rdt-btn-childtheme').addClass('loading');
                do_ajax();
            }
        }
    }

    function PluginManager() {

        var complete;
        var items_completed = 0;
        var current_item = "";
        var $current_node;
        var current_item_hash = "";

        function ajax_callback(response, status, jqXHR) {
			console.log(response); 
		
            if (typeof response === "object" && response.message) {
                $current_node.find("span.plugin-action").html(response.message);
                $current_node.find("span.plugin-status").addClass('loading');
				// $current_node.find("span").html( '<img class="loading_img" src="'+road_image_url+'" alt="loading.gif" />');
                if (response.url) {
                    current_item_hash = response.hash;
                    jQuery.post(response.url, response, function(response2) {
                        process_current();
                        $current_node.find("span.plugin-action").html(response.message);
                    }).fail(ajax_callback);
                } else {
                    $current_node.find("span.plugin-action").addClass('success');
                    $current_node.find("span.plugin-status").removeClass('loading').addClass('success');
                    find_next();
                }
            } else {
                // Some plugins do redirection after being activated successfully.
                if (typeof response == "string" && jqXHR.getResponseHeader('content-type').indexOf('text/html') >= 0) {
                    $current_node.find("span.plugin-action").html(response.message);
                } else {
                    $current_node.find("span.plugin-action").text("Error");
                }
                find_next();
            }
        }

        function process_current() {
            if (current_item) {
                jQuery.post(ajaxurl, {
                    action: "rdt_ajax_plugins",
                    plugin_slug: current_item
                }, ajax_callback).fail(ajax_callback);
            }
        }

        function find_next() {
            var do_next = false;
            if ($current_node) {
                if (!$current_node.data("done_item")) {
                    items_completed++;
                    $current_node.data("done_item", 1);
                }
            }
            var $li = $(".roadthemez-plugins li");
			console.log('li length'); 
			console.log( $li.length);
			console.log(do_next);
            $li.each(function() {
                if (current_item == "" || do_next) {
                    current_item = $(this).data("slug");
                    $current_node = $(this);
                    process_current();
                    do_next = false;
                } else if ($(this).data("slug") == current_item) {
                    do_next = true;
                }
            });
			console.log('items_completed');
			console.log(items_completed);
            if (items_completed >= $li.length) {
				
                // finished all plugins!
				console.log('setup complete plugins');
				jQuery('.roadtheme_plugins a.install').remove();
                $('.icon--checkmark').show();
                complete();
            }
        }

        return {
            init: function() {
                $(".roadtheme_plugins").addClass("installing");
                complete = function() {
                    setTimeout(function(){
                        $('.icon--checkmark').hide();
                        $('.rdt-popup-import__plugins').hide();
                        $('.rdt-popup-import__content').show();
                    },2000);
                };
                $('.rdt-popup-import__plugins .rdt-step-buttons a').html('Installing...').removeClass('rdt-btn-plugins');
                find_next();
            }
        }
    }

    function ContentManager(){
        var complete;
        var items_completed     = 0;
        var current_item        = "";
        var $current_node;
        var current_item_hash   = "";
        var current_content_import_items = 1;
        var total_content_import_items = 0;
        var progress_bar_interval;

        function ajax_callback(response) {
            var currentSpan = $current_node.find("label");
            console.log(response);
            if(typeof response == "object" && typeof response.message !== "undefined"){
                currentSpan.addClass(response.message.toLowerCase());

                if(typeof response.url !== "undefined"){
                    // we have an ajax url action to perform.
                    if(response.hash === current_item_hash){
                        currentSpan.addClass("status--failed");
                        find_next();
                    }else {
                        current_item_hash = response.hash;

                        jQuery.post(response.url, response, ajax_callback).fail(ajax_callback); // recuurrssionnnnn
                    }
                }else if(typeof response.done !== "undefined"){
                    // finished processing this plugin, move onto next
                    find_next();
                }else{
                    // error processing this plugin
                    find_next();
                }
            }else{
                
                // error - try again with next plugin
                currentSpan.addClass("status--error");
                find_next();
            }
        }

        function process_current(){
            if(current_item){
                var $check = $current_node.find("input:checkbox");
                if($check.is(":checked")) {
                    jQuery.post(ajaxurl, {
                        action: "rdt_ajax_content",
                        content: current_item,
                        selected_demo: $( '.rdt_install-demo-form' ).data('demo'),
                    }, ajax_callback).fail(ajax_callback);
                }else{
                    $current_node.addClass("skipping");
                    setTimeout(find_next,300);
                }
            }
        }

        function find_next(){
            var do_next = false;
            if($current_node){
                if(!$current_node.data("done_item")){
                    items_completed++;
                    $current_node.data("done_item",1);
                }
                $current_node.find(".spinner").css("visibility","hidden");
            }
            var $items = $(".rdt_install-demo-form .import_content_item");
            $items.each(function(){
                if (current_item == "" || do_next) {
                    current_item = $(this).data("content");
                    $current_node = $(this);
                    process_current();
                    do_next = false;
                } else if ($(this).data("content") == current_item) {
                    do_next = true;
                }
            });
            if(items_completed >= $items.length){
                complete();
            }
        }

        return {
            init: function(){
                complete = function(){
                    $('.rdt-popup-import__content').hide();
                    $('.rdt-popup-import__success').show();
                    $('.rdt-popup-import').addClass('completed');
                };
                $('.rdt-popup-import__content .rdt-step-buttons button').html('Importing...').removeClass('.rdt-import');
                find_next();
            }
        }
    	
    }

    return {
        init: function() {
            t = this;
            $(window_loaded);
        }
    }
})( jQuery );