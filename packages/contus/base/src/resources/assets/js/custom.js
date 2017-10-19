$(document).ready(function() {
	 
	$('#close-tpabel').on('click', function() {
		$('.top-news-section').slideUp();
	});
	$(window).scroll(function() {
		if ($(this).scrollTop() > 300) {
			$('.goTop').fadeIn(500);
		} else {
			$('.goTop').fadeOut(500);
		}
	});

	// Animate the scroll to top
	$('#scrolltop').click(function(event) {
		event.preventDefault();

		$('html, body').animate({
			scrollTop : 0
		}, 300);
	})
	$("#currentaffiars-slider,.playlist-collections-slider").owlCarousel({
		loop : true,
		dots:false,
		nav : true,
		margin : 15,
		autoplay : true,
		mouseDrag : true,
		responsive : {
			0 : {
				items : 1
			},
			600 : {
				items : 2
			},
			700 : {
				items : 3
			},
			992 : {
				items : 4,
				loop : false
			}
		}

	});

	$("#scheduled-filteredvideos").owlCarousel({
		loop : true,
		nav : true,
		dots : false,
		margin : 10,
		autoplay : true,
		mouseDrag : true,
		pagination : true,
		responsive : {
			0 : {
				items : 1
			},
			600 : {
				items : 1
			},
			700 : {
				items : 4
			},
			992 : {
				items : 4,
				loop : false
			}
		}
	});
	 
	/* list and grid view */
	$('#list').click(function(event) {
		event.preventDefault();
		$('#products .item').addClass('list-group-item');
	});
	$('#grid').click(function(event) {
		event.preventDefault();
		$('#products .item').removeClass('list-group-item');
		$('#products .item').addClass('grid-group-item');
	});

	$(".settext-count,.live-video-name").each(function() {
		var settext = $(this).text().length;
		if (settext > 48) {
			$(this).text($(this).text().substr(0, 48) + '...');
		}
	});
	$('.show-more-content').on('click', function(){
		$('.video-comments .panel-body p.extend').css('height','auto');
	
});
	/* Accordion */
	var acc = document.getElementsByClassName("accordion");
	var i;

	for (i = 0; i < acc.length; i++) {
		acc[i].onclick = function() {
			this.classList.toggle("active");
			this.nextElementSibling.classList.toggle("show");
		}
	}
});