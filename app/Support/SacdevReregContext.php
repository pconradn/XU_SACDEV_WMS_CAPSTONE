<?php

namespace App\Support;

use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SacdevReregContext
{
    public const SESSION_KEY = 'sacdev_sy_id';

    public static function getSyId(Request $request): int
    {
        $syId = (int) $request->session()->get(self::SESSION_KEY);

        if ($syId > 0) {
            return $syId;
        }

        // default to active SY
        $active = (int) SchoolYear::activeId();
        $request->session()->put(self::SESSION_KEY, $active);

        return $active;
    }

    public static function setSyId(Request $request, int $syId): void
    {
        $request->session()->put(self::SESSION_KEY, $syId);
    }
}
