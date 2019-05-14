<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Http\Resources\Customer as CustomerResource;
use App\CustomClasses\ParseOptions;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{

    /**
     * Return a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Parse options to return a filtered/ordered by/paginated collection of Customers
        $options = new ParseOptions($request);
        $orderBy = $options->getOrderedBy();
        if(empty($options->getFilters())) {
            $customers = Customer::orderBy($orderBy[0], $orderBy[1])
              ->paginate($options->getPaginate());
        } else {
            $customers = Customer::where([$options->getFilters()])
              ->orderBy($orderBy[0], $orderBy[1])
              ->paginate($options->getPaginate());
        }

        // Load relationship contacts and nested relationship orders
        $customers->load('contacts.orders');

        // Return CustomerResource collection
        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created resource or update an old resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // If it's an update, get the Customer, else create new Customer
        $customer = $request->isMethod('put') ? Customer::findOrFail($request->input('id')) : new Customer;

        // If it's an update, set id to customer_id
        if($request->isMethod('put')) {
            $customer->id = $request->input('id');
        }

        $customer->name = $request->input('name');
        $customer->short_name = $request->input('short_name');
        $customer->number = $request->input('number');

        // Try to save Customer
        if($customer->save()) {
            // Return CustomerResource
            return new CustomerResource($customer);
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
        // Get Customer by id
        $customer = Customer::findOrFail($id);

        // Return CustomerResource
        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get Customer by id
        $customer = Customer::findOrFail($id);

        // Try to delete Customer
        if($customer->delete()) {
            // Return CustomerResource
            return new CustomerResource($customer);
        }

        return redirect('/customers')->with('success', 'Customer Deleted');
    }

    /**
     * Return a resource with values set to column types to show model/table structure.
     *
     * @return \Illuminate\Http\Response
     */
    public function describe()
    {
        $table = (new Customer)->getTable();
        $result = DB::select(DB::raw("DESCRIBE {$table}"));
        $emptyCustomer = [];
        foreach($result as $column) {
            $emptyCustomer[$column->Field] = $column->Type;
        }
        return ["data" => $emptyCustomer];
    }

    /**
     * Return a blank resource with values set to appropriate column "empty" values.
     *
     * @return \Illuminate\Http\Response
     */
    public function blank()
    {
        $table = (new Customer)->getTable();
        $result = DB::select(DB::raw("DESCRIBE {$table}"));
        $emptyCustomer = [];
        foreach($result as $column) {
            $emptyCustomer[$column->Field] = get_empty_value_for_column($column);
        }
        return ["data" => $emptyCustomer];
    }
}
