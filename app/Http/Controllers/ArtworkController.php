<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SebastianBergmann\CodeCoverage\Util\Percentage;

use function PHPSTORM_META\map;

class ArtworkController extends Controller
{
    public function ex1(Request $request)
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
        $discounts = $input['discounts'];
        $array = [];
        foreach ($discounts as $discount) {
            $type = $discount['type'];
            $value = $discount['value'];
            if ($type == 'flat') {
                $array[] = $price - $value;
            } else if ($type == 'percentage') {
                $array[] = $value * $price / 100;
            }
        }
        $final_result = min($array);
        return response()->json(
            [
                'success' => true,
                'data' => ["final_result" => $final_result],
                'error' => null
            ]
        );
    }

    public function ex6(Request $request)
    {

        $input = $request['input'];
        $price = $input['price'];
        $discounts = $input['discounts'];
        $array = [];
        foreach ($discounts as $discount) {
            $type = $discount['type'];
            $value = $discount['value'];
            if ($type == 'flat') {
                $array[] = $price - $value;
            } else if ($type == 'percentage') {
                $array[] = $value * $price / 100;
            }
        }
        $final_result = min($array);
        return response()->json(
            [
                'success' => true,
                'data' => ["final_result" => $final_result],
                'error' => null
            ]
        );
    }

    public function ex7(Request $request)
    {
        $request->validate([
            'input' => 'required|array',
            'input.stock' => 'required|integer',
            'input.requests' => 'required|array',
        ]);

        $input = $request->input('input');
        $stock = $input['stock'];
        $requests = $input['requests'];
        $remainRequest = [];

        foreach ($requests as $req) {
            $stock = $stock - $req;
            if ($stock >= 0) {
                $remainRequest[] = true;
            } else {
                $stock = $stock + $req;
                $remainRequest[] = false;
            }
        }
        return response()->json([
            'success' => true,
            'data' => [$remainRequest],
            "error" => null
        ]);
    }

    public function ex8(Request $request)
    {
        $request->validate([
            'input' => 'required|array',
            'input.ordered' => 'required|numeric|min:0',
            'input.shipped' => 'required|array',
        ]);
        $input = $request['input'];
        $ordered = $input['ordered'];
        $shipped = $input['shipped'];

        $quantityLeft = 0;
        foreach ($shipped as $ship) {
            if ($ship > 0) {
                $ordered = $ordered - $ship;
                $quantityLeft = $ordered;
            }
        }

        if ($quantityLeft < 0) {
            return response()->json([
                'success' => true,
                'message' => "No Quantity Left",
                'error' => null
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => ["remaining :" => $quantityLeft],
            'error' => null
        ]);
    }

    public function ex9(Request $request)
    {
        $request->validate([
            'input' => 'required|array',
            'input.*.id' => 'required|string',
            'input.*.time' => 'required|numeric',
        ]);
        $input = $request['input'];

        $result = collect($input)->unique("id")->filter()->values()->pluck("id");

        return response()->json([
            'success' => true,
            'data' => $result,
            'error' => null
        ]);
    }

    public function ex10(Request $request)
    {
        $request->validate([
            "input" => "required",
            "input.created_at" => "required|date_format:Y-m-d",
            "input.valid_days" => "required|numeric",
            "input.current_date" => "required|date_format:Y-m-d",
        ]);
        $input = $request->input('input');
        $valid_days = $input['valid_days'];
        $created_at = Carbon::parse($input["created_at"])->format("Y-m-d");
        $current_date = Carbon::parse($input['current_date'])->format("Y-m-d");
        $expiration_date = Carbon::parse($created_at)->addDays($valid_days)->format("Y-m-d");

        if (Carbon::parse($current_date)->greaterThan($expiration_date)) {
            return response()->json([
                "success" => false,
                "data" => ["valid" => false],
                "error" => "The date has expired."
            ]);
        } else {
            return response()->json([
                "success" => true,
                "data" => ["valid" => true],
                "error" => null
            ]);
        }
    }


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

        if ($appliedbundlePrice >= 0) {
            return response()->json([
                'success' => true,
                'data' => ["final_price" => $bundlePrice],
                'error' => null
            ]);
        }
        return response()->json([
            'success' => true,
            'data' => ["final_price" => $totalPrice],
            'error' => null
        ]);
    }


    public function ex13(Request $request)
    {
        $request->validate([
            'input' => 'required|array',
            'input.guest' => 'array',
            'input.guest.*.id' => 'required|integer',
            'input.guest.*.qty' => 'required|integer',
            'input.user' => 'array',
            'input.user.*.id' => 'required|integer',
            'input.user.*.qty' => 'required|integer',
        ]);

        $input = $request->input('input');
        $guests = $input['guest'];
        $users = $input['user'];

        $mergedArray = array_merge($guests, $users);
        $mergedArray = collect($mergedArray)->groupBy('id')->map(function ($item) {
            return [
                'id' => $item[0]['id'],
                'qty' => $item->sum('qty')
            ];
        })->values()->toArray();

        return response()->json([
            'success' => true,
            'data' => $mergedArray,
            'error' => null
        ]);
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

    public function ex15(Request $request) {

    }
}
