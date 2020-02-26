<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    App\Products,
    Illuminate\Support\Facades\Auth,
    App\Traits\UploadTrait,
    Illuminate\Support\Str,
    App\ProductsPrices,
    Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    use UploadTrait;

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Products::orderBy('created', 'desc')->paginate(9);

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'price'=> 'required|numeric',
            'img_src' => 'required|image|mimes:jpeg,jpg,png'
        ]);

        DB::beginTransaction();

        try {

            $product = new Products([
                'name' => $request->get('name'),
                'user_id' => Auth::id(),
                'img_src'=> $request->get('img_src')
            ]);

            if ($request->has('img_src')) {
                $image = $request->file('img_src');
                $name = Str::slug($product->name).'_'.time();
                $folder = '/uploads/images/';
                $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                $this->uploadOne($image, $folder, 'public', $name);
                $product->img_src = $filePath;
            }

            if ($product->save()) {
                $mainPrice = $request->get('price');

                $currenciesRates = ProductsPrices::getCurrenciesRates();
                if (!$currenciesRates) {
                    $currenciesRates = [ProductsPrices::CURRENCY_UAH => 1];
                }
                $productsCurrencies = ProductsPrices::$currencies;
                foreach ($productsCurrencies as $currency) {
                    if (array_key_exists($currency, $currenciesRates)) {
                        $productPrice = new ProductsPrices([
                            'product_id' => $product->id,
                            'currency' => $currency,
                            'amount' => ((float)$currenciesRates[$currency] >= 1 ? (float)$mainPrice / (float)$currenciesRates[$currency] : (float)$mainPrice * (float)$currenciesRates[$currency]),
                        ]);

                        $productPrice->save();
                    }
                }
                DB::commit();
            } else {
                
            }

            return redirect('/products')->with('success', 'Stock has been added');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/products')->with('error', 'Created error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Products::find($id);

        return view('products.show', compact('product'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Products::find($id);
        $product->delete();

        return redirect('/products')->with('success', 'Stock has been deleted Successfully');
    }
}
