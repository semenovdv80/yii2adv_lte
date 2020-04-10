<?php
namespace backend\models;

use common\models\User;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class SendMailJob extends BaseObject implements JobInterface
{
    public $senderId;
    public $userId;

    public function execute($queue)
    {
        $user = User::findOne($this->userId);

        \Yii::$app->mailer->compose()
            ->setFrom('from@domain.com')
            ->setTo($user->email)
            ->setSubject('Тема сообщения')
            ->setTextBody('Текст сообщения')
            ->setHtmlBody('<b>текст сообщения в формате HTML</b>')
            ->send();
    }
}
