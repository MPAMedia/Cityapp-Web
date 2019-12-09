$(function(){
    App.Init();
});



/**
 * Main Namespace
 * App
 */
var App = {};


    /**
     * Initialize
     */
    App.Init = function()
    {
        $("body").on("click", ".next-btn", function() {
            var nextstep = $($(this).data("next"));

            $("html, body").animate({
                scrollTop: 0
            }, 200, function() {
                $(".step").stop(true, true).hide();
                nextstep.stop(true, true).fadeIn(1000);

                $("header").addClass("mini");
            });
        });



        //show controls interface
        $("html, body").animate({
            scrollTop: 0
        }, 200, function() {
            $("#controls").show(500);
        });

        App.Controls();
    };
    

    /**
     * Submit controls
     */
    App.Controls = function()
    {
        var form = $("form#controls");

        $(":input", form).on("focus", function() {
            $(this).removeClass('error');
        });


        $(":input[name='upgrade']").on("change", function() {
            if ($(this).val()) {
                $(".upgrade-only :input", form).prop("disabled", false);
                $(".upgrade-only", form).removeClass("none");

                $(".install-only :input", form).prop("disabled", true);
                $(".install-only", form).addClass("none");
            } else {
                $(".upgrade-only :input", form).prop("disabled", true);
                $(".upgrade-only", form).addClass("none");

                $(".install-only :input", form).prop("disabled", false);
                $(".install-only", form).removeClass("none");
            }
        });


        form.on("submit", function() {


            var submitable = true;
            var errors = [];

            $(":input.required", form).not(":disabled").each(function() {
                if (!$(this).val()) {
                    $(this).addClass("error");
                    submitable = false;
                }
            });

            if (!submitable) {
                errors.push("Fill required fields!");
            }


            if (!submitable) {
                $(".form-errors", form).html("");

                for (var i=0; i<errors.length; i++) {
                    $(".form-errors", form)
                        .append("<div><span class='mdi mdi-close-circle'></span> "+errors[i]+"</div>");
                }

                $("html, body").animate({
                    scrollTop: 0
                }, 200, function() {
                    $(".form-errors", form).fadeIn(1000);
                });
            } else {

                $("body").addClass('on-progress');

                $.ajax({
                    url: form.attr("action"),
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: "update",
                        pid: $(":input[name='pid']").val(),
                    },
                    error: function(xhr, ajaxOptions, thrownError) {

                        console.log(xhr);
                        $("body").removeClass('on-progress');

                        $(".form-errors", form)
                            .html("<div><span class='mdi mdi-close-circle'></span> Unexpected error occured!</div>");
                        $("html, body").animate({
                            scrollTop: 0
                        }, 200, function() {
                            $(".form-errors", form).fadeIn(1000);
                        });

                    },

                    success: function(resp) {


                        console.log(resp);

                        if (resp.success == 0) {

                            $(".form-errors", form)
                                .html("<div><span class='mdi mdi-close-circle'></span> "+resp.error+"</div>");
                            $("html, body").animate({
                                scrollTop: 0
                            }, 200, function() {
                                $(".form-errors", form).fadeIn(1000);
                            });

                        } else if(resp.success == 1){
                            var nextstep = $("#success");

                            $(".step").stop(true, true).hide();
                            $("header").hide();

                            $("html, body").animate({
                                scrollTop: 0
                            }, 200, function() {
                                nextstep.stop(true, true).fadeIn(1000);
                            });

                            $("#userInfos").html(resp.msg);
                            $("#redirect").attr("href",resp.redirect);
                        }

                        $("body").removeClass('on-progress');
                    }
                });
            }

            return false;
        })
    }


/* FUNCTIONS */

/**
 * Validate email
 * @param  {String}  email 
 * @return {Boolean}       
 */
function isValidEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}
