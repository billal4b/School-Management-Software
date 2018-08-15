
<style type="text/css">
    .div-inline{
        display: inline-block;
    }
</style>

<?php
$RolesID = [4, 11, 12, 13, 14, 15, 16, 17, 18, 19];
?>


<div class="row">
    <div class="col-md-12">

        @if(session()->has('parentMenuList'))

        @foreach(session('parentMenuList') as $pml)

        <?php
        if ($pml->menu_id == 42) {

            $CheckUserIsBranchAdmin = in_array(Auth::user()->role_id, $RolesID);
            $QueryForCheckIsClassTeacher = DB::table('tbl_class_teachers')
                    ->select('class_teacher_id')
                    ->where('user_id', Auth::user()->user_id)
                    ->first();
            $CheckIsClassTeacher = empty($QueryForCheckIsClassTeacher) ? 0 : 1;
            if ($CheckIsClassTeacher || $CheckUserIsBranchAdmin) {
                ?>
                <div class="btn-group div-inline">
                    <button class="btn btn-primary btn-sm dropdown-toggle  text-uppercase" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-{{ $pml->icon }}"></i>&nbsp;{{ $pml->menu_name }}
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <?php
                        $ChildMenus = display_children($pml->menu_id, 1);
                        ?>
                    </ul>
                </div>
                <?php
            }
        }else{
        ?>
        <div class="btn-group div-inline">
            <button class="btn btn-primary btn-sm dropdown-toggle  text-uppercase" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-{{ $pml->icon }}"></i>&nbsp;{{ $pml->menu_name }}
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php
                $ChildMenus = display_children($pml->menu_id, 1);
                ?>
            </ul>
        </div>
        <?php
        }
        ?>
        @endforeach

        @endif

    </div>
</div>

<?php

//print_r();
//exit();
//
//display_children(0, 1);

function display_children($parent, $level) {
    $result = DB::select("SELECT a.menu_id, a.menu_name, a.url, Deriv1.Count,a.icon FROM `tbl_gen_menus` a  LEFT OUTER JOIN (SELECT parent_menu_id,
     COUNT(*) AS Count FROM `tbl_gen_menus` GROUP BY parent_menu_id) Deriv1 ON a.menu_id = Deriv1.parent_menu_id WHERE a.parent_menu_id=" . $parent . ' and a.is_active=1');
    //echo '<pre>';print_r($result);echo '</pre>';exit();
//    print_r($result);
//    exit();
//    echo "<ul>";
    foreach ($result as $rs) {
        if ($rs->Count > 0) {
            echo "<li><a href='" . url('!') . '/' . $rs->url . "'><i class=\"fa fa-" . $rs->icon . "\"></i>&nbsp;" . $rs->menu_name . "</a>";
            display_children($rs->menu_id, $level + 1);
            echo "</li>";
        } elseif ($rs->Count == 0) {
            echo "<li><a href='" . url('!') . '/' . $rs->url . "'><i class=\"fa fa-" . $rs->icon . "\"></i>&nbsp;" . $rs->menu_name . "</a></li>";
        } 
            else;
    }
//    echo "</ul>";
}
