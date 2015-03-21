// (function($) {

// 	FormStorage.init();

// 	$(function() {
// 		// Textarea auto expand
// 		$('.autosize').autosize({
// 			append: "\n"
// 		});

// 		// Restore form data
// 		FormStorage.restore();

// 		// Placeholder style
// 		$("[placeholder]").focusout(function() {
// 			if ($(this).val() == '' || $(this).val() == $(this).prop("placeholder")) {
// 				$(this).addClass("bold-input")
// 			}
// 		}).bind('change paste keyup', function() {
// 			if ($(this).val() != '') {
// 				$(this).removeClass("bold-input")
// 			}else{
// 				$(this).addClass("bold-input")
// 			}
// 		}).focusout();


// 	});

// })(jQuery);

(function($){
	$(function(){
        // Slide menu for mobile
		$('.button-collapse').sideNav();

        // Delete confirm
        $("[data-confirm]").on('click', function(e) {
            return window.confirm("Are you sure delete this data?")
        });
    });
})(jQuery);
