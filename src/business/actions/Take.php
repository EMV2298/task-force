<?php

namespace taskforce\business\actions;

use taskforce\business\Task;
use Yii;

class Take extends Actions {
  
  public function getActionName(): string
  {
    return 'Откликнуться на задание';
  }

  public function getInternalName(): string
  {
    return Task::ACTION_EXECUTOR_TAKE;
  }

  public function getActionData(): string
  {
    return 'act_response';
  }

  public function getClass(): string
  {
    return 'button button--blue action-btn';    
  }

  public static function checkAccess(int $userId, int $customerId, ?int $executorId): bool
  {
    $isExecutor = Yii::$app->user->getIdentity()->is_executor;
    return $userId !== $customerId && !$executorId && $isExecutor;
  }

}