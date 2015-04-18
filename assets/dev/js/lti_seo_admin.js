(function ($) {
    'use strict';

    $(document).ready(function () {

        var seo_header = $('#lti-seo-header');

        if (seo_header.length) {
            if (seo_header.hasClass('lti_update')) {
                setTimeout(
                    function () {
                        seo_header.removeClass('lti_update');
                    }, 3000);
                setTimeout(
                    function () {
                        $('.lti-seo-message').empty();
                    }, 5000);
            } else if (seo_header.hasClass('lti_reset')) {
                setTimeout(
                    function () {
                        seo_header.removeClass('lti_reset');
                    }, 3000);
                setTimeout(
                    function () {
                        $('.lti-seo-message').empty();
                    }, 5000);
            } else if (seo_header.hasClass('lti_error')) {
                setTimeout(
                    function () {
                        seo_header.removeClass('lti_error');
                    }, 3000);
                setTimeout(
                    function () {
                        $('.lti-seo-message').empty();
                    }, 5000);
            }
        }

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
        };
        input.prototype.enable = function () {
            this.objectToLookIntoID.find('input').removeAttr("disabled");
            this.objectToLookIntoID.find('textarea').removeAttr("disabled");
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

        $('[data-toggle="seo-options"]').each(function () {
            var targetToDisable = $(this).attr('data-target');
            if (typeof targetToDisable != "undefined") {
                var element = new input();
                element.init("#" + $(this).attr('id'), targetToDisable);
                element.toggleDisabled();
            }
        });

        var lti_seo_tabs = $('#lti_seo_tabs');
        if (lti_seo_tabs.length) {
            var hash = window.location.hash;
            if (hash) {
                lti_seo_tabs.find('a[href="' + hash + '"]').tab('show');
            } else {
                lti_seo_tabs.find('a[href="#tab_general"]').tab('show');
            }

            lti_seo_tabs.find('a').click(function (e) {
                $(this).tab('show');
                window.location.hash = this.hash;
                $('html').scrollTop($('body').scrollTop());
            });

            $('#flseo').on('submit', function () {
                var hash = window.location.hash;
                if (hash) {
                    $(this).attr('action', $(this).attr('action') + hash);
                }

            });
        }

        //Thank you http://www.webmaster-source.com/2013/02/06/using-the-wordpress-3-5-media-uploader-in-your-plugin-or-theme/
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

        var updateCount = function (target, targetCharCount, targetMax) {
            if (targetCharCount > targetMax) {
                var targetID = $(this).attr('id');
                $('#w' + target).addClass("danger");
            } else {
                $('#w' + target).removeClass("danger");
            }
            //console.log(targetCharCount+' '+targetMax);
            //console.log( $('#c'+target).val());
            $('#c' + target).html(targetCharCount);
        };

        var fieldsWithCounter = [
            [$("#frontpage_description_text"), 160],
            [$("#lti_seo_description"), 160]
        ];

        var nbFields = fieldsWithCounter.length;

        for (var i = 0; i < nbFields; i++) {
            if (fieldsWithCounter[i][0].length) {
                var tmp = fieldsWithCounter[i];
                updateCount(tmp[0].attr('id'), tmp[0].val().length, tmp[1]);
                tmp[0].bind("change keyup click", function () {
                    updateCount(tmp[0].attr('id'), tmp[0].val().length, tmp[1]);
                });
            }
        }
    });
})(jQuery);
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
