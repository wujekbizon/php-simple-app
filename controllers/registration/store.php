<?php

use Core\App;
use Core\Database;
use Core\Validator;

$email = $_POST['email'];
$password = $_POST['password'];

$errors = [];

if (! Validator::email($email)) {
  $errors['email'] = 'Please provide a valid email address';
}

if (! Validator::string($password, 8, 255)) {
  $errors['password'] = 'Please provide a password of at least seven characters.';
}


if (! empty($errors)) {

  return view("registration/create.view.php", [
    'errors' => $errors
  ]);
}


$db = App::resolve(Database::class);

// check if the account already exists
$user = $db->query('select * from users where email = :email', [
  'email' => $email,
])->find();

// if yes, redirect to login page.
if ($user) {

  header('location: /');
  exit();
} else {

  // If not, save one to the database, and then log the user in, and redirect.

  $db->query('INSERT INTO `myapp`.`users` (`email`, `password`) VALUES (:email, :password)', [
    'email' => $email,
    'password' => password_hash($password, PASSWORD_BCRYPT)
  ]);

  login($user);

  header('location: /');
  exit();
}
