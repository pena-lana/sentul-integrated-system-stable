<?php

namespace App\Http\Controllers\Rollie;

use App\Models\Master\Product;
use App\Models\Master\ProductType;
use App\Models\Master\FillingMachine;
use App\Models\Master\FillingSampelCode;
use App\Models\Master\FillingMachineGroupHead;
use App\Models\Master\FillingMachineGroupDetail;
use App\Models\Master\Brand;
use App\Models\Master\Subbrand;

use App\Models\Transaction\Rollie\WoNumber;
use App\Models\Transaction\Rollie\RPDFillingHead;
use App\Models\Transaction\Rollie\RPDFillingDetailPi;
use App\Models\Transaction\Rollie\RPDFillingDetailPiAtEvent;

use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\Rollie\PPQController;
use Maatwebsite\Excel\Facades\Excel;

use Auth;
use DB;
use DataTables;
use Session;
use Hash;
use View;
class RPDFillingController extends ResourceController
{
    private $route                      = "rollie.rpd_filling";
    private $ppq_controller             = PPQController::class;
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
                            A.plan_batch_size, A.actual_batch_size, A.explanation_1, A.explanation_2, A.explanation_3, A.formula_revision, C.id as rpd_id, C.rpd_status
                            FROM ".$database_transaction.".wo_numbers A
                            INNER JOIN ".$database_master.".products B
                            ON B.id = A.product_id
                            LEFT JOIN ".$database_transaction.".rpd_filling_heads C
                            ON C.id = A.rpd_filling_head_id
                            WHERE A.wo_status = '2' OR A.wo_status ='3'";


                $rpd_fillings   = DB::select($sql);
                if (count($rpd_fillings) > 0)
                {
                    foreach ($rpd_fillings as $key => $rpd_filling)
                    {
                        switch ($rpd_filling->wo_status)
                        {
                            case '0':
                                $rpd_fillings[$key]->production_status      = "WIP Mixing";
                            break;
                            case '1':
                                $rpd_fillings[$key]->production_status      = "In Progress Mixing";
                            break;
                            case '2':
                                $rpd_fillings[$key]->production_status      = "WIP Fillpack";
                                $rpd_fillings[$key]->btn_extra              = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm '.Session::get('edit').'" onclick="proccessRPDFilling(this)" id="'.$rpd_filling->product_name.'_'.$rpd_filling->wo_number.'_'.$this->encrypt($rpd_filling->id).'"><i class="fab fa-wpforms"></i> Process RPD</a>&nbsp;';
                            break;
                            case '3':
                                $rpd_fillings[$key]->production_status     = "In Progress Fillpack";
                                if ($rpd_filling->rpd_status == '0')
                                {
                                    $rpd_fillings[$key]->btn_extra              = '<a href="javascript:void(0)" class="edit btn btn-outline-primary btn-sm '.Session::get('edit').'" onclick="document.location.href=\'/rollie/rpd-filling/form/'.$this->encrypt($rpd_filling->rpd_id).'\'" id="production_schedule_'.$this->encrypt($rpd_filling->rpd_id).'"><i class="fas fa-file-excel"></i> Form RPD</a>&nbsp;';
                                }
                                else
                                {
                                    $rpd_fillings[$key]->btn_extra              = '<a href="javascript:void(0)" class="edit btn btn-outline-primary btn-sm '.Session::get('edit').'" onclick="RPDFillingForm(this)" id="production_schedule_'.$this->encrypt($rpd_filling->rpd_id).'"><i class="fas fa-eye"></i>Lihat RPD</a>&nbsp;';
                                }
                            break;
                            case '4':
                                $rpd_fillings[$key]->production_status     = "Waiting For Close";
                            break;

                            case '5':
                                $rpd_fillings[$key]->production_status     = "Closed Wo";
                            break;

                            case '6':
                                $rpd_fillings[$key]->production_status     = "Canceled Schedule";
                                $rpd_fillings[$key]->btn_extra             = '';
                            break;

                        }
                    }
                    return Datatables::of($rpd_fillings)
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
                    return Datatables::of($rpd_fillings)
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

    public function processRPDFilling(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $wo_number                  = WoNumber::find($this->decrypt($request->wo_id));
            $rpd_filling_active         = RPDFillingHead::where('rpd_status','0')->get();
            $start_filling              = date('Y-m-d');
            if (count($rpd_filling_active) > 0)
            {

                /* $rpd_filling_active_for_product     = $rpd_filling_active->where('product_id',$wo_number->product_id);
                dd($rpd_filling_active_for_product); */

            }
            else
            {
                /* input data ke rpd filling table nya */
                $RPDFilling   = RPDFillingHead::create([
                    'product_id'                => $wo_number->product->id,
                    'start_filling_date'        => $start_filling,
                    'rpd_status'                => '0'
                ]);
                /*  update status wo nya jadi on progress fillpack dan tanggal fillingnya juga sesuai hari ini */
                $wo_number->wo_status                   = '3';
                $wo_number->rpd_filling_head_id         = $RPDFilling->id;
                $wo_number->fillpack_date               = $start_filling;
                $wo_number->save();
                $accessCheck['rpd_filling_head_id']     = $this->encrypt($RPDFilling->id);
                $accessCheck['message']                 = "RPD Filling Produk ".$wo_number->product->product_name." berhasil di proses, kamu akan dialihkan secara otomatis oleh sistem";
                return $accessCheck;
            }

        }
        else
        {
            return $accessCheck;
        }

    }

    public function showRPDFillingForm($rpd_filling_head_id)
    {
        $accessCheck    = $this->accessCheck('view',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $rpd_filling_head           = RPDFillingHead::find($this->decrypt($rpd_filling_head_id));
            $product                    = $this->encryptId($rpd_filling_head->product,'product_type_id');
            $wo_number                  = $rpd_filling_head->woNumbers;
            $rpd_filling_head           = $this->encryptId($rpd_filling_head,'product_id');
            $rpd_filling_active         = RPDFillingHead::where('rpd_status','0')->get();
            foreach ($rpd_filling_active as $list_rpd)
            {
                $product    = $list_rpd->product;
            }
            $rpd_filling_active         = $this->encryptId($rpd_filling_active,'product_id');
            return view($this->route.'.form',['rpd_filling_head' =>$rpd_filling_head,'rpd_filling_active'=>$rpd_filling_active]);
        }
        else
        {
            return redirect(route('rollie.rpd_filling'))->with('error',$accessCheck['message']);
        }

    }

    public function getDraftFillingSample(Request $request)
    {
        if ($request->ajax())
        {
            $database_master            = DB::connection('master_data')->getDatabaseName();
            $database_transaction       = DB::connection('transaction_data')->getDatabaseName();

            $accessCheck = $this->accessCheck('view',$this->route);
            if ($accessCheck['status'] !== '01' || $accessCheck['status'] == '02' )
            {
                $arr_data           = array();
                $sql                = " SELECT B.wo_number, C.filling_machine_code, CONCAT(A.filling_date,' ', A.filling_time) as filling_time, D.filling_sampel_code, D.filling_sampel_event, A.id AS rpd_filling_detail_id,
                                        A.airgap,A.ts_accurate_kanan,A.ts_accurate_kiri,A.ls_accurate,A.sa_accurate,A.surface_check,A.pinching,A.strip_folding,
                                        A.konduktivity_kanan,A.konduktivity_kiri,A.design_kanan,A.design_kiri,A.dye_test,A.residu_h2o2,A.prod_code_and_no_md,A.correction
                                        FROM ".$database_transaction.".rpd_filling_detail_pis A
                                        INNER JOIN ".$database_transaction.".wo_numbers B
                                        ON B.id = A.wo_number_id
                                        INNER JOIN ".$database_master.".filling_machines C
                                        ON C.id = A.filling_machine_id
                                        INNER JOIN ".$database_master.".filling_sampel_codes D
                                        ON D.id = A.filling_sampel_code_id
                                        WHERE
                                        A.airgap IS NULL AND
                                        A.ts_accurate_kanan IS NULL AND
                                        A.ts_accurate_kiri IS NULL AND
                                        A.ls_accurate IS NULL AND
                                        A.sa_accurate IS NULL AND
                                        A.surface_check IS NULL AND
                                        A.pinching IS NULL AND
                                        A.strip_folding IS NULL AND
                                        A.konduktivity_kanan IS NULL AND
                                        A.konduktivity_kiri IS NULL AND
                                        A.design_kanan IS NULL AND
                                        A.design_kiri IS NULL AND
                                        A.dye_test IS NULL AND
                                        A.residu_h2o2 IS NULL AND
                                        A.prod_code_and_no_md IS NULL AND
                                        A.correction IS NULL ORDER BY filling_time";
                $rpd_filling_pis                = DB::select($sql);
                if (count($rpd_filling_pis) > 0)
                {
                    $params_filling_machine_btn_analisa     = array();
                    foreach ($rpd_filling_pis as $key => $rpd_filling_pi)
                    {
                        $rpd_filling_pis[$key]->action  = "";
                        if (!in_array($rpd_filling_pi->filling_machine_code,$params_filling_machine_btn_analisa))
                        {
                            $rpd_filling_pis[$key]->action              = '<a href="javascript:void(0)" class="edit btn btn-outline-primary btn-sm'.Session::get('edit').'" onclick="analisa_sampel_pi(this)" id="'.$this->encrypt($rpd_filling_pi->rpd_filling_detail_id).'_nonevent"><i class="fas fa-edit"></i></a>&nbsp;';
                            array_push($params_filling_machine_btn_analisa,$rpd_filling_pi->filling_machine_code);
                        }
                        $rpd_filling_pis[$key]->action                  .= '<a href="javascript:void(0)" class="delete btn btn-outline-danger btn-sm'.Session::get('delete').'" onclick="delete_sampel_pi(this)" id="'.$this->encrypt($rpd_filling_pi->rpd_filling_detail_id).'_nonevent"><i class="fas fa-trash"></i></a>&nbsp;';
                        array_push($arr_data,$rpd_filling_pi);
                    }
                }
                $sql_2                          = " SELECT B.wo_number, C.filling_machine_code, CONCAT(A.filling_date,' ', A.filling_time) as filling_time, CONCAT(D.filling_sampel_code,' (Event)') as filling_sampel_code, D.filling_sampel_event, A.id AS rpd_filling_detail_id,
                                                    A.ls_sa_sealing_quality, A.ls_sa_proportion, A.status_akhir
                                                    FROM ".$database_transaction.".rpd_filling_detail_at_events A
                                                    INNER JOIN ".$database_transaction.".wo_numbers B
                                                    ON B.id = A.wo_number_id
                                                    INNER JOIN ".$database_master.".filling_machines C
                                                    ON C.id = A.filling_machine_id
                                                    INNER JOIN ".$database_master.".filling_sampel_codes D
                                                    ON D.id = A.filling_sampel_code_id
                                                    WHERE
                                                    A.ls_sa_sealing_quality IS NULL AND
                                                    A.ls_sa_proportion IS NULL AND
                                                    A.status_akhir IS NULL";
                $rpd_filling_pi_at_events       = DB::select($sql_2);
                if (count($rpd_filling_pi_at_events) > 0)
                {
                    foreach ($rpd_filling_pi_at_events as $key => $rpd_filling_pi)
                    {
                        $rpd_filling_pi_at_events[$key]->action              = '<a href="javascript:void(0)" class="edit btn btn-outline-primary btn-sm'.Session::get('edit').'" onclick="analisa_sampel_pi(this)" id="'.$this->encrypt($rpd_filling_pi->rpd_filling_detail_id).'_event"><i class="fas fa-edit"></i></a>&nbsp;';
                        $rpd_filling_pi_at_events[$key]->action              .= '<a href="javascript:void(0)" class="delete btn btn-outline-danger btn-sm'.Session::get('delete').'" onclick="delete_sampel_pi(this)" id="'.$this->encrypt($rpd_filling_pi->rpd_filling_detail_id).'_event"><i class="fas fa-trash"></i></a>&nbsp;';
                        array_push($arr_data,$rpd_filling_pi);
                    }
                }
                if (count($arr_data) > 0)
                {
                    return Datatables::of($arr_data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $actionBtn = $row->action;
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
                }
                else
                {
                    return Datatables::of($arr_data)
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
    public function getDoneFillingSample(Request $request)
    {

        if ($request->ajax())
        {
            $database_master            = DB::connection('master_data')->getDatabaseName();
            $database_transaction       = DB::connection('transaction_data')->getDatabaseName();
            $accessCheck = $this->accessCheck('view',$this->route);
            if ($accessCheck['status'] !== '01' || $accessCheck['status'] == '02' )
            {
                $arr_data           = array();
                $sql                = " SELECT B.wo_number, C.filling_machine_code, CONCAT(A.filling_date,' ', A.filling_time) as filling_time, D.filling_sampel_code, D.filling_sampel_event, A.id AS rpd_filling_detail_id,
                                        A.airgap,A.ts_accurate_kanan,A.ts_accurate_kiri,A.ls_accurate,A.sa_accurate,A.surface_check,A.pinching,A.strip_folding,
                                        A.konduktivity_kanan,A.konduktivity_kiri,A.design_kanan,A.design_kiri,A.dye_test,A.residu_h2o2,A.prod_code_and_no_md,A.correction,
                                        A.status_akhir
                                        FROM ".$database_transaction.".rpd_filling_detail_pis A
                                        INNER JOIN ".$database_transaction.".wo_numbers B
                                        ON B.id = A.wo_number_id
                                        INNER JOIN ".$database_master.".filling_machines C
                                        ON C.id = A.filling_machine_id
                                        INNER JOIN ".$database_master.".filling_sampel_codes D
                                        ON D.id = A.filling_sampel_code_id
                                        WHERE
                                        A.airgap IS NOT NULL AND
                                        A.ts_accurate_kanan IS NOT NULL AND
                                        A.ts_accurate_kiri IS NOT NULL AND
                                        A.ls_accurate IS NOT NULL AND
                                        A.sa_accurate IS NOT NULL AND
                                        A.surface_check IS NOT NULL AND
                                        A.pinching IS NOT NULL AND
                                        A.strip_folding IS NOT NULL AND
                                        A.konduktivity_kanan IS NOT NULL AND
                                        A.konduktivity_kiri IS NOT NULL AND
                                        A.design_kanan IS NOT NULL AND
                                        A.design_kiri IS NOT NULL AND
                                        A.dye_test IS NOT NULL AND
                                        A.residu_h2o2 IS NOT NULL AND
                                        A.prod_code_and_no_md IS NOT NULL AND
                                        A.correction IS NOT NULL";
                $rpd_filling_pis                = DB::select($sql);
                if (count($rpd_filling_pis) > 0)
                {
                    foreach ($rpd_filling_pis as $key => $rpd_filling_pi)
                    {
                        $rpd_filling_pis[$key]->action              = '<a href="javascript:void(0)" class="edit btn btn-outline-primary btn-sm'.Session::get('edit').'" onclick="analisa_sampel_pi(this)" id="'.$rpd_filling_pi->filling_sampel_code.'-'.$rpd_filling_pi->filling_sampel_event.'_'.$rpd_filling_pi->filling_machine_code.'_'.$this->encrypt($rpd_filling_pi->rpd_filling_detail_id).'_nonevent"><i class="fas fa-edit"></i></a>&nbsp;';
                        $rpd_filling_pis[$key]->action              .= '<a href="javascript:void(0)" class="delete btn btn-outline-danger btn-sm'.Session::get('delete').'" onclick="delete_sampel_pi(this)" id="'.$rpd_filling_pi->filling_sampel_code.'-'.$rpd_filling_pi->filling_sampel_event.'_'.$rpd_filling_pi->filling_machine_code.'_'.$this->encrypt($rpd_filling_pi->rpd_filling_detail_id).'_nonevent"><i class="fas fa-trash"></i></a>&nbsp;';
                        array_push($arr_data,$rpd_filling_pi);
                    }
                }
                $sql_2                          = " SELECT B.wo_number, C.filling_machine_code, CONCAT(A.filling_date,' ', A.filling_time) as filling_time, CONCAT(D.filling_sampel_code,' (Event)') as filling_sampel_code, D.filling_sampel_event, A.id AS rpd_filling_detail_id,
                                                    A.ls_sa_sealing_quality, A.ls_sa_proportion, A.status_akhir
                                                    FROM ".$database_transaction.".rpd_filling_detail_at_events A
                                                    INNER JOIN ".$database_transaction.".wo_numbers B
                                                    ON B.id = A.wo_number_id
                                                    INNER JOIN ".$database_master.".filling_machines C
                                                    ON C.id = A.filling_machine_id
                                                    INNER JOIN ".$database_master.".filling_sampel_codes D
                                                    ON D.id = A.filling_sampel_code_id
                                                    WHERE
                                                    A.ls_sa_sealing_quality IS NOT NULL AND
                                                    A.ls_sa_proportion IS NOT NULL AND
                                                    A.status_akhir IS NOT NULL";
                $rpd_filling_pi_at_events       = DB::select($sql_2);
                if (count($rpd_filling_pi_at_events) > 0)
                {
                    foreach ($rpd_filling_pi_at_events as $key => $rpd_filling_pi)
                    {
                        $rpd_filling_pi_at_events[$key]->action              = '<a href="javascript:void(0)" class="edit btn btn-outline-primary btn-sm'.Session::get('edit').'" onclick="analisa_sampel_pi(this)" id="'.$rpd_filling_pi->filling_sampel_code.'-'.$rpd_filling_pi->filling_sampel_event.'_'.$rpd_filling_pi->filling_machine_code.'_'.$this->encrypt($rpd_filling_pi->rpd_filling_detail_id).'_nonevent"><i class="fas fa-edit"></i></a>&nbsp;';
                        $rpd_filling_pi_at_events[$key]->action              .= '<a href="javascript:void(0)" class="delete btn btn-outline-danger btn-sm'.Session::get('delete').'" onclick="delete_sampel_pi(this)" id="'.$rpd_filling_pi->filling_sampel_code.'-'.$rpd_filling_pi->filling_sampel_event.'_'.$rpd_filling_pi->filling_machine_code.'_'.$this->encrypt($rpd_filling_pi->rpd_filling_detail_id).'_nonevent"><i class="fas fa-trash"></i></a>&nbsp;';
                        array_push($arr_data,$rpd_filling_pi);
                    }
                }
                if (count($arr_data) > 0)
                {

                    return Datatables::of($arr_data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $actionBtn = $row->action;
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
                }
                else
                {
                    return Datatables::of($arr_data)
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

    public function addFillingSampelModal(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $rpd_filling_head_id                        = $this->decrypt($request->rpd_filling_head_id);
            $rpd_filling_head                           = RPDFillingHead::find($rpd_filling_head_id);
            $rpd_filling_head->woNumbers                = $this->encryptId($rpd_filling_head->woNumbers);
            $rpd_filling_head                           = $this->encryptId($rpd_filling_head);
            $last_wo_number                             = $rpd_filling_head->woNumbers[count($rpd_filling_head->woNumbers)-1];
            $last_wo_number->filling_machine_group      = $last_wo_number->product->fillingMachineGroupHead->fillingMachineGroupDetails ;
            foreach ($last_wo_number->filling_machine_group as $key => $filling_machine_group)
            {
                $filling_machine    = $this->encryptId($filling_machine_group->fillingMachine);
                $last_wo_number->filling_machine_group[$key]->filling_machine   = $filling_machine;
            }
            return view($this->route.'._form-add-sampel',['last_wo_number'=>$last_wo_number,'rpd_filling_head'=>$rpd_filling_head]);

        }
        else
        {
            return $accessCheck;
        }
    }
    public function getFillingSampelCode(Request $request)
    {
        $accessCheck        = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $filling_machine_id     = $this->decrypt($request->filling_machine_id);
            $product_type_id        = $this->decrypt($request->product_type_id);
            $filling_sampel_codes   = FillingSampelCode::where('product_type_id',$product_type_id)->where('filling_machine_id',$filling_machine_id)->get();
            $option                 = '';
            foreach ($filling_sampel_codes as $filling_sampel_code)
            {
                $option     .= "<option value='".$this->encrypt($filling_sampel_code->id)."'>".$filling_sampel_code->filling_sampel_code." - ".$filling_sampel_code->filling_sampel_event."<option>";
            }

            return [
                'status'    => '00',
                'message'   => 'Behasil',
                'option_filling_sampel_code'    => $option
            ];
        }
        else
        {
            return $accessCheck;
        }

    }
    public function checkFillingSampelModal(Request $request)
    {
        $accessCheck        = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $filling_sampel_code_id     = $this->decrypt($request->filling_sampel_code_id);
            $filling_sampel_code_data   = FillingSampelCode::find($filling_sampel_code_id);
            $filling_sampel_code        = $filling_sampel_code_data->filling_sampel_code;
            $filling_sampel_code        = explode('(',$filling_sampel_code);
            $array_at_event_sampel      = array('B','C','D','E','F','G');
            $option                     = "";
            if (in_array($filling_sampel_code[0],$array_at_event_sampel))
            {
                $option     .= "<option value='0'> # Event </option>";
                $option     .= "<option value='1'> Event </option>";
            }
            else
            {
                $option     .= "<option value='0' selected> # Event </option>";
            }
            $pi         = $filling_sampel_code_data->pi;
            return [
                'status'    => '00',
                'message'   => 'Behasil',
                'option'    => $option,
                'pi'        => $pi
            ];
        }
        else
        {
            return $accessCheck;
        }
    }
    public function addFillingSampelCode(Request $request)
    {
        $accessCheck        = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $wo_number_id               = $this->decrypt($request->wo_number_id);
            $rpd_filling_head_id        = $this->decrypt($request->rpd_filling_head_id);
            $filling_machine_id         = $this->decrypt($request->filling_machine_id);
            $filling_sampel_code        = $this->decrypt($request->filling_sampel_code);
            $filling_event              = $request->filling_event_note;
            $filling_date               = $request->filling_date;
            $filling_time               = $request->filling_time;
            $berat_kanan                = $request->berat_kanan;
            $berat_kiri                 = $request->berat_kiri;
            $filling_sampel_code_data   = FillingSampelCode::find($filling_sampel_code);
            if ($filling_sampel_code_data->pi > 0)
            {
                $rpd_filling_pi                 = RPDFillingDetailPi::create([
                    'wo_number_id'              => $wo_number_id,
                    'rpd_filling_head_id'       => $rpd_filling_head_id,
                    'filling_machine_id'        => $filling_machine_id,
                    'filling_sampel_code_id'       => $filling_sampel_code,
                    'filling_date'              => $filling_date,
                    'filling_time'              => $filling_time,
                    'berat_kanan'               => $berat_kanan,
                    'berat_kiri'                => $berat_kiri
                ]);
            }
            else
            {
                $rpd_filling_pi                 = RpdFillingDetailPi::create([
                    'wo_number_id'              => $wo_number_id,
                    'rpd_filling_head_id'       => $rpd_filling_head_id,
                    'filling_machine_id'        => $filling_machine_id,
                    'filling_sampel_code_id'       => $filling_sampel_code,
                    'filling_date'              => $filling_date,
                    'filling_time'              => $filling_time,
                    'berat_kanan'               => $berat_kanan,
                    'berat_kiri'                => $berat_kiri,
                    'airgap'                    => '-',
                    'ts_accurate_kanan'         => '-',
                    'ts_accurate_kiri'          => '-',
                    'ls_accurate'               => '-',
                    'sa_accurate'               => '-',
                    'surface_check'             => '-',
                    'pinching'                  => '-',
                    'strip_folding'             => '-',
                    'konduktivity_kanan'        => '-',
                    'konduktivity_kiri'         => '-',
                    'design_kanan'              => '-',
                    'design_kiri'               => '-',
                    'dye_test'                  => '-',
                    'residu_h2o2'               => '-',
                    'prod_code_and_no_md'       => '-',
                    'correction'                => '-',
                    'overlap'                   => '00.00',
                    'ls_sa_proportion'          => '-',
                    'volume_kanan'              => '0',
                    'volume_kiri'               => '0',
                    'status_akhir'              => 'OK'
                ]);
            }

            if ($filling_event == '1')
            {
                $insertEvent                    = RPDFillingDetailPiAtEvent::create([
                    'wo_number_id'              => $wo_number_id,
                    'rpd_filling_head_id'       => $rpd_filling_head_id,
                    'filling_machine_id'        => $filling_machine_id,
                    'filling_sampel_code_id'       => $filling_sampel_code,
                    'filling_date'              => $filling_date,
                    'filling_time'              => $filling_time,
                ]);
            }
            return [
                'status'    => '00',
                'message'   => 'Sampel berhasil ditambahkan ke draft analisa.'
            ];

        }
        else
        {
            return $accessCheck;
        }
    }

    public function addBatchModal(Request $request)
    {
        $accessCheck        = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            return view($this->route.'._form-add-batch',['rpd_filling_head_id'=>$request->rpd_filling_head_id]);
        }
        else
        {
            return $accessCheck;
        }
    }
    public function getWoNumberBatch(Request $request)
    {
        $accessCheck        = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $rpd_filling_head_id    = $this->decrypt($request->rpd_filling_head_id);
            $add_type               = $request->add_type;
            $rpd_filling_head       = RPDFillingHead::find($rpd_filling_head_id);
            $option                 = "<option value='none' selected disabled>Pilih Nomor Wo</option>";
            if ($add_type == '0')
            {
                $product_id         = $rpd_filling_head->woNumbers[0]->product->id;
                $rangesebelum       = date('Y-m-d', strtotime($rpd_filling_head->woNumbers[0]->production_realisation_date. '-2 days'));
                $rangesesudah       = date('Y-m-d', strtotime($rpd_filling_head->woNumbers[0]->production_realisation_date. '+2 days'));
                $wo_numbers         = WoNumber::whereBetween('production_realisation_date',[$rangesebelum,$rangesesudah])->where('wo_status','2')->where('product_id',$product_id)->get();
                if (count($wo_numbers) > 0)
                {
                    foreach ($wo_numbers as $wo_number)
                    {
                        $option     .= "<option value='".$this->encrypt($wo_number->id)."'>".$wo_number->wo_number." - ".$wo_number->product->product_name."</option>";
                    }
                    return [
                        'status'    => '00',
                        'message'   => 'batch tersedia',
                        'option'    => $option
                    ];
                }
                else
                {
                    return [
                        'status'    => '01',
                        'message'   => 'Tidak ada batch lain yang siap filling, Harap hubungi penyelia apabila seharusnya ada batch lain yang siap filling'
                    ];
                }
            }
            else
            {
                $active_rpd     = RPDFillingHead::where('rpd_status','0')->get();
                if (count($active_rpd) > 1)
                {
                    return [
                        'status'    => '01',
                        'message'   => 'Sudah terdapat RPD Filling Aktif dengan Mesin Filling Berbeda. Harap selesaikan RPD Terlebih dahulu atau tambahkan proses dengan tambah batch jika produk terdapat pada RPD Aktif'
                    ];
                }
                else
                {
                    $database_master            = DB::connection('master_data')->getDatabaseName();
                    $database_transaction       = DB::connection('transaction_data')->getDatabaseName();
                    $filling_machine_group_head_active  = $rpd_filling_head->woNumbers[0]->product->fillingMachineGroupHead->id;
                    $sql            = "SELECT A.id, A.wo_number, B.product_name FROM ".$database_transaction.".wo_numbers A
                                        INNER JOIN ".$database_master.".products B
                                        ON B.id = A.product_id
                                        INNER JOIN ".$database_master.".filling_machine_group_heads C
                                        ON C.id = B.filling_machine_group_head_id
                                        WHERE C.id != '".$filling_machine_group_head_active."' AND A.wo_status='2' AND A.id NOT IN('30','31','32')";
                    $wo_numbers     = DB::select($sql);
                    if (count($wo_numbers) > 0)
                    {
                        foreach ($wo_numbers as $wo_number)
                        {
                            $option     .= "<option value='".$this->encrypt($wo_number->id)."'>".$wo_number->wo_number." - ".$wo_number->product_name."</option>";
                        }
                        return [
                            'status'    => '00',
                            'message'   => 'batch tersedia',
                            'option'    => $option
                        ];
                    }
                    else
                    {
                        return [
                            'status'    => '01',
                            'message'   => 'Tidak ada produk dengan kelompok mesin filling lain yang siap filling'
                        ];
                    }

                }
            }
        }
        else
        {
            return $accessCheck;
        }
    }
    public function addBatch(Request $request)
    {
        $accessCheck        = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            if (isset($request->add_type) && isset($request->wo_number_id))
            {
                $add_type               = $request->add_type;
                $rpd_filling_head_id    = $this->decrypt($request->rpd_filling_head_id);
                $wo_number_id           = $this->decrypt($request->wo_number_id);
                $wo_number              = WoNumber::find($wo_number_id);
                $start_filling          = date('Y-m-d');
                $message    = "Penambahan batch proses produk RPD Filling berhasil dilakukan";
                if ($add_type == '1')
                {
                    $rpd_filling_head   = RPDFillingHead::create([
                        'product_id'                => $wo_number->product->id,
                        'start_filling_date'        => $start_filling,
                        'rpd_status'                =>'0'
                    ]);
                    $rpd_filling_head_id    = $rpd_filling_head->id;
                    $message    = "Penambahan proses RPD Filling Berbeda Mesin berhasil dilakukan";
                }
                $wo_number->rpd_filling_head_id     = $rpd_filling_head_id;
                $wo_number->fillpack_date           = $start_filling;
                $wo_number->wo_status               = '3';
                $wo_number->save();
                return
                [
                    'status'                => '00',
                    'message'               => $message,
                    'rpd_filling_head_id'   => $this->encrypt($rpd_filling_head_id)
                ];
            }
            else
            {
                return
                [
                    'status'                => '01',
                    'message'               => "Harap pilih jenis penambahan dan wo number terlebih dahulu"
                ];
            }
        }
        else
        {
            return $accessCheck;
        }
    }
    public function analisaFillingSampelModal(Request $request)
    {
        $accessCheck        = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $rpd_filling_detail_id  = $this->decrypt($request->rpd_filling_detail_id);
            $event_sampel           = $request->event_sampel;
            if ($event_sampel == 'nonevent')
            {
                $rpd_filling_detail                     = RPDFillingDetailPi::find($rpd_filling_detail_id);
                $view                                   = $this->route.'._form_analisa_sampel_pi';
            }
            else
            {
                $rpd_filling_detail                     = RPDFillingDetailPiAtEvent::find($rpd_filling_detail_id);
                $filling_sampel_code                    = explode('(',$rpd_filling_detail->fillingSampelCode->filling_sampel_code);
                switch ($filling_sampel_code[0])
                {
                    case 'B':
                        $rpd_filling_detail->params = 'paper-splicing';
                    break;
                    case 'C':
                        $rpd_filling_detail->params = 'paper-splicing';
                    break;
                    case 'D':
                        $rpd_filling_detail->params = 'strip-splicing';
                    break;
                    case 'E':
                        $rpd_filling_detail->params = 'strip-splicing';
                    break;
                    case 'F':
                        $rpd_filling_detail->params = 'short-stop';
                    break;
                    case 'G':
                        $rpd_filling_detail->params = 'short-stop';
                    break;
                }
                $view                                   = $this->route.'._form_analisa_sampel_pi_at_event';
            }
            $rpd_filling_detail->product                = $this->encryptId($rpd_filling_detail->woNumber->product);
            $rpd_filling_detail->wo_number              = $this->encryptId($rpd_filling_detail->woNumber,'product_id');
            $rpd_filling_detail->filling_machine        = $this->encryptId($rpd_filling_detail->fillingMachine);
            $rpd_filling_detail->filling_sampel_code    = $this->encryptId($rpd_filling_detail->fillingSampelCode);
            $rpd_filling_detail                         = $this->encryptId($rpd_filling_detail,'wo_number_id','filling_machine_id','filling_sampel_code_id');
            return view($view,['rpd_filling_detail'=>$rpd_filling_detail]);
        }
        else
        {
            return $accessCheck;
        }
    }
    public function analisaFillingSampel(Request $request)
    {
        $accessCheck        = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $rpd_filling_detail_id  = $request->rpd_filling_detail_id;
            $filling_machine_id     = $request->filling_machine_id;
            $filling_sampel_code    = $request->filling_sampel_code;
            $product_name           = $request->product_name;
            $filling_machine        = $request->filling_machine;
            $filling_date           = $request->filling_date;
            $filling_time           = $request->filling_time;
            $overlap                = $request->overlap;
            $ls_sa_proportion       = $request->ls_sa_proportion;
            $volume_kanan           = $request->volume_kanan;
            $volume_kiri            = $request->volume_kiri;
            $airgap                 = $request->airgap;
            $ts_accurate_kiri       = $request->ts_accurate_kiri;
            $ts_accurate_kanan      = $request->ts_accurate_kanan;
            $ls_accurate            = $request->ls_accurate;
            $sa_accurate            = $request->sa_accurate;
            $surface_check          = $request->surface_check;
            $pinching               = $request->pinching;
            $strip_folding          = $request->strip_folding;
            $konduktivity_kiri      = $request->konduktivity_kiri;
            $konduktivity_kanan     = $request->konduktivity_kanan;
            $design_kiri            = $request->design_kiri;
            $design_kanan           = $request->design_kanan;
            $dye_test               = $request->dye_test;
            $residu_h2o2            = $request->residu_h2o2;
            $prod_code_no_md        = $request->prod_code_no_md;
            $status_akhir           = $request->status_akhir;
            $correction             = $request->correction;
            $filling_date_old       = $request->filling_date_old;
            $filling_time_old       = $request->filling_time_old;

            if ($ts_accurate_kiri == '#OK')
            {
                $ts_accurate_kiri   = $request->ts_accurate_kiri_not_ok;
            }
            if ($ts_accurate_kanan == '#OK')
            {
                $ts_accurate_kanan   = $request->ts_accurate_kanan_not_ok;
            }
            if ($ls_accurate == '#OK')
            {
                $ls_accurate   = $request->ls_accurate_not_ok;
            }
            if ($sa_accurate == '#OK')
            {
                $sa_accurate   = $request->sa_accurate_not_ok;
            }
            if ($surface_check == '#OK')
            {
                $surface_check   = $request->surface_check_not_ok;
            }
            $rpd_filling_detail_pi                         = RPDFillingDetailPi::find($this->decrypt($rpd_filling_detail_id));
            /* $rpd_filling_detail_pi->filling_date           = $filling_date;
            $rpd_filling_detail_pi->filling_time           = $filling_time;
            $rpd_filling_detail_pi->overlap                = $overlap;
            $rpd_filling_detail_pi->ls_sa_proportion       = $ls_sa_proportion;
            $rpd_filling_detail_pi->volume_kanan           = $volume_kanan;
            $rpd_filling_detail_pi->volume_kiri            = $volume_kiri;
            $rpd_filling_detail_pi->airgap                 = $airgap;
            $rpd_filling_detail_pi->ts_accurate_kiri       = $ts_accurate_kiri;
            $rpd_filling_detail_pi->ts_accurate_kanan      = $ts_accurate_kanan;
            $rpd_filling_detail_pi->ls_accurate            = $ls_accurate;
            $rpd_filling_detail_pi->sa_accurate            = $sa_accurate;
            $rpd_filling_detail_pi->surface_check          = $surface_check;
            $rpd_filling_detail_pi->pinching               = $pinching;
            $rpd_filling_detail_pi->strip_folding          = $strip_folding;
            $rpd_filling_detail_pi->konduktivity_kiri      = $konduktivity_kiri;
            $rpd_filling_detail_pi->konduktivity_kanan     = $konduktivity_kanan;
            $rpd_filling_detail_pi->design_kiri            = $design_kiri;
            $rpd_filling_detail_pi->design_kanan           = $design_kanan;
            $rpd_filling_detail_pi->dye_test               = $dye_test;
            $rpd_filling_detail_pi->residu_h2o2            = $residu_h2o2;
            $rpd_filling_detail_pi->prod_code_and_no_md    = $prod_code_no_md;
            $rpd_filling_detail_pi->correction             = $correction;
            $rpd_filling_detail_pi->status_akhir           = $status_akhir;
            $rpd_filling_detail_pi->save();

            $rpd_filling_detail_pi_at_event                =    RPDFillingDetailPiAtEvent::where('wo_number_id',$rpd_filling_detail_pi->wo_number_id)
                                                                ->where('filling_date',$filling_date_old)
                                                                ->where('filling_time',$filling_time_old)
                                                                ->where('filling_sampel_code_id',$rpd_filling_detail_pi->filling_sampel_code_id)
                                                                ->where('filling_machine_id',$rpd_filling_detail_pi->filling_machine_id)
                                                                ->first();
            if (!is_null($rpd_filling_detail_pi_at_event))
            {
                $rpd_filling_detail_pi_at_event->filling_time    = $filling_time;
                $rpd_filling_detail_pi_at_event->filling_date    = $filling_date;
                $rpd_filling_detail_pi_at_event->save();
            } */

            if ($status_akhir == 'OK')
            {
                $database_transaction       = DB::connection('transaction_data')->getDatabaseName();
                $sql                        = " SELECT id, wo_number_id, filling_machine_id, filling_sampel_code_id, rpd_filling_head_id,
                                                CONCAT(filling_date,' ',filling_time) as filling_time, status_akhir FROM ".$database_transaction.".rpd_filling_detail_pis
                                                WHERE rpd_filling_head_id='".$rpd_filling_detail_pi->rpd_filling_head_id."' AND wo_number_id='".$rpd_filling_detail_pi->wo_number_id."' AND filling_machine_id = '".$rpd_filling_detail_pi->filling_machine_id."' ORDER BY filling_time";
                $rpd_filling_details        = DB::select($sql);
                if ($rpd_filling_details[0]->id !==  $rpd_filling_detail_pi->id)
                {
                    $key_sampel_active    = array_search($rpd_filling_detail_pi->id,array_column($rpd_filling_details,'id'));
                    if ($rpd_filling_details[$key_sampel_active-1]->status_akhir == '#OK')
                    {
                        $make_ppq  = PPQController::createDraftPPQ("Package Integrity",$rpd_filling_detail_pi);
                        dd($make_ppq);
                        $view   =  (string)View::make($this->route.'._form_ppq_pi');
                        return [
                            'status'    => '00',
                            'ppq'       => true,
                            'message'   => "Terdapat hasil analisa #OK, mohon mengisi form PPQ Produk Terlebih Dahulu",
                            'ppq_view'  => $view
                        ];
                    }
                }
                return [
                    'status'    => '00',
                    'ppq'       => false,
                    'message'   => "Analisa PI Berhasil dilakukan"
                ];
            }
            else
            {
                if ($rpd_filling_detail_pi->fillingSampelCode->filling_sampel_code == 'H')
                {
                    dd(' ini akhir filling');
                }
                return [
                    'status'    => '00',
                    'ppq'       => false,
                    'message'   => "Analisa PI Berhasil dilakukan"
                ];
            }
        }
        else
        {
            return $accessCheck;
        }
    }
    public function analisaFillingSampelEvent(Request $request)
    {
        $accessCheck        = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $rpd_filling_detail_id          = $request->rpd_filling_detail_id;
            $params                         = $request->params;

            $filling_date                   = $request->filling_date;
            $filling_time                   = $request->filling_time;

            $filling_date_old               = $request->filling_date_old;
            $filling_time_old               = $request->filling_time_old;


            $ls_sa_sealing_quality          = $request->ls_sa_sealing_quality;
            $ls_sa_proportion               = $request->ls_sa_proportion;

            $status_akhir                   = $request->status_akhir;
            $keterangan                     = $request->keterangan;

            $rpd_filling_detail_pi_at_event = RPDFillingDetailPiAtEvent::find($this->decrypt($rpd_filling_detail_id));
            $rpd_filling_detail_pi          = RPDFillingDetailPi::where('wo_number_id',$rpd_filling_detail_pi_at_event->wo_number_id)
                                              ->where('filling_date',$filling_date_old)
                                              ->where('filling_time',$filling_time_old)
                                              ->where('filling_sampel_code_id',$rpd_filling_detail_pi_at_event->filling_sampel_code_id)
                                              ->where('filling_machine_id',$rpd_filling_detail_pi_at_event->filling_machine_id)
                                              ->first();
            if (!is_null($rpd_filling_detail_pi))
            {
                $rpd_filling_detail_pi->filling_time    = $filling_time;
                $rpd_filling_detail_pi->filling_date    = $filling_date;
                $rpd_filling_detail_pi->save();
            }
            $rpd_filling_detail_pi_at_event->filling_time           = $filling_time;
            $rpd_filling_detail_pi_at_event->filling_date           = $filling_date;
            $rpd_filling_detail_pi_at_event->ls_sa_sealing_quality  = $ls_sa_sealing_quality;
            $rpd_filling_detail_pi_at_event->ls_sa_proportion       = $ls_sa_proportion;
            $rpd_filling_detail_pi_at_event->status_akhir           = $status_akhir;
            $rpd_filling_detail_pi_at_event->keterangan             = $keterangan;
            switch ($params)
            {
                case 'paper-splicing':
                    $sideway_sealing_alignment      = $request->sideway_sealing_alignment;
                    $overlap                        = $request->overlap;
                    $package_length                 = $request->package_length;
                    $paper_splice_sealing_quality    = $request->paper_splice_sealing_quality;
                    $no_kk                          = $request->no_kk;
                    $no_md                          = $request->no_md;
                    $rpd_filling_detail_pi_at_event->sideway_sealing_alignment      = $sideway_sealing_alignment;
                    $rpd_filling_detail_pi_at_event->overlap                        = $overlap;
                    $rpd_filling_detail_pi_at_event->package_length                 = $package_length;
                    $rpd_filling_detail_pi_at_event->paper_splice_sealing_quality   = $paper_splice_sealing_quality;
                    $rpd_filling_detail_pi_at_event->no_kk                          = $no_kk;
                    $rpd_filling_detail_pi_at_event->no_md                          = $no_md;

                break;
                case 'strip-splicing':
                    $ls_sa_sealing_quality_strip                                    = $request->ls_sa_sealing_quality_strip;
                    $rpd_filling_detail_pi_at_event->ls_sa_sealing_quality_strip    = $ls_sa_sealing_quality_strip;
                break;
                case 'short-stop':
                    $ls_sealing_quality_short_stop  = $request->ls_sealing_quality_short_stop;
                    $sa_sealing_quality_short_stop  = $request->sa_sealing_quality_short_stop;
                    $rpd_filling_detail_pi_at_event->ls_short_stop_quality      = $ls_sealing_quality_short_stop;
                    $rpd_filling_detail_pi_at_event->sa_short_stop_quality      = $sa_sealing_quality_short_stop;
                break;
            }
            $rpd_filling_detail_pi_at_event->save();
            return [
                'status'    => '00',
                'message'   => 'Analisa Sampel PI At Event Berhasil Dilakukan'
            ];
        }
        else
        {
            return $accessCheck;
        }
    }
}
