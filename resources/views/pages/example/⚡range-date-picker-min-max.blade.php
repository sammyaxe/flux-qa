<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts.app')]
#[Title('Date Picker Range Auto-Select')]
class extends Component
{
    public ?array $dateRange = null;
};
?>

<div>
    <div class="mb-6">
        <flux:button variant="ghost" href="{{ route('index') }}" icon="arrow-left">
            Back to Examples
        </flux:button>
    </div>

    <flux:heading size="xl">Date Picker Range Auto-Select</flux:heading>
    <flux:subheading class="mt-2">UX Issue: Fixed range length should auto-complete selection</flux:subheading>

    <flux:separator class="my-8" />

    <flux:heading size="lg">The Problem</flux:heading>
    <flux:text class="mt-2">
        When using <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">min-range</code> and 
        <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">max-range</code> with the same value,
        the user is forced to manually select both the start and end dates, even though there's only one possible range length.
    </flux:text>

    <flux:text class="mt-4">
        This creates poor UX because:
    </flux:text>
    <ul class="mt-4 ml-6 list-disc space-y-2 text-zinc-600 dark:text-zinc-400">
        <li>The user must click twice when a single click would suffice</li>
        <li>There's only one valid end date after selecting the start date</li>
        <li>It's not intuitive - users expect the obvious choice to be made for them</li>
        <li>Common use case: "Select a 10-day period" should auto-complete after picking the start</li>
    </ul>

    <flux:separator class="my-8" />

    <flux:heading size="lg">Current Behavior</flux:heading>
    <flux:text class="mt-2 mb-4">
        Try selecting a date range below. Notice you must manually select both dates, 
        even though the end date is predetermined (exactly 10 days from start):
    </flux:text>

    <flux:card class="mb-8">
        <flux:field>
            <flux:label>Select a 10-day period</flux:label>
            <flux:date-picker 
                mode="range" 
                min-range="10" 
                max-range="10" 
                wire:model="dateRange"
                placeholder="Pick start date..."
            />
            <flux:description>
                Both min-range and max-range are set to 10 days.
            </flux:description>
        </flux:field>

        @if($dateRange)
            <div class="mt-4">
                <flux:text class="text-sm">
                    Selected range: 
                    <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded">
                        {{ $dateRange[0] ?? 'null' }} → {{ $dateRange[1] ?? 'null' }}
                    </code>
                </flux:text>
            </div>
        @endif
    </flux:card>

    <flux:separator class="my-8" />

    <flux:heading size="lg">Expected Behavior</flux:heading>
    <flux:text class="mt-2 mb-4">
        When <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">min-range === max-range</code>,
        the date picker should automatically select the end date after the user picks the start date:
    </flux:text>

    <flux:card class="mb-6">
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <flux:badge color="zinc" class="mt-0.5">1</flux:badge>
                <flux:text>User clicks on a start date (e.g., December 15)</flux:text>
            </div>
            <div class="flex items-start gap-3">
                <flux:badge color="green" class="mt-0.5">2</flux:badge>
                <flux:text>
                    <strong>Automatically:</strong> End date is set to December 25 (10 days later) and the picker closes
                </flux:text>
            </div>
        </div>
    </flux:card>

    <flux:heading size="lg" class="mt-8">Code Example</flux:heading>
    <flux:card class="mt-4">
        <pre class="text-sm overflow-x-auto"><code class="text-zinc-600 dark:text-zinc-300">&lt;!-- When min-range equals max-range, auto-complete should trigger --&gt;
&lt;flux:date-picker 
    mode="range" 
    min-range="10" 
    max-range="10"
    wire:model="dateRange"
/&gt;

&lt;!-- User clicks Dec 15 → automatically selects Dec 15 to Dec 25 --&gt;</code></pre>
    </flux:card>

    <flux:callout icon="light-bulb" class="mt-6">
        <flux:callout.heading>Suggested Implementation</flux:callout.heading>
        <flux:callout.text>
            After the first date is selected, if <code class="px-1 py-0.5 bg-zinc-200 dark:bg-zinc-600 rounded text-xs">minRange === maxRange</code>, 
            automatically calculate and set the end date as <code class="px-1 py-0.5 bg-zinc-200 dark:bg-zinc-600 rounded text-xs">startDate + minRange</code> days,
            then close the picker or confirm the selection.
        </flux:callout.text>
    </flux:callout>
</div>
