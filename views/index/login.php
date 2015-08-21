<?php
use c006\activeForm\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model c006\user\models\form\Login */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1 class="title-large"><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>


    <div class="">
        <?php $form = ActiveForm::begin(['id' => 'form-login']); ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="form-group">
            <?= Html::SubmitButton('Login', ['class' => 'btn btn-primary', 'name' => 'button-submit']) ?>

            <?= Html::a(Yii::t('app', 'Register'), ['/user/signup'], ['class' => 'btn btn-success inline-block float-right']) ?>
            <?= Html::a(Yii::t('app', 'Reset Password'), ['/user/request-password-reset'], ['class' => 'btn btn-success float-right inline-block margin-right-10']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>


<?php if (class_exists('c006\\spinner\\SubmitSpinner')) : ?>
    <?= c006\spinner\SubmitSpinner::widget(['form_id' => $form->id]); ?>
<?php endif ?>

