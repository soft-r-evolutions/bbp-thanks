=== bbPress Thanks ===
Contributors: ewae
Donate link: https://paypal.me/ewae
Tags: bbpress, voting, vote, rating, rate, topics, replies, up, thanks, stackoverflow, forum
Requires at least: 3.0
Tested up to: 5.4
Stable tag: 1.0.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Let visitors thanks post on your bbPress topics and replies! It is compatible with bbPress Voting.

== Description ==

This simple plugin integrates with the bbPress plugin to add a new feature which allows users to thank post on topics and replies.  Each topic and reply has a total thanks with a button. The thanks can be removed.

This plugin uses AJAX to save the thank on-the-fly without refreshing the page.

It's also AMP compatible!

Visitors can only thanks once on each topic or reply.

== Installation ==

1. Just install and activate

== Frequently Asked Questions ==

= Are there other plugins like this?

Yes, and it is also compatible with the famous bbPress Voting plugin. I needed it to disallow the vote down. I was wanting a plugin to do this, and after much seaching, I came to the conclusion that it didn't exist, so I forked bbPress Voting plugin to do it. I wanted a button smaller than the bbPress Voting plugin. I needed to manage french language too.

= How do you change the "Thanks" and "Not Helpful" labels?

Simply add this code to your child theme's functions.php file...

`add_filter('bbp_voting_helpful', function() {
    return 'Thanks';
});
add_filter('bbp_voting_not_helpful', function() {
    return 'No Thanks';
});`

= How do you remove the "Helpful" and "Not Helpful" labels?

To completely remove the labels, add this code to your child theme's functions.php file...

`add_filter('bbp_voting_show_labels', '__return_false');`

= How do you allow voting only on topics and not replies or vice versa?

Simply add this code to your child theme's functions.php file...

`add_filter( 'bbp_voting_only_topics', '__return_true' );`

Or, vice versa...

`add_filter( 'bbp_voting_only_replies', '__return_true' );`

= How do you hook into the function that saves the vote to write custom code?

This hook fires after a new vote has been saved.

`add_action( 'bbp_voting_voted', 'my_function_on_bbp_vote', 10, 3 );
function my_function_on_bbp_vote( $post_id, $direction, $score ) {
    // Do something
    // $post_id will be the ID of the topic or reply that was voted on
    // $direction will be 1 or -1 representing the upvote or downvote
    // $score will be the new total score
}`

= How do you allow voting only on certain forums?

This function in your child theme's functions.php file will let you choose which forum IDs are allowed to have voting...

`add_filter( 'bbp_voting_allowed_on_forum', 'allowed_voting_forums', 10, 2 );
function allowed_voting_forums( $allowed, $forum_id ) {
    if( in_array( $forum_id, array(123, 124, 125) ) ) {
        $allowed = true;
    } else {
        $allowed = false;
    }
    return $allowed;
}`

= Can you allow administrators to vote repeatedly without limitations?

By default nobody can vote repeatedly on the same topic or reply, but you can enable this only for administrators by adding this filter hook into your child theme's functions.php file...

`add_filter('sort_bbpress_admin_bypass', '__return_true');`

= Can you sort the bbPress replies by their voting score?

Yes, using this plugin, you can now enable the feature that sorts replies by votes simply by adding this filter hook into your child theme's functions.php file...

`add_filter('sort_bbpress_replies_by_votes', '__return_true');`

== Screenshots ==

1. This is what the thanks button and score looks like.

== Changelog ==

= 1.0.0 =
* First version compatible with bbPress Voting.
Contributors: natekinkead
Donate link: https://paypal.me/natekinkead
Tags: bbpress, voting, vote, rating, rate, topics, replies, up, down, stackoverflow, forum
Requires at least: 3.0
Tested up to: 5.4
Stable tag: 1.2.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

Let visitors vote up and down on your bbPress topics and replies just like StackOverflow!

== Description ==

This simple plugin integrates with the bbPress plugin to add a new feature which allows users to vote up or down on topics and replies.  Each topic and reply has a total score with an up arrow and a down arrow.

This plugin uses AJAX to save the vote on-the-fly without refreshing the page.

It's also AMP compatible!

Visitors can only vote once on each topic or reply.

== Installation ==

1. Just install and activate

== Frequently Asked Questions ==

= Are there other plugins like this?

No, and it's the strangest thing.  I was wanting a plugin to do this, and after much seaching, I came to the conclusion that it didn't exist, so I developed one myself.

= How do you change the "Helpful" and "Not Helpful" labels?

Simply add this code to your child theme's functions.php file...

`add_filter('bbp_voting_helpful', function() {
    return 'Thanks';
});
add_filter('bbp_voting_not_helpful', function() {
    return 'No Thanks';
});`

= How do you remove the "Helpful" and "Not Helpful" labels?

To completely remove the labels, add this code to your child theme's functions.php file...

`add_filter('bbp_voting_show_labels', '__return_false');`

= How do you allow voting only on topics and not replies or vice versa?

Simply add this code to your child theme's functions.php file...

`add_filter( 'bbp_voting_only_topics', '__return_true' );`

Or, vice versa...

`add_filter( 'bbp_voting_only_replies', '__return_true' );`

= How do you hook into the function that saves the vote to write custom code?

This hook fires after a new vote has been saved.

`add_action( 'bbp_voting_voted', 'my_function_on_bbp_vote', 10, 3 );
function my_function_on_bbp_vote( $post_id, $direction, $score ) {
    // Do something
    // $post_id will be the ID of the topic or reply that was voted on
    // $direction will be 1 or -1 representing the upvote or downvote
    // $score will be the new total score
}`

= How do you allow voting only on certain forums?

This function in your child theme's functions.php file will let you choose which forum IDs are allowed to have voting...

`add_filter( 'bbp_voting_allowed_on_forum', 'allowed_voting_forums', 10, 2 );
function allowed_voting_forums( $allowed, $forum_id ) {
    if( in_array( $forum_id, array(123, 124, 125) ) ) {
        $allowed = true;
    } else {
        $allowed = false;
    }
    return $allowed;
}`

= Can you allow administrators to vote repeatedly without limitations?

By default nobody can vote repeatedly on the same topic or reply, but you can enable this only for administrators by adding this filter hook into your child theme's functions.php file...

`add_filter('sort_bbpress_admin_bypass', '__return_true');`

= Can you sort the bbPress replies by their voting score?

Yes, using this plugin, you can now enable the feature that sorts replies by votes simply by adding this filter hook into your child theme's functions.php file...

`add_filter('sort_bbpress_replies_by_votes', '__return_true');`

== Screenshots ==

1. This is what the voting and score looks like.

== Changelog ==

= 1.2.5 =
* Don't sort by votes when voting is not allowed on that forum.

= 1.2.4 =
* Added action into the bbp_template_before_lead_topic hook.

= 1.2.3 =
* Added action into the bbp_theme_before_topic_author_details hook.

= 1.2.2 =
* Added vote tracking by user ID when logged in and fallback to IP when not logged in.

= 1.2.1 =
* Fix CSS compatibility issue with bbPress 2.6 RC 7
* Added filter hook for bbp_voting_only_replies

= 1.2.0 =
* New features release!  See the FAQs for several new hooks to access the new features.
* Now AMP compatible
* Double vote tracking now uses IPs rather than browser cookie
* Your current vote shows with a green up arrow or a red down arrow
* Show only on topics, not replies
* Allow voting only on certain forums
* New admin bypass filter

= 1.1.7 =
* Another bug fix with sorting replies by voting score

= 1.1.6 =
* Bug fix with sorting replies by voting score

= 1.1.5 =
* Added filter, sort_bbpress_replies_by_votes, to enable a new feature that sorts replies by voting score

= 1.1.4 =
* Added filter, bbp_voting_show_labels

= 1.1.3 =
* Removed unlimited voting from administrators

= 1.1.2 =
* Fixed javascript bug with repeated votes
* Added Helpful and Not Helpful labels with filters to modify them

= 1.1.1 =
* Added hover effect to show the up and down votes

= 1.1.0 =
* Added tracking of up votes and down votes
* Added hover style on arrows
* Bug fixes

= 1.0.3 =
* Fixed jquery bug

= 1.0.2 =
* Bug fix with nopriv AJAX

= 1.0.1 =
* Bug fix

= 1.0.0 =
* Initial release
