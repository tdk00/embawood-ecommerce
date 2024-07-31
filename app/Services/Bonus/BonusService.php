<?php
namespace App\Services\Bonus;
use App\Models\Bonus\Bonus;
use App\Models\Bonus\BonusExecution;
use App\Models\Bonus\BonusSetting;
use App\Models\Bonus\UserProductView;
use Carbon\Carbon;

class BonusService
{
    public function awardRegistrationBonus($user)
    {
        $setting = BonusSetting::where('type', 'registration')->first();

        if ($setting) {
            $bonus = Bonus::create(['type' => 'registration', 'amount' => $setting->bonus_amount]);
            BonusExecution::create([
                'user_id' => $user->id,
                'bonus_id' => $bonus->id,
                'executed_at' => Carbon::now(),
            ]);

            $this->updateUserBonusAmounts($user, $setting->bonus_amount);
        }
    }

    public function handleProductView($user, $productId)
    {
        $now = Carbon::now();
        $today = $now->toDateString();

        // Get the bonus setting
        $setting = BonusSetting::where('type', 'product_view')->first();
        if (!$setting) {
            return;
        }

        // Determine the period (daily or weekly)
        $period = $setting->period;

        // Calculate the start date for the period
        if ($period == 'daily') {
            $periodStartDate = $today;
        } else {
            $periodStartDate = $now->startOfWeek()->toDateString();
        }

        if ($user->last_product_view_bonus_achieved_at) {
            $lastBonusAchievedAt = Carbon::parse($user->last_product_view_bonus_achieved_at);
            if (($period == 'daily' && $lastBonusAchievedAt->isSameDay($now)) ||
                ($period == 'weekly' && $lastBonusAchievedAt->isSameWeek($now))) {
                return; // Bonus already achieved within the current period
            }
        }

        // Check if the product has already been viewed within the current period
        $existingView = UserProductView::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('viewed_date', '>=', $periodStartDate)
            ->exists();

        if (!$existingView) {
            // Count current unique product views within the current period
            $productViewCount = UserProductView::where('user_id', $user->id)
                ->where('viewed_date', '>=', $periodStartDate)
                ->distinct('product_id')
                ->count();

            // Only record the view if it contributes towards earning the bonus
            if ($productViewCount < $setting->target_count) {
                // Record the product view
                UserProductView::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'viewed_date' => $today,
                ]);

                // Check if the user is now eligible for a bonus
                $this->checkBonusEligibility($user, $period);
            }
        }
    }

    public function checkBonusEligibility($user, $period)
    {
        $setting = BonusSetting::where('type', 'product_view')->first();
        if (!$setting) {
            return;
        }

        $now = Carbon::now();
        $today = $now->toDateString();
        $periodStartDate = ($period == 'daily') ? $today : $now->startOfWeek()->toDateString();

        $productViews = UserProductView::where('user_id', $user->id)
            ->where('viewed_date', '>=', $periodStartDate)
            ->distinct('product_id')
            ->count();

        if ($productViews >= $setting->target_count) {
            $bonus = Bonus::create(['type' => 'product_view', 'amount' => $setting->bonus_amount]);
            BonusExecution::create([
                'user_id' => $user->id,
                'bonus_id' => $bonus->id,
                'executed_at' => $now,
            ]);

            $user->last_product_view_bonus_achieved_at = $now;
            $user->save();

            $this->updateUserBonusAmounts($user, $setting->bonus_amount);

            UserProductView::where('user_id', $user->id)
                ->where('viewed_date', '>=', $periodStartDate)
                ->delete();
        }
    }

    public function awardCheckoutBonus($user, $transactionAmount)
    {
        $setting = BonusSetting::where('type', 'checkout')->first();

        if ($setting) {
            $bonusAmount = ($transactionAmount / 100) * $setting->bonus_amount;
            $bonus = Bonus::create(['type' => 'checkout', 'amount' => $bonusAmount]);
            BonusExecution::create([
                'user_id' => $user->id,
                'bonus_id' => $bonus->id,
                'executed_at' => Carbon::now(),
            ]);

            $this->updateUserBonusAmounts($user, $bonusAmount);
        }
    }

    private function updateUserBonusAmounts($user, $bonusAmount)
    {
        $user->total_bonus_amount += $bonusAmount;
        $user->remaining_bonus_amount += $bonusAmount;
        $user->save();
    }

    public function getProductViewBonusProgress($user)
    {
        $now = Carbon::now();
        $today = $now->toDateString();

        // Get the bonus setting
        $setting = BonusSetting::where('type', 'product_view')->first();
        if (!$setting) {
            return [
                'progress' => 0,
                'target' => 0,
                'period' => 'N/A',
                'status' => 'loading'
            ];
        }

        // Determine the period (daily or weekly)
        $period = $setting->period;

        // Calculate the start date for the period
        if ($period == 'daily') {
            $periodStartDate = $today;
        } else {
            $periodStartDate = $now->startOfWeek()->toDateString();
        }

        // Count current unique product views within the current period
        $productViewCount = UserProductView::where('user_id', $user->id)
            ->where('viewed_date', '>=', $periodStartDate)
            ->distinct('product_id')
            ->count();

        // Determine the status
        $status = 'uncompleted';
        if ($productViewCount >= $setting->target_count) {
            $status = 'completed';
        } elseif ($user->last_product_view_bonus_achieved_at) {
            $lastBonusAchievedAt = Carbon::parse($user->last_product_view_bonus_achieved_at);
            if (($period == 'daily' && $lastBonusAchievedAt->isSameDay($now)) ||
                ($period == 'weekly' && $lastBonusAchievedAt->isSameWeek($now))) {
                $status = 'completed';
            }
        }

        return [
            'progress' => $productViewCount,
            'target' => $setting->target_count,
            'period' => $period,
            'status' => $status
        ];
    }
}
