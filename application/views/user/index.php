<h2>Manage users</h2>
<p>Active users can administer the site. Use this page to create new users and deactivate old accounts.<br/>
   You can also edit your own account details, including your password.</p>

<a href="/users/new" title="Add a new user" class="admin_button">
  <img src="/images/design/icons/add.png" />
  <div class="link">Add a new user</div>
</a><div class="clear"></div>

<a href="/users/edit/<?= $_SESSION['username'] ?>" title="Edit my account" class="admin_button">
  <img src="/images/design/icons/edit.png" />
  <div class="link">Edit my account</div>
</a>
<div class="clear"></div>

<?
if(count($users) > 0)
{
  echo '<h3>Active users</h3><p>';
  $active = 1;
  foreach($users as $user)
  {
    if ($active != $user['active'])
      echo '</p><h3>Inactive users</h3><p>';
    if ($user['active'])
      echo '<a href="/users/deactivate/' . $user['username'] . '" title="deactivate user"><img src="/images/design/icons/cancel.png" width="16" height="16" /></a> ';
    else
      echo '<a href="/users/activate/' . $user['username'] . '" title="activate user"><img src="/images/design/icons/uncancel.png" width="16" height="16" /></a> ';
    if ($_SESSION['role'] == 1 && $user['active'])
      echo '<a href="/users/edit/' . $user['username'] . '" title="edit user"><img src="/images/design/icons/edit.png" width="16" height="16" /></a> ';
    echo $user['username'];
    echo ' (' . $user['first_name'] . ' ' . $user['last_name'] . ')<br />';
    $active = $user['active'];
  }
  echo '</p>';
}
else
  echo '<p>No users have been created yet. Which is odd, because if that\'s the case, 
        you can\'t be reading this.</p>';
?>