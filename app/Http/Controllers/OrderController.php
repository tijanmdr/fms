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
        $validate = $this->_validationOrder($req);
        
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        } else {
            $user = JWTAuth::user()->id;
            $order = $this->order->where('table',$req['table'])->checkStatus(2,3)->count();

            if (!str_contains(url()->current(), 'create_order_next')) { // checking new order and creating another for same table
                if ($order>0) { // avoid placing order if the order is not finished or deleted
                    return returnMessage(false, 'Sorry, the order in table '.$req['table'].' is still active. Cannot process order on this table!');
                }
            }

            $this->_createOrder($user, $req);

            return returnMessage(true, 'The order has been placed!');
        }
    }

    public function getActiveOrders()
    {
        $orders = $this->order->checkStatus(3,2)->latest()->get(); // avoiding completed and deleted orders
        foreach ($orders as $order) {
        $order['details'] = $order->details;
            foreach ($order['details'] as $detail) {
                if ($detail->food_id) {
                    $detail->foodName;
                } elseif($detail->beverage_id) {
                    $detail->beverageName;
                }
            }
        }
        return returnMessage(true, 'Active orders successfully retrieved', $orders);
    }

    public function changeOrderStatus(Request $req)
    {
        $req = $req->only('id', 'status');
        $validate = Validator::make($req, [
            'id'=>'required|integer|exists:orders,id',
            'status' => 'required|integer|min:1|max:2'
        ]);

        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        } else {
            $order = $this->order->whereId($req['id'])->checkStatus(3)->first(); // checking destroyed orders
            if ($order) {
                $order->update(['status'=>$req['status']]);
                return returnMessage(true, 'The order status has been updated!');
            } else {
                return returnMessage(false, 'The order could not be found!');
            }
        }
    }

    public function deleteOrder(Request $req)
    {
        $req = $req->only('id');
        $validate = Validator::make($req, [
            'id'=>'required|integer|exists:orders,id',
        ]);

        $user = JWTAuth::user()->id;

        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        } else {
            $order = $this->order->whereId($req['id'])->checkStatus(3)->first(); // checking destroyed orders
            if ($order) {
                $order->update(['status'=>3, 'destroyed_by'=>$user]);
                return returnMessage(true, 'The order status has been deleted!');
            } else {
                return returnMessage(false, 'The order could not be found!');
            }
        }
    }

    public function printOrder(Request $req)
    {
        $req = $req->only('id');
        $validate = Validator::make($req, [
            'id'=>'required|integer|exists:orders,id',
        ]);


        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        } else {
            $order = $this->order->checkStatus(0)->find($req['id']);
            $order['details'] = $order->details;
            foreach ($order['details'] as $detail) {
                if ($detail->food_id) {
                    $detail->foodName;
                } elseif($detail->beverage_id) {
                    $detail->beverageName;
                }
            }
            return returnMessage(true, 'The order details are successfully retrieved!', $order);
        }
    }

    public function _createOrder($user, $req) {
        try {
            DB::beginTransaction();
            $order = $this->order->create([
                'user'=> $user,
                'table'=>$req['table'],
                'notes'=>$req['note']
            ]);

            if ($order) {
                foreach($req['order_list'] as $_order) {
                    $input = [
                        'order'=>$order->id,
                        'user'=>$user,
                        'entree'=>$_order['entree'],
                        'quantity'=>$_order['quantity'],
                        'serve'=>1,
                        'note'=>''
                    ];
                    if ($_order['food_beverage'] === 0)
                        $input['food_id'] = $_order['id'];
                    else
                        $input['beverage_id'] = $_order['id'];

                    if (isset($_order['note']))
                        $input['note'] = $_order['note'];

                    $this->orderDetail->create($input);
                }

                DB::commit();
            } else {
                DB::rollback();
            }
        } catch (\Exception $e)
        {
            return $e;
        }
    }

    public function _validationOrder($req)
    {
        $validation = Validator::make($req, [
            'table'=>'required|integer',
            'order_list'=>'required|array',
            'order_list.*.id'=>'required|integer',
            'order_list.*.quantity'=>'required|integer',
            'order_list.*.food_beverage'=>'required|integer|min:0|max:1', // food - 0, beverage - 1
            'order_list.*.entree'=>'required|integer|min:0|max:2', // 0 - entree, 1 - mains, 2 - dessert
            'order_list.*.note'=>'string',
            'note' => 'string',
        ]);
        return $validation;
    }
}
