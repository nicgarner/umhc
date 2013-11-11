<h2>Configure announcements</h2>

<a href="/announcements/new" title="Add a new announcement" class="admin_button">
<img src="<?= base_url() ?>images/design/icons/add.png">
<div class="link">Add a new announcement</div>
</a>
<div class="clear"></div>

<? if(count($future) > 0): ?>
<h3>Future announcements</h3>
<p>Announcements scheduled to appear in the future.</p>
<ul class="admin_announcements">
<? foreach($future as $announcement): ?>
  <li>  
    <a href="/announcements/edit/<?= $announcement['id'] ?>" title="Edit announcement">
      <img src=<?= base_url() ?>images/design/icons/edit.png width="16" height="16" /></a>
    <a href="/announcements/delete/<?= $announcement['id'] ?>" title="Delete announcment">
      <img src=<?= base_url() ?>images/design/icons/delete.png width="16" height="16" /></a>
    <strong><?= $announcement['content'] ?></strong> (starts <?= $announcement['start'] ?>)
  </li>
<? endforeach ?>
</ul>
<? endif ?>

<? if(count($current) > 0): ?>
<h3>Current announcements</h3>
<p>Announcements currently displayed on the site.</p>
<ul class="admin_announcements">
<? foreach($current as $announcement): ?>
  <li>
    <a href="/announcements/edit/<?= $announcement['id'] ?>" title="Edit announcement">
      <img src=<?= base_url() ?>images/design/icons/edit.png width="16" height="16" /></a>
    <a href="/announcements/expire/<?= $announcement['id'] ?>" title="Expire announcment">
      <img src=<?= base_url() ?>images/design/icons/cancel.png width="16" height="16" /></a>
    <strong><?= $announcement['content'] ?></strong> (expires <?= $announcement['end'] ?>)
  </li>
<? endforeach ?>
</ul>
<? endif ?>


<? if(count($old) > 0): ?>
<h3>Old announcements</h3>
<p>Announcements from the past.</p>
<ul class="admin_announcements">
<? foreach($old as $announcement): ?>
  <li><strong><?= $announcement['content'] ?></strong> (expired <?= $announcement['end'] ?>)</li>
<? endforeach ?>
</ul>
<? endif ?>
