<?php
use c006\activeForm\ActiveForm;
use common\assets\AppHelpers;
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
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'rememberMe')->toggle() ?>

        <div class="form-group">
            <?= Html::SubmitButton('Login', ['class' => 'btn btn-primary', 'name' => 'button-submit']) ?>

            <?= Html::button('Register', ['class' => 'btn btn-success float-right', 'id' => 'button-register']) ?>
            <?= Html::button('Reset Password', ['class' => 'btn btn-success float-right', 'id' => 'button-reset', 'style' => 'margin-right: 10px;']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>


<?php if (class_exists('c006\\spinner\\SubmitSpinner')) : ?>
    <?= c006\spinner\SubmitSpinner::widget(['form_id' => $form->id]); ?>
<?php endif ?>

<script type="text/javascript">
    jQuery(function () {
        jQuery('#button-register')
            .click(
            function () {
                document.location.href = '<?= AppHelpers::formatUrl(['/user/signup']) ?>';
            });
        jQuery('#button-reset')
            .click(
            function () {
                document.location.href = '<?= AppHelpers::formatUrl(['/user/request-password-reset','e'=>'']) ?>' + jQuery('#login-email').val();
            });
        <?php if (class_exists('common\\assets\\AssetExtrasJs')) : ?>
        var $extras = new Extras();
        $extras.init();
        <?php endif ?>
        <?php if ($model->rememberMe) : ?>
        setTimeout(function () {
            jQuery('span.c006-activeform-toggle-on').trigger('click');
        }, 600);
        <?php endif ?>
     });
</script>

