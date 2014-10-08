<?php
   if (isset($_SESSION['username']))
  {
    echo '<div class="right"><a href="/pages/edit/'.$slug.'" title="Edit page" class="admin_button">';
    echo '<img src="'.base_url().'images/design/icons/edit.png">';
    echo '<div class="link">Edit page</div>';
    echo '</a>';
    echo '<div class="clear"></div></div>';
  }
?>
<?= $content ?>
<?php
  if(isset($tiles))
  {
    echo "<div class=\"tiles\">";
    $col = 1;
		foreach($tiles as $tile)
		{
			echo "<div class=\"UMHContentbox ".$tile["style"]."\">";
			if ($tile["link"] && $tile["image"])
			{
				echo "<a href=\"/".$tile["link"]."\">";
				echo "<img class=\"btn\" src=\"/images/design/".$tile["image"]."\" title=\"".$tile["name"]."\" 
				           width=\"210\" height=\"121\" /></a>";				 
			}
			echo $tile["content"];
			if(isset($tile["extra"]))
				echo $tile["extra"];
			echo "</div>";
			if ($col == 4) echo "<div class=\"clear\"></div></div>";
			$col++;
    }
  }

?>