<?php

use Core\App;
use Core\Database;
use Core\Validator;
use Http\Forms\LoginForm;

$email = $_POST['email'];
$password = $_POST['password'];

$db = App::resolve(Database::class);

$form = new LoginForm();

if (! $form->validate($email, $password)) {
  return view("sessions/create.view.php", [
    'errors' => $form->getErrors()
  ]);
};


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
