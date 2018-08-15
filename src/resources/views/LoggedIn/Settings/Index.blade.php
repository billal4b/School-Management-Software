@extends('Layouts.Application')

@section('MainContent')


<?php
if (isset($ChildMenus)) {

    $TotalChildMenu = count($ChildMenus);
    $TotalRow = ceil($TotalChildMenu / 12) + 1;
    //echo $TotalRow;exit();
    $ChildMenuCount = 0;
    for ($i = 0; $i < $TotalRow; $i++) {
        ?>
        <div class="row">
            <?php
            for ($j = 0; $j < 6; $j++) {
                ?>
                <div class="col-md-2">

                    <?php
                    if (isset($ChildMenus[$ChildMenuCount]->menu_id)) {
                        ?>
                        <a class="btn btn-primary btn-block" href="{{ url('!/' . $ChildMenus[$ChildMenuCount]->url) }}"><i class="fa fa-{{ $ChildMenus[$ChildMenuCount]->icon }}"></i>&nbsp;{{ $ChildMenus[$ChildMenuCount]->menu_name }}</a>
                        <?php
                        $ChildMenuCount++;
                    } else {
                        echo '&nbsp;';
                    }
                    ?>

                </div>
                <?php
            }
            ?>
        </div>
        <div class="row">
            <div class="col-md-12">&nbsp;</div>
        </div>
        <?php
    }
}
?>

@endsection
