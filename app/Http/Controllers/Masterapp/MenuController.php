<?php

namespace App\Http\Controllers\Masterapp;

use App\Models\Master\Menu;
use App\Models\Master\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;

use Auth;
use DB;
use DataTables;
use Session;
use Hash;
class MenuController extends ResourceController
{
    private $route = 'master_app.manage_menu';

    public function index()
    {
        return view($this->route.'.index');
    }

    public function getDataMenu(Request $request)
    {
        if ($request->ajax())
        {
            $accessCheck = $this->accessCheck('view',$this->route);

            if ($accessCheck['status'] !== '01' || $accessCheck['status'] == '02' )
            {
                $sql            = " SELECT A.id as menu_id, B.application_name, A.menu_name, A.menu_route, A.menu_position, A.is_active
                                    FROM menus A
                                    INNER JOIN applications B
                                    ON B.id = A.application_id";
                $menu_data      = DB::select($sql);
                $status         = '';
                foreach ($menu_data as $key => $menu)
                {
                    $btn_extra       = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm '.Session::get('edit').'" onclick="editMenu(this)" id="edit_menu_'.$this->encrypt($menu->menu_id).'"><i class="fas fa-edit"></i></a>&nbsp;';
                    if ($menu->is_active == '1')
                    {
                        $status     = '<div class="custom-control custom-switch">';
                        $status     .= '<input type="checkbox" class="custom-control-input" id="status_menu_'.$this->encrypt($menu->menu_id).'" name="status" onchange="changeStatusMenu(this)" checked>';
                        $status     .= '<label class="custom-control-label" for="status_menu_'.$this->encrypt($menu->menu_id).'">Active</label>';
                        $status     .= '</div>';
                    }
                    else
                    {
                        $status     = '<div class="custom-control custom-switch">';
                        $status     .= '<input type="checkbox" class="custom-control-input" id="status_menu_'.$this->encrypt($menu->menu_id).'" name="status" onchange="changeStatusMenu(this)">';
                        $status     .= '<label class="custom-control-label" for="status_menu_'.$this->encrypt($menu->menu_id).'">Inactive</label>';
                        $status     .= '</div>';
                    }
                    $menu->menu_id  = $this->encrypt($menu->menu_id);
                    $menu_data[$key]->status     = $status;
                    $menu_data[$key]->btn_extra  = $btn_extra;
                }
                return Datatables::of($menu_data)
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

    public function addNewMenuModal(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $applications       = $this->encryptId(Application::all());
            return view($this->route.'._form',['applications'=>$applications]);
        }
        else
        {
            return $accessCheck;
        }

    }

    public function changeApplication(Request $request)
    {
        $accessCheck    = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $get_menu   = Menu::where('application_id',$this->decrypt($request->application_id))->where('parent_id','0')->orderBy('menu_position','ASC')->get();
            $options    = "<option value='".$this->encrypt('0')."'>Make As A Parent</option>";
            foreach ($get_menu as $menu)
            {
                $options     .= "<option value='".$this->encrypt($menu->id)."'>".$menu->menu_name."</option>";
                if (count($menu->childMenus) > 0)
                {
                    foreach ($menu->childMenus as $child)
                    {
                        $options     .= "<option value='".$this->encrypt($child->id)."'>".$menu->menu_name." >> ".$child->menu_name."</option>";
                        if (count($child->childMenus) > 0 )
                        {
                            foreach ($child->childMenus as $child2)
                            {
                                $options     .= "<option value='".$this->encrypt($child2->id)."'>".$menu->menu_name." >> ".$child->menu_name." >> ".$child2->menu_name."</option>";
                            }
                        }
                    }

                }
            }
            return $options;
        }
        else
        {
            return $accessCheck;
        }

    }

    public function addNewMenu(Request $request)
    {
        $accessCheck        = $this->accessCheck('create',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $application_id         = $this->decrypt($request->application_id);
            $parent_id            = $this->decrypt($request->parent_menu);
            $menu_name              = $request->menu_name;
            $menu_route             = $request->menu_route;
            $menu_icon              = $request->menu_icon;
            $checking_menu_exist    = DB::table('menus')->where('menu_name',$menu_name)->where('application_id',$application_id)->get();
            if (count($checking_menu_exist) > 0)
            {
                $application        = Application::find($application_id);
                return [
                    'status'    => '01',
                    'message'   => 'Menu '.$menu_name.' Pada aplikasi '.$application->application_name
                ];
            }
            else
            {
                $menu_position  = DB::table('menus')->where('parent_id',$parent_id)->where('application_id',$application_id)->orderBy('menu_position','DESC')->get();
                if (count($menu_position) > 0)
                {
                    $menu_position  = $menu_position[0]->menu_position+1;
                }
                else
                {
                    $menu_position  = '1';
                }
                if ($parent_id !== '0')
                {
                    $update_parent               = Menu::find($parent_id);
                    $update_parent->menu_route   = '-';
                    $update_parent->save();
                }
                /* Insert Menu to Table Menu */
                $insert         = Menu::create([
                    'application_id'    => $application_id,
                    'parent_id'         => $parent_id,
                    'menu_name'         => $menu_name,
                    'menu_route'        => $menu_route,
                    'menu_icon'         => $menu_icon,
                    'menu_position'     => $menu_position
                ]);
                return [
                    'status'    => '00',
                    'message'   => 'Menu '.$insert->menu_name.' pada aplikasi '.$insert->application->application_name.' Telah berhasil ditambahkan '
                ];
            }

        }
        else
        {
            return $accessCheck;
        }

    }

    public function editDataMenu(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $menu_data                  = Menu::find($this->decrypt($request->menu_id));
            $menu_data->application     = $this->encryptId($menu_data->application);
            $menu_data                  = $this->encryptId($menu_data,'application_id');
            $menus                      = Menu::where('application_id',$this->decrypt($menu_data->encrypt_application_id))->where('parent_id','0')->orderBy('menu_position','ASC')->get();
            $options                    = "<option value='".$this->encrypt('0')."'>Make As A Parent</option>";
            foreach ($menus as $menu)
            {
                $options     .= "<option value='".$this->encrypt($menu->id)."' ";
                if ($this->encrypt($menu_data->parent_id) == $this->encrypt($menu->id))
                {
                    $options .= " selected ";
                }
                $options     .= ">".$menu->menu_name."</option>";
                if (count($menu->childMenus) > 0)
                {
                    foreach ($menu->childMenus as $child)
                    {
                        $options     .= "<option value='".$this->encrypt($child->id)."' ";
                        if ($this->encrypt($menu_data->parent_id) == $this->encrypt($child->id))
                        {
                            $options .= " selected ";
                        }
                        $options     .=">".$menu->menu_name." >> ".$child->menu_name."</option>";
                        if (count($child->childMenus) > 0 )
                        {
                            foreach ($child->childMenus as $child2)
                            {
                                $options     .= "<option value='".$this->encrypt($child2->id)."'";
                                if ($this->encrypt($menu_data->parent_id) == $this->encrypt($child2->id))
                                {
                                    $options .= " selected ";
                                }
                                $options     .= ">".$menu->menu_name." >> ".$child->menu_name." >> ".$child2->menu_name."</option>";
                            }
                        }
                    }

                }
            }
            return view($this->route.'._edit',['menu'=>$menu_data,'menus'=>$options]);
        }
        else
        {
            return $accessCheck;
        }
    }
    public function updateDataMenu(Request $request)
    {
        $accessCheck        = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $application_name   =  $request->application_name;
            $application_id     =  $this->decrypt($request->application_id);
            $parent_id          =  $this->decrypt($request->parent_id);
            $menu_name          =  $request->menu_name;
            $menu_id            =  $this->decrypt($request->menu_id);
            $menu_route         =  $request->menu_route;
            $menu_icon          =  $request->menu_icon;
            $menu               = Menu::find($menu_id);
            if ($menu->parent_id !=  $parent_id)
            {
                if (($menu->parent_id !== '0'))
                {
                    if (count($menu->parentMenu->childMenus) == 1)
                    {
                        $menu->parentMenu->is_active    = '0';
                        $menu->parentMenu->save();
                    }
                }
            }
            $menu->application_id   = $application_id;
            $menu->parent_id        = $parent_id;
            $menu->menu_name        = $menu_name;
            $menu->menu_route       = $menu_route;
            $menu->menu_icon        = $menu_icon;
            $menu->save();
            return [
                'status'    => '00',
                'message'   => 'Data menu berhasil di ubah'
            ];
        }
        else
        {
            return $accessCheck;
        }

    }
    public function changeStatusMenu(Request $request)
    {
        $accessCheck    = $this->accessCheck('edit',$this->route);
        if ($accessCheck['status'] == '00')
        {
            $menu                = Menu::find($this->decrypt($request->menu_id));
            $menu->is_active     = $request->status_menu;
            $menu->save();
            return ['status' => '00','message'=>'Data berhasil diubah'];
        }
        else
        {
            return $accessCheck;
        }
    }
}
