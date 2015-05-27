(function ($) {
    'use strict';

    $(document).ready(function () {

        /**
         * Sets messages in the admin screen header
         * Triggered after updates and resets
         *
         * @param headerElem CSS class of the header element
         * @constructor
         */
        var Header = function (headerElem) {
            this.elem = $(headerElem);
            this.evalClass = function (elemClass) {
                var elem = this.elem;
                if (elem.hasClass(elemClass)) {
                    setTimeout(
                        function () {
                            elem.removeClass(elemClass);
                        }, 3000);
                    setTimeout(
                        function () {
                            $('.lti-seo-message').empty();
                        }, 5000);
                }
            };
        };

        var seo_header = $('#lti-seo-header');
        if (seo_header.length) {
            seo_header = new Header(seo_header);
            seo_header.evalClass('lti_update');
            seo_header.evalClass('lti_reset');
            seo_header.evalClass('lti_error');

            $('#jsonld_reset').on('click', function () {
                $('#jsonld_img').val('');
                $('#jsonld_img_id').val('');
            });
            $('#frontpage_social_reset').on('click', function () {
                $('#frontpage_social_img').val('');
                $('#frontpage_social_img_id').val('');
            });
        }


        /**
         * Allows to enable/disable groups of input fields
         * when the user activates/deactivates certain features
         *
         * Targets a div through its id and disables/enables any input, textarea or select inside it.
         */
        var input = function () {
            this.objectToLookIntoID = null;
            this.optionID = null;
        };

        input.prototype.init = function (optionID, objectToLookIntoID) {
            this.optionID = $(optionID);
            this.objectToLookIntoID = $(objectToLookIntoID);
        };

        input.prototype.disable = function () {
            this.objectToLookIntoID.find('input').attr("disabled", "disabled");
            this.objectToLookIntoID.find('textarea').attr("disabled", "disabled");
            this.objectToLookIntoID.find('select').attr("disabled", "disabled");
        };
        input.prototype.enable = function () {
            this.objectToLookIntoID.find('input').removeAttr("disabled");
            this.objectToLookIntoID.find('textarea').removeAttr("disabled");
            this.objectToLookIntoID.find('select').removeAttr("disabled");
        };

        input.prototype.toggleDisabled = function () {
            if (this.optionID.length) {
                if (typeof this.optionID.attr('checked') === "undefined") {
                    this.disable();
                }
                var option = this.optionID;
                var $this = this;
                this.optionID.click(function () {
                    if (this.checked) {
                        $this.enable();
                    } else {
                        $this.disable();
                    }
                });

            }
        };

        /**
         * When we initialize the page, we need to make sure groups of fields are disabled
         * if the parent option is not ticked
         */
        $('[data-toggle="seo-options"]').each(function () {
            var targetToDisable = $(this).attr('data-target');
            if (typeof targetToDisable != "undefined") {
                var element = new input();
                element.init("#" + $(this).attr('id'), targetToDisable);
                element.toggleDisabled();
            }
        });

        /**
         * Triggered when clicking "reset" on image fields.
         */
        $('#lti_social_reset').on('click', function () {
            $('#lti_social_img').val('');
            $('#lti_social_img_id').val('');
        });

        /**
         * Handles tabbing feature
         *
         * @type {*|HTMLElement}
         */
        var lti_seo_tabs = $('#lti_seo_tabs');
        if (lti_seo_tabs.length) {
            var hash = window.location.hash;
            if (hash) {
                lti_seo_tabs.find('a[href="' + hash + '"]').tab('show');
            } else {
                lti_seo_tabs.find('a[href="#tab_general"]').tab('show');
            }

            lti_seo_tabs.find('a').click(function (e) {
                window.location.hash = this.hash;
                e.preventDefault();
                $(this).tab('show');
            });

            //We make sure we come back to the last active tab before the page is reloaded
            $('#flseo').on('submit', function () {
                var hash = window.location.hash;
                if (hash) {
                    $(this).attr('action', $(this).attr('action') + hash);
                }

            });
        }

        /**
         * Activates Wordpress' media window for image selection
         *
         * Thank you http://www.webmaster-source.com/2013/02/06/using-the-wordpress-3-5-media-uploader-in-your-plugin-or-theme/
         */
        $('.upload_image_button').click(function (e) {
            var target_id = $(this).attr('id').replace(/_button$/, '');
            e.preventDefault();

            var custom_uploader = wp.media.frames.file_frame = wp.media({
                title: lti_seo_i8n.use_img,
                button: {text: lti_seo_i8n.use_img}
            });
            custom_uploader.on('select', function () {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                //We grab the attachment URL for display and we stash away the ID to retrieve image information if needed.
                $('#' + target_id).val(attachment.url);
                $('#' + target_id + "_id").val(attachment.id);
            });
            custom_uploader.open();
        });

        /**
         * Updates character count fields below textareas
         *
         * @param target ID of the element to play with
         * @param targetCharCount Character count to apply to the field
         * @param targetMax Character count threshold
         */
        var updateCount = function (target, targetCharCount, targetMax) {
            if (targetCharCount > targetMax) {
                var targetID = $(this).attr('id');
                $('#w' + target).addClass("danger");
            } else {
                $('#w' + target).removeClass("danger");
            }
            $('#c' + target).html(targetCharCount);
        };

        /**
         * Initializing fields with a character count
         * @type {*[]}
         */
        var fieldsWithCounter = [
            [$("#frontpage_description_text"), 160],
            [$("#lti_seo_description"), 160]
        ];

        var nbFields = fieldsWithCounter.length;

        /**
         * On keyup, we change the character count
         */
        for (var i = 0; i < nbFields; i++) {
            if (fieldsWithCounter[i][0].length) {
                var tmp = fieldsWithCounter[i];
                updateCount(tmp[0].attr('id'), tmp[0].val().length, tmp[1]);
                tmp[0].bind("change keyup click", function () {
                    updateCount(tmp[0].attr('id'), tmp[0].val().length, tmp[1]);
                });
            }
        }

        /**
         * Fetches word count for post types
         * We use it in JSON-LD markup for articles
         *
         * @type {*|HTMLElement}
         */
        var postWordCount = $('#wp-word-count');
        if (postWordCount.length) {
            $('#post').on('submit', function () {
                $('#lti_seo_word_count').val(postWordCount.find('.word-count').html());
            });
        }

        /**
         * Manages Person/Organization field groups in the Frontpage tab
         * @type {*|HTMLElement}
         */
        var jsonld_entity_person = $('#jsonld_entity_person');
        if (jsonld_entity_person.length) {
            var jsonld_entity_organization = $('#jsonld_entity_organization');

            if (jsonld_entity_person.attr('checked') == 'checked') {
                $('#jsonld_entity_organization_group').addClass('hidden');
            } else {
                $('#jsonld_entity_person_group').addClass('hidden');

            }

            jsonld_entity_person.on('click', function () {
                $('#jsonld_entity_organization_group').addClass('hidden');
                $('#jsonld_entity_person_group').removeClass('hidden');
            });

            jsonld_entity_organization.on('click', function () {
                $('#jsonld_entity_organization_group').removeClass('hidden');
                $('#jsonld_entity_person_group').addClass('hidden');
            });

        }

        /**
         * General > Google > "Get authentication code" button
         * Opens a google authorization window that generates an access token that we need to use the google api
         */
        $('#btn-get-google-auth').click(function (e) {
            e.preventDefault();
            var auth_url = $('#google_auth_url').val();
            if (typeof auth_url == "string" && auth_url.length > 0) {
                window.open(auth_url,
                    '',
                    'top=' + (screen.height / 2 - 580 / 2) + ',left=' + (screen.width / 2 - 640 / 2) + ',width=640,height=580,resizable=0,scrollbars=0,menubar=0,toolbar=0,status=1,location=0'
                );

            }
        });

        /**
         * General > Google > "Log in" button
         * Triggers form submission, see #flsm submit event handler
         *
         */
        $('#btn-google-log-in').click(function (e) {
            var auth_token = $('#google_auth_token');
            if (auth_token.length > 0) {
                if (auth_token.val().length == 0) {
                    e.preventDefault();
                    return false;
                }
            }
        });

    });
})(jQuery);

/**
 * We add the little bits of Twitter Bootstrap that we need to handle tabs in our admin screen
 *
 * @link http://getbootstrap.com/javascript/#tabs
 */
if ("undefined" == typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");
+function (t) {
    "use strict";
    var a = t.fn.jquery.split(" ")[0].split(".");
    if (a[0] < 2 && a[1] < 9 || 1 == a[0] && 9 == a[1] && a[2] < 1)throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher")
}(jQuery), +function (t) {
    "use strict";
    function a(a) {
        return this.each(function () {
            var n = t(this), r = n.data("bs.tab");
            r || n.data("bs.tab", r = new e(this)), "string" == typeof a && r[a]()
        })
    }

    var e = function (a) {
        this.element = t(a)
    };
    e.VERSION = "3.3.2", e.TRANSITION_DURATION = 150, e.prototype.show = function () {
        var a = this.element, e = a.closest("ul:not(.dropdown-menu)"), n = a.data("target");
        if (n || (n = a.attr("href"), n = n && n.replace(/.*(?=#[^\s]*$)/, "")), !a.parent("li").hasClass("active")) {
            var r = e.find(".active:last a"), i = t.Event("hide.bs.tab", {relatedTarget: a[0]}), s = t.Event("show.bs.tab", {relatedTarget: r[0]});
            if (r.trigger(i), a.trigger(s), !s.isDefaultPrevented() && !i.isDefaultPrevented()) {
                var o = t(n);
                this.activate(a.closest("li"), e), this.activate(o, o.parent(), function () {
                    r.trigger({type: "hidden.bs.tab", relatedTarget: a[0]}), a.trigger({
                        type: "shown.bs.tab",
                        relatedTarget: r[0]
                    })
                })
            }
        }
    }, e.prototype.activate = function (a, n, r) {
        function i() {
            s.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !1), a.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded", !0), o ? (a[0].offsetWidth, a.addClass("in")) : a.removeClass("fade"), a.parent(".dropdown-menu").length && a.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !0), r && r()
        }

        var s = n.find("> .active"), o = r && t.support.transition && (s.length && s.hasClass("fade") || !!n.find("> .fade").length);
        s.length && o ? s.one("bsTransitionEnd", i).emulateTransitionEnd(e.TRANSITION_DURATION) : i(), s.removeClass("in")
    };
    var n = t.fn.tab;
    t.fn.tab = a, t.fn.tab.Constructor = e, t.fn.tab.noConflict = function () {
        return t.fn.tab = n, this
    };
    var r = function (e) {
        e.preventDefault(), a.call(t(this), "show")
    };
    t(document).on("click.bs.tab.data-api", '[data-toggle="tab"]', r).on("click.bs.tab.data-api", '[data-toggle="pill"]', r)
}(jQuery);
