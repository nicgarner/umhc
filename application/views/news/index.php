<?= $page ?>
<div class="news">

<?php
 if(count($news) > 0)
{
  echo '<div class="pagination">' . $links . '</div>';
  foreach($news as $news_item)
  {
    $link = '/news/'.$news_item['slug'];
    echo '<div class="news_item"><div class="headline">';
    echo '<h3>' . $news_item['title'] . '</h3>';
    echo '<p><em>' . $news_item['date'].'</em></p>';
    echo '</div><div class="content">';
    echo $news_item['content'];
    echo '</div></div>';
  }
  echo '<div class="pagination">' . $links . '</div>';
}
else
  echo 'No news here! <a href="/news">Go back to the first page.</a>';
?>
<div class="clear"></div>
</div>