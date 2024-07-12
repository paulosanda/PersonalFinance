<?php

//app/Helpers/inputMoneyHelper.php

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
