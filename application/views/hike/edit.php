<h2><?= $title ?></h2>

<?php if(validation_errors()): ?>
  <div class="form_errors">
    <h3>The hike couldn't be saved because of the following error(s):</h3>
	  <?= validation_errors() ?>
    <?php  $hike['location'] = $_POST['location'];
        $hike['area'] = $_POST['area'];
		    $hike['date'] = $_POST['date'];
		    $hike['end_date'] = $_POST['end_date'];
				$hike['type'] = $_POST['type'];
				$hike['notes'] = $_POST['notes'];
        $hike['image'] = $_POST['image'];
        $hike['email'] = $_POST['email'];
		    $hike['add_another'] = (array_key_exists('options',$_POST) ? 'on' : NULL);
    ?>
  </div>
<?php endif ?>

<?= form_open('hikes/' + $form) ?>

	<p><label for="location">Location:</label> 
	<input type="input" name="location" size="30"<?php if (isset($hike)) echo ' value="'.$hike['location']; ?>" /></p>

	<p><label for="area">Area:</label> 
	<input type="input" name="area" size="30"<?php if (isset($hike)) echo ' value="'.$hike['area']; ?>" />
  <span class="note">E.g. "Lake District", "Cairngorms"...</span></p>
  
  <p><label for="date">Date:</label>
	<input type="input" name="date" size="10"<?php if (isset($hike)) echo ' value="'.$hike['date']; ?>" />
  <span class="note"><tt>dd mm yyyy</tt></span></p>
  
  <p><label for="end_date">End date:</label>
	<input type="input" name="end_date" size="10"<?php if (isset($hike)) echo ' value="'.$hike['end_date']; ?>" />
  <span class="note"><tt>dd mm yyyy</tt>. Leave blank for day trip.</span></p>
  
  <p><label for="type">Type:</label> 
  <?php
   if (isset($hike))
    echo form_dropdown('type', $types, $hike['type']);
  else
    echo form_dropdown('type', $types);
  ?>
  
  <p><label for="notes">Note:</label>
	<input type="input" name="notes" size="30"<?php if (isset($hike)) echo ' value="'.$hike['notes']; ?>" />
  <span class="note">Any additional note on the trip, e.g. "Night hike", "Skills hike"...</span></p>
  
  
  <p><label for="email">Email:</label>
	<input type="input" name="email" size="30"<?php if (isset($hike)) echo ' value="'.$hike['email']; ?>" />
  <span class="note">Email URL from <a href="http://lists.umhc.org.uk/pipermail/umhc_club/"
                     target="umhc_club_archive" title="opens in new window">list archive</a>, e.g. 
                     <tt>2012-July/000821.html</tt></span></p>
  
  <label for="image">Background:</label>
  <a href="#" class="show" id="show" onclick="document.getElementById('images').style.display='block';
                                              document.getElementById('show').style.display='none';
                                              document.getElementById('hide').style.display='block';">
                                                [show options]</a>
  <a href="#" class="hide" id="hide" onclick="document.getElementById('images').style.display='none';
                                              document.getElementById('show').style.display='block';
                                              document.getElementById('hide').style.display='none';">
                                                [hide options]</a>
  <p class="note">The image shown on the background of the hike's cell on the hikes page.</p>
  <div class="images" id="images">
  <?php
   echo '<div class="event_cell">';
  if (isset($hike))
    if ($hike['image'] == 'none' || $hike['image'] == null)
      echo form_radio('image', 'none', true);
    else
      echo form_radio('image', 'none', false);
  else
    echo form_radio('image', 'none', true);
  echo ' No background</div>';
  $col = 2;
  foreach ($images as $image)
  {
    echo '<div class="event_cell';
    if ($col == 4) { echo ' event_cell_end'; $col = 0; }
    echo '" style="background:url(/images/hikes/'.$image.')">';
    if (isset($hike))
      if ($hike['image'] == $image)
        echo form_radio('image', $image, true);
      else
        echo form_radio('image', $image, false);
    else
      echo form_radio('image', $image, false);
      
    echo ' '.$image.'</div>';
    $col++;
  }
  ?>
  </div>
  
	<p>
    <input type="submit" name="submit" value="<?= $submit ?>" />
    <a href="/hikes">Cancel</a>
     <?php if ($form == 'new')
        {
          echo '<br />';
          if (isset($hike))
            if (array_key_exists("options",$_POST))
              echo form_checkbox('options', 'add_another', true);
            else
              echo form_checkbox('options', 'add_another', false);
          else
            echo form_checkbox('options', 'add_another', true);
          echo 'Add another hike after creating this one';
        }
     ?>
  </p>
  
</form>