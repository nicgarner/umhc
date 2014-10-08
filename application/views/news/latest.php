<div class="newstile">
<?php
 foreach($news as $news_item)
{
	echo '<h3><a href="/news">' . $news_item['title'] . '</a></h3>';
	echo '<em>' . $news_item['date'] . '</em>';
	echo word_limiter($news_item['content'], 12);
  
}
?>
</div>
<p><strong>See all our news on the <a href="/news">news page</a>.</strong>