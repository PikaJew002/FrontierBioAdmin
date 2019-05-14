<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\Http\Resources\Contact as ContactResource;
use App\CustomClasses\ParseOptions;
use Illuminate\Support\Facades\DB;

class ContactsController extends Controller
{

    /**
     * Return a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Parse options to return a filtered/ordered by/paginated collection of Contacts
        $options = new ParseOptions($request);
        $orderBy = $options->getOrderedBy();
        if(empty($options->getFilters())) {
            $contacts = Contact::orderBy($orderBy[0], $orderBy[1])
              ->paginate($options->getPaginate());
        } else {
            $contacts = Contact::where([$options->getFilters()])
              ->orderBy($orderBy[0], $orderBy[1])
              ->paginate($options->getPaginate());
        }

        // Load relationships cusomter and orders
        $contacts->load('customer', 'orders');

        // Return ProductInventoryItemResource collection
        return ContactResource::collection($contacts);
    }

    /**
     * Store a newly created resource or update an old resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // If it's an update, get the Contact, else create new Contact
        $contact = $request->isMethod('put') ? Contact::findOrFail($request->input('id')) : new Contact;

        // If it's an update, set id to contact_id from form
        if($request->isMethod('put')) {
            $contact->id = $request->input('id');
        }

        // Map the remaining inputs to their respective properties
        $contact->customer_id = $request->input('customer_id');
        $contact->prefix = $request->input('prefix');
        $contact->first_name = $request->input('first_name');
        $contact->last_name = $request->input('last_name');
        $contact->phone = $request->input('phone');
        $contact->email = $request->input('email');
        $contact->shipping_address = $request->input('shipping_address');
        $contact->billing_address = $request->input('billing_address');

        // Try to save Contact
        if($contact->save()) {
            // Return ContactResource
            return new ContactResource($contact);
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
        // Get Contact by id
        $contact = Contact::findOrFail($id);

        // Return ContactResource
        return new ContactResource($contact);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get Contact by id
        $contact = Contact::findOrFail($id);

        // Try to delete Contact
        if($contact->delete()) {
            // Return ContactResource
            return new ContactResource($contact);
        }
    }

    /**
     * Return a resource with values set to column types to show model/table structure.
     *
     * @return \Illuminate\Http\Response
     */
    public function describe()
    {
        $table = (new Contact)->getTable();
        $result = DB::select(DB::raw("DESCRIBE {$table}"));
        $emptyContact = [];
        foreach($result as $column) {
            $emptyContact[$column->Field] = $column->Type;
        }
        return ["data" => $emptyContact];
    }

    /**
     * Return a blank resource with values set to appropriate column "empty" values.
     *
     * @return \Illuminate\Http\Response
     */
    public function blank()
    {
        $table = (new Contact)->getTable();
        $result = DB::select(DB::raw("DESCRIBE {$table}"));
        $emptyContact = [];
        foreach($result as $column) {
            $emptyContact[$column->Field] = get_empty_value_for_column($column);
        }
        return ["data" => $emptyContact];
    }
}
