<?php
namespace App\Http\Controllers\User;

use App\Services\User\CreatioService;
use App\Services\User\CreatioApiService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiCreatioController extends Controller
{
    protected $creatioService;
    protected $creatioApiService;

    public function __construct(CreatioService $creatioService, CreatioApiService $creatioApiService)
    {
        $this->creatioService = $creatioService;
        $this->creatioApiService = $creatioApiService;
    }

    public function store(Request $request)
    {
        try {
            // Retrieve the access token
            $accessToken = $this->creatioService->getAccessToken();

            // Prepare client data
            $data = $request->only(['name', 'surname', 'phone', 'email']);

            // Call the API to create/update the client
            $response = $this->creatioApiService->createOrUpdateClient($data, $accessToken);

            return response()->json(['success' => true, 'data' => $response]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
