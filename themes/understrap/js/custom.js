(function ($) {
	$(document).ready(function () {
		// if navigation button is clicked add class nav-open
		$('.nav-button').click(function () {
			$('body').toggleClass('nav-open');
		});

		$("ul.dropdown-menu [data-toggle='dropdown']").on("click", function (event) {

			event.stopPropagation();

			$(this).siblings().toggleClass("show");
		});

		$(document).click(function (event) {
			if ($('body').hasClass('nav-open')) {
				console.log('body has class');
				if (!($(event.target).hasClass("main-menu") || $(event.target).is('#nav-icon3') || $(event.target).hasClass('side-panel-btn'))) {
					$('body').toggleClass('nav-open');
				}
			}
		});


		// handles animations for replicated sitescard
		$('.replicated-view-profile').click(function(){
			$('.replicated-profile-text-show').toggleClass("hide");
			$('.replicated-profile-text-hide').toggleClass("show");

			$('.replicated-rep-info').toggleClass("open");
			$('.arrow').toggleClass('up');
		});

		// handles ajax call for bp-finder
		$('#bp-finder-form').submit(function(e){
			e.preventDefault();

			var form = $(this);
			var id = $('#bp-finder-form').find('#id').val();

			$.ajax({
				url: 'https://api.kyani.net/rep/'+ id + '/verify',
				type: 'POST',
				data: form.serialize()
			}).done(function(data){
				if(data.valid === true){
					$('#bp-finder-msg-text').text("회원정보가 정확합니다");
					$('#bp-finder-msg-text').css("color", "green");
				} else {
					$('#bp-finder-msg-text').text("정보가 정확하지 않습니다");
					$('#bp-finder-msg-text').css("color", "red");
				}
			}).fail(function(data){
				console.log(data);
			});
		});

		// resets bp finder modal form data when a user closes the modal
		$('#bp-finder-modal').on('hidden.bs.modal', function () {
			$('#bp-finder-form').find('#id').val("");
			$('#bp-finder-form').find('#fname').val("");
			$('#bp-finder-form').find('#lname').val("");

			$('#bp-finder-msg-text').text("");
		});
	});
})(jQuery);

(function ($) {
	$('input.assessment-checkbox').on('change', function (evt) {
		if ($('input.assessment-checkbox:checked').length > 3) {
			this.checked = false;
			$(this).is(':checked').parent().toggleClass('active');
		}
	});
})(jQuery);

(function ($) {
	$('#assessment-form').submit(function (e) {
		if ($('div.checkbox-group.required :checkbox:checked').length < 3) {
			e.preventDefault(e);
			$('.assessment-item-sub-header').css('color', '#DC3545');
		} else {
			$('.assessment-item-sub-header').css('color', '#28A745');
		}
	});
})(jQuery);


