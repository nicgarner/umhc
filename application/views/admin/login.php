<h2>Log in</h2>

<? if(validation_errors()): ?>
  <div class="form_errors">
    <h3>You can't log in because:</h3>
    <?= validation_errors(); ?>
  </div>
<? endif ?>

<?= form_open('admin') ?>
<p>
   <?= form_label('Username: ', 'username') ?>
   <?= form_input('username', set_value('username'), 'id="username"'); ?>
</p>

<p>
   <?= form_label('Password: ', 'password') ?>
   <?= form_password('password', '', 'id="password"') ?>
</p>

<p>
  <?= form_submit('submit', 'Log in') ?>
</p>

<p>If you've forgotten your password, speak to the <a href="mailto:webmaster@umhc.org.uk">Website Secretary</a>.</p>

<?= form_close(); ?>