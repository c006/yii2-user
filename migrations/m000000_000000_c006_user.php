<?php
use yii\db\Migration;

class m000000_000000_c006_user extends Migration
{

    /**
     *  ~ Console command ~
     *
     * php yii migrate --migrationPath=@vendor/c006/yii2-user/migrations
     *
     */

    /**
     *
     */
    public function up()
    {

        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `user`');
        $this->execute('SET foreign_key_checks = 1;');



        $tables = Yii::$app->db->schema->getTableNames();
        $dbType = $this->db->driverName;
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        $tableOptions_mssql = "";
        $tableOptions_pgsql = "";
        $tableOptions_sqlite = "";
        /* MYSQL */
        if (!in_array('user', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%user}}', [
                    'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'username' => 'VARCHAR(255) NOT NULL',
                    'auth_key' => 'VARCHAR(32) NOT NULL',
                    'password_hash' => 'VARCHAR(255) NOT NULL',
                    'password_reset_token' => 'VARCHAR(255) NULL',
                    'email' => 'VARCHAR(255) NOT NULL',
                    'role' => 'SMALLINT(6) NOT NULL DEFAULT \'10\'',
                    'status' => 'SMALLINT(6) NOT NULL DEFAULT \'10\'',
                    'created_at' => 'TIMESTAMP NULL',
                    'updated_at' => 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ',
                    'phone' => 'VARCHAR(14) NULL',
                    'first_name' => 'VARCHAR(45) NOT NULL',
                    'last_name' => 'VARCHAR(45) NOT NULL',
                    'pin' => 'CHAR(32) NULL',
                    'pin_tries' => 'TINYINT(1) NULL DEFAULT \'2\'',
                    'security' => 'VARCHAR(100) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('user_roles', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%user_roles}}', [
                    'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'name' => 'VARCHAR(100) NOT NULL',
                    'level' => 'SMALLINT(6) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('user_roles_link', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%user_roles_link}}', [
                    'id' => 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'user_id' => 'INT(10) UNSIGNED NOT NULL',
                    'user_roles_id' => 'INT(10) UNSIGNED NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        $this->createIndex('idx_UNIQUE_email_0979_00','user','email',1);
        $this->createIndex('idx_UNIQUE_name_0996_01','user_roles','name',1);
        $this->createIndex('idx_user_id_1015_02','user_roles_link','user_id',0);

        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_user_1012_00','{{%user_roles_link}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION' );
        $this->execute('SET foreign_key_checks = 1;');

        $this->execute('SET foreign_key_checks = 0');
        $this->insert('{{%user}}',['id'=>'1','username'=>'admin','auth_key'=>'CcqfH1ZuHo-HxdM-DK3yX4d0Z9SyNWUq','password_hash'=>'$2y$13$E2Cedx.IWufS03d6IgGe..TwDYZxTz1WpNi7/65ZqghlLr5nHXgvS','password_reset_token'=>'','email'=>'admin@admin.com','role'=>'10','status'=>'10','created_at'=>'0000-00-00 00:00:00','updated_at'=>'0000-00-00 00:00:00','phone'=>'9492026160','first_name'=>'user','last_name'=>'admin','pin'=>'','pin_tries'=>'2','security'=>'']);
        $this->insert('{{%user_roles}}',['id'=>'1','name'=>'Admin','level'=>'100']);
        $this->insert('{{%user_roles}}',['id'=>'2','name'=>'User','level'=>'10']);
        $this->insert('{{%user_roles_link}}',['id'=>'1','user_id'=>'1','user_roles_id'=>'1']);
        $this->execute('SET foreign_key_checks = 1;');

    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `user`');
        $this->execute('SET foreign_key_checks = 1;');
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `user_roles`');
        $this->execute('SET foreign_key_checks = 1;');
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `user_roles_link`');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

