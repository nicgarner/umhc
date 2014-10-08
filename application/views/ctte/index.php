<?php
 	if (is_numeric($page['content']))
	{
		echo '<h2>Committee '.$page["content"].'/'.($page["content"]+1).'</h2>';
		echo '<p><strong>You can also see the <a href="'.base_url().'committee">committee details for the current academic year</a>.</strong></p>';
	}
	else
		echo $page;

	if($ctte_members)
	{
		$previousMembersCategory = '';
		$col = 1;
		echo '<div class="committee">';
		foreach($ctte_members as $member)
		{
			// if the category of the previous member is different to the category of the
			// current member, output a category heading
			if ($previousMembersCategory != $member['category'])
			{
				echo '<h3 class="clear">'.$member['category'].'</h3>';
				$col = 1;
			}
			echo '<div class="committeeMember';
			if ($col == 2)
			{
				echo ' end';
				$col = 0;
			}
			echo '">';
			
			echo '<h4>';
      if ($member['co_role'])
        echo $member['co_role'];
      else
        echo $member['role'];
      echo ': ' . $member['name'] . '</h4>'; 
			
			$test_root = $_SERVER{'DOCUMENT_ROOT'} . '/assets/images/committee/';
			$href_root = base_url() . 'images/committee/';
			$img_path = $member['year'] . '/' . $member['picture_prefix'];
			
			if(file_exists($test_root . $img_path . '1.jpg'))
			{
				echo '<img src="' . $href_root . $img_path . '1.jpg" height="150" width="150" class="right"';
				if(file_exists($test_root . $img_path . '2.jpg'))
				{
				  echo ' onmouseover="this.src=\'' . $href_root . $img_path . '2.jpg\'"';
					echo ' onmouseout="this.src=\'' . $href_root . $img_path.'1.jpg\'"';
				}
				echo ' />';
			}
			else
				echo '<img src="' . $href_root . 'default.jpg" height="150" width="150" class="right" />';	
			
			if ($member['course'])   echo '<strong>Course:</strong> ' .             $member['course'] . '<br/>';
			if ($member['age'])      echo '<strong>Vague age:</strong> ' .          $member['age'] . '<br/>';
			if ($member['spotted'])  echo '<strong>Spotted on hike:</strong> ' .    $member['spotted'] . '<br/>';
			if ($member['mountain']) echo '<strong>Favourite mountain:</strong> ' . $member['mountain'] . '<br/>';
			if ($member['tipple'])   echo '<strong>Tipple:</strong> ' .             $member['tipple'] . '<br/>';
      if ($member['quote'])   echo '<strong>Quote:</strong> "' .             $member['quote'] . '"<br/>';
			if ($member['email'])
			{
				echo 'Contact: <a href="mailto:' . $member['email'] . '@umhc.org.uk">';
				echo $member['email'] . '@umhc.org.uk</a>';
				if ($member['mobile']) 
          echo '<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'          . $member['mobile'] . ' (best to text)';
			}
			elseif ($member['mobile']) echo 'Contact: ' . $member['mobile'];	

			echo '</div>';
			
			// set member's category to compare at top of loop
			$previousMembersCategory = $member['category'];
			$col++;
		}
		echo '</div>';

	}
  else
		if (is_numeric($page["content"]))
			echo '<p>We don\'t have that committee\'s details on record.</p>';
  
  if (count($archive) > 0)
  {
    echo '<div class="clear"></div>';
    echo '<div class="archive">';
    echo '<h3>Archive</h3>';
    echo '<p>Feeling nostalgia for old times, or wanting to settle a bet? View committees from years gone by!</p>';
    foreach($archive as $link)
      echo '<a href="/committee/' . $link['year'] . '">' . $link['year'] . '/' . ($link['year']+1) . '</a> ';
    echo '</div>';
  }
?>