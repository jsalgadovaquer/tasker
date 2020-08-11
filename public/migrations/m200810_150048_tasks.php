<?php

use yii\db\Migration;

/**
 * Class m200810_150048_tasks
 */
class m200810_150048_tasks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableName = 'task';

        if ($this->db->getTableSchema($tableName, true) === null) {
            $this->createTable($tableName,
                [
                    'id' => $this->primaryKey(20)->unsigned()->comment('Id')->notNull(),
                    'user_id' => $this->integer(10)->unsigned()->comment('User Id')->null(),
                    'name' => $this->string(50)->comment('Task Name')->notNull(),
                    'start' => $this->timestamp()->comment('Start')->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
                    'end' => $this->timestamp()->comment('End')->null()
                ],
                'CHARACTER SET utf8 COLLATE utf8_general_ci'
            );

            echo "Migración: m200810_150048_tasks realizada con exito.\n";
            $return = true;
        } else {
            echo "Migración 'm200810_150048_tasks' fallida:  la tabla que se intenta crear ya exisite.\n";
            $return = false;
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $tableName = 'task';

        $this->dropTable($tableName);
        echo "m200810_150048_tasks revertida correctamente.\n";
        return true;


    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200810_150048_tasks cannot be reverted.\n";

        return false;
    }
    */
}
