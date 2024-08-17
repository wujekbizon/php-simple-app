<?php

use Core\App;
use Core\Database;
use Core\Validator;

$db = App::resolve(Database::class);
$userId = $_SESSION['user']['id'];
$errors = [];

if (! Validator::string($_POST['body'], 1, 1000)) {
  $errors['body'] = 'A body of no more than 1,000 characters is required';
}

if (! empty($errors)) {

  return view("notes/create.view.php", [
    'heading' => 'Create Note',
    'errors' => $errors
  ]);
}

$db->query('INSERT INTO `myapp`.`notes` (`body`, `user_id`) VALUES (:body, :user_id)', [
  'body' => $_POST['body'],
  'user_id' => $userId
]);

redirect('/notes');
