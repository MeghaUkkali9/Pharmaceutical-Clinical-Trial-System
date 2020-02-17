<!DOCTYPE html>
<html>
    <head> 
        <title> mongo and php - lists all subjects and gives the link to add, delete all subjects in all collections  </title> 
    </head>
    <style type="text/css">

        h2 {
            text-align: center;
        }
        a {
            text-decoration-line: none;
        }
        .next, .prev {
            font-weight: bold;
            float: center;
        }
        .my-class {
            text-align: center;
        }
        .subject {
            text-decoration: none;
            width:10%;
            border-radius: 5px;
            background-color: #32CD32;
            display: inline-block;
            padding: 10px;
            margin: 8px;
        }
        table {
             margin-left: auto;
            margin-right: auto;
            width: 30%;
        }
        th {
            background-color: red;
            color: white;
            text-align: center;
        }

        th, td {
          text-align: center;
          padding: 8px;
        }

        .footer {
            text-align: center;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            line-height: 0.6em;
            background-color: black;
            color: white;
            display: inline-block;
        }
    </style>
    <body> 
        <pre>
            <h2>IAD Final Project</h2>


<?php
#----------below code is to display all the subjects from DM collections-----------------------
    $displaySizeLimit = 8;

    echo '<div class="my-class">';
    echo "<table border='1'>";
    echo "<th colspan=\"2\"> List of All Subjects</th>";

    $m = new MongoDB\Driver\Manager("mongodb://localhost:27017" );
    $query = new MongoDB\Driver\Query( [] );
    $rows = $m->executeQuery( "mu721466.DM", $query );
    
    $count = 0;
    if(isset($_GET['start']) && isset($_GET['end'])) {
        $start = $_GET['start'];
        $end = $_GET['end'];
        $next_end = $end;
        $prev_start = $start-$displaySizeLimit;
        $prev_end = $end-$displaySizeLimit;
        foreach ($rows as $r) {
            if ($count >= $start && $count < $end) {
                echo "<tr>";
                echo "<td>Subject ID: $r->USUBJID</td>";
                echo "<td align='center'><a href='subject635449.php?subjectId=$r->USUBJID'>Click here</a></td>";
                echo "</tr>";
                $start = $start + 1;
            }
            $count++;
        }
        echo "</table>\n";
        $end = $start + $displaySizeLimit;
        if ($_GET['start'] >= $displaySizeLimit && $_GET['start'] <= $count) {
            echo "<a href=study350108.php?start=$prev_start&end=$prev_end class='prev' ><< Prev</a> \n";
        }
        if ($count > $next_end) {
            echo "<a href=study350108.php?start=$start&end=$end class='next'>Next >></a>\n";
        }
    } else {
        $start = 0;
        $end = $displaySizeLimit;
        foreach ($rows as $r) {
            if ($count >= $start && $count < $end) {
                echo "<tr>";
                 echo "<td>Subject ID: $r->USUBJID</td>";
                echo "<td align='center'><a href='subject635449.php?subjectId=$r->USUBJID'>Click here</a></td>";
                echo "</tr>";
                $start = $count + 1;
            }
            $count++;
        }
        $end = $start + $displaySizeLimit;
        echo "</table>\n";
        if ($count > $displaySizeLimit) {
            echo "<a href=study350108.php?start=$start&end=$end class='next'>Next>></a>\n";
        }
    }
    echo "<a href=add.php class='subject'>Add New Subject</a>\n";
    echo "<a href=deleteF.php class='subject'>Delete all Subject</a>\n";
    echo "<a href=insertG.php class='subject'>Insert Subjects</a>\n";
    echo '</div>';
?>
        <div class="footer">
            <p>Copyright &copy; 2019 IAD Megha Ukkali</p>
        </div>
    </pre> 
</body>
</html>
