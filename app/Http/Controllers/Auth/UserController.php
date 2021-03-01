<?php

namespace App\Http\Controllers\Auth;

use App\Models\Master\User;
/* use App\Models\Master\Departement; */
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use Auth;
use DB;
use DataTables;
use Session;
use Hash;
class UserController extends ResourceController
{
    private $route = 'master_app.manage_user';

    public function home()
    {
        $applications           = array();
        $application_access     = 0;
        foreach (Auth::user()->applicationPermissions as $application_permission)
        {
            if ($application_permission->is_active)
            {
                if ($application_permission->application->is_active)
                {
                    $application_access++;
                    array_push($applications,$application_permission->application);
                }
            }
        }
        return view('home',['applications'=>$applications,'application_access'=>$application_access]);
    }
    public function index()
    {
        /* $departements       = Departement::all();
        $departements       = $this->encryptId($departements); */
        return view($this->route.'.index'/* ['departemens'=>$departements] */);
    }
    public function getDataUser(Request $request)
    {
        if ($request->ajax())
        {
            $accessCheck = $this->accessCheck('view','master_app.manage_user');

            if ($accessCheck['status'] !== '01' || $accessCheck['status'] !== '02')
            {
                $sql            = "SELECT A.id, B.fullname, A.username, B.email, C.departement, A.is_active, A.verified, A.verified_by_admin
                                FROM users A
                                INNER JOIN employees B
                                ON A.employee_id = B.id
                                INNER JOIN departements C
                                ON B.departement_id = C.id";
                $data_users     = DB::select($sql);
                $status         = '';
                foreach ($data_users as $key => $user)
                {
                    $btn_extra       = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm '.Session::get('edit').'" onclick="editUser(this)" id="edit_user_'.$this->encrypt($user->id).'"><i class="fas fa-edit"></i></a>&nbsp;';
                    $btn_extra      .= '<a href="javascript:void(0)" class="btn btn-outline-primary btn-sm '.Session::get('edit').'" onclick="resetPassword(this)" id="reset_password_'.$this->encrypt($user->id).'"><i class="fas fa-magic"></i><i class="fas fa-key"></i></a>';
                    if ($user->verified == '1')
                    {
                        if ($user->verified_by_admin == '1')
                        {
                            if ($user->is_active == '1')
                            {
                                $status     = '<div class="custom-control custom-switch">';
                                $status     .= '<input type="checkbox" class="custom-control-input" id="status_user_'.$this->encrypt($user->id).'" name="status" onchange="changeStatus(this)" checked>';
                                $status     .= '<label class="custom-control-label" for="status_user_'.$this->encrypt($user->id).'">Active</label>';
                                $status     .= '</div>';
                            }
                            else
                            {
                                $status     = '<div class="custom-control custom-switch">';
                                $status     .= '<input type="checkbox" class="custom-control-input" id="status_user_'.$this->encrypt($user->id).'" name="status" onchange="changeStatus(this)">';
                                $status     .= '<label class="custom-control-label" for="status_user_'.$this->encrypt($user->id).'">Inactive</label>';
                                $status     .= '</div>';
                            }

                        }
                        else
                        {
                            $status     = '<a href="javascript:void(0)" class="btn btn-outline-primary btn-sm '.Session::get('edit').'" onclick="verifyUser(this)" id="verify_user_'.$this->encrypt($user->id).'"><i class="fas fa-times"></i> </a>&nbsp; Unverified By Admin';
                        }

                    }
                    else
                    {
                        $status     = '<a href="javascript:void(0)" class="btn btn-danger btn-sm '.Session::get('edit').'" id="verify_user_'.$this->encrypt($user->id).'" disabled><i class="fas fa-times"></i> </a>&nbsp; Unverified User';

                    }
                    $data_users[$key]->status       = $status;
                    $data_users[$key]->btn_extra    = $btn_extra;
                }
                return Datatables::of($data_users)
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
            } else
            {
                return $accessCheck;
            }

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editDataUser(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit','master_app.manage_user');
        if ($accessCheck['status'] == '00')
        {
            $user_id            = $this->decrypt($request->user_id);
            $user               = User::find($user_id);
            $user->employee     = $this->encryptId($user->employee,'departement_id');
            $user               = $this->encryptId($user);
            $departements       = DB::connection('master_data')->table('departements')->select('id','departement')->get();
            $departements       = $this->encryptId($departements);
            return view($this->route.'.edit',['user'=>$user,'departements'=>$departements]);
        }
        else
        {
            return ['status'=>$accessCheck['status'], 'message'=>$accessCheck['message']];
        }
    }

    public function updateDataUser(Request $request)
    {
        $accessCheck    = $this->accessCheck('update','master_app.manage_user');
        if ($accessCheck['status'] == '00')
        {
            $departement_id     = $this->decrypt($request->departement_id);
            $fullname           = $request->fullname;
            $username           = $request->username;
            $email              = $request->email;
            $user_id            = $this->decrypt($request->user_id);
            $user               = User::find($user_id);
            $user->username     = $username;
                $user->employee->fullname           = $fullname;
                $user->employee->departement_id     = $departement_id;
                $user->employee->email              = $email;
                $user->employee->save();
            $user->save();
            return [
                'status'    => '00',
                'message'   => 'Data berhasil di ubah.'
            ];
        }
        else
        {
            return [
                'status'    => $accessCheck['status'],
                'message'   => $accessCheck['message']
            ];
        }
    }

    public function changeStatusUser(Request $request)
    {
        $accessCheck    = $this->accessCheck('update','master_app.manage_user');
        if ($accessCheck['status'] == '00')
        {
            $user       = User::find($this->decrypt($request->user_id));
            $user->is_active    = $request->status_user;
            $user->save();
            return ['status' => '00','message'=>'Data berhasil diubah'];
        }
        else
        {
            return $accessCheck;
        }

    }

    public function verifyUser(Request $request)
    {
        $accessCheck    = $this->accessCheck('update','master_app.manage_user');
        if ($accessCheck['status'] == '00')
        {
            $user                       = User::find($this->decrypt($request->user_id));
            $user->verified_by_admin    = '1';
            $user->is_active            = '1';
            $user->save();
            return ['status' => '00','message'=>'User berhasil di verifikasi.'];
        }
        else
        {
            return $accessCheck;
        }

    }

    public function resetPassword(Request $request)
    {
        $accessCheck    = $this->accessCheck('update','master_app.manage_user');
        if ($accessCheck['status'] == '00')
        {
            $user                       = User::find($this->decrypt($request->user_id));
            $password                   = Hash::make('sentulappuser');
            $user->password             = $password;
            $user->is_active            = '1';
            $user->save();
            return ['status' => '00','message'=>'Password user '.$user->employee->fullname.' berhasil diubah menjadi password default aplikasi'];
        }
        else
        {
            return $accessCheck;
        }

    }
}
