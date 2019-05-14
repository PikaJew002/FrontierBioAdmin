<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\ProductInventoryItem;
use App\Http\Resources\ProductInventoryItem as ProductInventoryItemResource;
use App\CustomClasses\ParseOptions;
use Illuminate\Support\Facades\DB;

class ProductInventoryItemsController extends Controller
{

    /**
     * Return a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Parse options to return a filtered/ordered by/paginated collection of Items
        $options = new ParseOptions($request);
        $orderBy = $options->getOrderedBy();
        if(empty($options->getFilters())) {
            $items = ProductInventoryItem::orderBy($orderBy[0], $orderBy[1])
              ->paginate($options->getPaginate());
        } else {
            $items = ProductInventoryItem::where([$options->getFilters()])
              ->orderBy($orderBy[0], $orderBy[1])
              ->paginate($options->getPaginate());
        }

        // Load relationship product and nested relationship family
        $items->load('product.family');

        // Return ProductInventoryItemResource collection
        return ProductInventoryItemResource::collection($items);
    }

    /**
     * Store a newly created resource or update an old resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // If it's an update, get the ProductInventoryItem, else create new ProductInventoryItem
        $item = $request->isMethod('put') ? ProductInventoryItem::findOrFail($request->input('id')) : new ProductInventoryItem;

        // If it's an update, set id to item_id from form
        if($request->isMethod('put')) {
            $item->id = $request->input('id');
        }

        // If cofa_pdf present as a file, upload file
        // If cofa_pdf present (but not as a file), save as file path
        // If cofa_pdf not present (or empty), save as empty string
        $pathCofaPdf = "";
        if($request->hasFile('cofa_pdf')) {
            $pathCofaPdf = $request->file('cofa_pdf')->store('public/cofa_pdf');
            if($request->isMethod('put') && Storage::exists($item->cofa_pdf)) {
                Storage::delete($item->cofa_pdf);
            }
        } elseif($request->has('cofa_pdf')) {
            if($request->isMethod('put')) {
                if($request->input('cofa_pdf') == "delete" && Storage::exists($item->cofa_pdf)) {
                    Storage::delete($item->cofa_pdf);
                } elseif(Storage::exists($request->input('cofa_pdf'))) {
                    $pathCofaPdf = $request->input('cofa_pdf');
                }
            }
        }

        // If cofa_docx present as a file, upload file
        // If cofa_docx present (but not as a file), save as file path
        // If cofa_docx not present (or empty), save as empty string
        $pathCofaDocx = "";
        if($request->hasFile('cofa_docx')) {
            $pathCofaDocx = $request->file('cofa_docx')->store('public/cofa_docx');
            if($request->isMethod('put') && Storage::exists($item->cofa_docx)) {
                Storage::delete($item->cofa_docx);
            }
        } elseif($request->has('cofa_docx')) {
            if($request->isMethod('put')) {
                if($request->input('cofa_docx') == "delete" && Storage::exists($item->cofa_docx)) {
                    Storage::delete($item->cofa_docx);
                } elseif(Storage::exists($request->input('cofa_docx'))) {
                    $pathCofaDocx = $request->input('cofa_docx');
                }
            }
        }

        // Map the remaining inputs to their respective properties
        $item->product_id = $request->input('product_id');
        $item->lot_number = $request->input('lot_number');
        $item->prep_date = $request->input('prep_date');
        $item->exp_date = $request->input('exp_date');
        $item->current_stock = $request->input('current_stock');
        $item->coord1 = ($request->input('coord1') == "null" ? null : $request->input('coord1'));
        $item->coord2 = ($request->input('coord2') == "null" ? null : $request->input('coord2'));
        $item->coord3 = ($request->input('coord3') == "null" ? null : $request->input('coord3'));
        $item->in_current_stock = $request->input('in_current_stock');
        $item->previous_lot_number = ($request->input('previous_lot_number') == "null" ? null : $request->input('previous_lot_number'));
        $item->cofa_pdf = $pathCofaPdf;
        $item->cofa_docx = $pathCofaDocx;

        // Try to save ProductInventoryItem
        if($item->save()) {
            // Return ProductInventoryItemResource
            return new ProductInventoryItemResource($item);
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
        // Get ProductInventoryItem by id
        $item = ProductInventoryItem::findOrFail($id);

        // Return ProductInventoryItemResource
        return new ProductInventoryItemResource($item);
    }

    /**
     * Return the specified resource (by lot number).
     *
     * @param  int  $lotNumber
     * @return \Illuminate\Http\Response
     */
    public function showByLotNumber($lotNumber)
    {
        // Get ProductInventoryItem by lot_number
        $item = ProductInventoryItem::where('lot_number', $lotNumber)->firstOrFail();

        // Return ProductInventoryItemResource
        return new ProductInventoryItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get ProductInventoryItem by id
        $item = ProductInventoryItem::findOrFail($id);

        // Try to delete ProductInventoryItem
        if($item->delete()) {
            // Return ProductInventoryItemResource
            return new ProductInventoryItemResource($item);
        }
    }

    /**
     * Return a resource with values set to column types to show model/table structure.
     *
     * @return \Illuminate\Http\Response
     */
    public function describe()
    {
        $table = (new ProductInventoryItem)->getTable();
        $result = DB::select(DB::raw("DESCRIBE {$table}"));
        $emptyItem = [];
        foreach($result as $column) {
            $emptyItem[$column->Field] = $column->Type;
        }
        return ["data" => $emptyItem];
    }

    /**
     * Return a blank resource with values set to appropriate column "empty" values.
     *
     * @return \Illuminate\Http\Response
     */
    public function blank()
    {
        $table = (new ProductInventoryItem)->getTable();
        $result = DB::select(DB::raw("DESCRIBE {$table}"));
        $emptyItem = [];
        foreach($result as $column) {
            if($column->Field == "in_current_stock") {
                $emptyItem[$column->Field] = 1;
            } else {
                $emptyItem[$column->Field] = get_empty_value_for_column($column);
            }
        }
        return ["data" => $emptyItem];
    }
}
