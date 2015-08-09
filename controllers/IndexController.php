<?php

namespace c006\user\controllers;



use c006\alerts\Alerts;
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
 * Class IndexController
 *
 * @package c006\user\controllers
 */
class IndexController extends Controller
{


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : NULL,
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
                'only'  => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => TRUE,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => TRUE,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     *
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            $this->redirect(AppHelpers::formatUrl(['user/login']));

            return;
        }
        $this->redirect(AppHelpers::formatUrl(['/account']));

    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {

        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(AppHelpers::formatUrl([Yii::$app->session->get('C006_LOGIN_PATH')]));
        }

        $model = new Login();
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            if (!$model->login()) {
                Alerts::setMessage('Login failed, please try again');
                Alerts::setAlertType(Alerts::ALERT_DANGER);
            } else {

                if (isset($post['Login']['rememberMe'])) {
                    setcookie('LOGIN', md5(Yii::$app->user->id), time() + (86400 * 30)); /* 30 days */
                }

                $content = $this->renderPartial('user-login-message');
                Alerts::setMessage($content);
                Alerts::setAlertType(Alerts::ALERT_SUCCESS);
                if (Yii::$app->session->get('C006_LOGIN_PATH')) {
                    $path = Yii::$app->session->get('C006_LOGIN_PATH');

                    return $this->redirect(AppHelpers::formatUrl([$path]));
                }

                return $this->redirect(AppHelpers::formatUrl(['/account/dashboard']));
            }
        }

        $cookie = (isset($_COOKIE['LOGIN'])) ? $_COOKIE['LOGIN'] : FALSE;
        if ($cookie) {
            /** @var  $m \c006\user\models\User */
            $m = User::find()
                ->where(" MD5(id) = '" . $cookie . "' ")
                ->one();
            if (is_object($m)) {
                $model->email = $m->email;
                $model->password = base64_decode(str_replace(base64_encode($m->email), '', $m->login));
                $model->rememberMe = 1;
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);

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

                Alerts::setMessage('Welcome ' . $user->first_name);
                Alerts::setAlertType(Alerts::ALERT_SUCCESS);
                if (Yii::$app->getUser()->login($user)) {

                    /* ~ c006\email\EmailTemplates */
                    $array = [];
                    $array['page_title'] = ' :: Sign Up';
                    $array['name'] = $user->first_name;
                    $array['subject'] = 'c006 Development ' . $array['page_title'];
                    $array['message'] = \c006\email\assets\Assets::$MSG_SIGN_UP;
                    $array['message'] = str_replace('#NAME#', $array['name'], $array['message']);
                    $array['email_to'] = $user->email;
                    $array['email_from'] = Yii::$app->params['supportEmail'];
                    $array['email_from_name'] = Yii::$app->params['siteName'];

                    EmailTemplates::widget(['template'=>'sign-up', 'array'=> $array]);
                    /* End */

                    return $this->redirect(AppHelpers::formatUrl(['user/login']));
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }


    /**
     * @return string|void
     */
    public function actionPreferences()
    {
        if (\Yii::$app->user->isGuest) {
            $this->redirect(AppHelpers::formatUrl(['/user/login']));

            return;
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

            return $this->redirect(AppHelpers::formatUrl(['user/']));
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

}
