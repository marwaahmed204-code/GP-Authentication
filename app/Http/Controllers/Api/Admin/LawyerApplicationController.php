<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\LawyerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LawyerApplicationController extends Controller
{
    // Display pending lawyer applications
    public function index()
    {
        $applications = LawyerProfile::with('user')
            ->where('verification_status', 'pending')
            ->get();

        return response()->json([
            'applications' => $applications,
        ]);
    }

    // Approve a lawyer application
    public function approve($id)
    {
        $application = LawyerProfile::with('user')->findOrFail($id);

        if ($application->verification_status !== 'pending') {
            return response()->json([
                'message' => 'This application has already been processed.',
            ], 409);
        }

        DB::transaction(function () use ($application) {

            $application->verification_status = 'approved';
            $application->save();

            $application->user->role = 'lawyer';
            $application->user->save();

        });

        return response()->json([
            'message' => 'Lawyer application approved successfully.',
        ]);
    }

    // Reject a lawyer application
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $application = LawyerProfile::findOrFail($id);

        if ($application->verification_status !== 'pending') {
            return response()->json([
                'message' => 'This application has already been processed.',
            ], 409);
        }

        $application->verification_status = 'rejected';
        $application->rejection_reason    = $validated['rejection_reason'];
        $application->save();

        return response()->json([
            'message' => 'Lawyer application rejected successfully.',
        ]);
    }
}
