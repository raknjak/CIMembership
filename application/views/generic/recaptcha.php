<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
  var RecaptchaOptions = {
    theme:"<?php print $theme ?>",
    lang:"<?php print $lang ?>"
  };
</script>
<script type="text/javascript" src="<?php print $server ?>/challenge?k=<?php print $key.$errorpart ?>"></script>
<noscript>
		<iframe src="<?php print $server ?>/noscript?lang=<?php print $lang ?>&amp;k=<?php print $key.$errorpart ?>" height="300" width="500" class="recaptcha_iframe"></iframe><br/>
		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
		<input type="hidden" name="recaptcha_response_field" value="manual_challenge" />
</noscript>