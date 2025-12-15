<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts.app')]
#[Title('Time Picker Performance')]
class extends Component
{
    public int $pickerCount = 20;
    public int $interval = 5;
    public bool $showPickers = false;
    public bool $useMinMax = false;
    public string $minTime = '09:00';
    public string $maxTime = '17:00';

    public function loadPickers(): void
    {
        $this->showPickers = true;
    }

    public function resetPickers(): void
    {
        $this->showPickers = false;
    }
};
?>

<div
    x-data="{
        domCount: 0,
        initialDomCount: 0,
        domDiff: 0,
        measuring: false,
        
        countDomElements() {
            return document.getElementsByTagName('*').length;
        },
        
        measureBefore() {
            this.initialDomCount = this.countDomElements();
            this.measuring = true;
        },
        
        measureAfter() {
            this.$nextTick(() => {
                setTimeout(() => {
                    this.domCount = this.countDomElements();
                    this.domDiff = this.domCount - this.initialDomCount;
                    this.measuring = false;
                }, 500);
            });
        },
        
        resetMeasure() {
            this.domCount = 0;
            this.initialDomCount = 0;
            this.domDiff = 0;
        }
    }"
    x-init="initialDomCount = countDomElements()"
>
    <div class="mb-6">
        <flux:button variant="ghost" href="{{ route('index') }}" icon="arrow-left">
            Back to Examples
        </flux:button>
    </div>

    <flux:heading size="xl">Time Picker Performance</flux:heading>
    <flux:subheading class="mt-2">Performance Impact: DOM element scaling with multiple time pickers</flux:subheading>

    <flux:separator class="my-8" />

    <flux:heading size="lg">The Problem</flux:heading>
    <flux:text class="mt-2">
        Each <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">flux:time-picker</code> with small intervals 
        generates a large number of DOM elements for the dropdown options. With a 5-minute interval, that's 288 options per picker 
        (24 hours × 12 intervals per hour).
    </flux:text>

    <flux:text class="mt-4">
        When rendering multiple time pickers, this can significantly impact:
    </flux:text>
    <ul class="mt-4 ml-6 list-disc space-y-2 text-zinc-600 dark:text-zinc-400">
        <li>Initial page load time and Time to Interactive (TTI)</li>
        <li>Memory usage due to large DOM tree</li>
        <li>Hydration time for Livewire/Alpine components</li>
        <li>Scroll performance in forms with many pickers</li>
    </ul>

    <flux:separator class="my-8" />

    <flux:heading size="lg">DOM Element Counter</flux:heading>
    <flux:text class="mt-2 mb-4">
        This tool measures the DOM element count before and after rendering the time pickers:
    </flux:text>

    <flux:card class="mb-8">
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="text-center p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Initial DOM Count</flux:text>
                <div class="text-2xl font-bold mt-1" x-text="initialDomCount"></div>
            </div>
            <div class="text-center p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">Current DOM Count</flux:text>
                <div class="text-2xl font-bold mt-1" x-text="domCount || '—'"></div>
            </div>
            <div class="text-center p-4 rounded-lg" x-bind:class="domDiff > 1000 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-green-50 dark:bg-green-900/20'">
                <flux:text class="text-sm" x-bind:class="domDiff > 1000 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'">Elements Added</flux:text>
                <div class="text-2xl font-bold mt-1" x-bind:class="domDiff > 1000 ? 'text-red-700 dark:text-red-300' : 'text-green-700 dark:text-green-300'" x-text="domDiff ? '+' + domDiff : '—'"></div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <flux:field>
                <flux:label>Number of Pickers</flux:label>
                <flux:input type="number" wire:model="pickerCount" min="1" max="50" />
            </flux:field>
            <flux:field>
                <flux:label>Interval (minutes)</flux:label>
                <flux:select wire:model="interval">
                    <flux:select.option value="1">1 min (1440 options)</flux:select.option>
                    <flux:select.option value="5">5 min (288 options)</flux:select.option>
                    <flux:select.option value="15">15 min (96 options)</flux:select.option>
                    <flux:select.option value="30">30 min (48 options)</flux:select.option>
                    <flux:select.option value="60">60 min (24 options)</flux:select.option>
                </flux:select>
            </flux:field>
        </div>

        <div class="p-4 mb-6 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
            <div class="flex items-center gap-3 mb-4">
                <flux:switch wire:model.live="useMinMax" />
                <flux:label>Use min/max time constraints</flux:label>
            </div>
            
            @if($useMinMax)
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <flux:field>
                        <flux:label>Min Time</flux:label>
                        <flux:input type="time" wire:model="minTime" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Max Time</flux:label>
                        <flux:input type="time" wire:model="maxTime" />
                    </flux:field>
                </div>
                <flux:text class="mt-3 text-sm text-zinc-500">
                    With {{ $minTime }} - {{ $maxTime }} range and {{ $interval }}-min interval: 
                    @php
                        $minParts = explode(':', $minTime);
                        $maxParts = explode(':', $maxTime);
                        $minMinutes = (int)$minParts[0] * 60 + (int)$minParts[1];
                        $maxMinutes = (int)$maxParts[0] * 60 + (int)$maxParts[1];
                        $rangeMinutes = $maxMinutes - $minMinutes;
                        $optionCount = max(0, floor($rangeMinutes / $interval) + 1);
                    @endphp
                    <strong>{{ $optionCount }} options</strong> per picker (vs {{ 24 * 60 / $interval }} without constraints)
                </flux:text>
            @endif
        </div>

        <div class="flex gap-3">
            @if(!$showPickers)
                <flux:button 
                    variant="primary" 
                    wire:click="loadPickers"
                    x-on:click="measureBefore()"
                    icon="play"
                >
                    Load Time Pickers
                </flux:button>
            @else
                <flux:button 
                    variant="danger" 
                    wire:click="resetPickers"
                    x-on:click="resetMeasure()"
                    icon="arrow-path"
                >
                    Reset
                </flux:button>
            @endif
            
            <flux:button 
                variant="ghost"
                x-on:click="domCount = countDomElements(); domDiff = domCount - initialDomCount"
                icon="calculator"
            >
                Refresh Count
            </flux:button>
        </div>
    </flux:card>

    @if($showPickers)
        <flux:separator class="my-8" />

        <flux:heading size="lg">Lazy-Loaded Time Pickers</flux:heading>
        <flux:text class="mt-2 mb-4">
            These {{ $pickerCount }} time pickers are loaded via a lazy Livewire component to measure mount-to-render time:
        </flux:text>

        <flux:card 
            x-init="$nextTick(() => setTimeout(() => measureAfter(), 100))"
        >
            <livewire:time-pickers 
                :count="$pickerCount" 
                :interval="$interval" 
                :use-min-max="$useMinMax"
                :min-time="$minTime"
                :max-time="$maxTime"
                lazy 
            />
        </flux:card>
    @endif

    <flux:separator class="my-8" />

    <flux:heading size="lg">Performance Analysis</flux:heading>
    <flux:card class="mt-4">
        <div class="space-y-4">
            <div>
                <flux:heading size="base">DOM Elements per Picker</flux:heading>
                <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                    With a 5-minute interval: ~288 <code class="px-1 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-xs">&lt;option&gt;</code> elements + wrapper elements per picker
                </flux:text>
            </div>
            <div>
                <flux:heading size="base">Estimated Total (20 pickers × 5-min interval)</flux:heading>
                <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                    ~5,760 option elements + ~1,000 wrapper/container elements ≈ <strong>6,000+ DOM nodes</strong>
                </flux:text>
            </div>
            <div>
                <flux:heading size="base">With min/max constraints (09:00-17:00)</flux:heading>
                <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                    ~97 options per picker × 20 = ~1,940 option elements — <strong class="text-green-600 dark:text-green-400">66% reduction</strong>
                </flux:text>
            </div>
        </div>
    </flux:card>

    <flux:callout icon="light-bulb" class="mt-6">
        <flux:callout.heading>Suggested Solution</flux:callout.heading>
        <flux:callout.text>
            <p class="mt-2 text-sm">
                Add a <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-xs">lazy</code> attribute to 
                <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-xs">flux:time-picker</code> that defers 
                rendering of dropdown options until the picker is opened, and removes them from the DOM when closed.
            </p>
            <p class="mt-3 text-sm">
                This would dramatically reduce initial DOM size for forms with multiple time pickers, improving page load 
                performance and Time to Interactive (TTI).
            </p>
            <div class="mt-4 p-3 bg-zinc-100 dark:bg-zinc-800 rounded">
                <code class="text-sm text-zinc-700 dark:text-zinc-300">&lt;flux:time-picker interval="5" lazy /&gt;</code>
            </div>
        </flux:callout.text>
    </flux:callout>
</div>
