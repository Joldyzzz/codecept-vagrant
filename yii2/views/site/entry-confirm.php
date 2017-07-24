<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 001 01.06.17
 * Time: 11:29
 */

use yii\helpers\Html;

$this->title = 'Entry';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-entry">
    <p>Вы ввели следующую информацию:</p>

    <ul>
        <li><label>Name</label>: <?= Html::encode($model->name) ?></li>
        <li><label>Email</label>: <?= Html::encode($model->email) ?></li>
    </ul>
</div>