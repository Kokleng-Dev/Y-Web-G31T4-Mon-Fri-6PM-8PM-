<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\Product;
use DB;

class OrderController extends Controller
{
    public function index(Request $r){
        $data = Order::with('customer','order_details')
                ->select('orders.*', DB::raw('IF(orders.deleted_at IS NULL, "active","void") as status'))
                ->withTrashed()
                ->paginate($r->per_page ?? 10);
        return response()->json(['data' => $data]);
    }
    public function create(Request $r){
        // {
        //     "items" : [
        //         {"product_id":1,"price":2,"qty":1},
        //         {"product_id":2,"price":1,"qty":2}
        //     ],
        //     "discount":1,
        //     "customer_id": 1
        // }
        DB::beginTransaction();
        try{
            $items = $r->items;
                $discount = $r->discount ?? 0;
                $customer_id = $r->customer_id;

                $total = 0;
                foreach ($items as $key => $item) {
                    $product_exist = Product::find($item['product_id']);
                    if (!$product_exist) {
                        return response()->json(['message' => 'Product not found id=' . $item['product_id']], 404);
                    }
                    if($product_exist->qty < $item['qty']){
                        return response()->json(['message' => 'Product not enough qty id=' . $item['product_id']], 404);
                    }
                    $product_exist->qty = $product_exist->qty - $item['qty'];
                    $product_exist->save();

                    $total += $item['price'] * $item['qty'];
                }
                $grand_total = $total - $discount;

                // insert order
                $order = new Order();
                $order->customer_id = $customer_id;
                $order->total_amount = $total;
                $order->discount = $discount;
                $order->grand_total = $grand_total;
                $order->save();

                // insert order details
                foreach ($items as $key => $item) {
                    $order_detail = new OrderDetail();
                    $order_detail->order_id = $order->id;
                    $order_detail->product_id = $item['product_id'];
                    $order_detail->price = $item['price'];
                    $order_detail->qty = $item['qty'];
                    $order_detail->save();
                }

                DB::commit();
                return response()->json(['message' => 'Order created successfully', 'data' => $order]);
        } catch(Exception $e){
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function delete($id){
        DB::beginTransaction();
        try{
            $order = Order::find($id);
            if(!$order){
                return response()->json(['message' => 'Order not found'], 404);
            }
            $detai = OrderDetail::where('order_id', $order->id)->get();
            foreach ($detai as $key => $value) {
                $product = Product::find($value->product_id);
                $product->qty = $product->qty + $value->qty;
                $product->save();
            }

            #delete order
            $order->delete();

            DB::commit();
            return response()->json(['message' => 'Order deleted successfully']);
        } catch(Exception $e){
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
