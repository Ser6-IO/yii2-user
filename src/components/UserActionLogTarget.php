<?php

namespace ser6io\yii2user\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\db\Exception;
use yii\di\Instance;
use yii\helpers\VarDumper;

class UserActionLogTarget extends \yii\log\DbTarget
{
    public $logTable = '{{%user_log}}';
    public $levels =['error', 'warning', 'info', 'trace', 'profile'];
    public $categories = [APP_ID . '\*'];
    public $logVars = [];

    /**
     * Stores log messages to DB.
     * This target extends DbTarget to allow for logging of user IP, ID, and session in separate columns.
     * @throws Exception
     * @throws LogRuntimeException
     */
    public function export()
    {
        if (Yii::$app === null) {
            throw new LogRuntimeException('Unable to export User log - NO APP');
        }
        
        if (Yii::$app instanceof \yii\console\Application) {
            $user_id = -1;
        } else {
            $user_id = Yii::$app->user->id ?? null;
        }

        $session_id = Yii::$app->session->id ?? null;

        $ip = Yii::$app->request->userIP ?? null;

        if ($this->db->getTransaction()) {
            // create new database connection, if there is an open transaction
            // to ensure insert statement is not affected by a rollback
            $this->db = clone $this->db;
        }

        $tableName = $this->db->quoteTableName($this->logTable);

        $sql = "INSERT INTO $tableName ([[level]], [[category]], [[log_time]], [[ip]], [[user_id]], [[session_id]], [[message]])
                VALUES (:level, :category, :log_time, :ip, :user_id, :session_id, :message)";
        
        $command = $this->db->createCommand($sql);
        
        foreach ($this->messages as $message) {
            list($text, $level, $category, $timestamp) = $message;
            if (!is_string($text)) {
                // exceptions may not be serializable if in the call stack somewhere is a Closure
                if ($text instanceof \Exception || $text instanceof \Throwable) {
                    $text = (string) $text;
                } else {
                    $text = VarDumper::export($text);
                }
            }
            if ($command->bindValues([
                    ':level' => $level,
                    ':category' => $category,
                    ':log_time' => $timestamp,
                    ':ip' => $ip,
                    ':user_id' => $user_id,
                    ':session_id' => $session_id,
                    ':message' => $text,
                ])->execute() > 0) {
                continue;
            }
            throw new LogRuntimeException('Unable to export log through database!');
        }
    }
}