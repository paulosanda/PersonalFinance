<?php
//app/Helpers/helpers.php

function formatCurrency(string $value): string
{
    $formattedValue = number_format(floatval($value), 2, ',', '.');
    if ($value < 0) {
        return '<span class="text-red-500">R$ ' . $formattedValue . '</span>';
    } else {
        return 'R$ ' . $formattedValue;
    }
}

function formatMoneyToInput($value): float
{
    if (is_string($value)) {
        if (str_contains($value, ',')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }
    }

    return round((float) $value, 2);
}
