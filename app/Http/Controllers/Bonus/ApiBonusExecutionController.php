<?php

namespace App\Http\Controllers\Bonus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiBonusExecutionController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->user = Auth::guard('sanctum')->user();
    }

    public function getExecution()
    {
        // Get all bonus executions, ordered by the latest created date
        $executions = $this->user->bonusExecutions()
            ->orderBy('created_at', 'desc')
            ->get();

        // Ensure we include the latest registration type execution
        $registrationExecution = $executions->where('bonus.type', 'registration')->first();

        // Filter out the registration execution and take the latest 9 other executions
        $latestExecutions = $executions->where('bonus.type', '!=', 'registration')
            ->take(9);

        // Combine the registration execution and the latest executions
        if ($registrationExecution) {
            $latestExecutions = $latestExecutions->prepend($registrationExecution);
        }

        // Limit to 10 records
        $transformedExecutions = $latestExecutions->take(10)->map(function ($execution) {
            // Set the correct title based on the type
            $title = match ($execution->bonus->type) {
                'registration' => 'Qeydiyyatdan keçildiyi üçün',
                'product_view' => 'Məhsul nəzərdən keçirildiyi üçün',
                'order' => 'Sifarişdən qazanılan',
                default => $execution->bonus->type,
            };

            return [
                'title' => $title,
                'amount' => $execution->bonus->amount,
                'date' => $execution->bonus->created_at->format('d.m.Y h:m:s'),
            ];
        });

        return response()->json([
            'execution_records' => $transformedExecutions,
            'remaining_bonus_amount' => $this->user->remaining_bonus_amount,
            'used_bonus_amount' => $this->user->used_bonus_amount,
        ]);
    }


}
