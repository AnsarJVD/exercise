<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SebastianBergmann\CodeCoverage\Util\Percentage;

use function PHPSTORM_META\map;

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
         $request->validate([
            'input' => 'required|array',
            'input.order_qty' => 'required|boolean',
            'input.vendor' => 'required|array',
            'input.vendor.*.id' => 'required|integer',
            'input.vendor.*.stock' => 'required|integer',
        ]);
        
        $vendors = $request->input('input.vendor');
        $orderQty = $request->input('input.order_qty');

        $remainingStock = $orderQty;

       $vendors = collect($vendors)->map(function ($vendor) use (&$remainingStock) {
            if ($remainingStock <= 0) {
                return [
                    'id' => $vendor['id'],
                    'stock' => $vendor['stock']
                ];
            }

            $allocated = min($vendor['stock'], $remainingStock);
            $remainingStock -= $allocated;

            return [
                'id' => $vendor['id'],
                'stock' => $allocated
            ];
        });
        return response()->json([
            "success" => true,
            "data"  => [
                "vendors" => $vendors
            ],
            "error" => null
        ]);
    }

    public function ex5(Request $request)
    {

        //  $request->validate([
        //     'input' => 'required|array',
        //     'input.price' => 'required|numeric|min:0',
        //     'input.discounts' => 'required|array',
        //     'input.discounts.*.type' => 'required|string|in:percentage,flat',
        //     'input.discounts.*.value' => 'required|numeric|min:0',
        // ]); 

        $input = $request['input'];
        $price = $input['price'];
        $discounts =$input['discounts'];
        $array = [];
        foreach($discounts as $discount ){
            $type = $discount['type'];
            $value= $discount['value'];  
            if($type == 'flat'){
                $array[] = $price-$value;
            } 
            else if ($type == 'percentage'){
                $array[] = $value*$price /100;
            }   
        }
        $final_result = min($array);
        return response()->json(
            [
                'success'=> true,
                'data'=> ["final_result"=> $final_result],
                'error'=> null
            ]
        );
    }

    public function ex6(Request $request)
    {

        $input = $request['input'];
        $price = $input['price'];
        $discounts =$input['discounts'];
        $array = [];
        foreach($discounts as $discount ){
            $type = $discount['type'];
            $value= $discount['value'];  
            if($type == 'flat'){
                $array[] = $price-$value;
            } 
            else if ($type == 'percentage'){
                $array[] = $value*$price /100;
            }   
        }
        $final_result = min($array);
        return response()->json(
            [
                'success'=> true,
                'data'=> ["final_result"=> $final_result],
                'error'=> null
            ]
        );
    }
        // logger("data is here " . json_encode($request->all()));       
  
    public function ex12(Request $request)
    {
        $request->validate([
            "input" => "required|array",
            'input.bundle_price' => 'required|numeric|gt:0',
            "input.apply_bundle" => "required|boolean",
            "input.items.*.id" => "required|integer",
            "input.items.*.price" => "required|numeric|gt:0",
        ]);

        $input = $request->input('input');
        $items = $input['items'];
        $bundlePrice = $input['bundle_price'];
        $applyBundle = $input['apply_bundle'];
        $totalPrice = 0;
        $appliedbundlePrice = 0;

        foreach ($items as $item) {
            $itemPrice = $item['price'];
            $totalPrice += $itemPrice;
        }

        if ($applyBundle) {
            $appliedbundlePrice = $totalPrice - $bundlePrice;
        }

        if($appliedbundlePrice >= 0){
            return response()->json([
                'success' => true ,
                'data' => ["final_price" => $bundlePrice],
                'error' => null
            ]);
        }
        return response()->json([
        'success' => true ,
        'data' => ["final_price" => $totalPrice],
        'error' => null
        ]);
    }

    public function ex13(Request $request)
    {
       
    }


     public function ex14(Request $request)
{
    $request->validate([
        'input' => 'required|array',
        'input.nums' => 'required|array',
        'input.nums.*' => 'required|integer',
        'input.target' => 'required|integer',
    ]);

    $input = $request->input('input');
    $nums = $input['nums'];
    $target = $input['target'];

    for ($i = 0; $i < count($nums); $i++) {
        for ($j = $i + 1; $j < count($nums); $j++) {
            if ($nums[$i] + $nums[$j] === $target) {
                return response()->json([
                    'success' => true,
                    'data' => [$i, $j],
                    'error' => null,
                ]);
            }
        }
    }

    return response()->json([
        'success' => false,
        'data' => null,
        'error' => 'No matching numbers found',
    ]);
}

     public function ex15(Request $request)
{
    $request->validate([
        'input' => 'required|array',
        'input.order' => 'required|array',
        'input.order.weight' => 'required|numeric|gt:0',
        'input.order.country' => 'required|string',
        'input.rules' => 'required|array',
        'input.rules.*.id' => 'required|integer',
        'input.rules.*.method' => 'required|string',
        'input.rules.*.priority' => 'required|integer',
        'input.rules.*.max_weight' => 'nullable|numeric',
        'input.rules.*.country' => 'nullable|string',
    ]);

    $input = $request->input('input');
    $order = $input['order'];
    $rules = $input['rules'];

    $selectedRule = null;

    foreach ($rules as $rule) {
        $isMatched = true;

        if (isset($rule['max_weight']) && $order['weight'] > $rule['max_weight']) {
            $isMatched = false;
        }

        if (isset($rule['country']) && $order['country'] !== $rule['country']) {
            $isMatched = false;
        }

        if ($isMatched) {
            if ($selectedRule === null || $rule['priority'] > $selectedRule['priority']) {
                $selectedRule = $rule;
            }
        }
    }

    if ($selectedRule) {
        return response()->json([
            'success' => true,
            'data' => [
                'method' => $selectedRule['method'],
            ],
            'error' => null,
        ]);
    }

    return response()->json([
        'success' => false,
        'data' => null,
        'error' => 'No matching shipping rule found',
    ]);
}
}    
    

