<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDesktopDevice
{
    /**
     * Matches common mobile/tablet UA signatures. Not bulletproof (a spoofed
     * UA string can bypass this), but it's the practical first line of
     * defense the spec asks for — the real anti-cheat rigor comes from the
     * fullscreen/violation-tracking layer in Part 3, not from UA sniffing.
     */
    private const MOBILE_PATTERN = '/Mobi|Android(?!.*Tablet)|iPhone|iPod|IEMobile|BlackBerry|Opera Mini/i';

    private const TABLET_PATTERN = '/iPad|Android(?=.*Tablet)|Tablet|Kindle|Silk/i';

    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = (string) $request->userAgent();

        if (preg_match(self::MOBILE_PATTERN, $userAgent) || preg_match(self::TABLET_PATTERN, $userAgent)) {
            return response()->view('assessments.device-blocked', [], 403);
        }

        return $next($request);
    }
}