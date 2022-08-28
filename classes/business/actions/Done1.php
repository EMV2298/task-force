<?php

namespace taskforce\business\actions;

use taskforce\business\Task;

class Done extends Actions {
  
  public function getActionName(): string
  {
    return 'Выполнено';
  }

  public function getInternalName(): string
  {
    return Task::ACTION_CUSTOMER_DONE;
  }
  
  public static function checkAccess(int $userId, int $customerId, int $executorId): bool
  {
    return $userId === $customerId;
  }

}