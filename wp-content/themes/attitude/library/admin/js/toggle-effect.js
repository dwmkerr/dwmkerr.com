/**
 * Show Hide Toggle Box
 */
jQuery(document).ready(function($){
	
	$(".option-content").hide();

	$("h3.option-toggle").click(function(){
	$(this).toggleClass("option-active").next().slideToggle("fast");
		return false; 
	});

});
jQuery(document).ready(function ($) {
    setTimeout(function () {
        $(".fade").fadeOut("slow", function () {
            $(".fade").remove();
        });

    }, 100);
});