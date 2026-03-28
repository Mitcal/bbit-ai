<?php

namespace App\Ai\Tools;

use App\Enums\ServiceType;
use App\Models\Booking;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CreateBooking implements Tool
{
    public ?int $bookingId = null;

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Creates a window cleaning booking. Only call this after confirming the service and date with the user.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        if (empty($request['service']) || empty($request['date'])) {
            return 'Missing required fields. Please provide both service and date before creating a booking.';
        }

        $booking = Booking::create([
            'service' => $request['service'],
            'date' => $request['date'],
        ]);

        $this->bookingId = $booking->id;

        return "Booking #{$booking->id} created successfully for {$request['service']} on {$request['date']}.";
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'service' => $schema->string()->enum(ServiceType::class)->required(),
            'date' => $schema->string()->description('The booking date in Y-m-d format (e.g. "2026-04-15")')->required(),
        ];
    }
}
