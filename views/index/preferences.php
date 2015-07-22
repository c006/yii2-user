<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/2/2015
 * Time: 8:37 PM
 */
use c006\activeForm\ActiveForm;
use common\assets\AppHelpers;
use yii\helpers\Html;


/** @var $model c006\user\models\User */


$this->title                   = Yii::t('app', 'User Preferences');
$this->params['breadcrumbs'][] = ['label' => 'Account', 'url' => AppHelpers::formatUrl(['account/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="">
    <h1 class="title-large"><?= Html::encode($this->title) ?></h1>

    <div class="item-row">
        <div class="col-lg-0">
            <?php $form = ActiveForm::begin(['id' => 'form-preferences']); ?>
            <?= $form->field($model, 'first_name') ?>
            <?= $form->field($model, 'last_name') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'phone') ?>
            <div class="form-group">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'button-update']) ?>
                <?= Html::button('Reset Password', ['class' => 'btn btn-active', 'id' => 'button-reset']) ?>
            </div>
            <?php ActiveForm::end(); ?>
            <?php $form = ActiveForm::begin(['id' => 'form-reset', 'action' => AppHelpers::formatUrl(['user/index/request-password-reset'])]); ?>
            <input type="hidden" name="PasswordResetRequestForm[email]" value="<?= $model->email ?>"/>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(function () {
        jQuery('#button-reset')
            .click(function () {
                       jQuery('#form-reset').submit();
                   });
    });
</script>
