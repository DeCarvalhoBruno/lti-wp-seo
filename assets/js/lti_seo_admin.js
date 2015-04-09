(function ($) {
    'use strict';

    $(document).ready(function () {
        //var frontpage_description = $('#frontpage_description');
        //
        //if (frontpage_description.length) {
        //    if (typeof frontpage_description.attr("checked") === "undefined") {
        //        $('#frontpage_description_text').attr("disabled", "disabled");
        //    }
        //
        //    frontpage_description.click(function () {
        //        if (this.checked) {
        //            $('#frontpage_description_text').removeAttr("disabled");
        //        } else {
        //            $('#frontpage_description_text').attr("disabled", "disabled");
        //        }
        //    });
        //}
        //
        //var generate_keywords = $('#generate_keywords');
        //if (generate_keywords.length) {
        //    if (typeof generate_keywords.attr("checked") === "undefined") {
        //        $('.checkbox-group').find('input[type="checkbox"]').attr("disabled", "disabled");
        //    }
        //
        //    generate_keywords.click(function () {
        //        if (this.checked) {
        //            $('.checkbox-group').find('input[type="checkbox"]').removeAttr("disabled");
        //        } else {
        //            $('.checkbox-group').find('input[type="checkbox"]').attr("disabled", "disabled");
        //        }
        //    });
        //}

        //Thank you http://www.webmaster-source.com/2013/02/06/using-the-wordpress-3-5-media-uploader-in-your-plugin-or-theme/
        //for this snippet
        var custom_uploader;
        $('#upload_image_button').click(function (e) {
            var target_id = $(this).attr('id').replace(/_button$/,'');
            e.preventDefault();
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title   : lti_seo_i8n.use_img,
                button  : { text: lti_seo_i8n.use_img },
                multiple: false
            });
            custom_uploader.on('select', function () {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#'+target_id).val(attachment.url);
            });
            custom_uploader.open();
        });
    });

})(jQuery);
if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");+function(t){"use strict";var a=t.fn.jquery.split(" ")[0].split(".");if(a[0]<2&&a[1]<9||1==a[0]&&9==a[1]&&a[2]<1)throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher")}(jQuery),+function(t){"use strict";function a(a){return this.each(function(){var n=t(this),r=n.data("bs.tab");r||n.data("bs.tab",r=new e(this)),"string"==typeof a&&r[a]()})}var e=function(a){this.element=t(a)};e.VERSION="3.3.2",e.TRANSITION_DURATION=150,e.prototype.show=function(){var a=this.element,e=a.closest("ul:not(.dropdown-menu)"),n=a.data("target");if(n||(n=a.attr("href"),n=n&&n.replace(/.*(?=#[^\s]*$)/,"")),!a.parent("li").hasClass("active")){var r=e.find(".active:last a"),i=t.Event("hide.bs.tab",{relatedTarget:a[0]}),s=t.Event("show.bs.tab",{relatedTarget:r[0]});if(r.trigger(i),a.trigger(s),!s.isDefaultPrevented()&&!i.isDefaultPrevented()){var o=t(n);this.activate(a.closest("li"),e),this.activate(o,o.parent(),function(){r.trigger({type:"hidden.bs.tab",relatedTarget:a[0]}),a.trigger({type:"shown.bs.tab",relatedTarget:r[0]})})}}},e.prototype.activate=function(a,n,r){function i(){s.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded",!1),a.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded",!0),o?(a[0].offsetWidth,a.addClass("in")):a.removeClass("fade"),a.parent(".dropdown-menu").length&&a.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded",!0),r&&r()}var s=n.find("> .active"),o=r&&t.support.transition&&(s.length&&s.hasClass("fade")||!!n.find("> .fade").length);s.length&&o?s.one("bsTransitionEnd",i).emulateTransitionEnd(e.TRANSITION_DURATION):i(),s.removeClass("in")};var n=t.fn.tab;t.fn.tab=a,t.fn.tab.Constructor=e,t.fn.tab.noConflict=function(){return t.fn.tab=n,this};var r=function(e){e.preventDefault(),a.call(t(this),"show")};t(document).on("click.bs.tab.data-api",'[data-toggle="tab"]',r).on("click.bs.tab.data-api",'[data-toggle="pill"]',r)}(jQuery);
