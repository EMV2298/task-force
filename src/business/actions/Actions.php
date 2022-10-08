<?php

namespace taskforce\business\actions;

abstract class Actions {

    abstract public function getActionName(): string;

    abstract public function getInternalName(): string;
    
    abstract public function getActionData(): string;
    
    abstract public function getClass(): string;

    abstract public static function checkAccess(int $userId, int $customerId, ?int $executorId): bool;
} 
