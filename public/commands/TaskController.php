<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Task;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Expression;
use yii\helpers\Console;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TaskController extends Controller
{

    /**
     * Start or ends tasks based on the action selected and Task Name
     * @param string $action
     * @param string $taskName
     * @return int
     */
    public function actionIndex(string $action, string $taskName): int
    {
        switch ($action) {
            case 'start':
                $exit = $this->start($taskName);
                break;

            case 'end':
                $exit = $this->end($taskName);
                break;
            default:
                $this->stdout("No action selected.", Console::FG_YELLOW);
                $exit = ExitCode::OK;
        }
        return $exit;
    }


    /**
     * Create and start a new task
     * @param string $taskName
     * @return int
     */
    protected function start(string $taskName): int
    {
        $model = new Task();

        $model->name = $taskName;

        if ($model->save()) {
            $this->stdout("Task '{$model->name}' added successfully.", Console::FG_GREEN);
            $exit = ExitCode::OK;

        } else {
            $this->stdout("Error adding '{$model->name}' task.", Console::FG_RED);
            $exit = ExitCode::DATAERR;
        }
        return $exit;

    }


    /**
     * Ends tasks with the Task Name selected
     * @param string $taskName
     * @return int
     */
    protected function end(string $taskName): int
    {
        $tasks = Task::find()->where(['name' => $taskName, 'end' => null])->all();

        foreach ($tasks as $task) {
            $task->end = new Expression('NOW()');
            if ($task->save()) {
                $this->stdout("Task '{$task->name}' closed successfully.", Console::FG_GREEN);
                $exit = ExitCode::OK;
            } else {
                $this->stdout("Error ending '{$task->name}' task.", Console::FG_RED);
                $exit = ExitCode::DATAERR;
            }
        }

        return $exit;
    }

    /**
     * List all de task description
     * @return int
     */
    public function actionList(): int
    {
        $tasks = Task::find()
            ->select(['task' => new Expression('
                CONCAT_WS("","Task: ", name,", Start: ",start,", End: ", end, "Elapsed time: ",
                    TIMESTAMPDIFF(day,start,CASE WHEN end IS NULL THEN NOW() ELSE end END), " Days ",
                    LPAD(MOD(TIMESTAMPDIFF(hour,start,CASE WHEN end IS NULL THEN NOW() ELSE end END),24), 2, 0), " Hours ",
                    LPAD(MOD(TIMESTAMPDIFF(minute,start,CASE WHEN end IS NULL THEN NOW() ELSE end END),60), 2, 0), " Minutes ",
                    LPAD(MOD(TIMESTAMPDIFF(second,start,CASE WHEN end IS NULL THEN NOW() ELSE end END),60), 2, 0), " Seconds "
                )')])
            ->asArray()
            ->column();

        echo implode("\n", $tasks);

        return ExitCode::OK;
    }
}
