<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArtworkController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'input' => 'required|array',
            'input.*.id' => 'required|integer',
            'input.*.approved' => 'required|boolean',
            'input.*.rejected' => 'required|boolean',
            'input.*.time' => 'required|integer',
        ]);
        $input = $validated['input'];
        sort($input);

        $validItems = array_filter($input, function ($item) {
            return $item['approved'] && !$item['rejected'];
        });
        $validId = collect($validItems)->pluck('id')->last();

        if (!$validId) {
            return response()->json([
                "success" => false,
                "data"  => null,
                "error" => "No valid artwork found"
            ], 400);
        }
        return response()->json([
            "success" => true,
            "data"  => [
                "id" => $validId
            ],
            "error" => null
        ]);
    }


    public function ex3(Request $request)
    {
        $validated = $request->validate([
            'input' => 'required|array',
            'input.*.id' => 'required|integer',
            'input.*.required' => 'required|boolean',
            'input.*.done' => 'required|boolean',
        ]);
        $input = $validated['input'];
        sort($input);

        $invalidItems = array_filter($input, function ($item) {
            return $item['required'] && !$item['done'];
        });
        $invalidIds = collect($invalidItems)->pluck('id');

        
        return response()->json([
            "success" => true,
            "data"  => [
                "invalid_items" => $invalidIds,
                "valid" => $invalidIds->isEmpty()
            ],
            "error" => null
        ]);
    }

    public function ex4(Request $request)
    {
        $validated = $request->validate([
            'input' => 'required|array',
            'input.*.id' => 'required|integer',
            'input.*.required' => 'required|boolean',
            'input.*.done' => 'required|boolean',
        ]);
        $input = $validated['input'];
        sort($input);

        $invalidItems = array_filter($input, function ($item) {
            return $item['required'] && !$item['done'];
        });
        $invalidIds = collect($invalidItems)->pluck('id');

        
        return response()->json([
            "success" => true,
            "data"  => [
                "invalid_items" => $invalidIds,
                "valid" => $invalidIds->isEmpty()
            ],
            "error" => null
        ]);
    }
}
