$(document).ready(function(e) {
        try {
            $("body .select").msDropDown();
        } catch(e) {
            //alert(e.message);
        }
    });

    $(document).ready(function() {
        $(".menu-icon").click(function() {
            $("nav.mobile-active-menu").addClass("open");
        });
        $(".overlay").click(function() {
            $("nav.mobile-active-menu").removeClass("open");
        });
        $("#themes").click(function() {
            $(".section-menus li").removeClass("active");
            $(".analytics").hide();
            $(".integrations").hide();
            $(this).addClass("active");
            $(".themes").show();
        });
        $("#analytics").click(function() {
            $(".section-menus li").removeClass("active");
            $(".themes").hide();
            $(".integrations").hide();
            $(this).addClass("active");
            $(".analytics").show();
        });
        $("#integrations").click(function() {
            $(".section-menus li").removeClass("active");
            $(".analytics").hide();
            $(".themes").hide();
            $(this).addClass("active");
            $(".integrations").show();
        });
        $(".pick-plan .button").click(function() {
            $(".pick-plan, .pay").hide();
            $("#pick-plan").removeClass("active");
            $(".pick-plan-add").removeClass("open");
            $(".check-out").show();
            $(".create-add").addClass("open");
            $("#check-out").addClass("active completed");
        });
        $(".check-out .checkout-button").click(function() {
            $(".pick-plan, .check-out").hide();
            $(".create-add").removeClass("open");
            $("#check-out").removeClass("active");
            $(".pay").show();
            $(".payment-add").addClass("open");
            $("#pay").addClass("active completed");
        });
        $(".single-price-list .subscribe").click(function() {
            $(".modal-content").removeClass("zoomOut animated");
            $(".pay, .check-out").hide();
            $(".payment-popup").addClass("open");
            $(".pick-plan").show();
            $(".modal-content").addClass("zoomIn animated");
        });
        $(".modal .overlay, .modal .close").click(function() {
            $(".modal-content").removeClass("zoomIn animated");
            $(".modal-content").addClass("zoomOut animated");
            setTimeout(function(){ 
            $(".payment-popup, .signin-signup").removeClass("open"); }, 500);                    
        });
        $(".signin-signup .signin-link").click(function() {
            $(".signin-signup .signup-content, .signin-signup .forgot-content").hide();
            $(".signin-signup .signin-content").show();                  
        });
        $(".signin-signup .signup-link").click(function() {
            $(".signin-signup .signin-content, .signin-signup .forgot-content").hide();
            $(".signin-signup .signup-content").show();                  
        });
        $(".signin-signup .forgot-link").click(function() {
            $(".signin-signup .signin-content, .signin-signup .signup-content").hide();
            $(".signin-signup .forgot-content").show();                  
        });
        $("nav .signin").click(function() {
            $(".modal-content").removeClass("zoomOut animated");
            $(".signin-content, .forgot-content").hide();
            $(".signin-signup").addClass("open");
            $(".signup-content").show();
            $(".modal-content").addClass("zoomIn animated");
        });
        $("nav .signup").click(function() {
            $(".modal-content").removeClass("zoomOut animated");
            $(".signup-content, .forgot-content").hide();
            $(".signin-signup").addClass("open");
            $(".signin-content").show();
            $(".modal-content").addClass("zoomIn animated");
        });
    });