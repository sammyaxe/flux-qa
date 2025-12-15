<?php

use Livewire\Attributes\Lazy;
use Livewire\Component;

new
#[Lazy]
class extends Component
{
    public int $count = 20;
    public int $interval = 5;
    public bool $useMinMax = false;
    public string $minTime = '09:00';
    public string $maxTime = '17:00';
    public ?float $mountTime = null;
    public array $times = [];

    public function mount(): void
    {
        $this->mountTime = microtime(true) * 1000;
        
        // Parse min/max times to minutes
        $minParts = explode(':', $this->minTime);
        $maxParts = explode(':', $this->maxTime);
        $minMinutes = (int)$minParts[0] * 60 + (int)$minParts[1];
        $maxMinutes = (int)$maxParts[0] * 60 + (int)$maxParts[1];
        
        // Generate random pre-selected times for each picker
        // This forces Livewire to traverse options to find selected value
        for ($i = 1; $i <= $this->count; $i++) {
            if ($this->useMinMax) {
                // Generate time within min/max range
                $rangeMinutes = $maxMinutes - $minMinutes;
                $randomOffset = floor(rand(0, $rangeMinutes) / $this->interval) * $this->interval;
                $totalMinutes = $minMinutes + $randomOffset;
            } else {
                // Generate any time in 24-hour range
                $hour = rand(0, 23);
                $minute = floor(rand(0, 59) / $this->interval) * $this->interval;
                $totalMinutes = $hour * 60 + $minute;
            }
            
            $hour = floor($totalMinutes / 60);
            $minute = $totalMinutes % 60;
            $this->times[$i] = sprintf('%02d:%02d', $hour, $minute);
        }
    }
};
?>

<div 
    x-data="{ 
        mountTime: {{ $mountTime }},
        renderTime: null,
        duration: null,
        init() {
            this.renderTime = performance.now() + performance.timeOrigin;
            this.duration = this.renderTime - this.mountTime;
        }
    }"
>
    <flux:card class="mb-4">
        <div class="flex items-center gap-2 mb-2">
            <flux:icon name="clock" class="size-5" />
            <flux:heading size="sm">Render Timing</flux:heading>
        </div>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <flux:text>Mount timestamp:</flux:text>
                <flux:badge color="zinc" x-text="mountTime.toFixed(2) + ' ms'"></flux:badge>
            </div>
            <div>
                <flux:text>Render timestamp:</flux:text>
                <flux:badge color="zinc" x-text="renderTime.toFixed(2) + ' ms'"></flux:badge>
            </div>
            <div class="col-span-2 flex items-center gap-2">
                <flux:text class="font-medium">Mount → Render duration:</flux:text>
                <flux:badge color="amber" x-text="duration.toFixed(2) + ' ms'"></flux:badge>
            </div>
        </div>
    </flux:card>

    <flux:callout icon="information-circle" class="mb-4">
        <flux:callout.text>
            Each picker has a random pre-selected time bound via <code class="px-1 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-xs">wire:model</code>, 
            forcing Livewire to traverse all options to find and highlight the selected value.
            @if($useMinMax)
                <br><strong>Constraints:</strong> min="{{ $minTime }}" max="{{ $maxTime }}"
            @endif
        </flux:callout.text>
    </flux:callout>

    <div class="grid gap-4 sm:grid-cols-2">
        @for($i = 1; $i <= $count; $i++)
            <flux:field>
                <flux:label>Time Picker #{{ $i }} <span class="text-zinc-400 font-normal">({{ $times[$i] }})</span></flux:label>
                @if($useMinMax)
                    <flux:time-picker 
                        wire:model="times.{{ $i }}" 
                        :interval="$interval" 
                        :min="$minTime" 
                        :max="$maxTime" 
                    />
                @else
                    <flux:time-picker 
                        wire:model="times.{{ $i }}" 
                        :interval="$interval" 
                    />
                @endif
            </flux:field>
        @endfor
    </div>
</div>
