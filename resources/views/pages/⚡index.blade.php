<?php

use Livewire\Component;

new class extends Component
{

    public $examples = [];

    function mount(){

        $this->examples = collect(Route::getRoutes()->getRoutes())
        ->filter(fn ($route) => str_starts_with($route->getName() ?? '', 'examples.'))
        ->map(fn ($route) => [
            'name' => $route->getName(),
            'uri' => $route->uri(),
            'label' => str($route->getName())
                ->after('examples.')
                ->replace('.', ' / ')
                ->replace('-', ' ')
                ->title()
                ->toString(),
        ])
        ->sortBy('label')
        ->values();

    }

};
?>

<div>
    <flux:heading size="xl" class="mb-6">Flux QA Examples</flux:heading>

    <flux:text class="mb-8 text-zinc-600 dark:text-zinc-400">
        A collection of bug reproductions and feature request examples for Flux UI.
    </flux:text>

    @if(empty($examples))
        <flux:callout icon="information-circle" class="mb-6">
            <flux:callout.heading>No examples yet</flux:callout.heading>
            <flux:callout.text>
                Create views in <code class="text-sm bg-zinc-100 dark:bg-zinc-700 px-1.5 py-0.5 rounded">resources/views/pages/examples/</code> and add routes in the examples group.
            </flux:callout.text>
        </flux:callout>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($examples as $example)
                <flux:card class="flex flex-col justify-between">
                    <div class="mb-4">
                        <flux:heading size="lg" class="mb-2">{{ $example['label'] }}</flux:heading>
                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                            /{{ $example['uri'] }}
                        </flux:text>
                    </div>
                    <flux:button variant="primary" href="{{ route($example['name']) }}" class="w-full">
                        View Example
                    </flux:button>
                </flux:card>
            @endforeach
        </div>
    @endif
</div>