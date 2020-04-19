var previous;

function bbpress_post_thank_link_clicked(post_id, direction) {
    var data = {
        'action': 'bbpress_post_thank_link_clicked',
        'post_id': post_id,
        'direction': direction,
    };
    jQuery.post(ajax_object.ajax_url, data, function(response){
        if(response !== 0) {
            console.log('Voted ' + direction + ' for ' + post_id + ': ' + response);
            jQuery('.post-' + post_id + '.topic-author .bbp-thanks, .post-' + post_id + '.type-reply .bbp-thanks').each(function() {
                var thanks_el = jQuery(this);
                if (thanks_el.hasClass("pending") == false) {
		    if (previous == direction) {
			console.log("Cancel vote");
                    	thanks_el.removeClass("pending");
			return;
	            } else {
			previous = direction;
                    }
                    if(direction > 0) {
                    	set_vote_processing(post_id);

	            	var score_el = jQuery(this).find('.bbp-thanks-button');
                    	var score_number = score_el.text().split()[0];
                    	var score = parseInt(score_number) + direction;
                    	//score_el.html(score + " thanks!");

                        thanks_el.addClass("voted-up");
                        var newContent = '<a class="bbp-thanks-button remove" title="Remove thanks." onclick="bbpress_post_thank_link_clicked(' + post_id + ', -1); return false;">' + score + ' thanks!</a>';
                        thanks_el.html(newContent);
                    	thanks_el.removeClass("pending");
                    } else if (direction < 0) {
                    	set_vote_processing(post_id);

	            	var score_el = jQuery(this).find('.bbp-thanks-button');
                    	var score_number = score_el.text().split()[0];
                    	var score = parseInt(score_number) + direction;
                    	//score_el.html(score + " thanks!");

                        thanks_el.removeClass("voted-up");
                        var newContent = '<a class="bbp-thanks-button up" title="Say Thank you!" onclick="bbpress_post_thank_link_clicked(' + post_id + ', 1); return false;">' + score + ' thanks!</a>';
                        thanks_el.html(newContent);
                    	thanks_el.removeClass("pending");
                    }
                    console.log('Score: '+score);
                }
            });
        } else {
            console.log('Already thanks.');
        }
    });
}

function set_vote_processing(post_id) {
    jQuery('.post-' + post_id + '.topic-author .bbp-thanks, .post-' + post_id + '.type-reply .bbp-thanks').each(function() {
        var thanks_el = jQuery(this);
        thanks_el.addClass("pending");
    });
}
