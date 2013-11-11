<div class="hikes">
<?
	if (is_numeric($page["content"]))
	{
		
		echo "<h2>Hike programme ".$page["content"]."/".($page["content"]+1)."</h2>";
		echo "<p><strong>You can also see the <a href=\"".base_url()."hikes\">hike programme for the current academic year</a>.</strong></p>";
	}
	else
		echo $page;
    
  if (isset($_SESSION['username']))
  {
    echo '<a href="/hikes/new" title="Add a new hike" class="admin_button">';
    echo '<img src="'.base_url().'images/design/icons/add.png">';
    echo '<div class="link">Add a new hike</div>';
    echo '</a>';
    echo '<div class="clear"></div>';
  }

	if($hikes)
	{
		$semester = 1;
		$col = 1;
		$previousHikesYear = 1;
		foreach($hikes as $hike)
		{
			// if the year of the previous hike is less than the year of the
			// current hike, output a semester heading
			if ($previousHikesYear < substr($hike['testdate'], 0, 4))
			{
				echo '<h3 class="clear">Semester ' . $semester . ' hikes</h3>';
				$semester++;
				$col = 1;
			}
			// if the month of the previous hike is before June and this hike is after, we're 
			// at the first of some summer hikes, so output a heading
			elseif ($previousHikesMonth <= 5 && intval(substr($hike["testdate"], 4, 2)) >= 6 &&
              $previousHikesYear == substr($hike["testdate"], 0, 4))
			{
				echo '<h3 class="clear">Summer hikes</h3>';
				echo '<p>Please note that any trips planned over the summer vacation are subject to a sprinkling of 
				         uncertainty and last-minuteness, so treat dates with suspicion as what actually occurs may be 
								 very different. We will keep you up to date via the <a href="/mailing-list">mailing list</a>. 
								 Signup over the summer will be via email response once you receive information about a trip. 
								 Summer trips will also be cheaper, at around £5, but sadly lower in capacity as we will be using 
								 minibuses instead of a coach.</p>';
				$col = 1;
			}
      if ($hike['deleted'] && !isset($deleted))
      {
        echo '<h3 class="clear">Deleted hikes</h3>';
        echo '<p>These hikes are only visible because you are logged in, they are not generally visible. Click
                 the restore button to put the hike back in the programme.</p>';
        $col = 1;
        $deleted = 1;
      }
      
			echo '<div class="event_cell';
			if($col == 4)
			{
				echo ' event_cell_end';
				$col = 0;
			}
      if ($hike['deleted'] == 1)
        echo ' event_cell_deleted';
			echo '"';
			if ($hike['image'] != null)
			  echo 'style="background:url(../images/hikes/'.$hike['image'].')"';
			echo '>';
			
			if ($hike['cancelled'])
			{
				echo '<div class="cancelled_notice right">CANCELLED</div>';
				echo '<span class="event_cancelled">';
			}
			
			echo '<div class="icons">';
			echo '<a href="/wiki/index.php/'.$hike['location'].'"
							 title="Read more about '.$hike['location'].' on our wiki">'; 
			echo '<img class="icon" src="'.base_url().'images/design/wikipediaicon.png" 
								 height="16" width="16" alt="Wikipedia icon"/></a>';
			if ($hike['email'])
			{
				echo '<a href="/hikes/'.strtolower($hike['slug']).'_'.$hike['slug_date'].'" 
								 title="Read the trip information">';
				echo '<img class="icon" src="'.base_url().'images/design/emailicon.png" 
							 height="16" width="16" alt="Email icon" /></a>';
			}
      
      if (isset($_SESSION['username']))
      {
				echo '<a href="/hikes/edit/'.strtolower($hike['slug']).'_'.$hike['slug_date'].'" 
								 title="Edit hike">';
				echo '<img class="icon" src="'.base_url().'images/design/icons/edit.png" 
							 height="16" width="16" alt="Edit icon" /></a>';
				if ($hike['deleted'])
        {
          echo '<a href="/hikes/restore/'.strtolower($hike['slug']).'_'.$hike['slug_date'].'" 
                   title="Restore hike">';
          echo '<img class="icon" src="'.base_url().'images/design/icons/restore.png" 
                 height="16" width="16" alt="Restore icon" /></a>';
        }
        else
        {
          echo '<a href="/hikes/delete/'.strtolower($hike['slug']).'_'.$hike['slug_date'].'" 
                   title="Delete hike">';
          echo '<img class="icon" src="'.base_url().'images/design/icons/delete.png" 
                 height="16" width="16" alt="Delete icon" /></a>';
          if ($hike["cancelled"])
          {
            echo '<a href="/hikes/uncancel/'.strtolower($hike['slug']).'_'.$hike['slug_date'].'" 
                  title="Uncancel hike">';
            echo '<img class="icon" src="'.base_url().'images/design/icons/uncancel.png" 
                height="16" width="16" alt="Uncancel icon" /></a>';
          }
          else
          {
            echo '<a href="/hikes/cancel/'.strtolower($hike['slug']).'_'.$hike['slug_date'].'" 
                  title="Cancel hike">';
            echo '<img class="icon" src="'.base_url().'images/design/icons/cancel.png" 
                height="16" width="16" alt="Cancel icon" /></a>';
          }
        }
			}
			echo "</div>";
			
			echo "<h4>" . $hike["location"] . "</h4>";
			echo $hike["area"] . "<br/>";
			echo $hike["date"];
			if ($hike["end_date"] != null)
				echo " to " . $hike["end_date"];
			echo "<br/>";
		  if ($hike["notes"] != null)
				echo $hike["notes"] . "<br />";
			echo $hike["type"] . "<br/>";
			
			if ($hike["cancelled"])
				echo "</span>";
			
			echo "</div>";
			
			// set year and month of this hike to compare at top 
			// of loop and reset blankLines
			$previousHikesYear  = substr($hike["testdate"], 0, 4);
			$previousHikesMonth = substr($hike["testdate"], 4, 2);
			$col++;
		}

	}
  else
		if (is_numeric($page['content']))
			echo '<p>We don\'t have that programme on record.</p>';
		else
			echo '<div class="announcement">We are working hard on this year\'s programme and it will be displayed here soon!</div>';
      
  if (count($archive) > 0)
  {
    echo '<div class="clear"></div>';
    echo '<div class="archive">';
    echo '<h3>Archive</h3>';
    echo '<p>Feeling nostalgia for old times, or wanting to settle a bet? View hike programmes from years gone by!</p>';
    foreach($archive as $link)
      echo '<a href="/hikes/' . $link['year'] . '">' . $link['year'] . '/' . ($link['year']+1) . '</a> ';
    echo '</div>';
  }
?>
</div>