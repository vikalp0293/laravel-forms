<?php

namespace App\Services;

use Illuminate\Support\Arr;
use SevenUpp\Models\User;

class StripeService
{
    function __construct()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function updateCardDetails(User $user, array $stripeData): void
    {
        $user->fill([
            'name_on_card' => Arr::get($stripeData, 'name', ''),
            'card_brand' => Arr::get($stripeData, 'brand', ''),
            'card_last_four' => Arr::get($stripeData, 'last4', ''),
            // 'card_expiry' => Arr::get($stripeData, 'card.exp_month', '') . ' / ' . Arr::get($stripeData, 'card.exp_year', ''),
        ]);
        $user->card_expiry = Arr::get($stripeData, 'exp_month', '') . ' / ' . Arr::get($stripeData, 'exp_year', '');
        $user->save();
    }

    public function refund(User $user, $paymentIntentId)
    {
        $res = $user->refund($paymentIntentId);
        return $res->status;
    }
}
