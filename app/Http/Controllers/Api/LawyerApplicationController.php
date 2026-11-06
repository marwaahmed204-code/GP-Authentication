<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LawyerApplicationController extends Controller
{
    public function apply(Request $request)
    {
        $user = $request->user();

        // Check if the user already submitted an application
        if ($user->lawyerProfile()->exists()) {
            return response()->json([
                'message' => 'You have already submitted a lawyer application.'
            ], 409);
        }

        // Validate the application data
        $validated = $request->validate([
            'bar_registration_number' => 'required|string|max:255|unique:lawyer_profiles,bar_registration_number',
        ]);

        // Create the application
        $application = $user->lawyerProfile()->create([
            'bar_registration_number' => $validated['bar_registration_number'],
        ]);

        return response()->json([
            'message' => 'Lawyer application submitted successfully.',
            'application' => $application,
        ], 201);
    }
}