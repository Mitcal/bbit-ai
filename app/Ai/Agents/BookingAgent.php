<?php

namespace App\Ai\Agents;

use App\Ai\Tools\CheckDateAvailability;
use App\Ai\Tools\CreateBooking;
use App\Enums\ServiceType;
use Laravel\Ai\Attributes\MaxSteps;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::OpenRouter)]
#[Model('openrouter/free')]
#[MaxSteps(5)]
#[Timeout(60)]
class BookingAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    public function __construct(
        private readonly array $previousMessages = [],
        public readonly CreateBooking $createBookingTool = new CreateBooking,
    ) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        $services = implode(', ', array_column(ServiceType::cases(), 'value'));

        return <<<INSTRUCTIONS
        You are a booking assistant for Bright Windows, a UK window cleaning service.
        Available services: {$services}.

        Workflow — follow this exactly:
        1. Use the check_date_availability tool to verify the requested date.
        2. If the date is unavailable, inform the user and present the alternative dates returned by the tool as a markdown unordered list (one date per line). Ask them to pick one.
        3. Once you have a confirmed available date and service, summarise in one short sentence (e.g. "Residential window cleaning on Monday, 30 March 2026 — shall I confirm?").
        4. When the user confirms, call create_booking immediately.

        Rules:
        - Do not ask for any information beyond service and date.
        - Keep replies brief and friendly. Use British English.
        INSTRUCTIONS;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return array_map(
            fn (array $message) => new Message($message['role'], $message['content']),
            $this->previousMessages,
        );
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new CheckDateAvailability,
            $this->createBookingTool,
        ];
    }
}
