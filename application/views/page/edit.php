<h2><?= $title ?></h2>

<? if(validation_errors()): ?>
  <div class="form_errors">
    <h3>The page couldn't be saved because of the following error(s):</h3>
	  <?= validation_errors() ?>
    <?  $page['parent'] = $_POST['parent']; ?>
  </div>
<? endif ?>

<?= form_open('pages/' + $form) ?>
	<textarea name="content" rows="30" cols="113" /><? if (isset($page)) echo $page['content']; ?></textarea>
  
	<p>
    <input type="submit" name="submit" value="<?= $submit ?>" />
    <a href="/<?= $page['slug'] ?>">Cancel</a>
  </p>
  
</form>