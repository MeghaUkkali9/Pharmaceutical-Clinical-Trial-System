<!DOCTYPE html>
<html>
<head> <title> mongo and php - add into DM </title> </head>
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

        .update, .delete {
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
<body> <pre>
<form action="add.php" method="post">

<?php

            echo "<table border='1'>";
            $m = new MongoDB\Driver\Manager("mongodb://localhost:27017" );
            $usubjid = getDM( $m );
            $studyid = getStudyId($m);
            echo "<th>Add new subject id: $usubjid</th>";
            echo "<tr>";
                echo "<td align='center'>Study Id: $studyid </td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Domain: DM</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Subject Id: $usubjid</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Birth date: <input type='date' value='dob'
                name='start' value='1994-07-22' min='1990-01-01' max='2018-12-31' required = ''></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Sex: <select id = 'sex' name = 'sex' required = ''> <option value='m'>m</option>
                <option value='f'>f</option> </select></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Race: <textarea name='race' id='race' cols='15' rows='1' required = ''></textarea></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Country: <textarea name='country' id='country' cols='15' rows='1' required = ''></textarea></td>";
            echo "</tr>";
            echo "</table>\n";
            echo '<div class="my-class">';
            echo "<input type='submit' value='Add' class='update' name='submit'>";
            echo "<a href='study350108.php' class='update'>Home</a>";
            echo '</div>';
        

         function getDM ( $m ) {
            $query = new MongoDB\Driver\Query( [],[ 'sort' => ['USUBJID' => -1], 'limit' => 1 ] );
            $rows  = $m->executeQuery( "mu721466.DM", $query );
            $usubjid = 0;
            foreach ($rows as $r) {
                $usubjid = $r->USUBJID;
            }
            return $usubjid + 1;
        }

        function getStudyId ( $m ) {
                $query = new MongoDB\Driver\Query( [],[ 'sort' => ['STUDYID' => -1], 'limit' => 1 ] );
                $rows  = $m->executeQuery( "mu721466.DM", $query );
                foreach ($rows as $r) {
                    return $r->STUDYID;
                }
            }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            #----------
            $m = new MongoDB\Driver\Manager("mongodb://localhost:27017" );

            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $studyid = getStudyId($m);
            $dob = new DateTime( $_POST['start']);
            $sex = $_POST['sex'];
            $race = $_POST['race'];
            $country = $_POST['country'];

            $_id = $bulk->insert([
                                'STUDYID'  => $studyid,
                                'DOMAIN'   => 'DM',
                                'USUBJID'  => $usubjid,
                                'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                                'SEX'      => $sex,
                                'RACE'     => $race,
                                'COUNTRY'  => $country
                                ]);

            $result = $m->executeBulkWrite( 'mu721466.DM', $bulk );
            echo "<h4>Successfully added the Subject ID: ".$usubjid.".</h4>";
            
    }
    ?>
    <div class="footer">
            <p>Copyright &copy; 2019 IAD Megha Ukkali</p>
        </div>
</form>
</pre> </body>

</html>
