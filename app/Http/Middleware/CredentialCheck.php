<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Master\Application;
use App\Models\Master\ApplicationPermission;
use App\Models\Master\Menu;
use App\Models\Master\MenuPermission;
use App\Http\Middleware\Route;
use Session;
use Auth;
use DB;
class CredentialCheck
{
    public function handle($request, Closure $next)
    {
        $url                = \Request::getRequestUri();
        $data               = explode('/',$url);
        $application        = Application::where('application_link',$data[1])->first();
        Session::put('application',$application->application_name);
        Session::put('application_status',$application->is_active);
        if ($application->is_active)
        {
            $application_permission     = $application->applicationPermissions->where('user_id',Auth::user()->id)->first();
            if (!is_null($application_permission))
            {
                Session::put('application_access',$application_permission->is_active);
                if ($application_permission->is_active)
                {
                    $route_name  = request()->route()->getName();
                    if (!is_null($route_name))
                    {
                        $sql        = "SELECT A.menu_name, A.is_active, B.view, B.edit, B.create, B.delete
                                        FROM menus A
                                        INNER JOIN menu_permissions B
                                        ON B.menu_id = A.id
                                        WHERE A.menu_route = '".$route_name."' AND B.user_id ='".Auth::user()->id."'";
                        $menu       = DB::select($sql);
                        // dd($sql);
                        Session::put('menu_name',$menu[0]->menu_name);
                        if ($menu[0]->is_active)
                        {
                            if ($menu[0]->view)
                            {
                                Session::put('lihat','true');
                                if ($menu[0]->create)
                                {
                                    Session::put('create','show');
                                }
                                else
                                {
                                    Session::put('create','hidden');
                                }

                                if ($menu[0]->edit)
                                {
                                    Session::put('edit','show');
                                }
                                else
                                {
                                    Session::put('edit','hidden');
                                }

                                if ($menu[0]->delete)
                                {
                                    Session::put('delete','show');
                                }
                                else
                                {
                                    Session::put('delete','hidden');
                                }
                                return $next($request);
                            }
                            else
                            {
                                if($request->ajax())
                                {
                                    return  response()->json(['status'=>'02','message'=>'Anda tidak memiliki hak akses ke menu ini. Harap hubungi Administrator Aplikasi atau Mengisi Form Request Hak Akses untuk request hak akses pada menu tersebut']);
                                }
                                return redirect(url()->previous())->with('error', 'Anda tidak memiliki hak akses ke menu ini. Harap hubungi Administrator Aplikasi atau Mengisi Form Request Hak Akses untuk request hak akses pada menu tersebut');
                            }

                        }
                        else
                        {
                            if($request->ajax())
                            {
                                return  response()->json(['status'=>'02','message'=>'Menu yang anda akses tidak tersedia untuk sementara ini. Harap hubungi administrator aplikasi terkait untuk follow up masalah tersebut.']);
                            }
                            return redirect()->route('credential_access.home-page')->with('error', 'Menu yang anda akses tidak tersedia untuk sementara ini. Harap hubungi administrator aplikasi terkait untuk follow up masalah tersebut.');
                        }

                    }
                    else
                    {
                        return $next($request);
                    }
                }
                else
                {
                    Session::put('application_access','0');
                    if($request->ajax())
                    {
                        return  response()->json(['status'=>'02','message'=>'Anda tidak memiliki hak untuk mengakses aplikasi ini, Harap hubungi administrator untuk memberikan akses pada aplikasi atau klik tautan Help Page dan Klik Request Akses Aplikasi']);
                    }
                    return redirect()->route('credential_access.home-page')->with('error', 'Anda tidak memiliki hak untuk mengakses aplikasi ini, Harap hubungi administrator untuk memberikan akses pada aplikasi atau klik tautan Help Page dan Klik Request Akses Aplikasi');
                }
            }
            else
            {
                if($request->ajax())
                {
                    return  response()->json(['status'=>'02','message'=>'Anda tidak memiliki hak untuk mengakses aplikasi ini, Harap hubungi administrator untuk memberikan akses pada aplikasi atau klik tautan Help Page dan Klik Request Akses Aplikasi']);
                }
                return redirect()->route('credential_access.home-page')->with('error', 'Anda tidak memiliki hak untuk mengakses aplikasi ini, Harap hubungi administrator untuk memberikan akses pada aplikasi atau klik tautan Help Page dan Klik Request Akses Aplikasi');
            }
        }
        else
        {

            if($request->ajax())
            {
                return  response()->json(['status'=>'02','message'=>'Aplikasi yang ada akses telah di nonaktifkan. Harap hubungi administrator aplikasi terkait atau administrator di ext. 57156 apabila hal tersebut tidak seharusnya terjadi .']);
            }
            return redirect()->route('credential_access.home-page')->with('error', 'Aplikasi yang ada akses telah di nonaktifkan. Harap hubungi administrator aplikasi terkait atau administrator di ext. 57156 apabila hal tersebut tidak seharusnya terjadi .');
        }

    }
}
