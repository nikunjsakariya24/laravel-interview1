<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prize extends Model
{

    protected $guarded = ['id'];


    public static function nextPrize()
    {
        // Generate a random number between 0 and 1
        $randomNumber = mt_rand() / mt_getrandmax();
        $currentProbability = 0;
        $prizes = self::all();

        foreach ($prizes as $prize) {
            $currentProbability += $prize->probability / 100;
            if ($randomNumber <= $currentProbability) {
                $prize->increment('winner_count');
                break;
            }
        }
    }
}
