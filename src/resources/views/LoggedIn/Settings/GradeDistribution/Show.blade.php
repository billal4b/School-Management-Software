<?php
//echo '<pre>';
//print_r($ExamSubHeads);
//echo '</pre>';
//exit();
?>

<!--<div class="table-responsive">-->
<table class="table table-condensed table-striped table-bordered" style="" id="ShowGradeDistributionTbl">
    <thead>
        <tr>
            <!--<th>Sl.</th>-->
            <th>Marks Interval(%)</th>
            <th>GPA</th>
            <th>Grade</th>
            <th>Comment</th>
        </tr>
    </thead>

    <tbody>

        <?php
        if (isset($GradeDistributions)) {
            foreach ($GradeDistributions as $gd) {
                ?>

                <tr>
                    <td><?php echo $gd->marks_from . ' - ' . $gd->marks_to; ?></td>
                    <td><?php echo $gd->gpa; ?></td>
                    <td><?php echo $gd->grade; ?></td>
                    <td><?php echo $gd->comments; ?></td>
                </tr>
                <?php
            }
        }
        ?>

    </tbody>

</table>
<!--</div>-->
