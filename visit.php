<!DOCTYPE html>
<html>
    <head>
        <title> mongo and php - lists visit of one subject and insert visit  into SV </title>
        <style type="text/css">
            a {
            text-decoration-line: none;
        }
        h4{
            text-align: center;
        }
        .my-class {
            text-align: center;
        }
        .rowblank{
                background-color: #b7bfc8;
            }
        .next, .prev {
            font-weight: bold;
            float: center;
        }
        .add, .home {
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
        .comment {
            font-size: 1.3em;
            font-weight: bold;           
        }
        .visit {
            background-color: #F08080; 
            font-size: 1.3em;
            font-weight: bold;
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
    </head>

    <body>
         <pre>
<form action="" method="get">
    <div class="my-class">
           <?php
            $listLimit = 1;
            $count = 0;
            if (isset($_GET[ 'subjectId' ])) {
                $usubjid =  $_GET[ 'subjectId' ] + 0;
            }

            if(isset($_GET["id"])) {

                        $usubjid = $_GET["id"] + 0;
                        
                        function getSV ( $m, $usubjid ) {
                            $query = new MongoDB\Driver\Query( ['USUBJID' => $usubjid ] );
                            $rows  = $m->executeQuery( "mu721466.SV", $query );
                            $count = 0;
                            foreach ($rows as $r) {
                                ++$count;
                            }
                            return $count + 1;
                        }

                      #example of inserting a new comment for the above subject
                       
                        $m = new MongoDB\Driver\Manager( "mongodb://localhost:27017" );

                        $bulk = new MongoDB\Driver\BulkWrite();

                        $visitnum = getSV( $m, $usubjid );
                        $visit = $_GET['visit'];
                        $now     = new DateTime( date("Y-m-d H:i:s") );
                        $svendtc = new DateTime( date("Y-m-d H:i:s") );
                        $svendtc->modify( '+1 hour' );
                        $_id = $bulk->insert([
                             'STUDYID'   => '12700',
                            'DOMAIN'    => 'SV',
                            'USUBJID'   => $usubjid,
                            'VISITNUM'  => $visitnum,
                            'VISIT'     => $visit,
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'   => $svendtc->format( DateTime::ATOM ) ]);
                        $result = $m->executeBulkWrite( 'mu721466.SV', $bulk );
                        echo "<h3>Successfully added the new visit.</h3>";

        
            } 

                echo "<table border ='1'>";
                
                $m = new MongoDB\Driver\Manager("mongodb://localhost:27017" );

                if (isset($_GET[ 'subjectId' ])) {
                    $usubjid =  $_GET[ 'subjectId' ] + 0;
                } 
                $filter = ['USUBJID' =>  $usubjid];
                $options = [
                             'projection' => ['_id' => 0],
                ];
                $query = new MongoDB\Driver\Query( $filter, $options );
                $rows = $m->executeQuery( "mu721466.SV", $query );

                echo "<th> Visits of SubjectID: ".$usubjid."</th>";
                

                if(isset($_GET['start']) && isset($_GET['end'])) {
                    $start = $_GET['start'];
                    $end = $_GET['end'];
                    $next_end = $end;
                    $prev_start = $start-$listLimit;
                    $prev_end = $end-$listLimit;
                    foreach ($rows as $r) {
                        if ($count >= $start && $count < $end) {
                            echo "<tr>";
                            echo "<td align = 'center'>USUBJID: $r->USUBJID</td>\n";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>STUDYID: $r->STUDYID</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>DOMAIN: $r->DOMAIN</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>VISITNUM: $r->VISITNUM</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>VISIT: $r->VISIT</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>SVSTDTC: $r->SVSTDTC</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>SVENDTC: $r->SVENDTC</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center' class='rowblank'>***********</td>";
                            echo "</tr>";
                            $start = $start + 1;
                        }
                        $count++;
                    }
                    echo "</table>\n";
                    $end = $start + $listLimit;
                    if ($_GET['start'] >= $listLimit && $_GET['start'] <= $count) {
                        echo "<a href=visit578169.php?subjectId=$usubjid&start=$prev_start&end=$prev_end class='prev' ><< Prev</a> \n";
                    }
                    if ($count > $next_end) {
                        echo "<a href=visit578169.php?subjectId=$usubjid&start=$start&end=$end class='next'>Next >></a>\n";
                    }
                } else {
                    $start = 0;
                    $end = $listLimit;
                    foreach ($rows as $r) {
                        if ($count >= $start && $count < $end) {
                            echo "<tr>";
                            echo "<td align = 'center'>USUBJID: $r->USUBJID</td>\n";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>STUDYID: $r->STUDYID</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>DOMAIN: $r->DOMAIN</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>VISITNUM: $r->VISITNUM</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>VISIT: $r->VISIT</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>SVSTDTC: $r->SVSTDTC</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>SVENDTC: $r->SVENDTC</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center' class='rowblank'>***********</td>";
                            echo "</tr>";
                            
                            $start = $count + 1;
                        }
                        $count++;
                    }
                    $end = $start + $listLimit;
                    echo "</table>\n";
                    if ($count > $listLimit) {
                        echo "<a href='visit578169.php?subjectId=$usubjid&start=$start&end=$end' class='next'>Next>></a>\n";
                    }
                }
                echo "<div class='comment'>";
                echo '<br>';
                echo "<label for='comment'><b>Add New Visits: <b></label>";

                echo '<input type="text" id = "visit" name="visit" placeholder="Enter visit" required="">';
                echo '</div>'; 
                echo '<input type="submit" class="add" value="Add" name="submit">';
                echo '<a href="study350108.php" class="home">Home</a>';
            
            ?>
                    <input type="hidden" name="id" value="<?php echo $usubjid; ?>"/>
                    
                </div>
            </form>
            <div class="footer">
                <p>Copyright &copy; 2019 IAD Megha Ukkali</p>
            </div>
    </pre>
 </body>
</html>


