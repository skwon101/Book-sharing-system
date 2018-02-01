<?php // admin.php
  require_once 'login.php';
  $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

  if ($connection->connect_error) die($connection->connect_error);

  if (isset($_POST['delete']) && isset($_POST['username']))
  {
    $username   = get_post($connection, 'username');
    $query  = "DELETE FROM users WHERE username='$username'";
    $result = $connection->query($query);

  	if (!$result) echo "DELETE failed: $query<br>" .
      $connection->error . "<br><br>";
  }
  
  if (isset($_POST['username2']) && isset($_POST['password2']))
  {
	$salt1    = "qm&h*";
	$salt2    = "pg!@";
    $password = get_post($connection, 'password2');
	$token    = hash('ripemd128', "$salt1$password$salt2");
	
    $username   = get_post($connection, 'username2');
    $query  = "UPDATE users SET password = '$token' WHERE username='$username'";
    $result = $connection->query($query);

  	if (!$result) echo "UPDATE failed: $query<br>" .
      $connection->error . "<br><br>";
  }

  if (isset($_POST['forename'])   &&
      isset($_POST['surname'])    &&
      isset($_POST['username'])   &&
      isset($_POST['password'])) 
  {
	$salt1    = "qm&h*";
	$salt2    = "pg!@";
    $forename = get_post($connection, 'forename');
    $surname  = get_post($connection, 'surname');
    $username = get_post($connection, 'username');
    $password = get_post($connection, 'password');
	$token    = hash('ripemd128', "$salt1$password$salt2");
    $query    = "INSERT INTO users VALUES" .
      "('$forename', '$surname', '$username', '$token')";
    $result   = $connection->query($query);

  	if (!$result) echo "INSERT failed: $query<br>" .
      $connection->error . "<br><br>";
  }

  echo <<<_END
  <form action="admin.php" method="post"><pre>
    Forename <input type="text" name="forename">
    Surname  <input type="text" name="surname">
    Username <input type="text" name="username">
    Password <input type="text" name="password">
           <input type="submit" value="ADD RECORD">
  </pre></form>
_END;

  echo <<<_END
  <form action="admin.php" method="post"><pre>
    Username 	 <input type="text" name="username2">
    New Password <input type="text" name="password2">
           <input type="submit" value="REPLACE PASSWORD">
  </pre></form>
_END;

  $query  = "SELECT * FROM users";
  $result = $connection->query($query);

  if (!$result) die ("Database access failed: " . $connection->error);

  $rows = $result->num_rows;
  
  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

    echo <<<_END
  <pre>
    Forename $row[0]
    Surname  $row[1]
    Username $row[2]
    Password $row[3]
  </pre>
  <form action="admin.php" method="post">
  <input type="hidden" name="delete" value="yes">
  <input type="hidden" name="username" value="$row[2]">
  <input type="submit" value="DELETE RECORD">
  </form>
_END;
  }
  
  $result->close();
  $connection->close();
  
  function get_post($connection, $var)
  {
    return $connection->real_escape_string($_POST[$var]);
  }
?>
