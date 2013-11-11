<h2><?= $title ?></h2>

<? if(validation_errors()): ?>
  <div class="form_errors">
    <h3>The announcement couldn't be saved because of the following error(s):</h3>
	  <?= validation_errors() ?>
    <?  $announcement['content'] = $_POST['content'];
		    $announcement['start_date'] = $_POST['start_date'];
		    $announcement['end_date'] = $_POST['end_date'];
    ?>
  </div>
<? endif ?>

<?= form_open('announcements/' + $form) ?>

	<p><label for="content">Announcement:</label> 
	<textarea name="content" cols="90" rows="2"><?if (isset($announcement)) echo $announcement['content'];?></textarea></p>
  
  <p><label for="start_date">Start date:</label>
	<input type="input" name="start_date" size="10" value="<? if (isset($announcement)) echo $announcement['start_date']; else echo date("d m Y"); ?>" />
  <span class="note"><tt>dd mm yyyy</tt></span></p>
  
  <p><label for="end_date">End date:</label>
	<input type="input" name="end_date" size="10"<? if (isset($announcement)) echo ' value="'.$announcement['end_date']; ?>" />
  <span class="note"><tt>dd mm yyyy</tt></span></p>
  
	<p>
    <input type="submit" name="submit" value="<?= $submit ?>" />
    <a href="/announcements">Cancel</a>
  </p>
  
</form>