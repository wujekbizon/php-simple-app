<?php


use Core\Authenticator;
use Core\Session;
use Http\Forms\LoginForm;

$email = $_POST['email'];
$password = $_POST['password'];

$form = new LoginForm();

if ($form->validate($email, $password)) {

  if ((new Authenticator())->attempt($email, $password)) {
    redirect('/');
  }

  $form->error('email', 'No matching account found for the email address and password.');
};

Session::flash('errors', $form->getErrors());
Session::flash('old', [
  'email' => $email
]);

return redirect('/login');
