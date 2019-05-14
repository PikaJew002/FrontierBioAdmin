<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\ItemOrder as ItemOrderResource;
use App\CustomClasses\ParseOptions;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * Return a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Parse options to return a filtered/ordered by/paginated collection of Orders
        $options = new ParseOptions($request);
        $orderBy = $options->getOrderedBy();
        if(empty($options->getFilters())) {
            $orders = Order::orderBy($orderBy[0], $orderBy[1])
              ->paginate($options->getPaginate());
        } else {
            $orders = Order::where([$options->getFilters()])
              ->orderBy($orderBy[0], $orderBy[1])
              ->paginate($options->getPaginate());
        }

        // Load relationships contact and items
        $orders->load('contact.customer', 'items.product.family');

        // Return OrderResource collection
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource or update an old resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // If it's an update, get the Order, else create new Order
        $order = $request->isMethod('put') ? Order::findOrFail($request->input('id')) : new Order;

        // If it's an update, set id to id
        if($request->isMethod('put')) {
            $order->id = $request->input('id');
        }

        // Map the remaining inputs to their respective properties
        $order->contact_id = $request->input('contact_id');
        $order->shipping_address = $request->input('shipping_address');
        $order->billing_address = $request->input('billing_address');
        $order->purchase_order_number = $request->input('purchase_order_number');
        $order->shipping_cost = $request->input('shipping_cost');
        $order->tax = $request->input('tax');
        $order->notes = $request->input('notes');
        $order->fulfilled_at = $request->input('fulfilled_at');

        // Items array to be synced to order
        $items = [];

        // Loop through items in the 'items' input
        foreach($request->input('items') as $item) {
            // Add each Item to items array for syncing
            $items[$item['id']] = ['price' => $item['price'], 'quantity' => $item['quantity'], 'includes_msds' => $item['includes_msds'], 'includes_cofa' => $item['includes_cofa']];
        }

        // Try to save Order
        if($order->save()) {
            // Sync items array (id's with pivot columns)
            $order->items()->sync($items);

            // Return OrderResource
            return new OrderResource($order);
        }
    }

    /**
     * Return the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get Order by id
        $order = Order::findOrFail($id);

        // Load relationships contact and items
        $order->load('contact.customer', 'items.product.family');

        // Return OrderResource
        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get Order by id
        $order = Order::findOrFail($id);

        // Try to delete Order
        if($order->delete()) {
            // Return OrderResource
            return new OrderResource($order);
        }
    }

    /**
     * Return a resource with values set to column types to show model/table structure.
     *
     * @return \Illuminate\Http\Response
     */
    public function describe()
    {
        $table = (new Order)->getTable();
        $result = DB::select(DB::raw("DESCRIBE {$table}"));
        $emptyOrder = [];
        foreach($result as $column) {
            $emptyOrder[$column->Field] = $column->Type;
        }
        return ["data" => $emptyOrder];
    }

    /**
     * Return a blank resource with values set to appropriate column "empty" values.
     *
     * @return \Illuminate\Http\Response
     */
    public function blank()
    {
        $table = (new Order)->getTable();
        $result = DB::select(DB::raw("DESCRIBE {$table}"));
        $emptyOrder = [];
        foreach($result as $column) {
            $emptyOrder[$column->Field] = get_empty_value_for_column($column);
        }
        return ["data" => $emptyOrder];
    }
}
