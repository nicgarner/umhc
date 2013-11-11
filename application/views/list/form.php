<?= $page ?>

<? if(validation_errors()): ?>
  <div class="form_errors" style="width:424px">
    <h3>Please enter a valid email address.</h3>
    <? $list['email'] = $_POST['email'];
       $list['action'] = $_POST['action']; ?>
  </div>
<? endif ?>

<? if($action == 'subscribe'): ?>
  <div class="form_confirm">
    <h3>The subscription process has started</h3>
    Please check your inbox for confirmation. Some email providers, such as Hotmail, may put this confirmation message in your spam folder. <strong>Thank you for subscribing!</strong>
  </div>
<? elseif($action == 'unsubscribe'): ?>
  <div class="form_confirm">
    <h3>The unsubscribe process has started.</h3>
    Please check your inbox for confirmation. Some email providers, such as Hotmail, may put this confirmation message in your spam folder. <strong>We're sorry to see you leave!</strong>
  </div>
<? elseif($action == 'fail'): ?>
  <div class="form_errors">
    <h3>Something went wrong.</h3>
    Something went wrong while trying to process your request. Please try again, or email <a href="mailto:webmaster@umhc.org.uk">webmaster@umhc.org.uk</a> for further help.
  </div>
<? endif ?>

<?= form_open('mailing-list', array('class' => 'mailingListForm')) ?>

<div class="label">Your email address:</div>
<input id="email" name="email" type="text" size="30"<? if (isset($list)) echo ' value="'.$list['email']; ?>" /><br/>

<input name="action" type="radio" class="radio" value="subscribe"
  <? if ((isset($list) && $list['action'] == 'subscribe') || !isset($list))
       echo 'checked="true"'; ?> /><div class="label">Subscribe</div>

<input name="action" type="radio" class="radio" value="unsubscribe"
  <? if (isset($list) && $list['action'] == 'unsubscribe')
       echo 'checked="true"'; ?> /><div class="label">Unsubscribe</div><br/>
<!--
<input name="confirm" type="checkbox" value="confirm"
 <? if (isset($list) && $list['confirm'] == 'confirm')
      echo 'checed="true"'; ?> /><div class="label"><small><small>I understand that to hike with UMHC I must wear hiking boots with ankle support and waterproof clothing. (For more information, read the <a href="crucial-guide">Crucial Guide</a>.)</small></small></div>
-->
<input type="submit" class="button" name="Submit" value="Submit">
</form>

<ul>
<li>The mailing list archives can be found 
  <a href="http://lists.umhc.org.uk/pipermail/umhc_club/" target="_blank">here</a>.</li>
<li>For committee members the committee list archives are 
  <a href="http://lists.umhc.org.uk/cgi-bin/mailman/private/umhc_ctte/" target="_blank">here</a>.</li>
</ul>
