<?php

namespace c006\user\controllers;

use c006\alerts\Alerts;
use c006\core\assets\CoreHelper;
use c006\email\EmailTemplates;
use c006\user\models\form\Login;
use c006\user\models\form\PasswordResetRequest;
use c006\user\models\form\ResetPassword;
use c006\user\models\form\Signup;
use c006\user\models\User;
use common\assets\AppHelpers;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Class UserController
 *
 * @package c006\user\controllers
 */
class UserController extends Controller
{


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => TRUE,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => TRUE,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     *
     */
    function init()
    {

    }

    /**
     *
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['user/login']);
        }

        return self::userRedirect();
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {

            return self::userRedirect();
        }

        $model = new Login();
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            if (!$model->login()) {
                Alerts::setMessage('Login failed, please try again');
                Alerts::setAlertType(Alerts::ALERT_DANGER);
            } else {
                $content = $this->renderPartial('user-login-message');
                Alerts::setMessage($content);
                Alerts::setAlertType(Alerts::ALERT_SUCCESS);

                return self::userRedirect();
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);

    }

    /**
     * @param $token
     *
     * @return \yii\web\Response
     */
    public function actionEmail($token)
    {

        if (stripos($token, '.') != FALSE) {

            list($security, $pin) = explode('.', $token);
            $model = User::find()
                ->where(" SUBSTRING(`security`, 1 ,11) = '" . $security . "' AND MD5(MD5(pin)) = '" . md5($pin) . "'")
                ->asArray()
                ->one();
            if (sizeof($model)) {
                /** @var  $model \c006\user\models\User */
                $model = self::loadModel($model['id']);
                $model->security = '';
                $model->status = 10;
                $model->updated_at = CoreHelper::mysqlTimestamp();
                $model->save();
                Yii::$app->getUser()->login($model);

                $content = $this->renderPartial('user-login-message');
                Alerts::setMessage($content);
                Alerts::setAlertType(Alerts::ALERT_SUCCESS);
                Alerts::setCountdown(5);

                if (Yii::$app->session->get('C006_LOGIN_PATH')) {
                    $path = Yii::$app->session->get('C006_LOGIN_PATH');

                    return $this->redirect(AppHelpers::formatUrl([$path]));
                }

                return $this->redirect('/');
            }
        }

        Alerts::setMessage('Your token did not match our records, please contact support');
        Alerts::setAlertType(Alerts::ALERT_WARNING);

        return $this->redirect('/');
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {
        $model = new Signup();
        if ($model->load(Yii::$app->request->post())) {

            if ($user = $model->signup()) {

                Alerts::setMessage('Thank you ' . $user->first_name . ', for signing up.<br>Please check your email to complete the process.');
                Alerts::setAlertType(Alerts::ALERT_SUCCESS);
                if (Yii::$app->getUser()->login($user)) {

                    /* ~ c006\email\EmailTemplates */
                    $array = [];
                    $array['company_name'] = Yii::$app->params['siteName'];
                    $array['page_title'] = ' :: Sign Up';
                    $array['name'] = $user->first_name;
                    $array['subject'] = $array['company_name'] . $array['page_title'];
                    $array['message'] = \c006\email\assets\Assets::$MSG_SIGN_UP_VERIFY;
                    $array['message'] = str_replace('#NAME#', $array['name'], $array['message']);
                    $array['message'] = str_replace('#VERIFY_URL#', Yii::$app->params['siteUrl'] . '/user/email?token=' . substr($user->security, 0, 11) . '.' . md5($user->pin), $array['message']);
                    $array['email_to'] = $user->email;
                    $array['email_from'] = Yii::$app->params['supportEmail'];
                    $array['email_from_name'] = Yii::$app->params['siteName'];

                    EmailTemplates::widget(['template' => 'sign-up', 'array' => $array]);

                    return $this->redirect(['user/login']);
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }


    /**
     * @return string
     */
    public function actionPreferences()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/user/login']);
        }
        $model = User::find()
            ->where(['id' => Yii::$app->user->id])
            ->one();
        if (isset($_POST['User'])) {

            $post = $_POST['User'];
            $model->email = $post['email'];
            $model->username = $post['email'];
            $model->phone = $post['phone'];
            $model->first_name = $post['first_name'];
            $model->last_name = $post['last_name'];
            $model->save();

            Alerts::setMessage('Preferences updated');
            Alerts::setAlertType(Alerts::ALERT_SUCCESS);

            return $this->redirect(['user/']);
        }

        return $this->render('preferences', [
            'model' => $model,
        ]);
    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequest();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        if (isset($_REQUEST['e'])) {
            $model->email = $_REQUEST['e'];
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * @param $token
     *
     * @return string|\yii\web\Response
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPassword($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,

        ]);
    }

    /**
     * @return array
     */
    private function userRedirect()
    {
        if (Yii::$app->params['user_login_path']) {
            $path = Yii::$app->params['user_login_path'];
            return $path;
        }

        return ['/'];
    }

    /**
     * @param $id
     *
     * @return null|static
     */
    private function loadModel($id)
    {
        return User::findOne($id);
    }

}
