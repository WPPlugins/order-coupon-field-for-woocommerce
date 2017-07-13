<?php
/**
 * Author: Potent Plugins
 * License: GNU General Public License version 2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 */
 ?>
 <div style="background-color: #ff6600; max-width: 500px; text-align: center; padding: 20px; color: #fff;">
	<a href="https://potentplugins.com/?utm_source=<?php echo($potent_slug); ?>&amp;utm_medium=link&amp;utm_campaign=wp-plugin-credit-link" target="_blank">
	<img src="<?php echo(plugins_url('images/potent-logo.png', __FILE__)); ?>" alt="Potent Plugins" style="max-width: 100%;" />
	</a>
	<div style="margin-bottom: 10px; font-size: 1.2em;">
		<strong>If you like our free plugin, please:</strong>
	</div>
	<div style="margin-bottom: 10px;">
		<a href="https://wordpress.org/support/view/plugin-reviews/<?php echo($potent_slug); ?>" target="_blank" class="button-secondary">Write a Review</a>
		<a href="https://potentplugins.com/donate/?utm_source=<?php echo($potent_slug); ?>&amp;utm_medium=link&amp;utm_campaign=wp-plugin-credit-donate-link" target="_blank" class="button-secondary">Make a Donation</a>
	</div>
	<div style="margin-bottom: 10px;">
		<div class="fb-page" data-href="http://facebook.com/potentplugins" data-width="250" data-height="70" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="http://facebook.com/potentplugins"><a href="http://facebook.com/potentplugins">Potent Plugins</a></blockquote></div></div>
	</div>
	<div>
		<a href="https://twitter.com/potentplugins" class="twitter-follow-button" data-show-count="false" data-size="large" data-dnt="true">Follow @potentplugins</a>
	</div>
</div>

<!-- FB -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<!-- Twitter -->
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>