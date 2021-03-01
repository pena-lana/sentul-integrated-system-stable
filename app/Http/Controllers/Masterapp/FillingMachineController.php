<?php

namespace App\Http\Controllers\Masterapp;

use App\Models\Master\FillingMachine;
use App\Http\Controllers\ResourceController;
use Illuminate\Http\Request;
use Auth;
use DB;
use DataTables;
use Session;
use Hash;
class FillingMachineController extends ResourceController
{
    private $route = 'master_app.manage_filling_machine';

    public function index()
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
                $sql                    = "SELECT A.id as filling_machine_id, A.filling_machine_name, A.filling_machine_code, A.is_active FROM filling_machines A";
                $filling_machines        = DB::select($sql);
                foreach ($filling_machines as $key => $filling_machine)
                {
                    $btn_extra       = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm '.Session::get('edit').'" onclick="editFillingMachine(this)" id="edit_filling_machine_'.$this->encrypt($filling_machine->filling_machine_id).'"><i class="fas fa-edit"></i></a>&nbsp;';
                    if ($filling_machine->is_active == '1')
                    {
                        $status     = '<div class="custom-control custom-switch">';
                        $status     .= '<input type="checkbox" class="custom-control-input" id="status_filling_machine_'.$this->encrypt($filling_machine->filling_machine_id).'" name="status" onchange="changeStatusFillingMachine(this)" checked>';
                        $status     .= '<label class="custom-control-label" for="status_filling_machine_'.$this->encrypt($filling_machine->filling_machine_id).'">Active</label>';
                        $status     .= '</div>';
                    }
                    else
                    {
                        $status     = '<div class="custom-control custom-switch">';
                        $status     .= '<input type="checkbox" class="custom-control-input" id="status_filling_machine_'.$this->encrypt($filling_machine->filling_machine_id).'" name="status" onchange="changeStatusFillingMachine(this)">';
                        $status     .= '<label class="custom-control-label" for="status_filling_machine_'.$this->encrypt($filling_machine->filling_machine_id).'">Inactive</label>';
                        $status     .= '</div>';
                    }
                    $filling_machine->filling_machine_id  = $this->encrypt($filling_machine->filling_machine_id);
                    $filling_machines[$key]->status     = $status;
                    $filling_machines[$key]->btn_extra  = $btn_extra;
                }

                return Datatables::of($filling_machines)
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


    public function changeFillingMachineStatus(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $filling_machine                = FillingMachine::find($this->decrypt($request->filling_machine_id));
            $filling_machine->is_active     = $request->status_filling_machine;
            $filling_machine->save();
            return ['status' => '00','message'=>'Data berhasil diubah'];
        }
        else
        {
            return $accessCheck;
        }
    }


    public function addFillingMachineModal(Request $request)
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

    public function addFillingMachine(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $filling_machine_name           = $request->filling_machine_name;
            $filling_machine_code           = $request->filling_machine_code;
            $is_active                      = '1';
            $check_filling_machine_code     = FillingMachine::where('filling_machine_code',$filling_machine_code)->get();
            if (count($check_filling_machine_code) > 0)
            {
                return [
                    'status'    => '01',
                    'message'   => 'Mesin Filling Dengan Kode '.$filling_machine_code.' telah terdaftar. Harap input mesin filling dengan kode yang baru'
                ];
            }
            else
            {
                $filling_machine    = FillingMachine::create([
                    'filling_machine_code'  => $filling_machine_code,
                    'filling_machine_name'  => $filling_machine_name,
                    'is_active'             => $is_active
                ]);
                return [
                    'status'    => '00',
                    'message'   => 'Mesin Filling baru dengan Kode '.$filling_machine->filling_machine_code.' berhasil ditambahkan'
                ];
            }
        }
        else
        {
            return $accessCheck;
        }

    }


    public function editFillingMachine(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $filling_machine    = FillingMachine::find($this->decrypt($request->filling_machine_id));
            $filling_machine    = $this->encryptId($filling_machine);
            return view($this->route.'._edit',['filling_machine'=>$filling_machine]);
        }
        else
        {
            return $accessCheck;
        }
    }


    public function updateFillingMachine(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $filling_machine_name           = $request->filling_machine_name;
            $filling_machine_code           = $request->filling_machine_code;
            $id                             = $this->decrypt($request->encrypt_id);
            $check_filling_machine_code     = FillingMachine::where('filling_machine_code',$filling_machine_code)->get();
            if (count($check_filling_machine_code) > 0)
            {
                return [
                    'status'    => '01',
                    'message'   => 'Mesin Filling Dengan Kode '.$filling_machine_code.' telah terdaftar. Harap input mesin filling dengan kode yang baru'
                ];
            }
            else
            {
                $filling_machine    = FillingMachine::find($id);
                $filling_machine->filling_machine_code  = $filling_machine_code;
                $filling_machine->filling_machine_name  = $filling_machine_name;
                $filling_machine->save();
                return [
                    'status'    => '00',
                    'message'   => 'Mesin Filling Berhasil Di Update'
                ];
            }
        }
        else
        {
            return $accessCheck;
        }
    }
}
