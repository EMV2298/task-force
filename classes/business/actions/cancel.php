<?php

namespace taskforce\business\actions;

class cancel extends Actions {
  
  public function getActionName(): string
  {
    return 'Отменить';
  }

  public function getInternalName(): string
  {
    return 'cancel';
  }
  
  public static function checkAccess(int $userId, int $customerId, int $executorId): bool
  {
    return $userId === $customerId;
  }

}
