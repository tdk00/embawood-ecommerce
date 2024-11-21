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

    /**
     * @OA\Get(
     *     path="/api/get-bonus-execution",
     *     operationId="getBonusExecution",
     *     tags={"Bonuses"},
     *     summary="Retrieve bonus execution records for the authenticated user",
     *     description="Returns the latest bonus execution records for the authenticated user, including registration bonuses and other recent bonus actions.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Bonus execution records retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="execution_records",
     *                 type="array",
     *                 description="List of bonus execution records",
     *                 @OA\Items(
     *                     @OA\Property(property="title", type="string", description="Description of the bonus execution", example="Qeydiyyatdan keçildiyi üçün"),
     *                     @OA\Property(property="amount", type="number", format="float", description="Amount of the bonus", example=10.5),
     *                     @OA\Property(property="date", type="string", format="datetime", description="Date when the bonus was executed", example="20.11.2024 15:30:00")
     *                 )
     *             ),
     *             @OA\Property(property="remaining_bonus_amount", type="number", format="float", description="Total remaining bonus amount for the user", example=150.0),
     *             @OA\Property(property="used_bonus_amount", type="number", format="float", description="Total used bonus amount for the user", example=50.0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="User not authenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", description="Operation status", example=false),
     *             @OA\Property(property="message", type="string", description="Error message", example="User not authenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="An error occurred while retrieving bonus execution records",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", description="Operation status", example=false),
     *             @OA\Property(property="message", type="string", description="Error message", example="An error occurred while retrieving bonus execution records"),
     *             @OA\Property(property="error", type="string", description="Detailed error message", example="SQLSTATE[42S22]: Column not found: 1054 Unknown column...")
     *         )
     *     )
     * )
     */
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
