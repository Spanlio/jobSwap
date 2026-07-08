<?php

namespace App\Exceptions;

use App\Models\User;
use RuntimeException;

class PaymentMethodMissingException extends RuntimeException
{
    public function __construct(public readonly User $user)
    {
        parent::__construct("User #{$user->id} has no saved payment method.");
    }
}
