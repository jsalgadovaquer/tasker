<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "task".
 *
 * @property int $id Id
 * @property int|null $user_id User Id
 * @property string $name Task Name
 * @property string $start Start
 * @property string|null $end End
 */
class Task extends ActiveRecord
{
    public $stat;
    public $timeD;
    public $timeH;
    public $timeM;
    public $timeS;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['name'], 'required'],
            [['start', 'end'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('task', 'Id'),
            'user_id' => Yii::t('task', 'User Id'),
            'name' => Yii::t('task', 'Task Name'),
            'start' => Yii::t('task', 'Start'),
            'end' => Yii::t('task', 'End'),
        ];
    }


    /**
     * Finds the summary of tasks for today
     * @return array|ActiveRecord[]
     */
    public static function getWorkdayTime():array
    {
        $total = [];
        $total['Task'] = 'Total';
        $total['Days'] = 0;
        $total['Hours'] = 0;
        $total['Minutes'] = 0;
        $total['Seconds'] = 0;

        $tasks = self::find()
            ->select(['Task' => 'name'])
            ->addSelect(['Days' => new Expression('SUM(TIMESTAMPDIFF(day,start, CASE WHEN end IS NULL THEN NOW() ELSE end END))')])
            ->addSelect(['Hours' => new Expression('SUM(MOD(TIMESTAMPDIFF(hour,start, CASE WHEN end IS NULL THEN NOW() ELSE end END),24))')])
            ->addSelect(['Minutes' => new Expression('SUM(MOD(TIMESTAMPDIFF(minute,start, CASE WHEN end IS NULL THEN NOW() ELSE end END),60))')])
            ->addSelect(['Seconds' => new Expression('SUM(MOD(TIMESTAMPDIFF(second,start, CASE WHEN end IS NULL THEN NOW() ELSE end END),60))')])
            ->where(new Expression('DATE(start) = CURDATE()'))
            ->groupBy(['name'])
            ->asArray()
            ->all();

        foreach ($tasks as $task) {
            $total['Seconds'] = (($total['Seconds'] + $task['Seconds']) % 60);
            $extraM = floor((($total['Seconds'] + $task['Seconds']) / 60));

            $total['Minutes'] = (($total['Minutes'] + $task['Minutes'] + $extraM) % 60);
            $extraH = floor((($total['Minutes'] + $task['Minutes'] + $extraM) / 60));

            $total['Hours'] = (($total['Hours'] + $task['Hours'] + $extraH) % 24);
            $extraD = floor((($total['Hours'] + $task['Hours'] + $extraH) / 24));

            $total['Days'] =($total['Days'] + $task['Days'] + $extraD);

        }
        $tasks['total'] = $total;

        return $tasks;
    }
}
