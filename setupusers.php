<?php //setupusers.php
  require_once 'login.php';
  $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

  if ($connection->connect_error) die($connection->connect_error);

  $query = "CREATE TABLE users (
    forename VARCHAR(32) NOT NULL,
    surname  VARCHAR(32) NOT NULL,
    username VARCHAR(32) NOT NULL UNIQUE,
    password VARCHAR(32) NOT NULL
  )";
  $result = $connection->query($query);
  if (!$result) die($connection->error);

  $salt1    = "qm&h*";
  $salt2    = "pg!@";

  $forename = 'Bill';
  $surname  = 'Smith';
  $username = 'user1';
  $password = 'user1';
  $token    = hash('ripemd128', "$salt1$password$salt2");

  add_user($forename, $surname, $username, $token);

  $forename = 'Pauline';
  $surname  = 'Jones';
  $username = 'user2';
  $password = 'user2';
  $token    = hash('ripemd128', "$salt1$password$salt2");

  add_user($forename, $surname, $username, $token);

  $forename = 'Jason';
  $surname  = 'Kwon';
  $username = 'user3';
  $password = 'user3';
  $token    = hash('ripemd128', "$salt1$password$salt2");

  add_user($forename, $surname, $username, $token);
  
  $forename = 'William';
  $surname  = 'Kwon';
  $username = 'admin1';
  $password = 'admin1';
  $token    = hash('ripemd128', "$salt1$password$salt2");

  add_user($forename, $surname, $username, $token);
  
  function add_user($fn, $sn, $un, $pw)
  {
    global $connection;

    $query  = "INSERT INTO users VALUES('$fn', '$sn', '$un', '$pw')";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
  }
?>