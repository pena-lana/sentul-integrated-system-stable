<?php

namespace App\Http\Controllers\Masterapp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\FillingMachine;
use App\Models\Master\FillingMachineGroupHead;
use App\Models\Master\FillingMachineGroupDetail;

use App\Http\Controllers\ResourceController;
use Auth;
use DB;
use DataTables;
use Session;
use Hash;
class FillingMachineGroupController extends ResourceController
{
    private $route = 'master_app.manage_filling_machine_group';

    public function index(Request $request)
    {
        return view($this->route.".index");
    }
    public function getData(Request $request)
    {
        if ($request->ajax())
        {
            $accessCheck = $this->accessCheck('view',$this->route);
            if ($accessCheck['status'] !== '01' || $accessCheck['status'] == '02' )
            {
                $sql                                = "SELECT A.filling_machine_group_name, A.id as filling_machine_group_id, A.is_active FROM filling_machine_group_heads A ";
                $filling_machine_group_head         = DB::select($sql);
                foreach ($filling_machine_group_head as $key => $filling_machine)
                {
                    $btn_extra       = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm '.Session::get('edit').'" onclick="editFillingMachineGroupHead(this)" id="edit_filling_machine_'.$this->encrypt($filling_machine->filling_machine_group_id).'"><i class="fas fa-edit"></i></a>&nbsp;';
                    $btn_extra      .= '<a href="javascript:void(0)" class="create btn btn-primary btn-sm '.Session::get('create').'" onclick="createFillingMachineGroupHead(this)" id="create_filling_machine_'.$this->encrypt($filling_machine->filling_machine_group_id).'"><i class="fas fa-plus"></i></a>&nbsp;';
                    $btn_extra      .= '<a class="btn btn-outline-primary details-control" href="javascript:void(0);" ><i class="fas fa-eye"></i>&nbsp; Filling Machine Group Detail</a>';
                    if ($filling_machine->is_active == '1')
                    {
                        $status     = '<div class="custom-control custom-switch">';
                        $status     .= '<input type="checkbox" class="custom-control-input" id="status_filling_machine_'.$this->encrypt($filling_machine->filling_machine_group_id).'" name="status" onchange="changeStatusFillingMachineGroupHead(this)" checked>';
                        $status     .= '<label class="custom-control-label" for="status_filling_machine_'.$this->encrypt($filling_machine->filling_machine_group_id).'">Active</label>';
                        $status     .= '</div>';
                    }
                    else
                    {
                        $status     = '<div class="custom-control custom-switch">';
                        $status     .= '<input type="checkbox" class="custom-control-input" id="status_filling_machine_'.$this->encrypt($filling_machine->filling_machine_group_id).'" name="status" onchange="changeStatusFillingMachineGroupHead(this)">';
                        $status     .= '<label class="custom-control-label" for="status_filling_machine_'.$this->encrypt($filling_machine->filling_machine_group_id).'">Inactive</label>';
                        $status     .= '</div>';
                    }
                    $filling_machine->encrypt_id                    = $this->encrypt($filling_machine->filling_machine_group_id);
                    $filling_machine_group_head[$key]->status       = $status;
                    $filling_machine_group_head[$key]->btn_extra    = $btn_extra;
                }

                return Datatables::of($filling_machine_group_head)
                    ->addIndexColumn()
                    ->addColumn('status', function($row){
                        $statusBtn = $row->status;
                        return $statusBtn;
                    })->addColumn('action', function($row){
                        $actionBtn = $row->btn_extra;
                        return $actionBtn;
                    })
                    ->rawColumns(['action','status'])
                    ->make(true);
            }
            else
            {
                return $accessCheck;
            }
        }
    }
    public function getDataDetail(Request $request)
    {
        if ($request->ajax())
        {
            $accessCheck = $this->accessCheck('view',$this->route);
            if ($accessCheck['status'] !== '01' || $accessCheck['status'] == '02' )
            {
                $sql                                = "SELECT B.filling_machine_name, B.filling_machine_code, A.is_active, A.id as filling_machine_group_detail_id
                                                        FROM filling_machine_group_details A
                                                        INNER JOIN filling_machines B
                                                        ON A.filling_machine_id = B.id
                                                        WHERE A.filling_machine_group_head_id='".$this->decrypt($request->filling_machine_group_head_id)."'";
                $filling_machine_group_details      = DB::select($sql);
                if (count($filling_machine_group_details) > 0)
                {
                    foreach ($filling_machine_group_details as $key => $filling_machine)
                    {
                        if ($filling_machine->is_active == '1')
                        {
                            $status     = '<div class="custom-control custom-switch">';
                            $status     .= '<input type="checkbox" class="custom-control-input" id="statusdetail_filling_machine_'.$this->encrypt($filling_machine->filling_machine_group_detail_id).'_'.$request->filling_machine_group_head_id.'" name="status" onchange="changeStatusFillingMachineGroupDetail(this)" checked>';
                            $status     .= '<label class="custom-control-label" for="statusdetail_filling_machine_'.$this->encrypt($filling_machine->filling_machine_group_detail_id).'_'.$request->filling_machine_group_head_id.'">Allow</label>';
                            $status     .= '</div>';
                        }
                        else
                        {
                            $status     = '<div class="custom-control custom-switch">';
                            $status     .= '<input type="checkbox" class="custom-control-input" id="statusdetail_filling_machine_'.$this->encrypt($filling_machine->filling_machine_group_detail_id).'_'.$request->filling_machine_group_head_id.'" name="status" onchange="changeStatusFillingMachineGroupDetail(this)">';
                            $status     .= '<label class="custom-control-label" for="statusdetail_filling_machine_'.$this->encrypt($filling_machine->filling_machine_group_detail_id).'_'.$request->filling_machine_group_head_id.'">Denied</label>';
                            $status     .= '</div>';
                        }
                        $filling_machine->encrypt_id                    = $this->encrypt($filling_machine->filling_machine_group_detail_id);
                        $filling_machine_group_details[$key]->status       = $status;
                    }
                    return Datatables::of($filling_machine_group_details)
                    ->addIndexColumn()
                    ->addColumn('status', function($row){
                        $statusBtn = $row->status;
                        return $statusBtn;
                    })
                    ->rawColumns(['status'])
                    ->make(true);
                }
                else
                {
                    return Datatables::of($filling_machine_group_details)
                    ->addIndexColumn()
                    ->addColumn('status')
                    ->rawColumns(['status'])
                    ->make(true);
                }
            }
            else
            {
                return $accessCheck;
            }
        }
    }
    public function changeFillingMachineStatus(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $filling_machine_group_head                = FillingMachineGroupHead::find($this->decrypt($request->filling_machine_group_head_id));
            $filling_machine_group_head->is_active     = $request->status_filling_machine_group_head;
            $filling_machine_group_head->save();
            return ['status' => '00','message'=>'Data berhasil diubah'];
        }
        else
        {
            return $accessCheck;
        }
    }
    public function editFillingMachineGroupHead(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $filling_machine    = FillingMachineGroupHead::find($this->decrypt($request->filling_machine_group_head_id));
            $filling_machine    = $this->encryptId($filling_machine);
            return view($this->route.'._edit_filling_machine_group_head',['filling_machine_group_head'=>$filling_machine]);
        }
        else
        {
            return $accessCheck;
        }
    }
    public function updateFillingMachineGroupHead(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $filling_machine_group_head_id  = $this->decrypt($request->encrypt_id);
            $filling_machine_group_name     = $request->filling_machine_group_name;
            $filling_machine_group_head     = FillingMachineGroupHead::find($filling_machine_group_head_id);
            if ($filling_machine_group_head->filling_machine_group_name == $filling_machine_group_name)
            {
                return [
                    'status'    => '01',
                    'message'   => 'Nama kelompok mesin filling tidak boleh sama dengan data sebelumnya, apabila ingin membatalkan proses ubah harap tekan tombol cancel.'
                ];
            }
            else
            {
                $check_filling_machine_name     = FillingMachineGroupHead::where('filling_machine_group_name',$filling_machine_group_name)->get();
                if (count($check_filling_machine_name) > 0)
                {
                    return [
                        'status'    => '01',
                        'message'   => 'Nama kelompok mesin filling sudah terdaftar, harap menggantinya dengan nama yang unik'
                    ];
                }
                else
                {
                    $filling_machine_group_head->filling_machine_group_name     = $filling_machine_group_name;
                    $filling_machine_group_head->save();
                    return [
                        'status'    => '00',
                        'message'   => 'Nama kelompok mesin filling berhasil diubah'
                    ];
                }
            }
        }
        else
        {
            return $accessCheck;
        }
    }
    public function changeFillingMachineDetailStatus(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $filling_machine_group_detail                = FillingMachineGroupDetail::find($this->decrypt($request->filling_machine_group_detail_id));
            $filling_machine_group_detail->is_active     = $request->status_filling_machine_group_detail;
            $filling_machine_group_detail->save();
            return ['status' => '00','message'=>'Data berhasil diubah'];
        }
        else
        {
            return $accessCheck;
        }
    }
    public function addFillingMachineGroupDetailModal(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $filling_machine_group_head     = FillingMachineGroupHead::find($this->decrypt($request->filling_machine_group_head_id));
            $array_filling_machine_id       = array();
            if (!is_null($filling_machine_group_head->fillingMachineGroupDetails))
            {
                foreach ($filling_machine_group_head->fillingMachineGroupDetails as $key => $filling_machine_detail)
                {
                    array_push($array_filling_machine_id,$filling_machine_detail->filling_machine_id);
                }
            }
            $filling_machine    = FillingMachine::whereNotIn('id',$array_filling_machine_id)->get();
            $filling_machine    = $this->encryptId($filling_machine);
            $filling_machine_group_head    = $this->encryptId($filling_machine_group_head);
            return view($this->route.'._form_add_filling_machine_group_detail',['filling_machine_group_head'=>$filling_machine_group_head,'filling_machines'=>$filling_machine]);
        }
        else
        {
            return $accessCheck;
        }
    }
    public function addFillingMachineGroupDetail(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            if ( count($request->filling_machine) > 0 )
            {
                $filling_machine                = $request->filling_machine;
                $filling_machine_group_head_id  = $this->decrypt($request->encrypt_id);
                foreach ($filling_machine as $machine)
                {
                    $filling_machine_detail = FillingMachineGroupDetail::create([
                        'filling_machine_group_head_id'     => $filling_machine_group_head_id,
                        'filling_machine_id'                => $this->decrypt($machine),
                        'is_active'                         => '1'
                    ]);
                }
                return [
                    'status'    => '00',
                    'message'   => 'Mesin Filling berhasil ditambahkan ke kelompok mesin filling '.$filling_machine_detail->fillingMachineGroupHead->filling_machine_group_name
                ];
            }
            else
            {
                return [
                    'status'        => '01',
                    'message'       => 'Harap pilih salah satu mesin filling untuk ditambahkan ke kelompok mesin filling'
                ];
            }
        }
        else
        {
            return $accessCheck;
        }
    }
    public function addNewFillingMachineGroupModal(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            return view($this->route.'._form');
        }
        else
        {
            return $accessCheck;
        }
    }
    public function addNewFillingMachineGroup(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $filling_machine_group_name     = $request->filling_machine_group_name;
            $check_filling_machine_name     = FillingMachineGroupHead::where('filling_machine_group_name',$filling_machine_group_name)->get();
            if (count($check_filling_machine_name) > 0)
            {
                return [
                    'status'    => '01',
                    'message'   => 'Nama kelompok mesin filling sudah terdaftar, harap menggantinya dengan nama yang unik'
                ];
            }
            else
            {
                $filling_machine_group  = FillingMachineGroupHead::create([
                    'filling_machine_group_name'    => $filling_machine_group_name,
                    'is_active' => '0'
                ]);
                return [
                    'status'    => '00',
                    'message'   => 'Kelompok mesin filling baru berhasil diinput'
                ];
            }
        }
        else
        {
            return $accessCheck;
        }
    }


}
