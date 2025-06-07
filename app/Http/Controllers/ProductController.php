<?php

namespace App\Http\Controllers;

use App\Models\Product; //panggil model
use App\Models\Category; //panggil model
use App\Models\Label; //panggil model
use App\Models\Pesanan; //panggil model
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //jika pakai query builder
use Illuminate\Support\Str;
// use App\Exports\BukuExport; // Will be handled later if BukuExport is renamed/updated
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
// use Spatie\PdfToImage\Pdf; // Assuming this is not directly related to Buku/Product model changes for now

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // kategori table name is 'kategori', labels table is 'labels'
        // Also view will be 'products.index'
        $ar_products = DB::table('products')
                ->join('kategori', 'kategori.id', '=', 'products.kategori_id')
                ->join('labels', 'labels.id', '=', 'products.label_id')
                ->select('products.*', 'kategori.nama as category_name', 'labels.name as label_name')
                ->orderBy('products.id', 'desc')
                ->get();
        return view('products.index', compact('ar_products'));
    }

    // dataBuku method seems to be for a specific section (hero) showing bestsellers.
    // Renaming to dataProductsForHero for clarity.
    public function dataProductsForHero(Request $request)
    {
        $ar_products = Product::leftJoin('pesanan', 'products.id', '=', 'pesanan.product_id')
            ->leftJoin('kategori', 'products.kategori_id', '=', 'kategori.id') // Corrected to 'kategori' table
            ->select(
                'products.id',
                'products.judul',
                'products.harga',
                'products.diskon',
                'products.foto',
                'products.slug',
                'kategori.nama as category_name', // Corrected to 'kategori.nama'
                DB::raw('COUNT(pesanan.product_id) as jumlah_pesanan')
            )
            ->groupBy(
                'products.id',
                'products.judul',
                'products.harga',
                'products.diskon',
                'products.foto',
                'products.slug',
                'kategori.nama' // Corrected to 'kategori.nama'
            )
            ->orderBy('jumlah_pesanan', 'desc')
            ->get();

        $search = $request->search;
        // This $product_terpilih logic seems redundant if $ar_products is already fetched and ordered.
        // If it's for a separate search on the same view, it needs to be clarified.
        // For now, I'll assume it's part of the same display, and the search should apply to the main query if needed.
        // However, the current $product_terpilih query is not used in the compact if $ar_products is the primary data.
        // Let's refine this if the view's purpose is clearer.
        // For now, I'll comment out the separate $product_terpilih query as its results aren't passed to the view if $ar_products is used.

        // $product_terpilih = Product::query();
        // if ($search) {
        //     $product_terpilih->where(function ($query) use ($search) {
        //         $query->where('judul', 'like', '%'.$search.'%')
        //             ->orWhere('harga', 'like', '%'.$search.'%')
        //             ->orWhere('description', 'like', '%'.$search.'%');
        //     });
        // }
        // $product_terpilih = $product_terpilih->get();

        return view('landingpage.hero', compact('ar_products', 'search'));
    }


    public function readProduct(Request $request)
    {
        $filename = $request->query('file');
        $path = public_path('landingpage/pdf/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }


    public function productDiskon(Request $request)
    {
        $ar_products = Product::where('diskon', '>', 0)->get();

        $search = $request->search;
        // Similar to dataProductsForHero, this secondary query might be redundant or needs clarification.
        // If $ar_products is the main list, search should apply to it.
        // Commenting out for now.
        // $product_terpilih = Product::query();
        // if ($search) {
        //     $product_terpilih->where(function ($query) use ($search) {
        //         $query->where('judul', 'like', '%'.$search.'%')
        //               ->orWhere('harga', 'like', '%'.$search.'%')
        //               ->orWhere('description', 'like', '%'.$search.'%');
        //     });
        // }
        // $product_terpilih = $product_terpilih->get();

        return view('landingpage.promo', compact('ar_products', 'search'));
    }

    public function filterProduct(Request $request)
    {
        // The logic for $ar_products based on specific IDs seems for display purposes, not filtering the main query.
        // This part might be for sidebars or other UI elements.
        // $categoryIds = [1, 2, 3, 4, 5, 6, 7, 8];
        // $labelIds = [1, 2, 3, 4, 5];
        // $ar_products_display = [];
        // foreach ($categoryIds as $categoryId) {
        //     $ar_products_display[$categoryId] = Product::where('kategori_id', $categoryId)->get();
        // }
        // foreach ($labelIds as $labelId) {
        //     $ar_products_display[$labelId] = Product::where('label_id', $labelId)->get();
        // }

        $selectedCategory = $request->kategori_id; // Assuming request uses 'kategori_id'
        $selectedLabel = $request->label_id; // Assuming request uses 'label_id'
        $urutan = $request->urutan;
        $hargaMin = intval(str_replace('.', '', $request->input('harga_min')));
        $hargaMax = intval(str_replace('.', '', $request->input('harga_max')));
        $promo = $request->has('promo');
        $search = $request->search;

        $products_query = Product::query(); // Changed from $product_terpilih to $products_query for clarity

        if ($selectedCategory) {
            $products_query->where('kategori_id', $selectedCategory);
        }

        if ($selectedLabel) {
            $products_query->where('label_id', $selectedLabel);
        }

        if ($hargaMin) {
            $products_query->where('harga', '>=', $hargaMin);
        }

        if ($hargaMax) {
            $products_query->where('harga', '<=', $hargaMax);
        }

        if ($promo) {
            $products_query->where('diskon', '>', 0);
        }

        if ($search) {
            $products_query->where(function ($query) use ($search) {
                $query->where('judul', 'like', '%'.$search.'%')
                      ->orWhere('description', 'like', '%'.$search.'%')
                      ->orWhereHas('category', function ($q) use ($search) { // Search by category name
                            $q->where('nama', 'like', '%' . $search . '%');
                        })
                      ->orWhereHas('label', function ($q) use ($search) { // Search by label name
                            $q->where('name', 'like', '%' . $search . '%');
                        });
            });
        }

        if ($urutan == 'terbaru') {
            $products_query->orderBy('id', 'desc');
        } elseif ($urutan == 'terlama') {
            $products_query->orderBy('id', 'asc');
        } elseif ($urutan == 'harga-tertinggi') {
            $products_query->orderByRaw('harga - (harga * IFNULL(diskon, 0) / 100) DESC'); // Order by price after discount
        } elseif ($urutan == 'harga-terendah') {
            $products_query->orderByRaw('harga - (harga * IFNULL(diskon, 0) / 100) ASC'); // Order by price after discount
        }

        $product_terpilih = $products_query->paginate(20); // Result of the main query

        $semua_products = Product::all();
        $semua_categories = Category::all();
        $semua_labels = Label::all();

        $breadcrumb = ['Home', 'Ebook'];

        if ($selectedCategory) {
            $category = Category::find($selectedCategory);
            if ($category) {
                $breadcrumb[] = $category->nama;
            }
        }

        if ($selectedLabel) {
            $label = Label::find($selectedLabel);
            if ($label) {
                $breadcrumb[] = $label->name;
            }
        }

        if ($promo) {
            $breadcrumb[] = 'Promo';
        }

        if ($search) {
            $breadcrumb[] = "Pencarian: '$search'";
        }
        // Assuming view name is 'landingpage.products' or similar.
        return view('landingpage.ebook', compact(
            // 'ar_products_display', // This variable was for the commented out display logic
            'product_terpilih',
            'selectedCategory',
            'selectedLabel',
            'semua_products',
            'semua_categories',
            'semua_labels',
            'urutan',
            'hargaMin',
            'hargaMax',
            'promo',
            'search',
            'breadcrumb'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //ambil master untuk dilooping di select option
        $ar_categories = Category::all(); // Renamed from $ar_kategori
        $ar_labels = Label::all(); // Renamed from $ar_penerbit
        //arahkan ke form input data
        return view('buku.form',compact('ar_categories', 'ar_labels')); // View name will be changed later
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:products,kode|max:5', // Ensure unique check is on products.kode
            'judul' => 'required|max:45',
            'kategori_id' => 'required|integer|exists:kategori,id', // Ensure kategori_id exists in kategori table
            'label_id' => 'required|integer|exists:labels,id', // Ensure label_id exists in labels table
            'description' => 'nullable|string',
            'harga' => 'required|numeric', // Simpler validation for numeric
            'diskon' => 'nullable|numeric', // Simpler validation for numeric
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:500',
            'long_product' => 'nullable|numeric',
            'width_product' => 'nullable|numeric',
        ],
        //custom pesan errornya
        [
            'kode.required'=>'Kode Wajib Diisi',
            'kode.unique'=>'Kode Sudah Ada (Terduplikasi)',
            'kode.max'=>'Kode Maksimal 5 karakter',
            'judul.required'=>'Judul Wajib Diisi',
            'judul.max'=>'Judul Maksimal 45 karakter',
            'kategori_id.required'=>'Kategori Wajib Diisi',
            'kategori_id.integer'=>'Kategori Harus Berupa Angka',
            'kategori_id.exists'=>'Kategori tidak valid',
            'label_id.required'=>'Label Wajib Diisi',
            'label_id.integer'=>'Label Harus Berupa Angka',
            'label_id.exists'=>'Label tidak valid',
            'harga.required'=>'Harga Wajib Diisi',
            'harga.numeric'=>'Harga Harus Berupa Angka',
            'diskon.numeric'=>'Diskon Harus Berupa Angka',
            'foto.max'=>'Ukuran file foto melebihi 500 KB',
            'foto.image'=>'File foto bukan gambar',
            'foto.mimes'=>'Extension file foto selain jpg,jpeg,png,svg',
            'long_product.numeric' => 'Panjang produk harus angka.',
            'width_product.numeric' => 'Lebar produk harus angka.',
        ]);

        // PDF handling logic removed as per previous changes
        // if ($request->hasFile('pdf_ebook')) {
            // ...
        // }

            if(!empty($request->foto)){
                $fileName = 'product_'.$request->kode.'.'.$request->foto->extension();
                $request->foto->move(public_path('landingpage/img'),$fileName);
            }
            else{
                $fileName = '';
            }

            $slug = Str::slug($request->judul);
            $originalSlug = $slug;
            $counter = 1;

            while (Product::where('slug', $slug)->exists()) { // Changed from Buku
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Simpan path PDF dan JPG ke database
            $product = new Product; // Changed from $buku
            $product->kode = $request->kode;
            $product->judul = $request->judul;
            $product->slug = $slug;
            $product->kategori_id = $request->kategori_id;
            $product->label_id = $request->label_id;
            $product->description = $request->description;
            $product->harga = $request->harga;
            $product->diskon = $request->diskon;
            $product->foto = $fileName;
            $product->long_product = $request->long_product;
            $product->width_product = $request->width_product;

            $product->save();

            return redirect()->route('product.index')->with('success', 'Produk berhasil ditambahkan.'); // Changed route name
        // } else {
            // return back()->withErrors(['msg' => 'File PDF harus diunggah.']); // This part is effectively removed
        // }
    }

    /**
     * Detail product adminpage
     */
    public function show(string $id, Request $request)
    {
        $rs = Product::with(['category', 'label'])->find($id); // Eager load relationships

        $search = $request->search;
        // Search logic needs to be adapted if it's still used in this view
        // For now, keeping it simple as the main focus is the Product display
        // $product_terpilih = Product::query();
        // if ($search) {
        //     $product_terpilih->where(function ($query) use ($search) {
        //         $query->where('judul', 'like', '%'.$search.'%')
        //               ->orWhere('harga', 'like', '%'.$search.'%')
        //               ->orWhere('description', 'like', '%'.$search.'%');
        //     });
        // }
        // $product_terpilih = $product_terpilih->get();

        return view('products.detail', compact('rs', 'search')); // Changed view name
    }

    /**
     * Detail product landingpage
     */
    public function detailProduct(Product $product, Request $request)
    {
        $rs = $product->loadCount('pesanan')->load(['category', 'label', 'productImages']); // Eager load

        $search = $request->search;
        // Similar to show(), search logic might need to be re-evaluated for this specific view
        // $product_terpilih = Product::query();
        // if ($search) {
        // ...
        // }
        // $product_terpilih = $product_terpilih->get();

        return view('landingpage.product_detail', compact('rs', 'search')); // Changed view name
    }

    // detailPengarang method is removed as Pengarang model is gone.
    // If similar functionality is needed for Labels or Categories, new methods should be created.


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ar_categories = Category::all();
        $ar_labels = Label::all();
        $row = Product::find($id);
        return view('products.form_edit',compact('row','ar_categories', 'ar_labels')); // Changed view name
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id); // Ensure product exists

        $request->validate([
            'kode' => 'required|max:5|unique:products,kode,'.$product->id, // Check unique except current product
            'judul' => 'required|max:45',
            'kategori_id' => 'required|integer|exists:kategori,id',
            'label_id' => 'required|integer|exists:labels,id',
            'description' => 'nullable|string',
            'harga' => 'required|numeric',
            'diskon' => 'nullable|numeric',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:500',
            'long_product' => 'nullable|numeric',
            'width_product' => 'nullable|numeric',
        ],
        [
            'kode.required'=>'Kode Wajib Diisi',
            'kode.unique'=>'Kode Sudah Ada (Terduplikasi)',
            'kode.max'=>'Kode Maksimal 5 karakter',
            'judul.required'=>'Judul Wajib Diisi',
            'judul.max'=>'Judul Maksimal 45 karakter',
            'kategori_id.required'=>'Kategori Wajib Diisi',
            'kategori_id.integer'=>'Kategori Harus Berupa Angka',
            'kategori_id.exists'=>'Kategori tidak valid',
            'label_id.required'=>'Label Wajib Diisi',
            'label_id.integer'=>'Label Harus Berupa Angka',
            'label_id.exists'=>'Label tidak valid',
            'harga.required'=>'Harga Wajib Diisi',
            'harga.numeric'=>'Harga Harus Berupa Angka',
            'diskon.numeric'=>'Diskon Harus Berupa Angka',
            'foto.max'=>'Ukuran file foto melebihi 500 KB',
            'foto.image'=>'File foto bukan gambar',
            'foto.mimes'=>'Extension file foto selain jpg,jpeg,png,svg',
            'long_product.numeric' => 'Panjang produk harus angka.',
            'width_product.numeric' => 'Lebar produk harus angka.',
        ]);

        $namaFileFotoLama = $product->foto;

        if($request->hasFile('foto')){
            if(!empty($namaFileFotoLama) && file_exists(public_path('landingpage/img/'.$namaFileFotoLama))){
                unlink(public_path('landingpage/img/'.$namaFileFotoLama));
            }
            $fileName = 'product_'.$request->kode.'.'.$request->file('foto')->extension();
            $request->file('foto')->move(public_path('landingpage/img'),$fileName);
        } else {
            $fileName = $namaFileFotoLama;
        }

        $product->update([
            'kode' => $request->kode,
            'judul' => $request->judul,
            'kategori_id' => $request->kategori_id,
            'label_id' => $request->label_id,
            'description' => $request->description,
            'harga' => $request->harga,
            'diskon' => $request->diskon,
            'foto' => $fileName,
            'long_product' => $request->long_product,
            'width_product' => $request->width_product,
        ]);

        return redirect()->route('product.show', $product->id) // Changed route name
            ->with('success','Data Produk Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        if (!empty($product->foto) && file_exists(public_path('landingpage/img/'.$product->foto))) {
            unlink(public_path('landingpage/img/'.$product->foto));
        }

        $product->delete();
        return redirect()->route('product.index') // Changed route name
                        ->with('success', 'Data Produk Berhasil Dihapus');
    }

    // delete method commented out as destroy is the standard resource controller method.
    /*
    public function delete($id)
    {
        // ...
    }
    */

    public function productExcel()
    {
        // return Excel::download(new ProductExport, 'data_produk_'.date('d-m-Y').'.xlsx'); // ProductExport needs to be created
        return redirect()->back()->with('info', 'Product export functionality will be available soon.');
    }

}
