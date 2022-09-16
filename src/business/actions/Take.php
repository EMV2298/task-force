<?php

namespace taskforce\business\actions;

use taskforce\business\Task;

class Take extends Actions {
  
  public function getActionName(): string
  {
    return 'Откликнуться';
  }

  public function getInternalName(): string
  {
    return Task::ACTION_EXECUTOR_TAKE;
  }
  
  public static function checkAccess(int $userId, int $customerId, ?int $executorId): bool
  {
    return $userId !== $customerId && !$executorId;
  }

}