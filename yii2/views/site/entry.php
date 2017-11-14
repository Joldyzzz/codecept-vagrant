<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 001 01.06.17
 * Time: 11:29
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Entry';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-entry">
    <div class="row">
        <div class="col-lg-4">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'name')->label('Ваше Имя') ?>

            <?= $form->field($model, 'email')->label('Ваш E-mail') ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>