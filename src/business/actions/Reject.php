<?php

namespace taskforce\business\actions;

use taskforce\business\Task;

class Reject extends Actions
{
    public function getActionName(): string
    {
        return 'Отказаться от задания';
    }

    public function getInternalName(): string
    {
        return Task::ACTION_EXECUTOR_REJECT;
    }

    public function getClass(): string
    {
        return 'button button--orange action-btn';
    }

    public function getActionData(): string
    {
        return 'refusal';
    }

    public static function checkAccess(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $executorId;
    }
}
