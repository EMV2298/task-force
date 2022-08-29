<?php

namespace taskforce\business\actions;

use taskforce\business\Task;

class Cancel extends Actions {
  
  public function getActionName(): string
  {
    return 'Отменить';
  }

  public function getInternalName(): string
  {
    return Task::ACTION_CUSTOMER_CANCEL;
  }
  
  public static function checkAccess(int $userId, int $customerId, int $executorId): bool
  {
    return $userId === $customerId;
  }

}
