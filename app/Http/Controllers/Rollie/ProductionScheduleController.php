<?php

namespace App\Http\Controllers\Rollie;


use App\Models\Master\Product;
use App\Models\Master\ProductType;
use App\Models\Master\FillingMachine;
use App\Models\Master\FillingMachineGroupHead;
use App\Models\Master\FillingMachineGroupDetail;
use App\Models\Master\Brand;
use App\Models\Master\Subbrand;
use App\Imports\Rollie\ProductionSchedule\UploadMtol;

use App\Models\Transaction\Rollie\WoNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use Maatwebsite\Excel\Facades\Excel;

use Auth;
use DB;
use DataTables;
use Session;
use Hash;
class ProductionScheduleController extends ResourceController
{
    private $route                      = "rollie.production_schedule";
    public function index()
    {
        $products       = Product::all();
        return view($this->route.".index",['products'=>$products]);
    }


    public function getData(Request $request)
    {
        if ($request->ajax())
        {
            $database_master            = DB::connection('master_data')->getDatabaseName();
            $database_transaction       = DB::connection('transaction_data')->getDatabaseName();

            $accessCheck = $this->accessCheck('view',$this->route);
            if ($accessCheck['status'] !== '01' || $accessCheck['status'] == '02' )
            {
                $sql     = "SELECT A.id, A.wo_number, B.product_name, B.oracle_code, A.production_plan_date, A.production_realisation_date, A.wo_status,
                            A.plan_batch_size, A.actual_batch_size, A.explanation_1, A.explanation_2, A.explanation_3, A.formula_revision
                            FROM ".$database_transaction.".wo_numbers A
                            INNER JOIN ".$database_master.".products B
                            ON B.id = A.product_id WHERE A.upload_status='1' ORDER BY production_plan_date ASC";
                $production_schedules   = DB::select($sql);
                if (count($production_schedules) > 0)
                {
                    foreach ($production_schedules as $key => $production_schedule)
                    {
                        $production_schedules[$key]->btn_extra       = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm '.Session::get('edit').'" onclick="updateProductionSchedule(this)" id="production_schedule_'.$this->encrypt($production_schedule->id).'"><i class="fas fa-edit"></i></a>&nbsp;';
                        switch ($production_schedule->wo_status)
                        {
                            case '0':
                                $production_schedules[$key]->production_status     = "WIP Mixing";
                                $production_schedules[$key]->btn_extra       .= '<a href="javascript:void(0)" class="delete btn btn-danger btn-sm '.Session::get('delete').'" onclick="cancelProductionSchedule(this)" id="'.$production_schedule->product_name.'_'.$production_schedule->wo_number.'_'.$this->encrypt($production_schedule->id).'"><i class="fas fa-trash"></i></a>&nbsp;';

                            break;
                            case '1':
                                $production_schedules[$key]->production_status     = "In Progress Mixing";
                            break;
                            case '2':
                                $production_schedules[$key]->production_status     = "WIP Fillpack";
                            break;
                            case '3':
                                $production_schedules[$key]->production_status     = "In Progress Fillpack";
                            break;
                            case '4':
                                $production_schedules[$key]->production_status     = "Waiting For Close";
                            break;

                            case '5':
                                $production_schedules[$key]->production_status     = "Closed Wo";
                            break;

                            case '6':
                                $production_schedules[$key]->production_status     = "Canceled Schedule";
                                $production_schedules[$key]->btn_extra             = '';
                            break;

                        }
                    }
                    return Datatables::of($production_schedules)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $actionBtn = $row->btn_extra;
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
                }
                else
                {
                    return Datatables::of($production_schedules)
                    ->addIndexColumn()
                    ->make(true);
                }

            }
            else
            {
                return $accessCheck;
            }
        }
    }



    public function newProductionScheduleForm(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $data_draft     = WoNumber::where('upload_status','0')->count();
            return view($this->route.'.new_production_schedule_form',['data_draft'=>$data_draft]);
        }
        else
        {
            return $accessCheck;
        }
    }
    public function getDataDraft(Request $request)
    {
        if ($request->ajax())
        {
            $database_master            = DB::connection('master_data')->getDatabaseName();
            $database_transaction       = DB::connection('transaction_data')->getDatabaseName();

            $accessCheck = $this->accessCheck('view',$this->route);
            if ($accessCheck['status'] !== '01' || $accessCheck['status'] == '02' )
            {
                $sql     = "SELECT A.id, A.wo_number, B.product_name, B.oracle_code, A.production_plan_date, A.production_realisation_date, A.wo_status,
                            A.plan_batch_size, A.actual_batch_size, A.explanation_1, A.explanation_2, A.explanation_3, A.formula_revision
                            FROM ".$database_transaction.".wo_numbers A
                            INNER JOIN ".$database_master.".products B
                            ON B.id = A.product_id WHERE A.upload_status='0'";
                $production_schedules   = DB::select($sql);
                if (count($production_schedules) > 0)
                {
                    foreach ($production_schedules as $key => $production_schedule)
                    {
                        $production_schedules[$key]->btn_extra       = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm '.Session::get('edit').'" onclick="updateDraftProductionSchedule(this)" id="production_schedule_'.$this->encrypt($production_schedule->id).'"><i class="fas fa-edit"></i></a>&nbsp;';
                        $production_schedules[$key]->btn_extra       .= '<a href="javascript:void(0)" class="delete btn btn-danger btn-sm '.Session::get('delete').'" onclick="deleteDraftProductionSchedule(this)" id="'.$production_schedule->product_name.'_'.$production_schedule->wo_number.'_'.$this->encrypt($production_schedule->id).'"><i class="fas fa-trash"></i></a>&nbsp;';
                        switch ($production_schedule->wo_status)
                        {
                            case '0':
                                $production_schedules[$key]->production_status     = "WIP Mixing";
                            break;
                            case '1':
                                $production_schedules[$key]->production_status     = "In Progress Mixing";
                            break;
                            case '2':
                                $production_schedules[$key]->production_status     = "WIP Fillpack";
                            break;
                            case '3':
                                $production_schedules[$key]->production_status     = "In Progres Fillpack";
                            break;
                            case '4':
                                $production_schedules[$key]->production_status     = "Waiting For Close";
                            break;

                            case '5':
                                $production_schedules[$key]->production_status     = "Closed Wo";
                            break;

                            case '6':
                                $production_schedules[$key]->production_status     = "Canceled Schedule";
                            break;

                        }
                    }
                    return Datatables::of($production_schedules)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $actionBtn = $row->btn_extra;
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
                }
                else
                {
                    return Datatables::of($production_schedules)
                    ->addIndexColumn()
                    ->make(true);
                }

            }
            else
            {
                return $accessCheck;
            }
        }
    }

    public function uploadMtolModal(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            return view($this->route.'._form_upload_mtol');
        }
        else
        {
            return $accessCheck;
        }
    }

    public function uploadMtol(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            if ($request->hasFile('mtol_file'))
            {
                $allowed_extention  = ['xls','xlsx'];
                if (in_array($request->mtol_file->getClientOriginalExtension(), $allowed_extention))
                {
                    $mtol_file          = $request->file('mtol_file');
                    $get_file_mtol      = Excel::toArray(new UploadMtol,$mtol_file);
                    $get_content_mtol   = $get_file_mtol['Mampu Telusur Produk Online (MT'];
                    for ($i=4; $i < count($get_content_mtol) ; $i++)
                    {
                        $wo_number          = $get_content_mtol[$i][3];
                        $oracle_code        = $get_content_mtol[$i][8];
                        $product_name       = $get_content_mtol[$i][9];
                        if ($wo_number !== '' && !is_null($wo_number) && $oracle_code !== '' && !is_null($oracle_code) && $product_name !== '' && !is_null($product_name))
                        {
                            /* for checking product data is exists on database or not */
                            if (strpos($wo_number,'/'))
                            {
                                $get_trial_code     = explode('/',$wo_number);
                                $trial_code         = end($get_trial_code);
                                $product            = Product::where('trial_code',$trial_code)->first();
                                if (is_null($product))
                                {
                                    return [
                                        'status'    => '01',
                                        'message'   => 'Jadwal produksi dengan nomor wo '.$wo_number.' berstatus Trial namun produk belum terdaftar pada database. Harap hubungi administrator aplikasi untuk menambahkan produk dengan kode trial tersebut'
                                    ];
                                }
                            }
                            else
                            {
                                if ($oracle_code !== '7500147M' && $oracle_code !== '7500150M')
                                {
                                    $product  = Product::where('oracle_code',$oracle_code)->first();
                                    if (is_null($product))
                                    {
                                        return [
                                            'status'    => '01',
                                            'message'   => 'Produk dengan kode oracle '.$oracle_code.' belum terdaftar pada database. Harap cek kembali excel mtol pada row ke '.$i.' atau hubungi administrasi aplikasi untuk menambahkan data produk'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                    $uploadjadwal   = Excel::import(new UploadMtol, $mtol_file);
                    return  [
                        'status'    => '00',
                        'message'   => 'Mtol File Berhasil di Upload'
                    ];
                }
                else
                {
                    return [
                        'status'    => '01',
                        'message'   => 'Hanya upload file excel berekstensi .xls atau .xlsx'
                    ];
                }

            }
            else
            {
                return [
                    'status'    => '01',
                    'message'   => 'Harap pilih file mtol terlebih dahulu'
                ];
            }
            // dd($request->hasFile('mtol_file'));
        }
        else
        {
            return $accessCheck;
        }
    }

    public function manualAddModal(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $products           = $this->encryptId(Product::all());
            return view($this->route.'._form_add_manually',['products'=>$products]);
        }
        else
        {
            return $accessCheck;
        }
    }
    public function manualAdd(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $product_id                 = $this->decrypt($request->product_id);
            $wo_number                  = $request->wo_number;
            $production_plan_date       = $request->production_plan_date;
            $plan_batch_size            = $request->plan_batch_size;
            $plan_qty_box               = $request->plan_qty_box;

            $schedule_check             = WoNumber::where('wo_number',$wo_number)->first();
            if (is_null($schedule_check))
            {
                $new_schedule           = WoNumber::create([
                    'wo_number'             => $wo_number,
                    'product_id'            => $product_id,
                    'production_plan_date'  => $production_plan_date,
                    'plan_batch_size'       => $plan_batch_size,
                    'plan_qty_box'          => $plan_qty_box,
                    'upload_status'         => '0'
                ]);
                return  [
                    'status'    => '00',
                    'message'   => 'Nomor wo '.$new_schedule->wo_number.' berhasil ditambahkan ke jadwal'
                ];
            }
            else
            {
                return [
                    'status'    => '01',
                    'message'   => 'Nomor wo '.$wo_number.' telah terdaftar dengan produk '.$schedule_check->product->product_name.'. Harap ubah cek kembali nomor wo yang ingin anda daftarkan'
                ];
            }
        }
        else
        {
            return $accessCheck;
        }
    }

    public function removeDraftSchedule(Request $request)
    {
        $accessCheck    = $this->accessCheck('delete',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $wo_id = $this->decrypt($request->wo_id);
            $wo_number  = WoNumber::destroy($wo_id);
            return [
                'status' => '00',
                'message'   => 'Jadwal berhasil dihapus'
            ];
        }
        else
        {
            return $accessCheck;
        }
    }
    public function updateDraftScheduleModal(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $wo_id      = $this->decrypt($request->wo_id);
            $wo_number  = $this->encryptId(WoNumber::find($wo_id),'product_id');
            $products   = $this->encryptId(Product::all());
            return view($this->route.'._edit_draft_schedule',['products'=>$products, 'wo_number'=>$wo_number]);

        }
        else
        {
            return $accessCheck;
        }
    }
    public function updateDraftSchedule(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $id                         = $this->decrypt($request->encrypt_id);
            $product_id                 = $this->decrypt($request->product_id);
            $wo_number                  = $request->wo_number;
            $production_plan_date       = $request->production_plan_date;
            $plan_batch_size            = $request->plan_batch_size;
            $plan_qty_box               = $request->plan_qty_box;

            $wo_data                    = WoNumber::find($id);
            if ($wo_data->wo_number == $wo_number)
            {
                $wo_data->wo_number                 = $wo_number;
                $wo_data->production_plan_date      = $production_plan_date;
                $wo_data->plan_batch_size           = $plan_batch_size;
                $wo_data->plan_qty_box              = $plan_qty_box;
                $wo_data->product_id                = $product_id;
                $wo_data->save();
            }
            else
            {
                $schedule_check             = WoNumber::where('wo_number',$wo_number)->first();
                if (is_null($schedule_check))
                {
                    $wo_data->wo_number                 = $wo_number;
                    $wo_data->production_plan_date      = $production_plan_date;
                    $wo_data->plan_batch_size           = $plan_batch_size;
                    $wo_data->plan_qty_box              = $plan_qty_box;
                    $wo_data->product_id                = $product_id;
                    $wo_data->save();
                }
                else
                {
                    return [
                        'status'    => '01',
                        'message'   => 'Nomor wo '.$wo_number.' telah terdaftar dengan produk '.$schedule_check->product->product_name.'. Harap ubah cek kembali nomor wo yang ingin anda daftarkan'
                    ];
                }

            }
            return [
                'status'    => '00',
                'message'   => 'Data jadwal produksi berhasil diupdate'
            ];

        }
        else
        {
            return $accessCheck;
        }
    }

    public function finalizeDraftSchedule(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $updateData = WoNumber::where('upload_status', '0')
                            ->update(['upload_status' =>'1']);
            return  [
                'status'    => '00',
                'message'   => 'Jadwal berhasil di finalize, anda akan dialihkan menuju dashboard secara otomatis oleh sistem'
            ];

        }
        else
        {
            return $accessCheck;
        }
    }
    public function updateScheduleModal(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $wo_id      = $this->decrypt($request->wo_id);
            $wo_number  = WoNumber::find($wo_id);
            $wo_number->product     = $wo_number->product;
            $wo_number  = $this->encryptId($wo_number,'product_id');
            return view($this->route.'._edit_schedule',['wo_number'=>$wo_number]);
        }
        else
        {
            return $accessCheck;
        }
    }
    public function updateSchedule(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $id                                 = $this->decrypt($request->encrypt_id);
            $wo_number                          = $request->wo_number;
            $production_plan_date               = $request->production_plan_date;
            $production_realisation_date        = $request->production_realisation_date;
            $actual_batch_size                   = $request->actual_batch_size;
            $wo_data                            = WoNumber::find($id);
            if ($wo_data->production_realisation_date =='' || is_null($wo_data->production_realisation_date))
            {
                $wo_data->wo_number                     = $wo_number;
                $wo_data->production_plan_date          = $production_plan_date;
                $wo_data->production_realisation_date   = $production_realisation_date;
                $wo_data->actual_batch_size              = $actual_batch_size;
                $wo_data->wo_status                     = '2';
                $wo_data->save();

            }
            else
            {
                $wo_data->wo_number                 = $wo_number;
                $wo_data->actual_batch_size         = $actual_batch_size;
                $wo_data->save();
            }
            return [
                'status'    => '00',
                'message'   => 'Data Wo Berhasil di Update'
            ];
        }
        else
        {
            return $accessCheck;
        }
    }
    public function cancelProductionSchedule(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $wo_id                      = $this->decrypt($request->wo_id);
            $wo_number                  = WoNumber::find($wo_id);
            $wo_number->wo_status       = '6';
            $wo_number->explanation_1   = $request->alasan_pembatan;
            $wo_number->save();
            return [
                'status' => '00',
                'message'   => 'Jadwal '.$wo_number->product->product_name.' dengan nomor wo '.$wo_number->wo_number.' berhasil dibatalkan'
            ];
        }
        else
        {
            return $accessCheck;
        }
    }
}
