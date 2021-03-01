<?php

namespace App\Http\Controllers\Masterapp;

use App\Models\Master\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use Auth;
use DB;
use DataTables;
use Session;
use Hash;
class ApplicationController extends ResourceController
{
    private $route = 'master_app.manage_applications';
    public function index()
    {
        return view($this->route.'.index');
    }
    public function getDataApplication(Request $request)
    {
        if ($request->ajax())
        {
            $application_data     = Application::all();
            $status         = '';
            foreach ($application_data as $key => $application)
            {
                $btn_extra       = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm '.Session::get('edit').'" onclick="editApplication(this)" id="edit_application_'.$this->encrypt($application->id).'"><i class="fas fa-edit"></i></a>&nbsp;';
                if ($application->is_active == '1')
                {
                    $status     = '<div class="custom-control custom-switch">';
                    $status     .= '<input type="checkbox" class="custom-control-input" id="status_application_'.$this->encrypt($application->id).'" name="status" onchange="changeStatusApplication(this)" checked>';
                    $status     .= '<label class="custom-control-label" for="status_application_'.$this->encrypt($application->id).'">Active</label>';
                    $status     .= '</div>';
                }
                else
                {
                    $status     = '<div class="custom-control custom-switch">';
                    $status     .= '<input type="checkbox" class="custom-control-input" id="status_application_'.$this->encrypt($application->id).'" name="status" onchange="changeStatusApplication(this)">';
                    $status     .= '<label class="custom-control-label" for="status_application_'.$this->encrypt($application->id).'">Inactive</label>';
                    $status     .= '</div>';
                }
                $application_data[$key]->status     = $status;
                $application_data[$key]->btn_extra  = $btn_extra;
            }
            return Datatables::of($application_data)
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
    }

    public function changeStatusApplication(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $application                = Application::find($this->decrypt($request->application_id));
            $application->is_active     = $request->status_application;
            $application->save();
            return ['status' => '00','message'=>'Data berhasil diubah'];
        }
        else
        {
            return $accessCheck;
        }
    }

    public function editDataApplication(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $application        = Application::find($this->decrypt($request->application_id));
            $application        = $this->encryptId($application);
            return view($this->route.'._edit',['application'=>$application]);
        }
        else
        {
            return $accessCheck;
        }
    }

    public function updateDataApplication(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $application    = Application::find($this->decrypt($request->application_id));
            $application->application_name          = $request->application_name;
            $application->application_description   = $request->application_description;
            $application->application_link          = $request->application_link;
            $application->save();
            return ['status'=>'00','message'=>'Data aplikasi berhasil diubah'];
        }
        else
        {
            return $accessCheck;
        }
    }

    public function addNewApplicationModal(Request $request)
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
    public function addNewApplication(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $addApplication    = Application::create($request->all());
            return ['status'=>'00','message'=>'Aplikasi baru berhasil ditambahkan'];
        }
        else
        {
            return $accessCheck;
        }
    }

}
