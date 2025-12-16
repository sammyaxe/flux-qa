<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

new
#[Layout('layouts.app')]
#[Title('Accordion State Control')]
class extends Component
{
    #[Url]
    public bool $refund = false;

    #[Url]
    public bool $discounts = false;

    #[Url]
    public bool $tracking = false;

    #[Url]
    public ?string $tab = 'account';
};
?>

<div>
    <div class="mb-6">
        <flux:button variant="ghost" href="{{ route('index') }}" icon="arrow-left">
            Back to Examples
        </flux:button>
    </div>

    <flux:heading size="xl">Accordion State Control</flux:heading>
    <flux:subheading class="mt-2">Bug: Accordion doesn't respect initial state from URL parameters</flux:subheading>

    <flux:separator class="my-8" />

    <flux:heading size="lg">The Problem</flux:heading>
    <flux:text class="mt-2">
        Flux accordion supports <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">wire:model.self</code> on individual accordion items
        to bind their open/close state. However, the component doesn't respect the initial state when the model is bound to URL parameters.
    </flux:text>

    <flux:callout icon="exclamation-triangle" variant="warning" class="mt-4">
        <flux:callout.heading>Try this:</flux:callout.heading>
        <flux:callout.text>
            <ol class="list-decimal ml-4 space-y-1">
                <li>Click on an accordion item below — notice the URL updates</li>
                <li>Copy the URL with the parameter (e.g., <code class="px-1 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-xs">?refund=true</code>)</li>
                <li>Open a new tab and paste that URL</li>
                <li><strong>Bug:</strong> The accordion item should be open, but it's not</li>
            </ol>
        </flux:callout.text>
    </flux:callout>

    <flux:separator class="my-8" />

    <flux:heading size="lg">Accordion with wire:model.self</flux:heading>
    <flux:text class="mt-2 mb-4">
        Each accordion item is bound to a Livewire property with <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">#[Url]</code>.
        State changes sync to URL, but initial state from URL is ignored:
    </flux:text>

    <flux:card class="mb-4">
        <flux:accordion>
            <flux:accordion.item wire:model.self="refund">
                <flux:accordion.heading>What's your refund policy?</flux:accordion.heading>
                <flux:accordion.content>
                    If you are not satisfied with your purchase, we offer a 30-day money-back guarantee.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item wire:model.self="discounts">
                <flux:accordion.heading>Do you offer discounts for bulk purchases?</flux:accordion.heading>
                <flux:accordion.content>
                    Yes, we offer special discounts for bulk orders. Please reach out to our sales team.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item wire:model.self="tracking">
                <flux:accordion.heading>How do I track my order?</flux:accordion.heading>
                <flux:accordion.content>
                    Once your order is shipped, you will receive an email with a tracking number.
                </flux:accordion.content>
            </flux:accordion.item>
        </flux:accordion>
    </flux:card>

    <flux:card class="mb-8">
        <flux:heading size="sm" class="mb-2">Current State (from Livewire)</flux:heading>
        <div class="flex flex-wrap gap-2">
            <flux:badge :color="$refund ? 'lime' : 'zinc'">refund: {{ $refund ? 'true' : 'false' }}</flux:badge>
            <flux:badge :color="$discounts ? 'lime' : 'zinc'">discounts: {{ $discounts ? 'true' : 'false' }}</flux:badge>
            <flux:badge :color="$tracking ? 'lime' : 'zinc'">tracking: {{ $tracking ? 'true' : 'false' }}</flux:badge>
        </div>
    </flux:card>

    <flux:separator class="my-8" />

    <flux:heading size="lg">How Flux Tabs Handle This (Correctly)</flux:heading>
    <flux:text class="mt-2 mb-4">
        Flux tabs support <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">wire:model</code> and correctly respect initial state from URL.
        Try visiting <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">?tab=billing</code> — the Billing tab will be selected:
    </flux:text>

    <flux:card class="mb-4">
        <flux:tabs wire:model="tab">
            <flux:tab name="account">Account</flux:tab>
            <flux:tab name="security">Security</flux:tab>
            <flux:tab name="billing">Billing</flux:tab>
        </flux:tabs>
    </flux:card>

    <flux:card class="mb-8">
        <flux:heading size="sm" class="mb-2">Current State</flux:heading>
        <flux:badge :color="$tab ? 'lime' : 'zinc'">tab: {{ $tab }}</flux:badge>
    </flux:card>

    <flux:separator class="my-8" />

    <flux:heading size="lg">Test Links</flux:heading>
    <flux:text class="mt-2 mb-4">
        Click these links to test initial state behavior. The accordion should open to the specified item:
    </flux:text>

    <div class="flex flex-wrap gap-3 mb-8">
        <flux:button variant="outline" href="{{ route('examples.accordion-state', ['refund' => 'true']) }}">
            Open Refund
        </flux:button>
        <flux:button variant="outline" href="{{ route('examples.accordion-state', ['discounts' => 'true']) }}">
            Open Discounts
        </flux:button>
        <flux:button variant="outline" href="{{ route('examples.accordion-state', ['refund' => 'true', 'tracking' => 'true']) }}">
            Open Refund + Tracking
        </flux:button>
        <flux:button variant="outline" href="{{ route('examples.accordion-state', ['tab' => 'billing']) }}">
            Tab: Billing (works ✓)
        </flux:button>
    </div>

    <flux:separator class="my-8" />

    <flux:heading size="lg">Expected Fix</flux:heading>
    <flux:text class="mt-2 mb-4">
        The accordion component should check the initial value of <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">wire:model.self</code>
        on mount and set its expanded state accordingly, just like tabs do with <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">wire:model</code>.
    </flux:text>

    <flux:card>
        <pre class="text-sm overflow-x-auto"><code class="text-zinc-600 dark:text-zinc-300">&lt;!-- Current usage (state changes work, initial state doesn't) --&gt;
&lt;flux:accordion&gt;
    &lt;flux:accordion.item wire:model.self="refund"&gt;
        &lt;flux:accordion.heading&gt;Refund Policy&lt;/flux:accordion.heading&gt;
        &lt;flux:accordion.content&gt;...&lt;/flux:accordion.content&gt;
    &lt;/flux:accordion.item&gt;
&lt;/flux:accordion&gt;

&lt;!-- Livewire Component --&gt;
&lt;?php
#[Url]
public bool $refund = false;

// When visiting ?refund=true, the accordion item should be open
// Currently: it stays closed (bug)
// Expected: it opens automatically
?&gt;</code></pre>
    </flux:card>

    <flux:callout icon="light-bulb" class="mt-6">
        <flux:callout.heading>Implementation Note</flux:callout.heading>
        <flux:callout.text>
            The Alpine component for <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">flux:accordion.item</code> likely initializes
            its <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">expanded</code> state before Livewire entanglement syncs the value.
            The fix would be to read the initial entangled value during Alpine's <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">init()</code>.
        </flux:callout.text>
    </flux:callout>
</div>
