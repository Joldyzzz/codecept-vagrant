<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 001 01.06.17
 * Time: 11:29
 */

use yii\helpers\Html;

$this->title = 'Say';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-say">
    <h1><?= Html::encode($message) ?></h1>

    <p>
        This is the My Test page
    </p>
</div>