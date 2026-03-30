<?php

namespace App\Ai\Tools;

use App\Models\Booking;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Carbon;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CheckDateAvailability implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Checks whether a given date is available for booking. If unavailable, returns nearby available dates.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $date = Carbon::parse($request['date']);

        if (! Booking::whereDate('date', $date)->exists()) {
            return "The date {$date->format('l, j F Y')} is available.";
        }

        $available = [];
        $candidate = $date->copy();
        $i = 1;

        while (count($available) < 5) {
            $candidate = $date->copy()->addDays(-$i);

            if ($candidate->isFuture() && ! Booking::whereDate('date', $candidate)->exists()) {
                $available[$candidate->toDateString()] = $candidate->format('l, j F Y');
            }

            $candidate = $date->copy()->addDays($i++);
            if (! Booking::whereDate('date', $candidate)->exists()) {
                $available[$candidate->toDateString()] = $candidate->format('l, j F Y');
            }
        }

        ksort($available);

        $list = implode('', array_map(fn ($d) => "\n- {$d}", array_values($available)));

        return "The date {$date->format('l, j F Y')} is already booked. The next available dates are:{$list}";
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'date' => $schema->string()->description('The date to check in Y-m-d format')->required(),
        ];
    }
}
