<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Assign a role to a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignRole(Request $request)
    {
        // Ensure the user making the request is a Super Admin
        if (!auth()->user()->hasRole('super-admin')) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // Check if user exists
            'role' => 'required|in:super-admin,expenses,accountant', // Valid roles
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        try {
            // Find the user by ID
            $user = User::findOrFail($request->input('user_id'));
            $role = $request->input('role');

            // Check if the user already has the role
            if ($user->hasRole($role)) {
                return response()->json([
                    'message' => "User already has the {$role} role."
                ], Response::HTTP_OK);
            }

            // Assign the role to the user
            $user->assignRole($role);

            return response()->json([
                'message' => "Role {$role} assigned successfully."
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to assign role.',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
