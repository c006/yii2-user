<?php
use c006\user\models\User;
use yii\db\Migration;
use yii\db\Schema;

class m000000_000000_c006_user extends Migration
{

    /*
    *  ~ Console command ~
    *
    * php yii migrate --migrationPath=@vendor/c006/yii2-user/migrations
    *
    */
    public function up()
    {
        $tableOptions = NULL;
        $dbType = $this->db->driverName;

        if ($dbType === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $model = new User();
        if (!array_key_exists('first_name', $model->attributes)) {
            $this->addColumn(
                '{{%user}}',
                'first_name',
                Schema::TYPE_STRING . '(45) NOT NULL'
            );
        }
        if (!array_key_exists('last_name', $model->attributes)) {
            $this->addColumn(
                '{{%user}}',
                'last_name',
                Schema::TYPE_STRING . '(45) NOT NULL'
            );
        }
        if (!array_key_exists('login', $model->attributes)) {
            $this->addColumn(
                '{{%user}}',
                'login',
                Schema::TYPE_STRING . '(100) NOT NULL'
            );
        }
        if (!array_key_exists('login', $model->attributes)) {
            $this->addColumn(
                '{{%user}}',
                'secure_string',
                Schema::TYPE_STRING . '(100) NOT NULL'
            );
        }


        if ($dbType == "mysql") {
            /* MYSQL */
            $this->createTable('{{%user_billing}}', [
                'id'              => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
                0                 => 'PRIMARY KEY (`id`)',
                'user_id'         => 'INT(10) UNSIGNED NOT NULL',
                'payment_type_id' => 'SMALLINT(5) UNSIGNED NOT NULL',
                'first_name'      => 'VARCHAR(60) NOT NULL',
                'last_name'       => 'VARCHAR(60) NOT NULL',
                'phone'           => 'VARCHAR(12) NULL',
                'country_id'      => 'INT(10) UNSIGNED NOT NULL',
                'postal_code_id'  => 'INT(10) UNSIGNED NOT NULL',
                'city_id'         => 'INT(10) UNSIGNED NOT NULL',
                'state_id'        => 'INT(10) UNSIGNED NULL',
                'region_id'       => 'INT(10) UNSIGNED NULL',
                'address'         => 'VARCHAR(100) NOT NULL',
                'address_2'       => 'VARCHAR(100) NULL',
                'apt_suite'       => 'VARCHAR(20) NULL',
                'email'           => 'VARCHAR(45) NULL',
            ], $tableOptions);
            $this->addForeignKey('fk_user_user_billing', '{{%user_billing}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'DELETE');
        }
        $tableOptions = "";
        if ($dbType == "mysql") {
            /* MYSQL */
            $this->createTable('{{%user_credits}}', [
                'id'                  => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
                0                     => 'PRIMARY KEY (`id`)',
                'user_id'             => 'INT(10) UNSIGNED NOT NULL',
                'transaction_type_id' => 'SMALLINT(5) UNSIGNED NOT NULL',
                'transaction_id'      => 'VARCHAR(100) NOT NULL',
                'credits'             => 'SMALLINT(5) UNSIGNED NOT NULL',
                'currency'            => 'DECIMAL(12,2) UNSIGNED NOT NULL',
                'processor'           => 'VARCHAR(30) NULL',
            ], $tableOptions);
            $this->addForeignKey('fk_user_user_credits', '{{%user_credits}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'DELETE');
        }
        $tableOptions = "";
        if ($dbType == "mysql") {
            /* MYSQL */
            $this->createTable('{{%user_roles}}', [
                'id'    => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
                0       => 'PRIMARY KEY (`id`)',
                'name'  => 'VARCHAR(100) NOT NULL',
                'level' => 'SMALLINT(6) NOT NULL',
            ], $tableOptions);
        }
        $tableOptions = "";
        if ($dbType == "mysql") {
            /* MYSQL */
            $this->createTable('{{%user_roles_link}}', [
                'id'            => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
                0               => 'PRIMARY KEY (`id`)',
                'user_id'       => 'INT(10) UNSIGNED NOT NULL',
                'user_roles_id' => 'INT(10) UNSIGNED NOT NULL',
            ], $tableOptions);
            $this->addForeignKey('fk_user_user_roles_link', '{{%user_roles_link}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'DELETE');
        }
        $tableOptions = "";
        if ($dbType == "mysql") {
            /* MYSQL */
            $this->createTable('{{%user_shipping}}', [
                'id'             => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
                0                => 'PRIMARY KEY (`id`)',
                'user_id'        => 'INT(10) UNSIGNED NOT NULL',
                'first_name'     => 'VARCHAR(60) NOT NULL',
                'last_name'      => 'VARCHAR(60) NOT NULL',
                'phone'          => 'VARCHAR(12) NULL',
                'country_id'     => 'INT(10) UNSIGNED NOT NULL',
                'postal_code_id' => 'INT(10) UNSIGNED NOT NULL',
                'city_id'        => 'INT(10) UNSIGNED NOT NULL',
                'state_id'       => 'INT(10) UNSIGNED NULL',
                'region_id'      => 'INT(10) UNSIGNED NULL',
                'address'        => 'VARCHAR(100) NOT NULL',
                'address_2'      => 'VARCHAR(100) NULL',
                'apt_suite'      => 'VARCHAR(20) NULL',
                'default'        => 'TINYINT(1) NULL',
            ], $tableOptions);
            $this->addForeignKey('fk_user_user_shipping', '{{%user_shipping}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'DELETE');
        }
        $tableOptions = "";
        if ($dbType == "mysql") {
            /* MYSQL */
            $this->createTable('{{%user_transaction_details}}', [
                'id'              => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
                0                 => 'PRIMARY KEY (`id`)',
                'user_credits_id' => 'INT(10) UNSIGNED NOT NULL',
                'user_id'         => 'INT(10) UNSIGNED NOT NULL',
                'key'             => 'VARCHAR(100) NOT NULL',
                'value'           => 'VARCHAR(100) NOT NULL',
            ], $tableOptions);
        }
        $tableOptions = "";
        if ($dbType == "mysql") {
            /* MYSQL */
            $this->createTable('{{%user_types}}', [
                'id'   => 'SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT',
                0      => 'PRIMARY KEY (`id`)',
                'name' => 'VARCHAR(45) NOT NULL',
            ], $tableOptions);
        }
        $tableOptions = "";
        if ($dbType == "mysql") {
            /* MYSQL */
            $this->createTable('{{%user_types_link}}', [
                'id'            => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
                0               => 'PRIMARY KEY (`id`)',
                'user_id'       => 'INT(10) UNSIGNED NOT NULL',
                'user_types_id' => 'SMALLINT(5) UNSIGNED NOT NULL',
            ], $tableOptions);
            $this->addForeignKey('fk_user_user_types_link', '{{%user_types_link}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'DELETE');
        }


    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'first_name');
        $this->dropColumn('{{%user}}', 'last_name');
        $this->dropTable('%user_roles');
        $this->dropTable('%user_roles_link');
    }
}

