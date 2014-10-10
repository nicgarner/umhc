<?php
 	if (isset($page['content']) && is_numeric($page["content"]))
	{
		
		echo "<h2>Social programme ".$page["content"]."/".($page["content"]+1)."</h2>";
		echo "<p><strong>You can also see the <a href=\"".base_url()."socials\">socials programme for the current academic year</a>.</strong></p>";
	}
	else
		echo $page;
    
  if (isset($_SESSION['username']))
  {
    echo '<a href="/socials/new" title="Add a new social" class="admin_button">';
    echo '<img src="'.base_url().'images/design/icons/add.png">';
    echo '<div class="link">Add a new social</div>';
    echo '</a>';
    echo '<div class="clear"></div>';
  }

	if($socials)
	{
		$semester = 1;
		$col = 1;
		$previousSocialsYear = 1;
		echo "<div class=\"socials\">";
		foreach($socials as $social)
		{
			// if the year of the previous social is less than the year of the
			// current social, output a semester heading
			if ($previousSocialsYear < substr($social["testdate"], 0, 4))
			{
				echo "<h3 class=\"clear\">Semester $semester socials</h3>";
				$semester++;
				$col = 1;
			}
			// if the month of the previous social is before June and this social is after, we're 
			// at the first of some summer socials, so output a heading
			elseif ($previousSocialsMonth <= 5 && intval(substr($social["testdate"], 4, 2)) >= 6 &&
              $previousSocialsYear == substr($social["testdate"], 0, 4))
			{
				echo "<h3 class=\"clear\">Summer socials</h3>";
				echo "<p>Please note that any socials planned over the summer vacation are subject to a sprinkling of 
				         uncertainty and last-minuteness, so treat dates with suspicion as what actually occurs may be 
								 very different. We will keep you up to date via the 
								 <a href=\"/mailing-list\">mailing list</a>.</p>";
				$col = 1;
			}
      if ($social['deleted'] && !isset($deleted))
      {
        echo '<h3 class="clear">Deleted socials</h3>';
        echo '<p>These socials are only visible because you are logged in, they are not generally visible. Click
                 the restore button to put the social back in the programme.</p>';
        $col = 1;
        $deleted = 1;
      }
      
			echo "<div class=\"event_cell";
			if($col == 4)
			{
				echo " event_cell_end";
				$col = 0;
			}
      if ($social['deleted'] == 1)
        echo ' event_cell_deleted';
			echo "\"";
			if ($social["image"] != null)
			{ 
				$image = $social["image"];
			  echo "style=\"background-image:url(../images/socials/$image)\"";
			}
			echo ">";
			
			if ($social["cancelled"])
			{
				echo "<div class=\"cancelled_notice right\">CANCELLED</div>";
				echo "<span class=\"event_cancelled\">";
			}
			
			if ($social["facebook"] || $social["email"] || isset($_SESSION['username']))
			{
				echo "<div class=\"icons\">";
				if ($social["email"])
				{
					echo "<a href=\"/socials/".$social["slug"]."_".$social["slug_date"]."\" title=\"Read the information\">";
					echo "<img class=\"icon\" src=\"".base_url()."images/design/emailicon.png\" 
										 height=\"16\" width=\"16\" alt=\"Email icon\"/></a>";
				}
				if ($social["facebook"])
				{
					echo "<a href=\"".$social["facebook"]."\" 
									 target=\"facebook\" title=\"Join the event on Facebook\">";
					echo "<img class=\"icon\" src=\"".base_url()."images/design/facebookicon.gif\" 
									 height=\"16\" width=\"16\" alt=\"Facebook icon\" /></a>";
				}
        
        if (isset($_SESSION['username']))
        {
          echo '<a href="/socials/edit/'.strtolower($social['slug']).'_'.$social['slug_date'].'" 
                   title="Edit social">';
          echo '<img class="icon" src="'.base_url().'images/design/icons/edit.png" 
                 height="16" width="16" alt="Edit icon" /></a>';
          if ($social['deleted'])
          {
            echo '<a href="/socials/restore/'.strtolower($social['slug']).'_'.$social['slug_date'].'" 
                     title="Restore social">';
            echo '<img class="icon" src="'.base_url().'images/design/icons/restore.png" 
                   height="16" width="16" alt="Restore icon" /></a>';
          }
          else
          {
            echo '<a href="/socials/delete/'.strtolower($social['slug']).'_'.$social['slug_date'].'" 
                     title="Delete social">';
            echo '<img class="icon" src="'.base_url().'images/design/icons/delete.png" 
                   height="16" width="16" alt="Delete icon" /></a>';
            if ($social["cancelled"])
            {
              echo '<a href="/socials/uncancel/'.strtolower($social['slug']).'_'.$social['slug_date'].'" 
                    title="Uncancel social">';
              echo '<img class="icon" src="'.base_url().'images/design/icons/uncancel.png" 
                  height="16" width="16" alt="Uncancel icon" /></a>';
            }
            else
            {
              echo '<a href="/socials/cancel/'.strtolower($social['slug']).'_'.$social['slug_date'].'" 
                    title="Cancel social">';
              echo '<img class="icon" src="'.base_url().'images/design/icons/cancel.png" 
                  height="16" width="16" alt="Cancel icon" /></a>';
            }
          }
        }
        
				echo "</div>";
			}
			
			echo '<h4>' . $social['title'] . '</h4>';
			
      if ($social['location'] != null)
        echo $social['location'];
      else
        echo 'Location to be confirmed';
      
      echo '<br/>';
      
			echo $social['date'];
      
      if ($social['time'] != '00:00')
			  echo ', ' . $social['time'];
        
      echo '<br/>';
			
			if ($social["cancelled"])
				echo "</span>";

			echo "</div>";
			
			// set year and month of this hike to compare at top 
			// of loop and reset blankLines
			$previousSocialsYear  = substr($social["testdate"], 0, 4);
			$previousSocialsMonth = substr($social["testdate"], 4, 2);
			$col++;
		}
		echo "</div>";

	}
  else
		if (is_numeric($page["content"]))
			echo "<p>We don't have that programme on record.</p>";
		else
			echo "<div class=\"announcement\">We are working hard on this year's programme and it will be displayed here soon!</div>";
      
  if (count($archive) > 0)
  {
    echo '<div class="clear"></div>';
    echo '<div class="archive">';
    echo '<h3>Archive</h3>';
    echo '<p>Feeling nostalgia for old times, or need an alibi for court? View social programmes from years gone by!</p>';
    foreach($archive as $link)
      echo '<a href="/socials/' . $link['year'] . '">' . $link['year'] . '/' . ($link['year']+1) . '</a> ';
    echo '</div>';
  }
?>