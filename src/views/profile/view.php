<?php

$this->title = 'User Profile';

$this->params['breadcrumbs'][] = $this->title;
?>

<h1>User Profile</h1>
<p><strong>User:</strong> <?= Yii::$app->user->identity->username ?></p>
