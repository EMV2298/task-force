<?php

namespace taskforce\business\actions;

use taskforce\business\Task;

class Reject extends Actions {
  
  public function getActionName(): string
  {
    return 'Отказаться';
  }

  public function getInternalName(): string
  {
    return Task::ACTION_EXECUTOR_REJECT;
  }
  
  public static function checkAccess(int $userId, int $customerId, ?int $executorId): bool
  {
    return $userId === $executorId;
  }

}