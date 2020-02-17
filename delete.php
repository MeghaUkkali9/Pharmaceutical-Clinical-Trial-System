<!DOCTYPE html>
<html>
<head>
 	<title> mongo and php - deletes all documants in all comments </title>
 	<style type="text/css">
 		h2{
 			text-align: center;
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
		 .subject {
                text-decoration: none;
                text-align: center;
                width:5%;
                border-radius: 5px;
                background-color: #32CD32;
                display: inline-block;
                padding: 10px;
                margin: 8px;
                margin-left: 45%;
                    }
 		}
	</style>
</head>
<body> 
	<pre>


		<?php
				
			$bulk = new MongoDB\Driver\BulkWrite();
			$bulk->delete( [] );

			$m = new MongoDB\Driver\Manager( "mongodb://localhost:27017" );

			$m->executeBulkWrite( "mu721466.DM", $bulk );

			$bulk1 = new MongoDB\Driver\BulkWrite();
			$bulk1->delete( [] );
			$m->executeBulkWrite( "mu721466.CO", $bulk1 );

			$bulk2 = new MongoDB\Driver\BulkWrite();
			$bulk2->delete( [] );
			$m->executeBulkWrite( "mu721466.SV", $bulk2 );

		    echo "<h2>ALL documents are deleted in all collections.</h2>";
		    echo "<a href=study350108.php class='subject'>Home</a>\n";

	    ?>
	    <div class="footer">
            <p>Copyright &copy; 2019 IAD Megha Ukkali</p>
        </div>

</pre> </body>
</html>

