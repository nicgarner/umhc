<? if ($announcements || isset($_SESSION['username'])): ?>
<div class="announcements">
<? if (isset($_SESSION['username'])): ?>
<div class="right">
  <a href="/announcements" title="Configure announcements" >
    <img src="<?= base_url() ?>images/design/icons/configure.png" />
  </a>
</div>
<? endif ?>
<?
  if ($announcements) {
    echo '<ul>';
    foreach ($announcements as $announcement)
      echo '<li>' . $announcement["content"] . '</li>';
    echo '</ul>';
  }
  else
    echo '<p><small>[ there are no annoucements at the moment ]</small></p>';
?>
</div>
<? endif ?>