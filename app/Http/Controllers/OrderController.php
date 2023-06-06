<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderController extends Controller
{
    private $order, $orderDetail;
    public function __construct(Order $order, OrderDetail $orderDetail) {
        $this->order = $order;
        $this->orderDetail = $orderDetail;
    }

    public function createOrder(Request $req) {
        $req = $req->only('table', 'order_list', 'note');
        $validate = Validator::make($req, [
            'table'=>'required|integer',
            'order_list'=>'required|array',
            'order_list.*.id'=>'required|integer',
            'order_list.*.quantity'=>'required|integer',
            'order_list.*.food_beverage'=>'required|integer|min:0|max:1', // food - 0, beverage - 1
            'order_list.*.entree'=>'required|integer|min:0|max:2', // 0 - entree, 1 - mains, 2 - dessert
            'order_list.*.note'=>'string',
            'note' => 'string',
        ]);
        
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        } else {
            $user = JWTAuth::user()->id;
            try {
                DB::beginTransaction();
                $order = $this->order->create([
                    'user'=> $user, 
                    'table'=>$req['table'], 
                    'notes'=>$req['note']
                ]);

                if ($order) {
                    foreach($req['order_list'] as $order) {
                        return $order;
                    }
                    DB::rollback();
                } else {
                    DB::rollback();
                }
            } catch (\Exception $e) 
            {
                return $e;
            }

            return $req;
        }
    }
}
