<h2><?= $title ?></h2>

<?php if(validation_errors()): ?>
  <div class="form_errors">
    <h3>The user couldn't be saved because of the following error(s):</h3>
	  <?= validation_errors() ?>
    <?php  if (array_key_exists('username', $_POST))
          $user['username'] = $_POST['username'];
        $user['email_address'] = $_POST['email_address'];
        $user['first_name'] = $_POST['first_name'];
		    $user['last_name'] = $_POST['last_name'];
    ?>
  </div>
<?php endif ?>

<?= form_open('users/' + $form) ?>

	<?php if ($form == 'new'): ?>
    <p><label for="username">Username:</label> 
    <input type="input" name="username" size="40"<?php if (isset($user)) echo ' value="'.$user['username']; ?>" /></p>
  <?php endif ?>
  
  <p><label for="email_address">Email address:</label> 
	<input type="input" name="email_address" size="40"<?php if (isset($user)) echo ' value="'.$user['email_address']; ?>" /></p>

	<p><label for="first_name">First name:</label> 
	<input type="input" name="first_name" size="40"<?php if (isset($user)) echo ' value="'.$user['first_name']; ?>" /></p>

	<p><label for="last_name">Last name:</label> 
	<input type="input" name="last_name" size="40"<?php if (isset($user)) echo ' value="'.$user['last_name']; ?>" /></p>
  
  <p><label for="password">Password:</label> 
  <input type="password" name="password" size="40" /></p>

  <p><label for="confirm_password">Confirm password:</label> 
  <input type="password" name="confirm_password" size="40" /></p>
  
	<p>
    <input type="submit" name="submit" value="<?= $submit ?>" />
    <a href="/users">Cancel</a>
  </p>
  
</form>