<!DOCTYPE html>
<html>
<head>
    <title> mongo and php - lists DM, and CO and SV links for particular subject </title> 
</head>
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
    .comment {
        background-color: #FFA07A;
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
<body> 
    <pre>
        <form action="subject635449.php" method="post">
<?php


#----------
    if(isset($_REQUEST['subjectId'])){
        echo "<table border='1'>";
        echo "<th>Details of subject</th>";
        $id = 0 + $_REQUEST['subjectId'];
        $updated = null;
        $saved = null;
        if (isset($_REQUEST['updated'])) {
            $updated = $_REQUEST['updated'];
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $saved = 'true';
        }
        $m = new MongoDB\Driver\Manager("mongodb://localhost:27017" );
        $query = new MongoDB\Driver\Query( ['USUBJID' => $id ] );
        $rows = $m->executeQuery( "mu721466.DM", $query );
            foreach ($rows as $r) {
                echo "<tr>";
                    echo "<td align='center'><input type='hidden' value='$r->STUDYID'  name = 'study_id'>Study Id: $r->STUDYID </td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td align='center'><input type='hidden' value='$r->DOMAIN' name='domain'>Domain: $r->DOMAIN</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td align='center'><input type='hidden' value='$r->USUBJID' name='subject_id'>Subject Id: $r->USUBJID</td>";
                echo "</tr>";
                echo "<tr>";
                if ($updated == 'true')
                    echo "<td align='center'>Birth date: <input type='date' name='start'
                     value='$r->BRTHDTC' min='1990-01-01' max='2018-12-31' required = ''></td>";
                 else
                    echo "<td align='center'>Birth Date: $r->BRTHDTC</td>";
                echo "</tr>";
                echo "<tr>";
                if ($updated == 'true')
                    echo "<td align='center'>Sex: <select id = 'sex', name = 'sex' required = ''> <option value='m'>m</option>
                    <option value='f'>f</option> </select></td>";
                 else
                    echo "<td align='center'>Sex: $r->SEX</td>";
                echo "</tr>";
                echo "<tr>";
                if ($updated == 'true')
                    echo "<td align='center'>Race: <input type='text' name='race' id='race' required = ''></td>";
                 else
                    echo "<td align='center'>Race: $r->RACE</td>";
                echo "</tr>";
                echo "<tr>";
                if ($updated == 'true')
                    echo "<td align='center'>Country: <input type='text' name='country' id='country' required = ''></td>";
                 else
                    echo "<td align='center'>Country: $r->COUNTRY</td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td align='center' class='comment'><a href = 'comment352323.php?subjectId=$r->USUBJID'>Comments </a></td>";
                echo "</tr>";
                echo "<tr>";
                    echo "<td align='center'class='visit'><a href = 'visit578169.php?subjectId=$r->USUBJID'>Study Visits </a></td>";
                echo "</tr>";

            }
        echo "</table>\n";
        echo '<div class="my-class">';
        if (isset($_REQUEST['updated'])) {
                echo "<input type='submit' value='Save' name='submit' class='update'>";
                echo '<a href="study350108.php" class="update">Home</a>';
            } elseif (isset($_REQUEST['deleted'])) {
                echo '<a href="study350108.php" class="update">Home</a>';
            } 
            else {

                 echo "<a href = 'subject635449.php?subjectId=$r->USUBJID&updated=true' class='update'>Update </a>";
                 echo "<a href = 'subject635449.php?subjectId=$r->USUBJID&deleted=true' class='delete'>Delete </a>";
                 echo '<a href="study350108.php" class="update">Home</a>';
            }
         echo '</div>';
    }

    if (isset($_POST['submit'])) {
        #example of inserting a new subject
        $m = new MongoDB\Driver\Manager("mongodb://localhost:27017" );
        $bulk = new MongoDB\Driver\BulkWrite();
        $dob = new DateTime( $_POST['start']);
        $date_of_birth = $dob->format( DateTime::ATOM );
        $study_id = $_POST['study_id'] + 0;
        $domain = $_POST['domain'];
        $subject_id = $_POST['subject_id'] + 0;
        $sex = $_POST['sex'];
        $race = $_POST['race'];
        $country = $_POST['country'];

        $_id = $bulk->update( ['USUBJID' => $subject_id],
                             ['$set' => ['BRTHDTC' => $dob->format( DateTime::ATOM ), 'SEX' => $sex, 'RACE' => $race,
                             'COUNTRY' => $country, 'STUDYID' => $study_id, 'DOMAIN' => 'DM', 'USUBJID' => $subject_id]],
                             ['multi' => false, 'upsert' => false]);
        $result = $m->executeBulkWrite( 'mu721466.DM', $bulk );
        echo "<table border='1'>";
        echo "<th>Details of subject</th>";
            echo "<tr>";
                echo "<td align='center'>Study Id:$study_id </td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Domain: $domain</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Subject Id: $subject_id</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Birth Date: $date_of_birth</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Sex: $sex</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Race: $race</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center'>Country: $country</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center' class='comment'><a href = 'comment352323.php?subjectId=$subject_id'>Comments </a></td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td align='center' class='visit'><a href = 'visit578169.php?subjectId=$subject_id'>Study Visits </a></td>";
            echo "</tr>";
    echo "</table>\n";
    echo '<div class="my-class">';
    echo '<a href="study350108.php" class="update">Home</a>';
    echo '</div>';
    }

    if(isset($_REQUEST['deleted'])){
            $m = new MongoDB\Driver\Manager("mongodb://localhost:27017" );
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = $_REQUEST['subjectId'] + 0;
            $bulk->delete( ['USUBJID' =>  $usubjid],['limit' => 1] );

            $m = new MongoDB\Driver\Manager( "mongodb://localhost:27017" );
            $m->executeBulkWrite( "mu721466.DM", $bulk );

            $bulk1 = new MongoDB\Driver\BulkWrite();
            $bulk1->delete( ['USUBJID' =>  $usubjid] );
            $m->executeBulkWrite( "mu721466.CO", $bulk1 );

            $bulk2 = new MongoDB\Driver\BulkWrite();
            $bulk2->delete( ['USUBJID' =>  $usubjid] );
            $m->executeBulkWrite( "mu721466.SV", $bulk2 );

            echo "<h4>Subject ID: ".$usubjid." is deleted</h4>";
    }

?>
<div class="footer">
            <p>Copyright &copy; 2019 IAD Megha Ukkali</p>
        </div>
</form>
</pre> </body>

</html>
