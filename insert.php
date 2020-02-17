<!DOCTYPE html>
<html>
<head>
 <title> mongo and php - insert into DM, CO, and SV </title> 
  <style type="text/css">    
             table {
            margin-left: auto;
            margin-right: auto;
            margin-top: 0;
            margin-bottom: 50px;
            border-radius: 5px;
            display:inline-block;
            width: 100%;
            }
            th {
                background-color: red;
                color: white;
                text-align: center;
            }

            th, tr {
              text-align: center;
              padding: 8px;
            }
            a {
                text-align: center;
            }
            .table1 {
                float: left;
                margin-right: 5%;
                margin-left: 5%;
            }
            .table2 {
                float: left;
                margin-right: 5%;
                margin-left: 5%;
            }
            .table3 {
                float: left;
                margin-right: 5%;
                margin-left: 5%;
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
                    }
            .rowblank{
                background-color: #b7bfc8;
            }
            .my-class {
                text-align: center;
                float: center;
            }
            a {
            text-decoration-line: none;
            }

            .insert, .button {
                text-decoration: none;
            width:10%;
            border-radius: 5px;
            background-color: #32CD32;
            display: inline-block;
            padding: 10px;
            margin: 8px;  
            float: center;
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
<body> <pre>
   
<form action="" method="post">
<?php
    #this function returns the next sequential subject id (USUBJID)
            function getDM ( $m ) {
                $query = new MongoDB\Driver\Query( [] );
                $rows  = $m->executeQuery( "mu721466.DM", $query );
                $count = 0;
                foreach ($rows as $r) {
                    ++$count;
                }
                return $count + 1;
            }
            #----------
            #this function returns the next sequential comment number (COSEQ)
            # for a given subject (USUBJID)
            function getCO ( $m, $usubjid ) {
                $query = new MongoDB\Driver\Query( ['USUBJID' => $usubjid ] );
                $rows  = $m->executeQuery( "mu721466.CO", $query );
                $count = 0;
                foreach ($rows as $r) {
                    ++$count;
                }
                return $count + 1;
            }
            #----------
            #this function returns the next sequential visit number (VISITNUM)
            # for a given subject (USUBJID)
            function getSV ( $m, $usubjid ) {
                $query = new MongoDB\Driver\Query( ['USUBJID' => $usubjid ] );
                $rows  = $m->executeQuery( "mu721466.SV", $query );
                $count = 0;
                foreach ($rows as $r) {
                    ++$count;
                }
                return $count + 1;
            }
            #----------
            $m = new MongoDB\Driver\Manager(
                         "mongodb://localhost:27017" );

            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'f',
                            'RACE'         => 'Menominee',
                            'COUNTRY' => 'Philippines' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'it is allergy',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'this is not good',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for MRI study',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for Scanning ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

        #--------------------------------------------*********** 1 ***********--------------------------------------

            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'm',
                            'RACE'         => 'caucasian',
                            'COUNTRY' => 'USA' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'it is good but few side effects',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'few side effects but this drug can cure diabetes',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for x-ray study',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for normal check up ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            #--------------------------------------------*********** 2 ***********--------------------------------------

            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'f',
                            'RACE'         => 'Kiowa',
                            'COUNTRY' => 'Costa Rica' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'this is not good',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'this is headache',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for MRI study',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for x-ray ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 3 ***********--------------------------------------


            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'm',
                            'RACE'         => 'Apache',
                            'COUNTRY' => 'France' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'few effects',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'few side effects',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for Scanning study',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for dental checkup ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 4 ***********--------------------------------------

            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'f',
                            'RACE'         => 'Taiwanese',
                            'COUNTRY' => 'USA' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'this drug is curable LBP',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'this is drug not good',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for MRI study',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for Scanning ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 5 ***********--------------------------------------


            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'f',
                            'RACE'         => 'Vietnamese',
                            'COUNTRY' => 'Vietnam' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'this is good',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'this is allergetic',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for x-ray study',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for Scanning ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 6 ***********--------------------------------------


            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'm',
                            'RACE'         => 'Yaqui',
                            'COUNTRY' => 'Kosovo' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'good but not often',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'not good',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for normal check up',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for dental check up ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 7 ***********--------------------------------------

            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'm',
                            'RACE'         => 'Cambodian',
                            'COUNTRY' => 'Jordan' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'this is not good for low BP',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'this is good for Diabetes',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for Diabetes',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for low blood pressure ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 8 ***********--------------------------------------

            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'f',
                            'RACE'         => 'Tongan',
                            'COUNTRY' => 'China' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'this is my comment',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'this is another comment',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for high blood pressure',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for eye check up ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 9 ***********--------------------------------------


            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'f',
                            'RACE'         => 'Lumbee',
                            'COUNTRY' => 'Bosnia and Herzegovina' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'this drug got few side effects',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'this drug got few side effects',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for MRI study',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for low blood check ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 10 ***********--------------------------------------


            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'f',
                            'RACE'         => 'Ottawa',
                            'COUNTRY' => 'USA' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'this is not good',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'this is allergetic',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for MRI study',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for Scanning ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 11 ***********--------------------------------------


            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'm',
                            'RACE'         => 'Navajo',
                            'COUNTRY' => 'Poland' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'this is my comment',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'this is another comment',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for LBP study',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for HBP study ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 12 ***********--------------------------------------


            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'f',
                            'RACE'         => 'Dravidian',
                            'COUNTRY' => 'India' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'this has been a good drug',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'this is good ',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for MRI study',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for Diabetes study ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 13 ***********--------------------------------------


            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'm',
                            'RACE'         => 'Hoysalas',
                            'COUNTRY' => 'India' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'This drug not worked',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'drug will be allergetic to some people',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for LBP study',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for Scanning ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 14 ***********--------------------------------------

            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'm',
                            'RACE'         => 'Ashokas',
                            'COUNTRY' => 'India' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'this is bad',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'this is not good ',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for HBP study',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for Scanning ',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

#--------------------------------------------*********** 15 ***********--------------------------------------


            #example of inserting a new subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $usubjid = getDM( $m );
            $dob = new DateTime( '2010-12-30 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'   => 'DM',
                            'USUBJID'    => $usubjid,
                            'BRTHDTC'  => $dob->format( DateTime::ATOM ),  # ISO8601
                            'SEX'            => 'f',
                            'RACE'         => 'Choctaw',
                            'COUNTRY' => 'Korea' ]);
            $m->executeBulkWrite( 'mu721466.DM', $bulk );


            #example of inserting a new comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2010-12-31 22:21:46' );
            $bulk->insert([
                            'STUDYID'  => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'     => $coseq,
                            'COVAL'     => 'this is my Mri comment',
                            'CODTC'     => $now->format( DateTime::ATOM ) ]);
            $m->executeBulkWrite( 'mu721466.CO', $bulk );

            #insert another comment for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $coseq = getCO( $m, $usubjid );
            $now = new DateTime( '2011-01-23 23:21:46' );
            $bulk->insert([
                            'STUDYID'   => '12700',
                            'DOMAIN'  => 'CO',
                            'USUBJID'   => $usubjid,
                            'COSEQ'      => $coseq,
                            'COVAL'      => 'this is scanning comment',
                            'CODTC'      => $now->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.CO', $bulk );
            #example of inserting subject visit for the above subject
            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for scanning',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

            $bulk = new MongoDB\Driver\BulkWrite();
            $visitnum = getSV( $m, $usubjid );
            $now     = new DateTime( date("Y-m-d H:i:s") );
            $svendtc = new DateTime( date("Y-m-d H:i:s") );
            $svendtc->modify( '+1 hour' );
            $bulk->insert([
                            'STUDYID'    => '12700',
                            'DOMAIN'   => 'SV',
                            'USUBJID'    => $usubjid,
                            'VISITNUM' => $visitnum,
                            'VISIT'          => 'visit for routine checkup',
                            'SVSTDTC'   => $now->format( DateTime::ATOM ),
                            'SVENDTC'  => $svendtc->format( DateTime::ATOM ) ]);
           
            $m->executeBulkWrite( 'mu721466.SV', $bulk );

   #--------------------------------------------*********** 16 ***********--------------------------------------
            echo "<a href=study350108.php class='subject'>Home</a>\n";
            echo "<div class = 'table1'>";
            echo "<table border ='1'>";
                
                $m = new MongoDB\Driver\Manager("mongodb://localhost:27017" );
                            
                $query = new MongoDB\Driver\Query( [] );
                $rows1 = $m->executeQuery( "mu721466.DM", $query );

                 echo "<th> Demographics of All Subjects </th>";

                foreach($rows1 as $r){
                
                echo "<tr>";
                echo "<td align = 'center'><b>USUBJID: $r->USUBJID</b></td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td align = 'center'>STUDYID: $r->STUDYID</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td align = 'center'>DOMAIN: $r->DOMAIN</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td align = 'center'>BRTHDTC: $r->BRTHDTC</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td align = 'center'>SEX: $r->SEX</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td align = 'center'>RACE: $r->RACE</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td align = 'center'>COUNTRY: $r->COUNTRY</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td align = 'center' class='rowblank'>***********</td>";
                echo "</tr>";

                
            }     
            echo "</table>";
            echo "</div>";


            echo "<div class = 'table2'>";
            echo "<table border ='1'>";
            $query1 = new MongoDB\Driver\Query( [] );
            $rows2 = $m->executeQuery( "mu721466.CO", $query1 );
            echo "<th> Comments of All Subjects </th>";  
            foreach($rows2 as $r){

                echo "<tr>";
                echo "<td align = 'center'><b>USUBJID: $r->USUBJID</b></td>";
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
            }  
        
            echo "</table>";
            echo "</div>";



            echo "<div class = 'table3'>";
            echo "<table border ='1'>";
            $query2 = new MongoDB\Driver\Query( [] );
            $rows3 = $m->executeQuery( "mu721466.SV", $query2 );
            echo "<th> Study visits of All Subjects </th>";  
            foreach($rows3 as $r){

                echo "<tr>";
                        echo "<td align = 'center'><b>USUBJID: $r->USUBJID</b></td>";
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
            }  
        
            echo "</table>";
            echo "</div>";

                    
            
        ?>
        </form>
        <div class="footer">
            <p>Copyright &copy; 2019 IAD Megha Ukkali</p>
        </div>
</pre> </body>
</html>
