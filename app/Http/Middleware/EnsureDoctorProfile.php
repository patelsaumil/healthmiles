<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Doctor;

class EnsureDoctorProfile
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->role === 'doctor' && !$user->doctor) {
            $existing = Doctor::where('email', $user->email)->first();

            if ($existing && !$existing->user_id) {
                $existing->user_id = $user->id;
                $existing->active  = $existing->active ?? 1;
                $existing->name    = $existing->name ?: $user->name;
                $existing->save();
            } elseif (!$existing) {
                Doctor::firstOrCreate(
                    ['user_id' => $user->id],
                    ['name' => $user->name, 'email' => $user->email, 'active' => 1]
                );
            }
            $user->unsetRelation('doctor');
            $user->load('doctor');
        }

        return $next($request);
    }
}
