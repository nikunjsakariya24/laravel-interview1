<?php

use App\Models\Prize;

$current_probability = floatval(Prize::sum('probability'));
?>
{{-- TODO: add Message logic here --}}
<div class="alert alert-danger">
    Sum of all prizes probability must be 100% currently is {{ $current_probability }}% You have yet to add {{
    floatval(100 - $current_probability) }}% to the prize.
</div>
