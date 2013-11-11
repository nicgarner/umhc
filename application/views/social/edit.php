<h2><?= $title ?></h2>

<? if(validation_errors()): ?>
  <div class="form_errors">
    <h3>The social couldn't be saved because of the following error(s):</h3>
	  <?= validation_errors() ?>
    <?  $social["title"] = $_POST["title"];
        $social["date"] = $_POST["date"];
		    $social["time"] = $_POST["time"];
		    $social["location"] = $_POST["location"];
				$social["map"] = $_POST["map"];
				$social["facebook"] = $_POST["facebook"];
        $social["image"] = $_POST["image"];
        $social["email"] = $_POST["email"];
		    $social["add_another"] = (array_key_exists("options",$_POST) ? "on" : NULL);
    ?>
  </div>
<? endif ?>

<?= form_open("socials/" + $form) ?>

	<p><label for="title">Title:</label> 
	<input type="input" name="title" size="30"<? if (isset($social)) echo ' value="'.$social['title']; ?>" /></p>
  
  <p><label for="date">Date:</label>
	<input type="input" name="date" size="10"<? if (isset($social)) echo ' value="'.$social['date']; ?>" />
  <span class="note"><tt>dd mm yyyy</tt></span></p>
  
  <p><label for="time">Time:</label>
	<input type="input" name="time" size="10"<? if (isset($social) && $social['time'] != '00:00') echo ' value="'.$social['time']; ?>" />
  <span class="note"><tt>hh:mm</tt></span></p>
  
  <p><label for="location">Location:</label>
	<input type="input" name="location" size="30"<? if (isset($social)) echo ' value="'.$social['location']; ?>" /></p>
  
  <p><label for="email">Email:</label>
	<input type="input" name="email" size="30"<? if (isset($social)) echo ' value="'.$social['email']; ?>" />
  <span class="note">Email URL from <a href="http://lists.umhc.org.uk/pipermail/umhc_club/"
                     target="umhc_club_archive" title="opens in new window">list archive</a>, e.g. 
                     <tt>2012-July/000821.html</tt></span></p>
  
  <p><label for="map">Map:</label>
	<input type="input" name="map" size="30"<? if (isset($social)) echo ' value="'.$social['map']; ?>" />
  <span class="note">URL for embedded Google Map</span></p>
  
  <p><label for="facebook">Facebook:</label>
	<input type="input" name="facebook" size="30" <? echo (isset($social) ? 'value="'.$social['facebook'] 
                                                                        : 'value="http://"') ?>" />
  <span class="note">Full URL for Facebook event or other Facebook link (e.g. group, page...)</span></p>
  
  <label for="image">Background:</label>
  <a href="#" class="show" id="show" onclick="document.getElementById('images').style.display='block';
                                              document.getElementById('show').style.display='none';
                                              document.getElementById('hide').style.display='block';">
                                                [show options]</a>
  <a href="#" class="hide" id="hide" onclick="document.getElementById('images').style.display='none';
                                              document.getElementById('show').style.display='block';
                                              document.getElementById('hide').style.display='none';">
                                                [hide options]</a>
  <p class="note">The image shown on the background of the social's cell on the socials page.</p>
  <div class="images" id="images">
  <?
  echo '<div class="event_cell">';
  if (isset($social))
    if ($social['image'] == 'none' || $social['image'] == null)
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
    echo '" style="background:url(/images/socials/'.$image.')">';
    if (isset($social))
      if ($social['image'] == $image)
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
    <a href="/socials">Cancel</a>
     <? if ($form == 'new')
        {
          echo '<br />';
          if (isset($social))
            if (array_key_exists("options",$_POST))
              echo form_checkbox('options', 'add_another', true);
            else
              echo form_checkbox('options', 'add_another', false);
          else
            echo form_checkbox('options', 'add_another', true);
          echo 'Add another social after creating this one';
        }
     ?>
  </p>
  
</form>