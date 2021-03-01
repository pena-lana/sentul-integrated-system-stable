<?php

namespace App\Http\Controllers\Masterapp;



use App\Models\Master\Product;
use App\Models\Master\ProductType;
use App\Models\Master\FillingMachine;
use App\Models\Master\FillingMachineGroupHead;
use App\Models\Master\FillingMachineGroupDetail;
use App\Models\Master\Brand;
use App\Models\Master\Subbrand;
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;

use Auth;
use DB;
use DataTables;
use Session;
use Hash;
class ProductController extends ResourceController
{
    private $route = 'master_app.manage_product';

    public function index()
    {
        $subbrands          = Subbrand::all();
        $product_types      = ProductType::all();
        return view($this->route.".index",['subbrands' => $subbrands,'product_types'=>$product_types]);
    }

    public function getData(Request $request)
    {
        if ($request->ajax())
        {
            $accessCheck = $this->accessCheck('view',$this->route);

            if ($accessCheck['status'] !== '01' || $accessCheck['status'] == '02' )
            {
                $sql        = " SELECT B.subbrand_name as brand_name, C.product_type, A.product_name, A.oracle_code, A.trial_code, A.expired_range as expired_date,
                                A.spek_ts_min, A.spek_ts_max, A.spek_ph_min, A.spek_ph_max, A.sla, A.waktu_analisa_mikro, A.inkubasi as waktu_inkubasi, A.id
                                FROM products A
                                INNER JOIN subbrands B
                                ON B.id = A.subbrand_id
                                INNER JOIN product_types C
                                ON C.id = A.product_type_id";
                $products   = DB::select($sql);
                foreach ($products as $key => $product)
                {
                    $products[$key]->action                     = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm '.Session::get('edit').'" onclick="editProduct(this)" id="edit_product_'.$this->encrypt($product->id).'"><i class="fas fa-edit"></i></a>&nbsp;';
                    $products[$key]->expired_date               = $product->expired_date.' Month';
                    $products[$key]->spek_ts                    = "Ts Min. : ".$product->spek_ts_min."<br> Ts Max. : ".$product->spek_ts_max;
                    $products[$key]->spek_ph                    = "pH Min. : ".$product->spek_ph_min."<br> pH Max. : ".$product->spek_ph_max;
                    $products[$key]->sla                        = $product->sla.' Days';
                    $products[$key]->waktu_analisa_mikro        = $product->waktu_analisa_mikro.' Days';
                    $products[$key]->waktu_inkubasi             = $product->waktu_inkubasi.' Days';
                }
                return Datatables::of($products)
                ->addIndexColumn()
                ->addColumn('spek_ts', function($row){
                    $spek_tsBtn = $row->spek_ts;
                    return $spek_tsBtn;
                })
                ->addColumn('spek_ph', function($row){
                    $spek_phBtn = $row->spek_ph;
                    return $spek_phBtn;
                })
                ->addColumn('action', function($row){
                    $actionBtn = $row->action;
                    return $actionBtn;
                })
                ->rawColumns(['spek_ts','spek_ph','action'])
                ->make(true);

            }
            else
            {
                return $accessCheck;
            }
        }
    }

    public function addNewProductModal(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $subbrands                      = $this->encryptId(Subbrand::all());
            $product_types                  = $this->encryptId(ProductType::all());
            $filling_machine_group_heads    = $this->encryptId(FillingMachineGroupHead::all());
            return view($this->route.'._form',['subbrands'=>$subbrands,'product_types'=>$product_types,'filling_machine_group_heads'=>$filling_machine_group_heads]);
        }
        else
        {
            return $accessCheck;
        }
    }

    public function addNewProduct(Request $request)
    {
        $accessCheck        = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $product_name                      = $request->product_name;
            $oracle_code                       = $request->oracle_code;
            $subbrand_id                       = $this->decrypt($request->subbrand_id);
            $product_type_id                   = $this->decrypt($request->product_type_id);
            $filling_machine_group_head_id     = $this->decrypt($request->filling_machine_group_head_id);
            $expired_range                     = $request->expired_range;
            $trial_code                        = $request->trial_code;
            $spek_ts_min                       = $request->spek_ts_min;
            $spek_ts_max                       = $request->spek_ts_max;
            $spek_ph_min                       = $request->spek_ph_min;
            $spek_ph_max                       = $request->spek_ph_max;
            $sla                               = $request->sla;
            $waktu_analisa_mikro               = $request->waktu_analisa_mikro;
            $inkubasi                          = $request->inkubasi;
            $is_active                         = $request->is_active;
            $check_product                      = Product::where('oracle_code',$oracle_code)->first();
            if (is_null($check_product))
            {
                /*ini untuk penambahan produk baru nya*/
                $newProduct = Product::create([
                                'product_name'                  => $product_name,
                                'oracle_code'                   => $oracle_code,
                                'subbrand_id'                   => $subbrand_id,
                                'product_type_id'               => $product_type_id,
                                'filling_machine_group_head_id' => $filling_machine_group_head_id,
                                'expired_range'                 => $expired_range,
                                'trial_code'                    => $trial_code,
                                'spek_ts_min'                   => $spek_ts_min,
                                'spek_ts_max'                   => $spek_ts_max,
                                'spek_ph_min'                   => $spek_ph_min,
                                'spek_ph_max'                   => $spek_ph_max,
                                'sla'                           => $sla,
                                'waktu_analisa_mikro'           => $waktu_analisa_mikro,
                                'inkubasi'                      => $inkubasi,
                                'is_active'                     => $is_active
                            ]);
                return [
                    'status' => '00',
                    'message'=>'Produk baru dengan nama '.$newProduct->product_name.' dan kode oracle '.$newProduct->oracle_code.' Berhasil ditambahkan'
                ];
            }
            else
            {
                return [
                    'status'    => '01',
                    'message'   => 'Produk dengan kode oracle '.$oracle_code.' sudah terdaftar dengan nama produk'.$check_product->product_name
                ];
            }

        }
        else
        {
            return $accessCheck;
        }
    }

    public function editProductModal(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $product_id                     = $this->decrypt($request->product_id);
            $product                        = $this->encryptId(Product::find($product_id),'subbrand_id','product_type_id','filling_machine_group_head_id');
            $subbrands                      = $this->encryptId(Subbrand::all());
            $product_types                  = $this->encryptId(ProductType::all());
            $filling_machine_group_heads    = $this->encryptId(FillingMachineGroupHead::all());
            return view($this->route.'._edit',['product'=>$product,'subbrands'=>$subbrands,'product_types'=>$product_types,'filling_machine_group_heads'=>$filling_machine_group_heads]);

        }
        else
        {
            return $accessCheck;
        }
    }


    public function editProduct(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $product_name                       = $request->product_name;
            $oracle_code                        = $request->oracle_code;
            $product_id                         = $this->decrypt($request->encrypt_id);
            $subbrand_id                        = $this->decrypt($request->subbrand_id);
            $product_type_id                    = $this->decrypt($request->product_type_id);
            $filling_machine_group_head_id      = $this->decrypt($request->filling_machine_group_head_id);
            $expired_range                      = $request->expired_range;
            $trial_code                         = $request->trial_code;
            $spek_ts_min                        = $request->spek_ts_min;
            $spek_ts_max                        = $request->spek_ts_max;
            $spek_ph_min                        = $request->spek_ph_min;
            $spek_ph_max                        = $request->spek_ph_max;
            $sla                                = $request->sla;
            $waktu_analisa_mikro                = $request->waktu_analisa_mikro;
            $inkubasi                           = $request->inkubasi;
            $is_active                          = $request->is_active;
            $product                            = Product::find($product_id);
            if ($product->oracle_code == $oracle_code)
            {
                $product->product_name                      = $product_name;
                $product->subbrand_id                       = $subbrand_id;
                $product->product_type_id                   = $product_type_id;
                $product->filling_machine_group_head_id     = $filling_machine_group_head_id;
                $product->expired_range                     = $expired_range;
                $product->trial_code                        = $trial_code;
                $product->spek_ts_min                       = $spek_ts_min;
                $product->spek_ts_max                       = $spek_ts_max;
                $product->spek_ph_min                       = $spek_ph_min;
                $product->spek_ph_max                       = $spek_ph_max;
                $product->sla                               = $sla;
                $product->waktu_analisa_mikro               = $waktu_analisa_mikro;
                $product->inkubasi                          = $inkubasi;
                $product->is_active                         = $is_active;
                $product->save();
                return [
                    'status'    => '00',
                    'message'   => 'Data product dengan kode oracle '.$product->oracle_code.' berhasil di ubah'
                ];
            }
            else
            {
                $check_product      = Product::where('oracle_code',$oracle_code)->first();
                if (count($check_product) > 0)
                {
                    return [
                        'status'    => '01',
                        'message'   => 'Kode oracle : '.$oracle_code.' sudah terdaftar dengan nama produk'.$check_product->product_name
                    ];
                }
                else
                {
                    $product->product_name                      = $product_name;
                    $product->oracle_code                       = $oracle_code;
                    $product->subbrand_id                       = $subbrand_id;
                    $product->product_type_id                   = $product_type_id;
                    $product->filling_machine_group_head_id     = $filling_machine_group_head_id;
                    $product->expired_range                     = $expired_range;
                    $product->trial_code                        = $trial_code;
                    $product->spek_ts_min                       = $spek_ts_min;
                    $product->spek_ts_max                       = $spek_ts_max;
                    $product->spek_ph_min                       = $spek_ph_min;
                    $product->spek_ph_max                       = $spek_ph_max;
                    $product->sla                               = $sla;
                    $product->waktu_analisa_mikro               = $waktu_analisa_mikro;
                    $product->inkubasi                          = $inkubasi;
                    $product->is_active                         = $is_active;
                    $product->save();
                    return [
                        'status'    => '00',
                        'message'   => 'Data product berhasil di ubah'
                    ];
                }

            }
        }
        else
        {
            return $accessCheck;
        }
    }


    public function destroy(Product $product)
    {
        //
    }
}
