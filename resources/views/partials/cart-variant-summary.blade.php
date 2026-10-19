@php
    $parts = [];

    if (! empty($item['size'])) {
        $parts[] = 'Size '.$item['size'];
    }

    if (isset($item['sugar']) && $item['sugar'] !== '') {
        $sugar = (int) $item['sugar'];
        $parts[] = $sugar === 0 ? 'Không đường' : $sugar.'% Đường';
    }

    if (isset($item['ice']) && $item['ice'] !== '') {
        $ice = (int) $item['ice'];
        $parts[] = $ice === 0 ? 'Không đá' : $ice.'% Đá';
    }

    if (! empty($item['spice_label'])) {
        $parts[] = $item['spice_label'];
    }

    $toppingNames = collect($item['toppings'] ?? [])->pluck('name')->filter()->implode(', ');
    if ($toppingNames !== '') {
        $parts[] = 'Topping '.$toppingNames;
    }

    $variantSummary = implode(' | ', $parts);
@endphp
@if($variantSummary !== '')
    <p class="text-label-sm text-on-surface-variant mt-xs">{{ $variantSummary }}</p>
@endif
