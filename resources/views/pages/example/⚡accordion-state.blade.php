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
    public ?string $accordion = null;

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
    <flux:subheading class="mt-2">Feature Request: Allow controlling accordion open/close state via Livewire</flux:subheading>

    <flux:separator class="my-8" />

    <flux:heading size="lg">The Problem</flux:heading>
    <flux:text class="mt-2">
        Currently, Flux accordion does not support <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">wire:model</code> binding, 
        making it impossible to control which accordion item is open via Livewire state. This prevents common use cases like:
    </flux:text>
    <ul class="mt-4 ml-6 list-disc space-y-2 text-zinc-600 dark:text-zinc-400">
        <li>Pre-opening a specific accordion item on page load</li>
        <li>Syncing accordion state to URL using <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">#[Url]</code> attribute</li>
        <li>Programmatically opening/closing accordion items from Livewire actions</li>
        <li>Deep linking to a specific FAQ answer</li>
    </ul>

    <flux:separator class="my-8" />

    <flux:heading size="lg">Current Accordion (No State Control)</flux:heading>
    <flux:text class="mt-2 mb-4">
        This accordion works, but there's no way to control its state from Livewire:
    </flux:text>

    <flux:card class="mb-8">
        <flux:accordion>
            <flux:accordion.item>
                <flux:accordion.heading>What's your refund policy?</flux:accordion.heading>
                <flux:accordion.content>
                    If you are not satisfied with your purchase, we offer a 30-day money-back guarantee.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading>Do you offer discounts for bulk purchases?</flux:accordion.heading>
                <flux:accordion.content>
                    Yes, we offer special discounts for bulk orders. Please reach out to our sales team.
                </flux:accordion.content>
            </flux:accordion.item>

            <flux:accordion.item>
                <flux:accordion.heading>How do I track my order?</flux:accordion.heading>
                <flux:accordion.content>
                    Once your order is shipped, you will receive an email with a tracking number.
                </flux:accordion.content>
            </flux:accordion.item>
        </flux:accordion>
    </flux:card>

    <flux:separator class="my-8" />

    <flux:heading size="lg">How Flux Tabs Handle This</flux:heading>
    <flux:text class="mt-2 mb-4">
        Flux tabs support <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">wire:model</code>, allowing state control. 
        Try changing tabs - notice the URL updates with <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">?tab=...</code>:
    </flux:text>

    <flux:card class="mb-8">
        <flux:tabs wire:model="tab">
            <flux:tab name="account">Account</flux:tab>
            <flux:tab name="security">Security</flux:tab>
            <flux:tab name="billing">Billing</flux:tab>
        </flux:tabs>

        <div class="mt-4">
            <flux:text class="text-sm">
                Current tab value: <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded">{{ $tab }}</code>
            </flux:text>
        </div>
    </flux:card>

    <flux:separator class="my-8" />

    <flux:heading size="lg">Proposed Solution</flux:heading>
    <flux:text class="mt-2 mb-4">
        Accordion should support the same pattern as tabs, with support for both <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">string</code> (single item) 
        and <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">array</code> (multiple items) types:
    </flux:text>

    <flux:heading size="base" class="mt-6 mb-3">Single Item Open (string)</flux:heading>
    <flux:card class="mb-6">
        <pre class="text-sm overflow-x-auto"><code class="text-zinc-600 dark:text-zinc-300">&lt;!-- Single item mode (default) --&gt;
&lt;flux:accordion wire:model="accordion"&gt;
    &lt;flux:accordion.item name="refund"&gt;
        &lt;flux:accordion.heading&gt;What's your refund policy?&lt;/flux:accordion.heading&gt;
        &lt;flux:accordion.content&gt;...&lt;/flux:accordion.content&gt;
    &lt;/flux:accordion.item&gt;

    &lt;flux:accordion.item name="discounts"&gt;
        &lt;flux:accordion.heading&gt;Do you offer discounts?&lt;/flux:accordion.heading&gt;
        &lt;flux:accordion.content&gt;...&lt;/flux:accordion.content&gt;
    &lt;/flux:accordion.item&gt;
&lt;/flux:accordion&gt;

&lt;!-- Livewire Component --&gt;
&lt;?php
#[Url]
public ?string $accordion = 'refund'; // Pre-opens "refund" item
?&gt;</code></pre>
    </flux:card>

    <flux:heading size="base" class="mt-6 mb-3">Multiple Items Open (array)</flux:heading>
    <flux:text class="mb-3 text-sm text-zinc-600 dark:text-zinc-400">
        For accordions that allow multiple items open simultaneously (e.g., FAQ sections, settings panels):
    </flux:text>
    <flux:card>
        <pre class="text-sm overflow-x-auto"><code class="text-zinc-600 dark:text-zinc-300">&lt;!-- Multiple items mode --&gt;
&lt;flux:accordion wire:model="accordion" multiple&gt;
    &lt;flux:accordion.item name="refund"&gt;
        &lt;flux:accordion.heading&gt;What's your refund policy?&lt;/flux:accordion.heading&gt;
        &lt;flux:accordion.content&gt;...&lt;/flux:accordion.content&gt;
    &lt;/flux:accordion.item&gt;

    &lt;flux:accordion.item name="discounts"&gt;
        &lt;flux:accordion.heading&gt;Do you offer discounts?&lt;/flux:accordion.heading&gt;
        &lt;flux:accordion.content&gt;...&lt;/flux:accordion.content&gt;
    &lt;/flux:accordion.item&gt;

    &lt;flux:accordion.item name="tracking"&gt;
        &lt;flux:accordion.heading&gt;How do I track my order?&lt;/flux:accordion.heading&gt;
        &lt;flux:accordion.content&gt;...&lt;/flux:accordion.content&gt;
    &lt;/flux:accordion.item&gt;
&lt;/flux:accordion&gt;

&lt;!-- Livewire Component --&gt;
&lt;?php
#[Url(as: 'open')]
public array $accordion = ['refund', 'tracking']; // Pre-opens multiple items
?&gt;</code></pre>
    </flux:card>

    <flux:callout icon="light-bulb" class="mt-6">
        <flux:callout.heading>URL Format for Arrays</flux:callout.heading>
        <flux:callout.text>
            With array binding, the URL would look like: <code class="px-1.5 py-0.5 bg-zinc-100 dark:bg-zinc-700 rounded text-sm">?open[]=refund&open[]=tracking</code>
        </flux:callout.text>
    </flux:callout>
</div>
