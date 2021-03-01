<?php
namespace App\Helpers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Validator;
use Route;
use Auth;

class GenerateMenu
{
    public static function generate($application)
	{
        $application_id         = DB::connection('master_data')->table('applications')->select("id")->where('application_name',$application)->first();
        $user_id                = Auth::user()->id;
        $parent_menu            = self::getParentMenu($application_id->id,$user_id);
        $generate_menu          = '';
        foreach ($parent_menu as $parent)
        {
            if ($parent->menu_route == '-')
            {
                $getChild     = self::getChildMenu($parent->menu_id,$user_id);
                if (count($getChild) > 0)
                {
                    $generate_menu  .= "<li class='nav-item ".$getChild['active_nav']."' id='parent-active-".strtolower(str_replace(' ','-',$parent->menu_name))."'>";
                    $generate_menu  .= "<a href='#' class='nav-link ".$getChild['active_nav_1']."' id='parent-active-".strtolower(str_replace(' ','-',$parent->menu_name))."'>";
                    $generate_menu  .= "<i class='nav-icon fas ".$parent->menu_icon."'></i>";
                    $generate_menu  .= "<p>".$parent->menu_name."<i class='right fas fa-angle-left'></i></p></a>";
                    $generate_menu  .= "<ul class='nav nav-treeview'>";
                    foreach ($getChild as $child_key => $child)
                    {
                        if ($child_key !== 'active_nav' && $child_key !== 'active_nav_1')
                        {
                            if ($child->menu_route == '-')
                            {
                                $getChild1     = self::getChildMenu($child->menu_id,$user_id);
                                if (count($getChild1) > 0)
                                {
                                    $generate_menu  .= "<li class='nav-item ".$getChild1['active_nav']."".$parent->active."' id='parent-active-".strtolower(str_replace(' ','-',$child->menu_name))."'>";
                                    $generate_menu  .= "<a href='#' class='nav-link ".$getChild1['active_nav_1']."' id='parent-active-".strtolower(str_replace(' ','-',$child->menu_name))."'>";
                                    $generate_menu  .= "<i class='nav-icon fas ".$child->menu_icon."'></i>";
                                    $generate_menu  .= "<p>".$child->menu_name."<i class='right fas fa-angle-left'></i></p></a>";
                                    $generate_menu  .= "<ul class='nav nav-treeview'>";
                                    foreach ($getChild1 as $child_key_1 => $child1)
                                    {
                                        if ($child_key_1 !== 'active_nav' && $child_key_1 !== 'active_nav_1')
                                        {
                                            $generate_menu  .= "<li class='nav-item ' id='active-".strtolower(str_replace(' ','-',$child1->menu_name))."'>";
                                            $generate_menu  .="<a href='".route($child1->menu_route)."' class='nav-link ".$child1->active."'>";
                                            $generate_menu  .= "<i class='nav-icon fas ".$child1->menu_icon."'></i>";
                                            $generate_menu  .= "<p>".$child1->menu_name."</p></a></li>";
                                        }

                                    }
                                    $generate_menu  .= "</ul>";
                                    $generate_menu  .= "</li>";
                                }
                            }
                            else
                            {
                                $generate_menu  .= "<li class='nav-item' id='active-".strtolower(str_replace(' ','-',$child->menu_name))."'>";
                                $generate_menu  .="<a href='".route($child->menu_route)."' class='nav-link ".$child->active."'>";
                                $generate_menu  .= "<i class='nav-icon fas ".$child->menu_icon."'></i>";
                                $generate_menu  .= "<p>".$child->menu_name."</p></a></li>";
                            }
                        }

                    }
                    $generate_menu  .= "</ul>";
                    $generate_menu  .= "</li>";
                }
            }
            else
            {
                $generate_menu  .= "<li class='nav-item' id='active-".strtolower(str_replace(' ','-',$parent->menu_name))."'>";
                $generate_menu  .="<a href='".route($parent->menu_route)."' class='nav-link ".$parent->active."'>";
                $generate_menu  .= "<i class='nav-icon fas ".$parent->menu_icon."'></i>";
                $generate_menu  .= "<p>".$parent->menu_name."</p></a></li>";
            }
        }
        return $generate_menu;
    }
    public static function getParentMenu($application_id,$user_id)
    {
        $sql                    = "SELECT A.view, A.create, A.edit, A.delete, B.menu_name, B.menu_icon, B.menu_route, B.menu_position, B.id as menu_id, B.is_active
                                    FROM menu_permissions A
                                    INNER JOIN menus B
                                    ON A.menu_id = B.id
                                    WHERE A.user_id = '".$user_id."' AND A.view='1' AND B.is_active = '1' AND B.application_id ='".$application_id."' AND B.parent_id ='0'
                                    ORDER BY B.menu_position ASC";
        $route_name             = request()->route()->getName();
        $get_parent             = DB::connection('master_data')->select($sql);
        foreach ($get_parent as $key => $parent)
        {
            if ($parent->menu_route == $route_name)
            {
                $get_parent[$key]->active    = 'active';
            }
            else
            {
                $get_parent[$key]->active    = '';
            }
        }
        return $get_parent;
    }
    public static function getChildMenu($parent_menu_id, $user_id)
    {
        $sql                    = "SELECT B.view, B.create, B.edit, B.delete, A.menu_name, A.menu_icon, A.menu_route, A.menu_position, A.id as menu_id, A.is_active
                                    FROM menus A
                                    INNER JOIN menu_permissions B
                                    ON B.menu_id = A.id
                                    WHERE B.user_id = '".$user_id."' AND B.view='1' AND A.is_active = '1' AND A.parent_id ='".$parent_menu_id."'
                                    ORDER BY menu_position ASC;
                                    ";
        $get_child             = DB::connection('master_data')->select($sql);
        $route_name             = request()->route()->getName();
        $get_child['active_nav']        = '';
        $get_child['active_nav_1']      = '';
        foreach ($get_child as $key => $child)
        {
            if ($key !== 'active_nav' && $key !== 'active_nav_1')
            {
                if ($child->menu_route == $route_name)
                {
                    $get_child['active_nav']            = 'menu-open';
                    $get_child['active_nav_1']          = 'active';
                    $get_child[$key]->active            = 'active';
                }
                else
                {
                    $sql_check_sub_child    = "SELECT B.view, B.create, B.edit, B.delete, A.menu_name, A.menu_icon, A.menu_route, A.menu_position, A.id as menu_id, A.is_active
                    FROM menus A
                    INNER JOIN menu_permissions B
                    ON B.menu_id = A.id
                    WHERE B.user_id = '".$user_id."' AND B.view='1' AND A.is_active = '1' AND A.parent_id ='".$child->menu_id."' AND A.menu_route='".$route_name."'
                    ORDER BY menu_position ASC";
                    $sub_child              = DB::select($sql_check_sub_child);
                    if (count($sub_child) > 0 )
                    {
                        $get_child['active_nav']            = 'menu-open';
                        $get_child['active_nav_1']          = 'active';
                        $get_child[$key]->active            = 'active';
                    }
                    else
                    {
                        $get_child[$key]->active        = '';
                    }
                }
            }
        }
        return $get_child;

    }
}
