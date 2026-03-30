# Bright Windows — AI-Powered Booking System

An AI-powered window cleaning booking system built with Laravel 13, Livewire 4, and the Laravel AI framework. The Laravel and Livewire is the standard stack Bumblebee IT Solutions uses to build applications. Customers pick a service and preferred date, then chat with an AI assistant that verifies availability and completes their booking in real time.

Built by [Bumblebee IT Solutions](https://bumblebeeitsolutions.com/) — a UK-based IT agency specialising in bespoke web applications.

<p align="center">
  <img src="https://bumblebeeitsolutions.com/img/logo.png" />
</p>

---

## How It Works

1. **Choose a service** — Residential or Commercial window cleaning
2. **Pick a date** — Select your preferred appointment date
3. **Chat with AI** — The assistant checks availability and confirms your booking instantly

The AI agent uses tool calling to check real-time availability, suggest alternatives if a date is taken, and create the booking once the customer confirms.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.5, Laravel 13 |
| AI | Laravel AI v0 (OpenAI) |
| Reactive UI | Livewire 4, Alpine.js |
| Components | Flux UI v2 (free + pro) |
| Styling | Tailwind CSS v4 |
| Calendar | FullCalendar.js 6 |

---

## Getting Started

### Requirements

- PHP 8.5+
- Composer
- Node.js & npm
- An [OpenAI](https://platform.openai.com/) API key

### Installation

```bash
git clone <repo-url>
cd ai-booking
cp .env.example .env
```

Add your API key to `.env`:

```env
OPENAI_API_KEY=your_key_here
```

Then run the setup script:

```bash
composer run setup
```

This installs dependencies, generates the app key, runs migrations, and builds frontend assets.

### Development

```bash
composer run dev
```

Starts the Laravel server, queue listener, log viewer (Pail), and Vite dev server concurrently.

---

## Project Structure

```
app/
├── Ai/
│   ├── Agents/BookingAgent.php        # AI agent with conversation logic
│   └── Tools/
│       ├── CheckDateAvailability.php  # Checks if a date is free
│       └── CreateBooking.php          # Creates booking records
├── Livewire/
│   ├── BookingCalendar.php            # Calendar view of all bookings
│   └── BookingWizard.php              # Multi-step booking form with AI chat
├── Models/
│   └── Booking.php
└── Enums/
    └── ServiceType.php                # Residential / Commercial
```

---

## Key Features

- **Streaming AI responses** — Real-time message output using Laravel AI's `TextDelta` events
- **Tool-based agent** — The AI calls structured tools to check availability and create bookings, rather than generating free-form SQL or code
- **Availability suggestions** — If a date is taken, the agent automatically suggests the 5 nearest available dates
- **Calendar overview** — All bookings displayed in a FullCalendar.js view
- **Rate limiting** — IP-based limiter (10 messages/minute) to prevent abuse
- **Dark mode** — Full dark mode support via Flux UI
- **No login required** — Public-facing booking system

---

## About Bumblebee IT Solutions

This project was developed by **[Bumblebee IT Solutions](https://bumblebeeitsolutions.com/)**, a UK-based IT agency building bespoke web applications for businesses of all sizes.

- [Subscribe to the newsletter](https://bumblebeeitsolutions.com/#newsletter) for updates and insights
- [Follow on Facebook](https://www.facebook.com/bumblebeeituk) for the latest news