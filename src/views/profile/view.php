<?php

$this->title = 'User Profile';

$this->params['breadcrumbs'][] = $this->title;
?>

<h1>User Profile</h1>
<p><strong>User name:</strong> <?= Yii::$app->user->identity->username ?></p>
<p><strong>Contact email:</strong> <?= Yii::$app->user->identity->email ?></p>
