<?php

namespace taskforce\business\actions;

class take extends Actions {
  
  public function getActionName(): string
  {
    return 'Откликнуться';
  }

  public function getInternalName(): string
  {
    return 'take';
  }
  
  public static function checkAccess(int $userId, int $customerId, int $executorId): bool
  {
    return $userId === $executorId;
  }

}