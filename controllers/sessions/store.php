<?php

use Core\App;
use Core\Database;
use Core\Validator;

$email = $_POST['email'];
$password = $_POST['password'];

$db = App::resolve(Database::class);

$errors = [];

if (! Validator::email($email)) {
  $errors['email'] = 'Please provide a valid email address';
}

if (! Validator::string($password)) {
  $errors['password'] = 'Please provide a valid password.';
}

if (! empty($errors)) {

  return view("sessions/create.view.php", [
    'errors' => $errors
  ]);
}

$user = $db->query('select * from users where email = :email', [
  'email' => $email,
])->find();

if ($user) {

  if (password_verify($password, $user['password'])) {
    login([
      'email' => $email,
      'id' => $user['id']
    ]);

    header('location: /');
    exit();
  }
}

return view("sessions/create.view.php", [
  'errors' => [
    'email' => 'No matching account found for the email address and password.'
  ]
]);
