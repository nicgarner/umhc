<div class="hikes">
<?php

foreach($hikes as $hike)
{
	
	echo "<div class=\"event_cell event_cell_end\"";
	if ($hike["image"] != null)
	{ 
		$image = $hike["image"];
		echo " style=\"background:url(/images/hikes/$image)\"";
	}
	echo ">";
	
	if ($hike["cancelled"])
	{
		echo "<div class=\"cancelled_notice right\">CANCELLED</div>";
		echo "<span class=\"event_cancelled\">";
	}
	
	echo "<div class=\"icons\">";
	echo "<a href=\"/wiki/index.php/".$hike["location"]."\" 
					 title=\"Read more about ".$hike["location"]." on our wiki\">"; 
	echo "<img class=\"icon\" src=\"".base_url()."images/design/wikipediaicon.png\" 
						 height=\"16\" width=\"16\" alt=\"Wikipedia icon\"/></a>";
	if ($hike["email"])
	{
		echo "<a href=\"/hikes/".strtolower($hike["slug"])."_".$hike["slug_date"]."\" 
						 title=\"Read the trip information\">";
		echo "<img class=\"icon\" src=\"".base_url()."images/design/emailicon.png\" 
					 height=\"16\" width=\"16\" alt=\"Email icon\" /></a>";
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
}
?>
</div>