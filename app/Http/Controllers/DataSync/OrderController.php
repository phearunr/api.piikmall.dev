<?php

namespace App\Http\Controllers\DataSync;

use Illuminate\Http\Request;
use App\Models\DataSync\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\DataSync\Orders\OrderResource;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $creds = $request->validate([
            'store_id' => 'required',
            'status_id' => 'required',
            'language_id' => 'required',
            'per_page' => 'required',
        ]);

        $query = Order::query();

        if ($creds['status_id'] == 20) {

            $query->where('store_id', '=', $creds['store_id']);
            $query->where('order_state', '=', 20);

            $query->orWhere(function ($q)  use ($creds){
                $q->where('order_state', '=',10)
                    ->where('store_id', '=', $creds['store_id'])
                    ->where('confirm_cash_pay_time', '>', 0);
            });
        } else {

            $query->where([
                'store_id' => $creds['store_id'],
                'order_state' => $creds['status_id'],
            ]);
        }

        $query->form();
        $query->term(request('term'));
        $query->orderBy(['status_id' => $request->status_id]);
        $data = $query->paginate(10);

        return OrderResource::collection($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DataSync\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $_data = Order::query()
            ->form()
            ->findOrFail($id);

        return response([
            'error' => 0,
            'data' => new OrderResource($_data)
        ], 200);
    }
}
