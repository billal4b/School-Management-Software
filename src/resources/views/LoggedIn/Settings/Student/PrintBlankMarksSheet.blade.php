<?php
//$TotalStudents = isset($Students) ? count($Students) : 0;
$TotalExamSubHeads = isset($ExamSubHeads) ? count($ExamSubHeads) : 0;
//echo '<pre>';
//        print_r($ActiveExamSubHeads);
//        echo '</pre>';
//        exit();
$GLOBALS['ApplicationRsrcPath'] = asset('rsrc') . '/';
$GLOBALS['ApplicationImgPath'] = $GLOBALS['ApplicationRsrcPath'] . 'multimedia/';
?>

<style type="text/css">
    table#mid {
        border-collapse: collapse;
    }

     table#mid, th#mid, td#mid {
        border: 1px solid black;
    }
    table#mid th{
/*        text-transform: uppercase;
        font-size: 12px;*/
    }
    span{
        font-family: Courier New,Courier,Lucida Sans Typewriter,Lucida Typewriter,monospace;
        text-transform: uppercase;
    }
</style>

<!--<div class="table-responsive">-->
<table width="100%" style="text-transform: uppercase; font-size: 12px; border: none">
    <tr>
        <!--<td style="border: none;" width="20">&nbsp;</td>-->
<!--        <td style="border: none; text-align: right" colspan="1"><img src="{{ $GLOBALS['ApplicationImgPath'] }}school_logo_1_b.png" width="50"></td>-->
        
        <td colspan="4" valign="middle" style="text-align: center; border: none; text-transform: uppercase; font-family: Arial Black,Arial Bold,Gadget,sans-serif;"><!--<img src="{{ $GLOBALS['ApplicationImgPath'] }}school_logo_1_b.png" width="40">--><strong>&nbsp;Mohammadpur Preparatory School & College</strong></td>
        <!--<td style="border: none;" width="5">&nbsp;</td>-->
    </tr>
    <tr>
        <td colspan="4" style="border: none; text-align: center; font-style: italic" width="100%">MARKS SHEET</td>
    </tr>
<!--    <tr style="border: none;">
        <td colspan="3">&nbsp;</td>
    </tr>-->
    <tr style="border: none;">
        <td width="5">CLASS: <span><?php echo $Students[0]->ClassName; ?><span></td>
        <td width="5">GROUP: <span><?php echo $Students[0]->GroupName; ?></span></td>
        <td width="30">SECTION: <span><?php echo $Students[0]->SectionName; ?></span></td>
        <td width="60">subject:</td>
    </tr>
    <tr style="border: none;">
        <td colspan="4">exam Type:</td>
    </tr>
    <tr style="border: none; padding-top: 10px">
        <td style="text-align: left;" colspan="2">Teacher ID: </td>
        <td style="text-align: left;" colspan="2">TEACHER NAME: </td>
    </tr>

</table>

<table style="font-size: 12px; width: 100%" id="mid" border="1">
    <thead>
        <tr>
            <!--<th>Sl.</th>-->
            <!--<th>StudentID</th>-->
            <th width="15">ROLL NO.</th>
            <th width="50">STUDENT ID</th>
            <th width="150">NAME</th>

            <?php
            if (isset($ExamSubHeads)) {
                foreach ($ExamSubHeads as $esh) {
                    echo '<th><span>' . $esh->exam_sub_head_alias . '</span></th>';
                }
            }
            ?>

            <th>TOTAL</th>
        </tr>
    </thead>

    <tbody>



        <?php
        if (isset($Students)) {
            foreach ($Students as $key => $value) {
                ?>

                <tr>
                    <!--<td><?php //echo $key + 1;                  ?></td>-->
                    <!--<td><?php //echo $value->system_generated_student_id;                  ?></td>-->
                    <td  height="20" style="text-align: center"><span><?php echo $value->roll_no; ?></span></td>
                    <td style="text-align: center"><span><?php echo $value->system_generated_student_id; ?></span></td>
                    <td style="text-transform: uppercase"><span><?php echo $value->student_name; ?></span></td>

                    <?php
                    if (isset($ExamSubHeads)) {
                        foreach ($ExamSubHeads as $key2 => $value2) {
                            ?>
                            <td>&nbsp;</td>
                            <?php
                        }
                    }
                    ?>
                    <td></td>      
                </tr>
                <?php
            }
            ?>

            <?php
        }
        ?>



    </tbody>


</table>
<!--</div>-->
