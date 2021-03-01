<?php

namespace App\Http\Controllers\Masterapp;

use App\Models\Master\ApplicationPermission;
use App\Models\Master\MenuPermission;
use App\Models\Master\Menu;
use App\Models\Master\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;

use Auth;
use DB;
use DataTables;
use Session;
use Hash;
class UserPermissionController extends ResourceController
{
    private $route = 'master_app.user_permissions';

    public function index()
    {
        return view($this->route.'.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataUser(Request $request)
    {
        if ($request->ajax())
        {
            $accessCheck = $this->accessCheck('view',$this->route);

            if ($accessCheck['status'] !== '01' || $accessCheck['status'] == '02' )
            {
                $sql            = " SELECT B.fullname, B.email, A.username, A.id as encrypt_id, C.departement as departement_name
                                    FROM users A
                                    INNER JOIN employees B
                                    ON B.id = A.employee_id
                                    INNER JOIN departements C
                                    ON C.id = B.departement_id";
                $user_data      = DB::select($sql);
                foreach ($user_data as $key => $user)
                {
                    $user->encrypt_id  = $this->encrypt($user->encrypt_id);
                }
                return Datatables::of($user_data)
                    ->addIndexColumn()
                    ->rawColumns(['action','status'])
                    ->make(true);
            }
            else
            {
                return $accessCheck;
            }
        }
    }
    public function getApplicationPermission(Request $request)
    {
        if ($request->ajax())
        {
            $accessCheck = $this->accessCheck('view',$this->route);
            if ($accessCheck['status'] !== '01' || $accessCheck['status'] == '02' )
            {
                $sql                    = " SELECT A.id, A.application_name, A.application_description, B.is_active
                                            FROM applications A
                                            RIGHT JOIN application_permissions B
                                            ON B.application_id = A.id
                                            WHERE B.user_id =".$this->decrypt($request->user_id);
                $application_data      = DB::select($sql);
                if (count($application_data) < 1)
                {
                    $applications   = DB::select("SELECT A.id FROM applications A");
                    foreach ($applications as $key => $application)
                    {
                        ApplicationPermission::create([
                            'application_id'    =>$application->id,
                            'user_id'           =>$this->decrypt($request->user_id),
                            'is_active'         =>'0'
                        ]);
                    }
                    $application_data      = DB::select($sql);
                }
                foreach ($application_data as $key => $application)
                {
                    $btn_extra       = '<a href="javascript:void(0)" class="edit btn btn-info btn-sm '.Session::get('edit').'" onclick="menuPermissionModal(this)" id="access_menu_'.$this->encrypt($application->id).'_'.$request->user_id.'_'.$application->application_name.'"><i class="fas fa-eye"></i> &nbsp; Menu Permissions</a>';
                    if ($application->is_active == '1')
                    {
                        $status     = '<div class="custom-control custom-switch">';
                        $status     .= '<input type="checkbox" class="custom-control-input" id="status_application_'.$this->encrypt($application->id).'_'.$request->user_id.'" name="status" onchange="changeApplicationPermission(this)" checked>';
                        $status     .= '<label class="custom-control-label" for="status_application_'.$this->encrypt($application->id).'_'.$request->user_id.'">Allow</label>';
                        $status     .= '</div>';
                    }
                    else
                    {
                        $status     = '<div class="custom-control custom-switch">';
                        $status     .= '<input type="checkbox" class="custom-control-input" id="status_application_'.$this->encrypt($application->id).'_'.$request->user_id.'" name="status" onchange="changeApplicationPermission(this)">';
                        $status     .= '<label class="custom-control-label" for="status_application_'.$this->encrypt($application->id).'_'.$request->user_id.'">Denied</label>';
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
            else
            {
                return $accessCheck;
            }
        }
    }

    public function changeApplicationPermission(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $application_id         = $this->decrypt($request->application_id);
            $user_id                = $this->decrypt($request->user_id);
            $is_active              = $request->is_active;
            $application_permission     = ApplicationPermission::where('application_id',$application_id)->where('user_id',$user_id)->first();
            if (is_null($application_permission))
            {
                // create application permissions data
                $application_permission     = ApplicationPermission::create([
                    'application_id'    => $application_id,
                    'user_id'           => $user_id,
                    'is_active'         => $is_active,
                ]);
            }
            else
            {
                $application_permission->is_active            = $is_active;
                $application_permission->save();
            }

            return ['status' => '00','message'=>'Akses aplikasi '.$application_permission->application_name.' untuk '.$application_permission->user->employee->fullname.' berhasil diubah'];
        }
        else
        {
            return $accessCheck;
        }
    }

    public function menuPermissionModal(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            return view($this->route.'._form',['data'=>$request->all()]);
        }
        else
        {
            return $accessCheck;
        }

    }


    public function getMenuPermission(Request $request)
    {
        if ($request->ajax())
        {
            $accessCheck = $this->accessCheck('view',$this->route);
            if ($accessCheck['status'] !== '01' || $accessCheck['status'] == '02' )
            {
                $application_id         = $this->decrypt($request->application_id);
                $user_id                = $this->decrypt($request->user_id);
                $menus                  = Menu::where('application_id',$application_id)->get();
                if (count($menus) > 0)
                {
                    foreach ($menus as $key => $menu)
                    {
                        if ($menu->parent_id != '0')
                        {
                            if ($menu->parentMenu->parent_id != '0')
                            {
                                $menus[$key]->menu_name     = $menu->parentMenu->parentMenu->menu_name.' >> '.$menu->parentMenu->menu_name.' >> '.$menu->menu_name;
                            }
                            else
                            {
                                $menus[$key]->menu_name     = $menu->parentMenu->menu_name.' >> '.$menu->menu_name;
                            }
                        }
                        $menu_permission    = $menu->menuPermissions->where('user_id',$user_id);
                        if (count($menu_permission) > 0)
                        {
                            $menu_permission = $menu_permission->first();
                            if ($menu_permission->view == '1')
                            {
                                $view      = '<div class="custom-control custom-switch">';
                                $view     .= '<input type="checkbox" class="custom-control-input" id="view_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'" name="status" onchange="changeViewPermission(this)" checked>';
                                $view     .= '<label class="custom-control-label" for="view_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'">Allowed</label>';
                                $view     .= '</div>';
                            }
                            else
                            {
                                $view      = '<div class="custom-control custom-switch">';
                                $view     .= '<input type="checkbox" class="custom-control-input" id="view_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'" name="status" onchange="changeViewPermission(this)">';
                                $view     .= '<label class="custom-control-label" for="view_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'">Allowed</label>';
                                $view     .= '</div>';
                            }
                            if ($menu_permission->create == '1')
                            {
                                $create      = '<div class="custom-control custom-switch">';
                                $create     .= '<input type="checkbox" class="custom-control-input" id="create_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'" name="status" onchange="changeCreatePermission(this)" checked>';
                                $create     .= '<label class="custom-control-label" for="create_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'">Allowed</label>';
                                $create     .= '</div>';
                            }
                            else
                            {
                                $create      = '<div class="custom-control custom-switch">';
                                $create     .= '<input type="checkbox" class="custom-control-input" id="create_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'" name="status" onchange="changeCreatePermission(this)">';
                                $create     .= '<label class="custom-control-label" for="create_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'">Allowed</label>';
                                $create     .= '</div>';
                            }
                            if ($menu_permission->edit == '1')
                            {
                                $edit      = '<div class="custom-control custom-switch">';
                                $edit     .= '<input type="checkbox" class="custom-control-input" id="edit_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'" name="status" onchange="changeEditPermission(this)" checked>';
                                $edit     .= '<label class="custom-control-label" for="edit_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'">Allowed</label>';
                                $edit     .= '</div>';
                            }
                            else
                            {
                                $edit      = '<div class="custom-control custom-switch">';
                                $edit     .= '<input type="checkbox" class="custom-control-input" id="edit_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'" name="status" onchange="changeEditPermission(this)">';
                                $edit     .= '<label class="custom-control-label" for="edit_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'">Allowed</label>';
                                $edit     .= '</div>';
                            }
                            if ($menu_permission->delete == '1')
                            {
                                $delete      = '<div class="custom-control custom-switch">';
                                $delete     .= '<input type="checkbox" class="custom-control-input" id="delete_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'" name="status" onchange="changeDeletePermission(this)" checked>';
                                $delete     .= '<label class="custom-control-label" for="delete_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'">Allowed</label>';
                                $delete     .= '</div>';
                            }
                            else
                            {
                                $delete      = '<div class="custom-control custom-switch">';
                                $delete     .= '<input type="checkbox" class="custom-control-input" id="delete_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'" name="status" onchange="changeDeletePermission(this)">';
                                $delete     .= '<label class="custom-control-label" for="delete_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'">Allowed</label>';
                                $delete     .= '</div>';
                            }
                        }
                        else
                        {
                            $view      = '<div class="custom-control custom-switch">';
                            $view     .= '<input type="checkbox" class="custom-control-input" id="view_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'" name="status" onchange="changeViewPermission(this)">';
                            $view     .= '<label class="custom-control-label" for="view_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'">Allowed</label>';
                            $view     .= '</div>';
                            $create      = '<div class="custom-control custom-switch">';
                            $create     .= '<input type="checkbox" class="custom-control-input" id="create_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'" name="status" onchange="changeCreatePermission(this)">';
                            $create     .= '<label class="custom-control-label" for="create_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'">Allowed</label>';
                            $create     .= '</div>';
                            $edit      = '<div class="custom-control custom-switch">';
                            $edit     .= '<input type="checkbox" class="custom-control-input" id="edit_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'" name="status" onchange="changeEditPermission(this)">';
                            $edit     .= '<label class="custom-control-label" for="edit_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'">Allowed</label>';
                            $edit     .= '</div>';
                            $delete      = '<div class="custom-control custom-switch">';
                            $delete     .= '<input type="checkbox" class="custom-control-input" id="delete_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'" name="status" onchange="changeDeletePermission(this)">';
                            $delete     .= '<label class="custom-control-label" for="delete_permission_'.$this->encrypt($menu->id).'_'.$this->encrypt($user_id).'">Allowed</label>';
                            $delete     .= '</div>';
                        }

                        $menus[$key]->view      = $view;
                        $menus[$key]->create    = $create;
                        $menus[$key]->edit      = $edit;
                        $menus[$key]->delete    = $delete;
                    }
                    return Datatables::of($menus)
                    ->addIndexColumn()
                    ->addColumn('view', function($row){
                        $viewBtn = $row->view;
                        return $viewBtn;
                    })
                    ->addColumn('create', function($row){
                        $createBtn = $row->create;
                        return $createBtn;
                    })
                    ->addColumn('edit', function($row){
                        $editBtn = $row->edit;
                        return $editBtn;
                    })
                    ->addColumn('delete', function($row){
                        $deleteBtn = $row->delete;
                        return $deleteBtn;
                    })
                    ->rawColumns(['view','create','edit','delete'])
                    ->make(true);

                }
                else
                {
                    return Datatables::of($menus)
                    ->addIndexColumn()
                    ->addColumn('view', function($row){
                    })
                    ->addColumn('edit', function($row){
                    })
                    ->addColumn('create', function($row){
                    })
                    ->addColumn('delete', function($row){
                    })
                    ->rawColumns(['action','status'])
                    ->make(true);
                }
            }
            else
            {
                return $accessCheck;
            }
        }
    }

    public function changeViewMenuPermission(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $menu_id                    = $this->decrypt($request->menu_id);
            $user_id                    = $this->decrypt($request->user_id);
            $is_active                  = $request->is_active;
            $menu                       = Menu::find($menu_id);
            $menu_permission            = $menu->menuPermissions->where('user_id',$user_id);
            if (is_null($menu_permission) || count($menu_permission) < 1)
            {
                // create menu permission baru
                $menu_permission        = MenuPermission::create([
                    'menu_id'       => $menu_id,
                    'user_id'       => $user_id,
                    'view'          => $is_active,
                    'create'        => '0',
                    'edit'          => '0',
                    'delete'        => '0',
                ]);
            }
            else
            {
                $menu_permission    = $menu_permission->first();
                $menu_permission->view       = $is_active;
                $menu_permission->save();
            }
            return ['status' => '00','message'=>'Akses lihat pada menu '.$menu->menu_name.' untuk '.$menu_permission->user->employee->fullname.' berhasil diubah. Harap refresh page terlebih dahulu apabila akses belum berubah.'];
        }
        else
        {
            return $accessCheck;
        }
    }

    public function changeCreateMenuPermission(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $menu_id                    = $this->decrypt($request->menu_id);
            $user_id                    = $this->decrypt($request->user_id);
            $is_active                  = $request->is_active;
            $menu                       = Menu::find($menu_id);
            $menu_permission            = $menu->menuPermissions->where('user_id',$user_id);
            if (is_null($menu_permission) || count($menu_permission) < 1)
            {
                // create menu permission baru
                $menu_permission        = MenuPermission::create([
                    'menu_id'       => $menu_id,
                    'user_id'       => $user_id,
                    'view'          => '0',
                    'create'        => $is_active,
                    'edit'          => '0',
                    'delete'        => '0',
                ]);
            }
            else
            {
                $menu_permission    = $menu_permission->first();
                $menu_permission->create       = $is_active;
                $menu_permission->save();
            }
            return ['status' => '00','message'=>'Akses tambah data pada menu '.$menu->menu_name.' untuk '.$menu_permission->user->employee->fullname.' berhasil diubah. Harap refresh page terlebih dahulu apabila akses belum berubah.'];
        }
        else
        {
            return $accessCheck;
        }
    }
    public function changeEditMenuPermission(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $menu_id                    = $this->decrypt($request->menu_id);
            $user_id                    = $this->decrypt($request->user_id);
            $is_active                  = $request->is_active;
            $menu                       = Menu::find($menu_id);
            $menu_permission            = $menu->menuPermissions->where('user_id',$user_id);
            if (is_null($menu_permission) || count($menu_permission) < 1)
            {
                // create menu permission baru
                $menu_permission        = MenuPermission::create([
                    'menu_id'       => $menu_id,
                    'user_id'       => $user_id,
                    'view'          => '0',
                    'create'        => '0',
                    'edit'          => $is_active,
                    'delete'        => '0',
                ]);
            }
            else
            {
                $menu_permission    = $menu_permission->first();
                $menu_permission->edit       = $is_active;
                $menu_permission->save();
            }
            return ['status' => '00','message'=>'Akses ubah data pada menu '.$menu->menu_name.' untuk '.$menu_permission->user->employee->fullname.' berhasil diubah. Harap refresh page terlebih dahulu apabila akses belum berubah.'];
        }
        else
        {
            return $accessCheck;
        }
    }
    public function changeDeleteMenuPermission(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $menu_id                    = $this->decrypt($request->menu_id);
            $user_id                    = $this->decrypt($request->user_id);
            $is_active                  = $request->is_active;
            $menu                       = Menu::find($menu_id);
            $menu_permission            = $menu->menuPermissions->where('user_id',$user_id);
            if (is_null($menu_permission) || count($menu_permission) < 1)
            {
                // create menu permission baru
                $menu_permission        = MenuPermission::create([
                    'menu_id'       => $menu_id,
                    'user_id'       => $user_id,
                    'view'          => '0',
                    'create'        => '0',
                    'edit'          => '0',
                    'delete'        => $is_active,
                ]);
            }
            else
            {
                $menu_permission    = $menu_permission->first();
                $menu_permission->delete       = $is_active;
                $menu_permission->save();
            }
            return ['status' => '00','message'=>'Akses hapus data pada menu '.$menu->menu_name.' untuk '.$menu_permission->user->employee->fullname.' berhasil diubah. Harap refresh page terlebih dahulu apabila akses belum berubah.'];
        }
        else
        {
            return $accessCheck;
        }
    }
}
