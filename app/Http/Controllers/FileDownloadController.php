<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Product;
use App\ProductInventoryItem as Item;

class FileDownloadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Force download response of product file (pdf or docx)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $type
     * @param  int                       $id
     * @return \Illuminate\Http\Response
     */
      public function product(Request $request, $type, $id) {
          $product = Product::findOrFail($id);
          $fileName = ($type == 'pdf' ? 'MSDS.pdf' : 'MSDS.docx');
          $filePath = ($type == 'pdf' ? $product->msds_pdf : $product->msds_docx);
          if(Storage::exists($filePath)) {
              return response()->download($filePath, $fileName);
          } else {
              return 404;
          }
      }

      /**
       * Force download response of item file (pdf or docx)
       *
       * @param  \Illuminate\Http\Request  $request
       * @param  string                    $type
       * @param  int                       $id
       * @return \Illuminate\Http\Response
       */
        public function item(Request $request, $type, $id) {
            $item = Item::findOrFail($id);
            $fileName = ($type == 'pdf' ? 'CofA.pdf' : 'CofA.docx');
            $filePath = ($type == 'pdf' ? $item->cofa_pdf : $item->cofa_docx);
            if(Storage::exists($filePath)) {
                return response()->download($filePath, $fileName);
            } else {
                return 404;
            }
        }
}
