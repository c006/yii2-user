<?php
use c006\activeForm\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model c006\user\models\form\Signup */

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-signup">
    <h1 class="title-large"><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields:</p>

    <div class="">
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <?= $form->field($model, 'first_name') ?>
        <?= $form->field($model, 'last_name') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_match')->passwordInput() ?>
        <?= $form->field($model, 'phone') ?>
        <div class="form-group">
            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>


<?php if (class_exists('c006\\spinner\\SubmitSpinner')) : ?>
    <?= c006\spinner\SubmitSpinner::widget(['form_id' => $form->id]); ?>
<?php endif ?>

<script type="text/javascript">
    jQuery(function () {
        <?php if (class_exists('common\\assets\\AssetExtrasJs')) : ?>
        var $extras = new Extras();
        $extras.init();
        <?php endif ?>
    });
</script>
