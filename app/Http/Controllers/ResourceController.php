<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Master\Application;
use App\Models\Master\Menu;
use App\Models\Master\MenuPermission;

use Auth;
use Session;
use DB;
class ResourceController extends Controller
{

	public function encrypt($string)
	{
		$output = false;
	    $encrypt_method = "AES-256-CBC";
	    $secret_key = 'sentul-apps';
	    $secret_iv = 'sentul-apps';

	    // hash
	    $key = hash('sha256', $secret_key);

	    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	    $iv = substr(hash('sha256', $secret_iv), 0, 16);

	    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	    $output = base64_encode($output);

	    return $output;
	}
	public function decrypt($string)
	{
		$output = false;
	    $encrypt_method = "AES-256-CBC";
	    $secret_key = 'sentul-apps';
	    $secret_iv = 'sentul-apps';

	    // hash
	    $key = hash('sha256', $secret_key);
	    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	    $iv = substr(hash('sha256', $secret_iv), 0, 16);
	    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	    return $output;
    }

    public function regenerateSession($route_name)
    {
        $sql        = "SELECT A.menu_name, A.is_active, B.view, B.edit, B.create, B.delete, C.is_active as application_status, D.is_active as application_access
                                        FROM menus A
                                        INNER JOIN menu_permissions B
                                        ON B.menu_id = A.id
                                        INNER JOIN applications C
                                        ON A.application_id = C.id
                                        LEFT JOIN application_permissions D
                                        ON D.application_id = C.id
                                        WHERE A.menu_route = '".$route_name."' AND B.user_id ='".Auth::user()->id."' AND D.user_id ='".Auth::user()->id."'";
        $menu       = DB::select($sql);
        Session::put('menu_name',$menu[0]->menu_name);
        Session::put('application_status',$menu[0]->application_status);
        if (!is_null($menu[0]->application_access))
        {
            Session::put('application_access',$menu[0]->application_access);
        }
        else
        {
            Session::put('application_access','0');
        }

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
        }
        else
        {
            Session::put('lihat','false');
        }
    }
    public function accessCheck($akses,$route_name)
    {
        $this->regenerateSession($route_name);
        if (Session::get('application_access') == '1')
        {
            if (Session::get('lihat') == 'true')
            {
                switch ($akses)
                {
                    case 'view':
                        if (Session::get('lihat') == 'true')
                        {
                            return ['status'=>'00','message'=>'success'];
                        }
                        else
                        {
                            return ['status'=>'02','message'=>'Anda tidak memiliki akses untuk lihat data pada menu ini'];
                        }
                    break;
                    case 'create':
                        if (Session::get('create') == 'show')
                        {
                            return ['status'=>'00','message'=>'success'];
                        }
                        else
                        {
                            return ['status'=>'01','message'=>'Anda tidak memiliki akses untuk menambahkan data pada menu ini'];
                        }
                    break;

                    case 'edit':
                        if (Session::get('edit') == 'show')
                        {
                            return ['status'=>'00','message'=>'success'];
                        }
                        else
                        {
                            return ['status'=>'01','message'=>'Anda tidak memiliki akses untuk mengubah data pada menu ini'];
                        }
                    break;

                    case 'update':
                        if (Session::get('edit') == 'show')
                        {
                            return ['status'=>'00','message'=>'success'];
                        }
                        else
                        {
                            return ['status'=>'01','message'=>'Anda tidak memiliki akses untuk mengubah data pada menu ini'];
                        }
                    break;

                    case 'delete':
                        if (Session::get('delete') == 'show')
                        {
                            return ['status'=>'00','message'=>'success'];
                        }
                        else
                        {
                            return ['status'=>'01','message'=>'Anda tidak memiliki akses untuk menghapus data pada menu ini'];
                        }
                    break;
                }
            }
            else
            {
                return ['status' => '02', 'message' => 'Anda tidak memiliki akses pada menu ini, harap hubungi administrator'];
            }

        }
        else
        {
            return ['status' => '02', 'message' => 'Anda tidak memiliki akses aplikasi, harap hubungi administrator aplikasi'];
        }

    }
    public function encryptId($arrays,$child1 ='',$child2='',$child3='',$child4='',$child5  ='',$child6='',$child7='')
    {
        if (isset($arrays[0]))
        {
            foreach ($arrays as $array)
            {
                $array->encrypt_id 	= $this->encrypt($array->id);
                unset($array->id);
                if ($child1 !== '')
                {
                    $array['encrypt_'.$child1]     = $this->encrypt($array[$child1]);
                    unset($array[$child1]);
                }

                if ($child2 !== '')
                {
                    $array['encrypt_'.$child2]     = $this->encrypt($array[$child2]);
                    unset($array[$child2]);
                }

                if ($child3 !== '')
                {
                    $array['encrypt_'.$child3]     = $this->encrypt($array[$child3]);
                    unset($array[$child3]);
                }

                if ($child4 !== '')
                {
                    $array['encrypt_'.$child4]     = $this->encrypt($array[$child4]);
                    unset($array[$child4]);
                }

                if ($child5 !== '')
                {
                    $array['encrypt_'.$child5]     = $this->encrypt($array[$child5]);
                    unset($array[$child5]);
                }
                if ($child6 !== '')
                {
                    $array['encrypt_'.$child6]     = $this->encrypt($array[$child6]);
                    unset($array[$child6]);
                }

                if ($child7 !== '')
                {
                    $array['encrypt_'.$child7]     = $this->encrypt($array[$child7]);
                    unset($array[$child7]);
                }
            }
        }
        else
        {
            $arrays->encrypt_id 	= $this->encrypt($arrays->id);
            unset($arrays->id);
            if ($child1 !== '')
            {
                $arrays['encrypt_'.$child1]     = $this->encrypt($arrays[$child1]);
                unset($arrays[$child1]);
            }

            if ($child2 !== '')
            {
                $arrays['encrypt_'.$child2]     = $this->encrypt($arrays[$child2]);
                unset($arrays[$child2]);
            }

            if ($child3 !== '')
            {
                $arrays['encrypt_'.$child3]     = $this->encrypt($arrays[$child3]);
                unset($arrays[$child3]);
            }

            if ($child4 !== '')
            {
                $arrays['encrypt_'.$child4]     = $this->encrypt($arrays[$child4]);
                unset($arrays[$child4]);
            }
            if ($child5 !== '')
            {
                $arrays['encrypt_'.$child5]     = $this->encrypt($arrays[$child5]);
                unset($arrays[$child5]);
            }

            if ($child6 !== '')
            {
                $arrays['encrypt_'.$child6]     = $this->encrypt($arrays[$child6]);
                unset($arrays[$child6]);
            }

            if ($child7 !== '')
            {
                $arrays['encrypt_'.$child7]     = $this->encrypt($arrays[$child7]);
                unset($arrays[$child7]);
            }
        }
        return $arrays;
    }

}
