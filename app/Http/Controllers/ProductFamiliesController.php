<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductFamily;
use App\Http\Resources\ProductFamily as ProductFamilyResource;
use App\CustomClasses\ParseOptions;
use Illuminate\Support\Facades\DB;

class ProductFamiliesController extends Controller
{

    /**
     * Return a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Parse options to return a filtered/ordered by/paginated collection of Families
        $options = new ParseOptions($request);
        $orderBy = $options->getOrderedBy();
        if(empty($options->getFilters())) {
            $productFamilies = ProductFamily::orderBy($orderBy[0], $orderBy[1])
              ->paginate($options->getPaginate());
        } else {
            $productFamilies = ProductFamily::where([$options->getFilters()])
              ->orderBy($orderBy[0], $orderBy[1])
              ->paginate($options->getPaginate());
        }

        // Load relationships products and items
        $productFamilies->load('products', 'items');

        // Return ProductFamilyResource collection
        return ProductFamilyResource::collection($productFamilies);
    }

    /**
     * Store a newly created resource or update an old resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // If it's an update, get the ProductFamily, else create new ProductFamily
        $productFamily = $request->isMethod('put') ? ProductFamily::findOrFail($request->id) : new ProductFamily;

        // If it's an update, set id to family_id
        if($request->isMethod('put')) {
            $productFamily->id = $request->input('id');
        }

        // Map the remaining inputs to their respective properties
        $productFamily->compound_id = $request->input('compound_id');
        $productFamily->compound = $request->input('compound');
        $productFamily->short_name = $request->input('short_name');

        // Try to save ProductFamily
        if($productFamily->save()) {
            // Return ProductFamilyResource
            return new ProductFamilyResource($productFamily);
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
        // Get ProductFamily by id
        $productFamily = ProductFamily::findOrFail($id);

        // Return ProductFamilyResource
        return new ProductFamilyResource($productFamily);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get ProductFamily by id
        $productFamily = ProductFamily::findOrFail($id);

        // Try to delete ProductFamily
        if($productFamily->delete()) {
            // Return ProductFamilyResource
            return new ProductFamilyResource($productFamily);
        }
    }

    /**
     * Return a resource with values set to column types to show model/table structure.
     *
     * @return \Illuminate\Http\Response
     */
    public function describe()
    {
        $table = (new ProductFamily)->getTable();
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
        $table = (new ProductFamily)->getTable();
        $result = DB::select(DB::raw("DESCRIBE {$table}"));
        $emptyProductFamily = [];
        foreach($result as $column) {
            $emptyProductFamily[$column->Field] = get_empty_value_for_column($column);
        }
        return ["data" => $emptyProductFamily];
    }
}
