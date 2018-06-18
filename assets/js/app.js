// waitForFinalEvent: set individual timers based on a randomly generated ID
// -------------------------------------------------------------------------------------------------------------
var waitForFinalEvent = (function() {
	var timers = {};
	return function (callback, ms, uniqueId) {
		if (!uniqueId) {
			uniqueId = "Don't call this twice without a uniqueId";
		}
		if (timers[uniqueId]) {
			clearTimeout (timers[uniqueId]);
		}
		timers[uniqueId] = setTimeout(callback, ms);
	};
})();

!function ($) {

	$(function() {

		'use strict';

		// scoped vars
		var $app                 = $(".wrap");                    // outter div
		var $navWrap             = $("#demo1");                   // sidemenu wrapper
		var $narrowTrigger       = $("#js-narrow-menu");          // button that makes the menu narrow or regular size
		var $hiddenTrigger       = $("#js-showhide-menu");        // button which hides or shows the sidemenu
		var $narrowNotIcon       = "fa-bars";             // icon to display when sidemenu is not narrow
		var $narrowIcon          = "fa-ellipsis-v";            // icon to display when sidemenu is narrow
		var $hiddenNotIcon       = "fa-eye";                      // icon to display when sidemenu is not hidden
		var $hiddenIcon          = "fa-eye-slash";                // icon to display when sidemenu is hidden
		var $breakPoint          = 768;                           // breakpoint for sidemenu to show or hide on window.resize
		var $extraMenuTrigger    = $("#js-extramenu");            // button for the extra menu that pops over content on the right (or left on rtl design)
		var $extraMenu           = $("#extramenu");               // the extra menu container that pops over content on the side

		var $infoContent         = $("#info-content");
		var $infoContentSelector = $('.js-info-tab-selector');


		// --> LocalStorage checks
		// -------------------------------------------------------------------------------------------------------------
		// check localStorage for aside
		if (typeof localStorage !== 'undefined' && localStorage !== null) {
			if (localStorage.getItem("asideNarrow") == "yep") {
				$app.addClass("aside-narrow");
				$narrowTrigger.find("i").removeClass($narrowNotIcon).addClass($narrowIcon);
			}

			if (localStorage.getItem("asideHidden") == "yep") {
				$app.addClass("aside-hidden");
				$hiddenTrigger.find("i").addClass($hiddenNotIcon).removeClass($hiddenIcon);
				$narrowTrigger.hide();
			}
		}

		// check localStorage for extramenu
		if (typeof localStorage !== 'undefined' && localStorage !== null) {
			if (localStorage.getItem("extraMenu") == "yep") {
				$extraMenu.addClass("shown");
			}
		}


		// narrow menu trigger
		// -------------------------------------------------------------------------------------------------------------
		$narrowTrigger.on("click", function() {

			var $_this = $(this);

			if ($app.hasClass("aside-narrow")) {
				// add aside-narrow class to wrapper
				$app.removeClass("aside-narrow");
				// update localStorage with new asideNarrow value
				localStorage.setItem("asideNarrow", "nope");
				// change the icon on the trigger itself
				$_this.find("i").removeClass($narrowIcon).addClass($narrowNotIcon);
				// turn off all open menus if any
				$navWrap.navgoco('toggle', false);
				// rebuild navigation: makes sure proper events are set
				$navWrap.navgoco('reset');
			}else{
				// remove aside-narrow class from wrapper
				$app.addClass("aside-narrow");
				// update localStorage with new asideNarrow value
				localStorage.setItem("asideNarrow", "yep");
				// change the icon on the trigger itself
				$_this.find("i").removeClass($narrowNotIcon).addClass($narrowIcon);
				// turn off all open menus if any
				$navWrap.navgoco('toggle', false);
				// rebuild navigation: makes sure proper events are set
				$navWrap.navgoco('reset');
			}
		});

		// hidden menu trigger
		// -------------------------------------------------------------------------------------------------------------
		$hiddenTrigger.on("click", function() {

			var $_this = $(this);

			if ($app.hasClass("aside-hidden")) {
				// remove aside-hidden class from wrapper
				$app.removeClass("aside-hidden");
				// switch icon
				$_this.find("i").removeClass($hiddenNotIcon).addClass($hiddenIcon);
				// hide narrow trigger
				$narrowTrigger.show();
				// update localstorage
				localStorage.setItem("asideHidden", "nope");
			}else{
				// add aside-hidden class to wrapper
				$app.addClass("aside-hidden");
				// switch icon
				$_this.find("i").addClass($hiddenNotIcon).removeClass($hiddenIcon);
				// hide narrow trigger
				$narrowTrigger.hide();
				// update localstorage
				localStorage.setItem("asideHidden", "yep");
			}
		});


		// extramenu trigger
		// -------------------------------------------------------------------------------------------------------------
		$extraMenuTrigger.on("click", function() {
			if ($extraMenu.hasClass("shown")) {
				$("#extramenu-content").hide();
				// do not show menu because it's visible and we need to hide it
				$extraMenu.removeClass("shown");
				localStorage.removeItem("extraMenu");
			}else{
				// menu is hidden, show it
				$extraMenu.addClass("shown");
				localStorage.setItem("extraMenu", "yep");
			}

			// Transition-end trigger for boxed layouts, is not triggered with fluid layouts because we don't use the html container in those themes.
			$extraMenu.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend',
				function(e) {
					if ($extraMenu.hasClass("shown")) {
						$("#extramenu-content").show();
					}
				}
			);
		});

		// show extramenu content when visible
		if ($extraMenu.hasClass('shown')) {
			$("#extramenu-content").show();
		}else{
			$("#extramenu-content").hide();
		}


		// change triggers depending on screen width (used below)
		// -------------------------------------------------------------------------------------------------------------
		function hiddenOnBreakPoint() {
			//console.log($(window).width());
			if ($('body').width() < $breakPoint) {

				// check localStorage and adjust triggers accordingly
				//if (typeof localStorage !== 'undefined' && localStorage !== null) {
				if (localStorage.getItem("asideHidden") == "nope" || typeof localStorage !== 'undefined') {
					// add aside-hidden to wrapper
					$app.addClass('aside-hidden');
					// trigger
					$hiddenTrigger.find("i").addClass($hiddenNotIcon).removeClass($hiddenIcon);
					$narrowTrigger.hide();
					// storage
					localStorage.setItem("asideHidden", "yep");
				}
			}else{

				// check localStorage and adjust triggers accordingly
				if (localStorage.getItem("asideHidden") == "yep") {
					// remove class aside-hidden from wrapper
					$app.removeClass('aside-hidden');
					// trigger
					$hiddenTrigger.find("i").removeClass($hiddenNotIcon).addClass($hiddenIcon);
					$narrowTrigger.show();
					// storage
					localStorage.setItem("asideHidden", "nope");
				}
			}
		}


		// resize verification with hammer protection
		// -------------------------------------------------------------------------------------------------------------
		$(window).on('resize', function() {
			waitForFinalEvent(function() {
				//console.log("resize");
				hiddenOnBreakPoint();
			}, 250, "hiddenOnBreakPoint");
		});


		// launch the navigation menu (custom Navgoco version)
		// -------------------------------------------------------------------------------------------------------------
		if ($navWrap.length > 0) {
			$navWrap.navgoco({
				caretClassCollapsed: 'fa fa-angle-down',
				caretClassExpanded: 'fa fa-angle-up',
				accordion: true,
				openClass: 'open',
				save: true,
				cookie: {
					name: 'sidemenuCIMembership',
					expires: false,
					path: '/'
				},
				slide: {
					duration: 200,
					easing: 'swing'
				}
			});
		}


		// tab menu switch control for sidebar (generic: add/remove menu tabs as you please)
		// -------------------------------------------------------------------------------------------------------------
		$infoContentSelector.on('show.bs.tab', function (e) {
			$infoContentSelector.removeClass('active in'); // remove all active and in classes from them tabs
			$(this).addClass('active in'); // set the current tab active

			localStorage.setItem("js-aside-info-active-tab", $(this).attr('id'));
		});

		if (typeof localStorage !== 'undefined' && localStorage !== null) {
			$infoContentSelector.removeClass('active in');
			$("#" + localStorage.getItem("js-aside-info-active-tab")).addClass('active in');

			$infoContent.find(".tab-pane.active").removeClass('active in');

			$infoContent.find(".tab-pane." + localStorage.getItem("js-aside-info-active-tab")).addClass('active in');
		}


		// select all functionality for manipulating multiple items at one go
		// -------------------------------------------------------------------------------------------------------------
		$(".js-select-all-members").on('change', function() {
			var c = this.checked;
			$(':checkbox').prop('checked',c);
		});
		// verify on each checkbox change whether everything is checked or not and change the select-all button accordingly
		$(".list_members_checkbox").on('change', function() {
			$(".js-select-all-members").prop("checked", $('.list_members_checkbox:checked').length == $('.list_members_checkbox').length);
		});


		// launch the Slimscroll plugin for dropdowns
		// -------------------------------------------------------------------------------------------------------------
		if ($('.scroll').length > 0) {
			$('.scroll').slimscroll(
				{
					distance: '0',
					color: '#b9b9b9',
					railVisible: true,
					railColor: '#ccc',
					size: '5px',
					height: '220px'
				}
			);
		}


		// Adminpanel bootbox
        // -------------------------------------------------------------------------------------------------------------

        // Alerts when clicking buttons on list members page
		var myBootbox = function($target) {
			$($target).click(function() {
				$("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
				$(this).attr("clicked", "true");
			});
		};

		myBootbox("form#mass_action_form input[type=submit]");

		$("form#mass_action_form button").on("click", function(evt) {

			var $target = $(this).attr('name');

			evt.preventDefault();
			bootbox.confirm("Warning: <br>" + $(this).data("title"), function(confirmed) {
				if (confirmed) {
					$("input#mass_action").val($target);
					$("#mass_action_form").submit();
				}
			});
		});


		// Parsley frontend validation
		// -------------------------------------------------------------------------------------------------------------
		var parsleyFactory = function($form, $button) {
			var $parsley = $form.parsley();

			$button.on('click', function(evt) {

				if ($parsley.isValid()) {

					$parsley.destroy();
					$form.submit();

					$(this).button('loading');
				}else{
					$(this).button("reset");
				}
                if (typeof(tinyMCE) != "undefined") {
                    tinyMCE.triggerSave();
                }
			});
		};

		// Parsley generic form
        $(".js-parsley").each(function(i, obj) {
            var $form = $(this);
            if ($form.length) {
                parsleyFactory( $form, $("." + $form.data('parsley-submit')) );
            }
        });

        // ---
        // custom validation rules with AJAX
        window.Parsley.addAsyncValidator('parsley_is_db_cell_available', function (xhr) {
            var response = xhr.responseText;
            var t = this.$element.attr('name');
            if (response === 'valid') {
                return true;
            } else {
                return false;
            }
        }, CONFIG.base_url + 'utils/parsley_custom_validation/parsley_is_db_cell_available');


        // set global CSRF token
        // -------------------------------------------------------------------------------------------------------------
        var key = CONFIG.csrf_token_name;
        var $csrf_token_name = {};
        $csrf_token_name[key] = CONFIG.csrf_cookie_name;

		// profile picture upload
		// -------------------------------------------------------------------------------------------------------------
        var $fileUpload = $('#fileupload');
        var acceptFileTypes = /^image\/(jpe?g|png)$/i;


        if ($fileUpload.length) {
            $fileUpload.fileupload({
                dropZone: $('#dropzone'),
				url: CONFIG.base_url + $fileUpload.attr("data-path"),
				dataType: 'json',
				disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
				maxFileSize: CONFIG.picture_max_upload_size * 1000,
				acceptFileTypes: acceptFileTypes,
				formData:
					$csrf_token_name,
                submit: function (e, data) {
                    data.formData = $csrf_token_name; // refreshing csrf token here works! No need to turn off CSRF refreshing anymore.
					$('#progress').removeClass('hidden');
					$('#files').text('');
				},
				add: function(e, data) {
					var uploadErrors = [];
					if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
						uploadErrors.push('Not an accepted file type.');
					}
					if(data.originalFiles[0]['size'].toString().length > 0 && data.originalFiles[0]['size'] > CONFIG.picture_max_upload_size * 1000) {
						uploadErrors.push('Filesize is too big.');
					}
					if(uploadErrors.length > 0) {
						$('#files').text(uploadErrors.join("\n"));
					} else {
						data.submit();
						//console.log(data); // if you want to troubleshoot this use your network tab in browser developer tools
					}
				},
				done: function (e, data) {
					$.each(data.result.files, function (index, file) {
						var $output = '';
						if (file.error != 0) {
							$output = file.error;
						}
						var d = new Date();
						//var extension = file.name.substr( (file.name.lastIndexOf('.') +1) );
						$('#progress').addClass('hidden');
						$('#progress .progress-bar').css('width', '0%');
						$('.js_profile_image').attr('src', CONFIG.base_url + 'assets/img/members/'+ $('#profile_username').val() + '/' + data.result.new_name + '?' + d.getTime());
						$('#files').text($output);
					});

                   // CONFIG.csrf_cookie_name = data.result.csrfHash;
                    CONFIG.csrf_cookie_name = data.result.csrfHash;
                    $csrf_token_name[key] = data.result.csrfHash;
                    $('body').find('input[name=csrf_token_name]').val(CONFIG.csrf_cookie_name);
				},
				progressall: function (e, data) {
					var progress = parseInt(data.loaded / data.total * 100, 10);
					$('#progress .progress-bar').css(
						'width',
						progress + '%'
					);
				},
				fail: function (e, data) {
					$('#files').text('File upload is down. Please try again later.');
				}
			}).prop('disabled', !$.support.fileInput)
				.parent().addClass($.support.fileInput ? undefined : 'disabled');
		}

        $fileUpload.on('click', function(){
			$("#files").text('');
		});


		// adminpanel text on search button -> list members page
		// -------------------------------------------------------------------------------------------------------------
		$("#js-search").on('click', function() {
			if ($("#search_wrapper").hasClass("in")) {
				$("#js-search-text").html('<i class="fa fa-expand pd-r-5"></i> ' + LANG.search_expand);
			}else{
				$("#js-search-text").html('<i class="fa fa-compress pd-r-5"></i> ' + LANG.search_collapse);
			}
		});


		// confirm delete
        // -------------------------------------------------------------------------------------------------------------
		$(".js-confirm-delete").on('click', function(){
			if(confirm(LANG.confirm_delete)) {
				$(this).button('loading');
				return true;
			}else{
				return false;
			}
		});

        // add member username from email
        // -------------------------------------------------------------------------------------------------------------
        $('.js-username-from-email').on('change', function () {
            var $target = $('.js-username-from-email-target');
            //var $_this = $(this);
            if (($target).is(":visible")) {
                $("#username").removeAttr('data-parsley-required');
                $target.hide();
            }else{
                $("#username").attr('data-parsley-required', '');
                $target.show();
            }
        });


        // site settings remember tab state, allow passing of url with tab id in location.hash
        // -------------------------------------------------------------------------------------------------------------
        if (location.hash) {
            $("a[href='" + location.hash + "']").tab("show");
        }
        $(document.body).on("click", ".nav-tabs a[data-toggle]", function(evt) {
            location.hash = this.getAttribute("href");
            localStorage.setItem('lastTab', $(this).attr('href'));
        });

        // go to the latest tab, if it exists:
        var lastTab = localStorage.getItem('lastTab');
        if (lastTab) {
            $('.nav-tabs a[href="' + lastTab + '"]').tab('show');
        }


        // Alerts when clicking on member detail - view username history
        // -------------------------------------------------------------------------------------------------------------
        $(".js-username-history").on("click", function(evt) {

            var $userId = $(this).data('userid');

            /*var $data = {};
            $data[CONFIG.csrf_token_name] = CONFIG.csrf_cookie_name;*/

            $.ajax({
                method: 'POST',
                url: CONFIG.base_url + 'adminpanel/member_detail/get_username_history/' + $userId,
                dataType: 'json',
                data: $csrf_token_name
            }).done(function(result) {

                var $endResult = [];

                $.each(result.resultData, function (i, args) {
                    $endResult.push(args.username + '<br>');
                });

                bootbox.dialog({
                    message: $endResult,
                    title: "Username history",
                    buttons: {
                        success: {
                            label: "Close",
                            className: "btn-success"
                        }
                    }
                });

                // refresh CSRF token
                CONFIG.csrf_cookie_name = result.csrfHash;
                $csrf_token_name[key] = result.csrfHash;

            }).fail(function( jqXHR, textStatus ) {
                console.log(textStatus);
            });
        });


        // prevent member dropdown from closing on click
        // -------------------------------------------------------------------------------------------------------------
        $('.dropdown-menu-member').on('click', function(e) {
            e.stopPropagation();
        });


        // catch CSRF timeout - JS page refresher
        // -------------------------------------------------------------------------------------------------------------
        // value must be same as $config['csrf_expire'] = 7200; in config.php
        // DISABLED FOR NOW: could be annoying or inconvenient to some
        /*setInterval(function () {
            if(alert('Your session has expired - click ok to continue')){
            }else{
                window.location.reload();
            }
        }, (CONFIG.csrf_expire * 1000) - 3000); // * 1000 to get seconds to miliseconds; subtract a few seconds to make sure we refresh BEFORE the session expires */

        // let's show our hidden body after everything is done
        var $pageContainer = $('body');
        $pageContainer.css('display', 'none');
        $pageContainer.css('visibility', 'inherit');
        $pageContainer.fadeIn(400);
        $pageContainer.removeClass("notransition");



        // workaround for removing #_-_ from url during Facebook authentication
        if (window.location.hash == '#_=_') {
            window.location.hash = ''; // for older browsers, leaves a # behind
            history.pushState('', document.title, window.location.pathname); // nice and clean
           // e.preventDefault(); // no page reload
        }

	});

}(window.jQuery);