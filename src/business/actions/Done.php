<?php

namespace taskforce\business\actions;

use taskforce\business\Task;

class Done extends Actions
{
    public function getActionName(): string
    {
        return 'Завершить задание';
    }

    public function getInternalName(): string
    {
        return Task::ACTION_CUSTOMER_DONE;
    }

    public function getClass(): string
    {
        return 'button button--pink action-btn';
    }

    public function getActionData(): string
    {
        return 'completion';
    }

    public static function checkAccess(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $customerId;
    }
}
