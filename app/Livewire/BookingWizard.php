<?php

namespace App\Livewire;

use App\Ai\Agents\BookingAgent;
use App\Ai\Tools\CreateBooking;
use App\Enums\ServiceType;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;
use Laravel\Ai\Streaming\Events\TextDelta;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Book a Window Clean')]
class BookingWizard extends Component
{
    #[Validate(['required', new Enum(ServiceType::class)])]
    public string $service = '';

    #[Validate('required|date|after_or_equal:today')]
    public string $preferredDate = '';

    public bool $chatStarted = false;

    public bool $isLoading = false;

    public bool $bookingCreated = false;

    public ?int $bookingId = null;

    public ?string $errorMessage = null;

    /** @var array<int, array{role: string, content: string}> */
    public array $messages = [];

    public string $userInput = '';

    public function startChat(): void
    {
        $this->validate();

        $this->chatStarted = true;
        $this->isLoading = true;
        $this->errorMessage = null;
        $formattedDate = Carbon::parse($this->preferredDate)->format('l, j F Y');
        $this->messages[] = ['role' => 'user', 'content' => "I'd like to book {$this->service} on $formattedDate."];

        $this->js('$wire.streamResponse()');
    }

    public function sendMessage(): void
    {
        $userMessage = trim($this->userInput);

        if ($userMessage === '' || $this->isLoading) {
            return;
        }

        $key = 'ai-booking:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, maxAttempts: 10)) {
            $this->errorMessage = 'You\'re sending messages too quickly. Please wait a moment and try again.';

            return;
        }

        RateLimiter::hit($key);

        $this->userInput = '';
        $this->isLoading = true;
        $this->errorMessage = null;
        $this->messages[] = ['role' => 'user', 'content' => $userMessage];

        $this->js('$wire.streamResponse()');
    }

    public function streamResponse(): void
    {
        try {
            $tool = new CreateBooking;
            $userMessage = end($this->messages)['content'];
            $previousMessages = array_slice($this->messages, 0, -1);

            $agent = new BookingAgent(
                previousMessages: $previousMessages,
                createBookingTool: $tool,
            );

            $fullResponse = '';
            $stream = $agent->stream($userMessage);

            foreach ($stream as $event) {
                if ($event instanceof TextDelta) {
                    $this->stream(content: $event->delta, to: 'streamingResponse');
                    $fullResponse .= $event->delta;
                }
            }

            $this->messages[] = ['role' => 'assistant', 'content' => $this->cleanResponse($fullResponse)];

            if ($tool->bookingId !== null) {
                $this->bookingId = $tool->bookingId;
                $this->bookingCreated = true;
            }
        } catch (\Throwable $e) {
            \Log::debug($e->getMessage());
            $this->errorMessage = 'Sorry, something went wrong connecting to the assistant. Please try again.';
        } finally {
            $this->isLoading = false;
        }
    }

    private function cleanResponse(string $response): string
    {
        return trim(preg_replace('/<[A-Z][A-Z_]*>.*?<\/[A-Z][A-Z_]*>/s', '', $response));
    }

    public function render(): View
    {
        return view('livewire.booking-wizard');
    }
}
