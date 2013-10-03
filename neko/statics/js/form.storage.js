var FormStorage = {

	selector: '.form-storage',

	key: function(element) {
		return window.location.pathname + "@" + $(element).prop('id')
	},


	init: function() {
		if (window.localStorage) {
			$(document).on('input', FormStorage.selector, function() {
				localStorage.setItem(FormStorage.key($(this)), $(this).val())
			});

			$(document).on('submit', 'form', function() {
				$(this).find(FormStorage.selector).each(function() {
					localStorage.removeItem(FormStorage.key(this));
				})
			});

			$(document).on('click', 'form .reset', function() {
				$(this).closest('form').find(FormStorage.selector).each(function() {
					localStorage.removeItem(FormStorage.key(this));
				});
			});
		}
	},

	restore: function() {
		if (window.localStorage) {
			$(FormStorage.selector).each(function() {
				var value = localStorage.getItem(FormStorage.key($(this)));

				if (value.length > 0) {
					$(this).val(value);
				}
			});
		}
	}

}
