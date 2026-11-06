<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LawyerController extends Controller
{
    // Check lawyer application status
    public function applicationStatus(Request $request)
    {
        $user = $request->user();

        $application = $user->lawyerProfile;

        if (! $application) {
            return response()->json([
                'message' => 'You have not submitted a lawyer application yet.',
            ], 404);
        }

        return response()->json([
            'application_status' => $application->verification_status,
            'rejection_reason'   => $application->rejection_reason,
        ]);
    }

    // Display lawyer profile
    public function profile(Request $request)
    {
        $user = $request->user();

        $profile = $user->lawyerProfile;

        return response()->json([
            'lawyer' => [
                'id'                      => $user->id,
                'name'                    => $user->name,
                'email'                   => $user->email,
                'bar_registration_number' => $profile->bar_registration_number,
                'verification_status'     => $profile->verification_status,
            ],
        ]);
    }
}
