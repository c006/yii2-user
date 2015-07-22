<?php
namespace c006\user\models\form;

use c006\user\models\User;
use Yii;
use yii\base\Model;

/**
 * Class Signup
 *
 * @package c006\user\models\form
 */
class Signup extends Model
{

    public  $username;

    public  $email;

    public  $password;

    public  $password_match;

    public  $phone;

    public  $first_name;

    public  $last_name;

    private $login;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['first_name', 'required'],
            ['first_name', 'string', 'min' => 2],
            ['last_name', 'required'],
            ['last_name', 'string', 'min' => 2],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\c006\user\models\User', 'message' => 'This email address has already been taken.'],
            ['phone', 'filter', 'filter' => 'trim'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password_match', 'required'],
            ['password_match', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords do not match"],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user           = new User();
            $user->email    = $this->email;
            $user->username = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->phone      = $this->phone;
            $user->first_name = $this->first_name;
            $user->last_name  = $this->last_name;
            $user->created_at = time();
            $user->login      = base64_encode($this->password) . base64_encode($user->email);
            $user->save();

            return $user;
        }

        return NULL;
    }
}
