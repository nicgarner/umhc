<div class="socials">
<?php

foreach($socials as $social)
{
	
	echo "<div class=\"event_cell event_cell_end\"";
	if ($social["image"] != null)
	{ 
		$image = $social["image"];
		echo " style=\"background:url(/images/socials/$image)\"";
	}
	echo ">";
	
	if ($social["cancelled"])
	{
		echo "<div class=\"cancelled_notice right\">CANCELLED</div>";
		echo "<span class=\"event_cancelled\">";
	}
	
	if ($social["facebook"] || $social["email"])
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
		echo "</div>";
	}
	
	echo "<h4>" . $social["title"] . "</h4>";
  
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
}
?>
</div>