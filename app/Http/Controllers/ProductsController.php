<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Product;
use App\Http\Resources\Product as ProductResource;
use App\CustomClasses\ParseOptions;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Return a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Parse options to return a filtered/ordered by/paginated collection of Products
        $options = new ParseOptions($request);
        $orderBy = $options->getOrderedBy();
        if(empty($options->getFilters())) {
            $products = Product::orderBy($orderBy[0], $orderBy[1])
              ->paginate($options->getPaginate());
        } else {
            $products = Product::where([$options->getFilters()])
              ->orderBy($orderBy[0], $orderBy[1])
              ->paginate($options->getPaginate());
        }

        // Load relationships family and items
        $products->load('family', 'items');

        // Return ProductResource collection
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource or update an old resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // If it's an update, get the Product, else create new Product
        $product = $request->isMethod('put') ? Product::findOrFail($request->input('id')) : new Product;

        // If it's an update, set id to product_id
        if($request->isMethod('put')) {
            $product->id = $request->input('id');
        }

        // If msds_pdf present as a file, upload file
        // If msds_pdf present (but not as a file), save as file path
        // If msds_pdf not present (or empty), save as empty string
        if($request->hasFile('msds_pdf')) {
            $pathMsdsPdf = $request->file('msds_pdf')->store('public/msds_pdf');
            if($request->isMethod('put') && Storage::exists($product->msds_pdf)) {
                Storage::delete($product->msds_pdf);
            }
        } elseif($request->has('msds_pdf')) {
            if($request->isMethod('put')) {
                if($request->input('msds_pdf') == "delete" && Storage::exists($product->msds_pdf)) {
                    Storage::delete($product->msds_pdf);
                    $pathMsdsPdf = "";
                } elseif(Storage::exists($request->input('msds_pdf'))) {
                    $pathMsdsPdf = $request->input('msds_pdf');
                } else {
                    $pathMsdsPdf = "";
                }
            } else {
                $pathMsdsPdf = "";
            }
        } else {
            $pathMsdsPdf = "";
        }

        // If msds_docx present as a file, upload file
        // If msds_docx present (but not as a file), save as file path
        // If msds_docx not present (or empty), save as empty string
        if($request->hasFile('msds_docx')) {
            $pathMsdsDocx = $request->file('msds_docx')->store('public/msds_docx');
            if($request->isMethod('put') && Storage::exists($product->msds_docx)) {
                Storage::delete($product->msds_docx);
            }
        } elseif($request->has('msds_docx')) {
            if($request->isMethod('put')) {
                if($request->input('msds_docx') == "delete" && Storage::exists($product->msds_docx)) {
                    Storage::delete($product->msds_docx);
                    $pathMsdsDocx = "";
                } elseif(Storage::exists($request->input('msds_docx'))) {
                    $pathMsdsDocx = $request->input('msds_docx');
                } else {
                    $pathMsdsDocx = "";
                }
            } else {
                $pathMsdsDocx = "";
            }
        } else {
            $pathMsdsDocx = "";
        }

        // Map the remaining inputs to their respective properties
        $product->family_id = $request->input('family_id');
        $product->type = $request->input('type');
        $product->concentration = $request->input('concentration');
        $product->concentration_units = ($request->input('concentration_units') == "null" ? null : $request->input('concentration_units'));
        $product->solvent = ($request->input('solvent') == "null" ? null : $request->input('solvent'));
        $product->amount = $request->input('amount');
        $product->amount_units = $request->input('amount_units');
        $product->list_price = $request->input('list_price');
        $product->msds_pdf = $pathMsdsPdf;
        $product->msds_docx = $pathMsdsDocx;

        // Try to save Product
        if($product->save()) {
            // Return ProductResource
            return new ProductResource($product);
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
        // Get Product by id
        $product = Product::findOrFail($id);

        // Return ProductResource
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get Product by id
        $product = Product::findOrFail($id);

        // If msds_pdf exists as a file, delete it
        if(Storage::exists($product->msds_pdf)) {
            Storage::delete($product->msds_pdf);
        }

        // If msds_docx exists as a file, delete it
        if(Storage::exists($product->msds_docx)) {
            Storage::delete($product->msds_docx);
        }

        // Try to delete Product
        if($product->delete()) {
          // Return ProductResource
          return new ProductResource($product);
        }
    }

    /**
     * Return a resource with values set to column types to show model/table structure.
     *
     * @return \Illuminate\Http\Response
     */
    public function describe()
    {
        $table = (new Product)->getTable();
        $result = DB::select(DB::raw("DESCRIBE {$table}"));
        $emptyProduct = [];
        foreach($result as $column) {
            $emptyProduct[$column->Field] = $column->Type;
        }
        return ["data" => $emptyProduct];
    }

    /**
     * Return a blank resource with values set to appropriate column "empty" values.
     *
     * @return \Illuminate\Http\Response
     */
    public function blank()
    {
        $table = (new Product)->getTable();
        $result = DB::select(DB::raw("DESCRIBE {$table}"));
        $emptyProduct = [];
        foreach($result as $column) {
            $emptyProduct[$column->Field] = get_empty_value_for_column($column);
        }
        return ["data" => $emptyProduct];
    }
}
