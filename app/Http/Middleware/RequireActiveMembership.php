<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireActiveMembership
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Admin always bypasses membership check
        if ($user->is_admin) {
            return $next($request);
        }

        // Kid accounts: check parent's membership
        if ($user->isKid() && $user->parent_id) {
            $parent = $user->parent;
            if ($parent && $parent->is_admin) {
                return $next($request);
            }
            $parentMembership = $parent?->membership;
            if ($parentMembership && (
                ($parentMembership->status === 'active' && !$parentMembership->expires_at?->isPast()) ||
                ($parentMembership->status === 'trial' && !$parentMembership->trial_ends_at?->isPast())
            )) {
                return $next($request);
            }
            return redirect()->route('membership.expired');
        }

        $membership = $user->membership;

        if (!$membership || $membership->status === 'banned') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Tài khoản đã bị khóa.');
        }

        if ($membership->status === 'expired'
            || ($membership->status === 'trial' && $membership->trial_ends_at?->isPast())
            || ($membership->status === 'active' && $membership->expires_at?->isPast())
        ) {
            return redirect()->route('membership.expired');
        }

        return $next($request);
    }
}
