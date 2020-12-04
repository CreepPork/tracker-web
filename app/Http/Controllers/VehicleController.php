<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function __invoke()
    {
        $padding = 1_000_000;
        $vehicles = collect();

        for ($i = 0; $i < 9; $i++) {
            $vehicles->add([
                'id' => $i,
                'latitude' => mt_rand(56.90 * $padding, 56.99 * $padding) / $padding,
                'longitude' => mt_rand(24.1 * $padding, 24.2 * $padding) / $padding,
                'name' => 'Vehicle ' . ($i + 1),
            ]);
        }

        return $vehicles;
    }
}
