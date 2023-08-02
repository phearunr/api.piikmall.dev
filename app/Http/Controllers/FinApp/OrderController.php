<?php

namespace App\Http\Controllers\FinApp;

use App\Helper\Constant;
use Illuminate\Http\Request;

use App\Models\FinApp\OD_cn\Order;
use App\Http\Controllers\Controller;
use App\Http\Resources\FinApp\OD_cn\Orders\OrderResource;
use App\Http\Resources\FinApp\OD_cn\Orders\OrderDetailResource;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $searchFilter = array(); 
        // if(!empty($_GET['search_keywords'])){ 
        //     $searchFilter['search'] = array( 
        //         'first_name' => $_GET['search_keywords'], 
        //         'last_name' => $_GET['search_keywords'], 
        //         'email' => $_GET['search_keywords'], 
        //         'country' => $_GET['search_keywords'] 
        //     ); 
        // } 

        // if(!empty($_GET['filter_option'])){ 
        //     $searchFilter['filter'] = array( 
        //         'gender' => $_GET['filter_option'] 
        //     ); 
        // } 

        // $search = $request->query('search', [
        //     'value' => '', 'regex' => false
        // ]);
        // return $search['value'];


        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);

        // $order = $request->query('order', array(1, 'asc'));
        // $filter = $search['value'];

        // $sortColumns = array(
        //     0 => 'order.store_name',
        //     1 => 'order.buyer_name',
        //     2 => 'order.order_type',
        //     3 => 'order.payment_code',
        // );

        $query = Order::query()->form();

        // if (!empty($filter)) {
        //     $query->where('products.name', 'like', '%'.$filter.'%');
        // }

        // $query->where('payment_code', 'like', '%aeonCredit%');
        $query->where('original_order_price_detail', '<>', ' ');


        $query->orderByDesc('order.order_id')
            ->take($length)
            ->skip($start);

        // $sortColumnName = $sortColumns[$order[0]['column']];
        // $query->orderBy($sortColumnName, $order[0]['dir'])
        //         ->take($length ?? 10)
        //         ->skip($start ?? 0);

        $recordsTotal = $query->count();
        $orders = OrderResource::collection($query->get());

        // foreach ($products as $product) {

        //     $json['data'][] = [
        //         $product->category->name,
        //         $product->name,
        //         $product->sku,
        //         $product->price,
        //         view('products.actions', ['product' => $product])->render(),
        //     ];
        // }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $orders,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($order = Order::query()->form()->find($id)) {
            return response()->json([
                'error' => 0,
                'data' => new OrderDetailResource($order)
            ], 200);
        }
        return response()->json([
            'error' => 1,
            'massage' => 'not found!.'
        ], 404);
    }

    public function filterKey($key)
    {
        $data['data'] = [];
        switch ($key) {
            case 'order_status':
                $data = ['data' => Constant::ORDER_STATUS];
                break;
            case 'order_type':
                $data = ['data' => Constant::ORDER_TYPE];
                break;
            case 'bill_cycle':
                $data = ['data' => Constant::BILL_CYCLE];
                break;
            case 'paymemt_code':
                $data = ['data' => Constant::PAYMENT_CODE];
                break;

            default:
                $data['data'] = [
                    'order_status' => Constant::ORDER_STATUS,
                    'order_type' => Constant::ORDER_TYPE,
                    'bill_cycle' => Constant::BILL_CYCLE,
                    'paymemt_code' => Constant::PAYMENT_CODE,
                ];
        }
        return response()->json($data, 200);
    }
}
