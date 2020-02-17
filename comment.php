<!DOCTYPE html>
<html>
    <head>
        <title> mongo and php - Lists the comments and add comment into CO </title>
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
        .next, .prev {
            font-weight: bold;
            float: center;
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
                            function getCO ( $m, $usubjid ) {
                                    $query = new MongoDB\Driver\Query( ['USUBJID' => $usubjid ] );
                                    $rows  = $m->executeQuery( "mu721466.CO", $query );
                                    $count = 0;
                                    foreach ($rows as $r) {
                                        ++$count;
                                    }
                                    return $count + 1;
                            }

                            #example of inserting a new comment for the above subject
                            $m = new MongoDB\Driver\Manager( "mongodb://localhost:27017" );
                            $bulk = new MongoDB\Driver\BulkWrite();
                            $coseq = getCO( $m, $usubjid );
                            $coval = $_GET['coval'];
                            $now = new DateTime( date("Y-m-d H:i:s") );
                            $_id = $bulk->insert([
                                'STUDYID'  => '12700',
                                'DOMAIN'  => 'CO',
                                'USUBJID'   => $usubjid,
                                'COSEQ'     => $coseq,
                                'COVAL'     => $coval,
                                'CODTC'     => $now->format( DateTime::ATOM ) ]);
                            $result = $m->executeBulkWrite( 'mu721466.CO', $bulk );
                            echo "<h3>Successfully added the new comment.<h3>";
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
                $rows = $m->executeQuery( "mu721466.CO", $query );

                echo "<th> Comments of SubjectID: ".$usubjid."</th>";
                

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
                            echo "<td align = 'center'>COSEQ: $r->COSEQ</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>COVAL: $r->COVAL</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>CODTC: $r->CODTC</td>";
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
                        echo "<a href='comment352323.php?subjectId=$usubjid&start=$prev_start&end=$prev_end' class='prev' ><< Prev</a> \n";
                    }
                    if ($count > $next_end) {
                        echo "<a href='comment352323.php?subjectId=$usubjid&start=$start&end=$end' class='next'>Next >></a>\n";
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
                            echo "<td align = 'center'>COSEQ: $r->COSEQ</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>COVAL: $r->COVAL</td>";
                            echo "</tr>";

                            echo "<tr>";
                            echo "<td align = 'center'>CODTC: $r->CODTC</td>";
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
                        echo "<a href='comment352323.php?subjectId=$usubjid&start=$start&end=$end' class='next'>Next>></a>\n";
                    }
                }

                echo "<div class='comment'>";
                echo '<br>';
                echo "<label for='comment'><b>Add New Comment: <b></label>";

                echo '<input type="text" id = "coval" name="coval" placeholder="Enter comment" required="">';
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


