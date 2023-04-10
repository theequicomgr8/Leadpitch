<?php

date_default_timezone_set('Asia/Calcutta'); 

	if( !isset($_POST['export']) ) {

		die("You can't access it directly");

	}

	 



	header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0'); 

	header('Pragma: no-cache'); 

	header('Content-Type: application/x-msexcel; format=attachment;'); 

	header('Content-Disposition: attachment; filename=Fees_File_'.date('d-m-Y H:i:s').'.xls'); 

	 

	if( isset($_POST['export']) && $_POST['export'] == 'export' ) {

        

        $filterCourse = $_POST['excel-course'];

        $filterDateFrom = $_POST['excel-date_from'];

        $filterDateTo = $_POST['excel-date_to'];

        $filterTrainer = $_POST['excel-trainer'];

        $filterCounsellor = $_POST['excel-counsellor'];



    if( $filterCourse != 'all' && $filterDateFrom != '' && $filterDateTo != '' && $filterTrainer != 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer GROUP BY payment_mode";
    }

    else if( $filterCourse != 'all' && $filterDateFrom != '' && $filterDateTo != '' && $filterTrainer != 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer";         

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer AND GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer GROUP BY payment_mode";
    }

    else if( $filterCourse != 'all' && $filterDateFrom != '' && $filterDateTo != '' && $filterTrainer == 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\")";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\")";    

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") GROUP BY payment_mode";
    }

    else if( $filterCourse != 'all' && $filterDateFrom != '' && $filterDateTo != '' && $filterTrainer == 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\")";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\")";    

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") GROUP BY payment_mode";
    }

    else if( $filterCourse != 'all' && $filterDateFrom != '' && $filterDateTo == '' && $filterTrainer != 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";   

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";
    }

    else if( $filterCourse != 'all' && $filterDateFrom != '' && $filterDateTo == '' && $filterTrainer != 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";     

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode"; 
    }

    else if( $filterCourse != 'all' && $filterDateFrom != '' && $filterDateTo == '' && $filterTrainer == 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now())";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now())";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";      
    }

    else if( $filterCourse != 'all' && $filterDateFrom != '' && $filterDateTo == '' && $filterTrainer == 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now())";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now())";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";      
    }

    else if( $filterCourse != 'all' && $filterDateFrom == '' && $filterDateTo != '' && $filterTrainer != 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";      
    }

    else if( $filterCourse != 'all' && $filterDateFrom == '' && $filterDateTo != '' && $filterTrainer != 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";      
    }

    else if( $filterCourse != 'all' && $filterDateFrom == '' && $filterDateTo != '' && $filterTrainer == 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now())";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now())";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";      
    }

    else if( $filterCourse != 'all' && $filterDateFrom == '' && $filterDateTo != '' && $filterTrainer == 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now())";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now())";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") GROUP BY f.payment_mode AND s.duplicate=0 AND s.deleted=0";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";      
    }

    else if( $filterCourse != 'all' && $filterDateFrom == '' && $filterDateTo == '' && $filterTrainer != 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE owner=$filterTrainer GROUP BY payment_mode";      
    }

    else if( $filterCourse != 'all' && $filterDateFrom == '' && $filterDateTo == '' && $filterTrainer != 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE owner=$filterTrainer GROUP BY payment_mode";      
    }

    else if( $filterCourse != 'all' && $filterDateFrom == '' && $filterDateTo == '' && $filterTrainer == 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details GROUP BY payment_mode";      
    }

    else if( $filterCourse != 'all' && $filterDateFrom == '' && $filterDateTo == '' && $filterTrainer == 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.courses=$filterCourse AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom != '' && $filterDateTo != '' && $filterTrainer != 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom != '' && $filterDateTo != '' && $filterTrainer != 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") AND owner=$filterTrainer GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom != '' && $filterDateTo != '' && $filterTrainer == 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\")";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\")";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom != '' && $filterDateTo != '' && $filterTrainer == 'all' && $filterCounsellor == 'all' ) {

 
      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\")";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\")";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(\"$filterDateTo\") GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom != '' && $filterDateTo == '' && $filterTrainer != 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom != '' && $filterDateTo == '' && $filterTrainer != 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom != '' && $filterDateTo == '' && $filterTrainer == 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now())";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now())";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom != '' && $filterDateTo == '' && $filterTrainer == 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now())";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now())";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) >= DATE(\"$filterDateFrom\") AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) >= DATE(\"$filterDateFrom\") AND DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom == '' && $filterDateTo != '' && $filterTrainer != 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom == '' && $filterDateTo != '' && $filterTrainer != 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now()) AND owner=$filterTrainer GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom == '' && $filterDateTo != '' && $filterTrainer == 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now())";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now())";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom == '' && $filterDateTo != '' && $filterTrainer == 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now())";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now())";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on) <= DATE(\"$filterDateTo\") AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE DATE(paid_on) <= DATE(now()) GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom == '' && $filterDateTo == '' && $filterTrainer != 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.trainer=$filterTrainer AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE owner=$filterTrainer GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom == '' && $filterDateTo == '' && $filterTrainer != 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details WHERE owner=$filterTrainer";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details WHERE owner=$filterTrainer";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.trainer=$filterTrainer AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details WHERE owner=$filterTrainer GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details WHERE owner=$filterTrainer GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom == '' && $filterDateTo == '' && $filterTrainer == 'all' && $filterCounsellor != 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND s.counsellor LIKE '%".$filterCounsellor."%' AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details GROUP BY payment_mode";      
    }

    else if( $filterCourse == 'all' && $filterDateFrom == '' && $filterDateTo == '' && $filterTrainer == 'all' && $filterCounsellor == 'all' ) {



      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on)=DATE(now()) AND s.duplicate=0 AND s.deleted=0";
      $addPayQueryString = "SELECT * FROM ".$wpdb->prefix."addpayments_details";
      $expenseQueryString = "SELECT * FROM ".$wpdb->prefix."expenses_details";

      $totalQueryString = "SELECT SUM(f.paid_amt) total,SUM(f.service_tax) st,f.payment_mode FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id FROM wp_fees_details) f ON s.id=f.s_id AND DATE(f.paid_on)=DATE(now()) AND s.duplicate=0 AND s.deleted=0 GROUP BY f.payment_mode";
      $totalAddPayQueryString = "SELECT SUM(paid_amt) total,SUM(service_tax) st,payment_mode FROM ".$wpdb->prefix."addpayments_details GROUP BY payment_mode";
      $totalExpenseQueryString = "SELECT SUM(paid_amt) total,payment_mode FROM ".$wpdb->prefix."expenses_details GROUP BY payment_mode";
    }     

  }

    else if( isset($_POST['export']) && $_POST['export'] == 'export_by_receipt' ) {

      global $wpdb;

      $receiptNo = $_POST['excel-receipt_no'];

      $queryString = "SELECT s.*,f.* FROM wp_students_details s INNER JOIN (SELECT id as transaction_id,fees_invoice,s_id,due_date,due_amt,paid_on,paid_amt,service_tax,payment_mode,payment_bank,chq_card_no,balance,remark,collector_id,source FROM wp_fees_details) f ON s.id=f.s_id AND s.duplicate=0 AND s.deleted=0 AND f.fees_invoice=$receiptNo ";

    }    



?>

    <table border="1" cellspacing="10" cellpadding="2" style="font-family:Garmond;font-size:9pt;">

    <thead>

      <tr>

        <th style="color:#fff;background-color:#333333;">Serial</th>

        <th style="color:#fff;background-color:#333333;">Receipt No</th>

        <th style="color:#fff;background-color:#333333;">Paid On</th>

        <th style="color:#fff;background-color:#333333;">Student ID</th>

        <th style="color:#fff;background-color:#333333;">Name</th>
         <th style="color:#fff;background-color:#333333;">Type</th>
        <th style="color:#fff;background-color:#333333;">Total Fees </th>

        <!--<th style="color:#fff;background-color:#333333;">Mobile</th>

        <th style="color:#fff;background-color:#333333;">Email</th>-->

        <th style="color:#fff;background-color:#333333;">Amt. Paid</th>
        <th style="color:#fff;background-color:#333333;">GST Tax</th>
        <th style="color:#fff;background-color:#333333;">Total Amt</th>
        <th style="color:#fff;background-color:#333333;">Mode</th>

        <th style="color:#fff;background-color:#333333;">Sub Mode</th>

        <th style="color:#fff;background-color:#333333;">Course</th>

        <th style="color:#fff;background-color:#333333;">Trainer</th>
        <th style="color:#fff;background-color:#333333;">Counsellor</th>
        <th style="color:#fff;background-color:#333333;">Collector</th>
        
        <th style="color:#fff;background-color:#333333;">Remark</th>

      </tr>

    </thead>

    <tbody>

    <?php

      global $wpdb;	

      $results = $wpdb->get_results( $queryString );
      $add_pay_results = $wpdb->get_results( $addPayQueryString );
      $expense_results = $wpdb->get_results( $expenseQueryString );

      $total_results = $wpdb->get_results( $totalQueryString );
      $total_add_pay_results = $wpdb->get_results( $totalAddPayQueryString );
      $total_expense_results = $wpdb->get_results( $totalExpenseQueryString );    

      if( count($results) > 0 || count($add_pay_results) > 0 || count($expense_results) > 0 ){



          $count = 0;

          foreach( $results as $result ) {



            ++$count;

            ?>

            <tr>

              <td style="text-align:center;"><?php echo $count; ?></td>

              <td style="text-align:center;"><?php echo $result->fees_invoice; ?></td>

              <td style="text-align:center;">

                <?php

                  $date = date_create($result->paid_on);

                  echo date_format($date, "d-M-Y"); ?>

              </td>

              <td style="text-align:center;"><?php echo $result->stud_id; ?></td>

              <td style="text-align:left;"><?php echo $result->name; echo empty($result->mode_of_payment) ? "" : ($result->mode_of_payment == 'digital' ? " <strong>[D]</strong>" : " <strong>[C]</strong>");echo ((isset($result->gst_status) && $result->gst_status=='Yes')?" <strong>[G]</strong>":"")?></td>

             <!-- <td style="text-align:center;"><?php echo $result->phone; ?></td>

              <td style="text-align:left;"><?php echo $result->email; ?></td>-->
        	    <td style="text-align:center;"><?php echo str_replace('()','',str_replace($result->stud_id,'',$result->source)); ?></td>
              <td style="text-align:right;"><?php echo number_format($result->to_be_paid); ?></td>
              <td style="text-align:right;"><?php echo number_format($result->paid_amt-$result->service_tax); ?></td>
              <td style="text-align:right;"><?php echo number_format($result->service_tax); ?></td>
                <td style="text-align:right;"><?php echo number_format($result->paid_amt); ?></td>
               <td style="text-align:center;"><?php if($result->payment_mode=='edc-1520599748' || $result->payment_mode=='edc'){   echo "EDC"; }else if($result->payment_mode=='website-1520598979'){ echo "Website"; }else if($result->payment_mode=='paytmqr-1527937037'){ echo "PaytmQr"; }else if($result->payment_mode=='tez-1532604466'){ echo "GooglePay"; }else{ echo ucfirst($result->payment_mode); } ?> </td>

              <td style="text-align:center;">
                  <?php
                    if( $result->payment_mode == 'cash' || $result->payment_mode == 'online' ){
                      echo "NA";
                    }else if( $result->payment_mode == 'bank' || $result->payment_mode == 'neft' || $result->payment_mode == 'paytm' || $result->payment_mode == 'paytmqr-1527937037' || $result->payment_mode == 'edc-1520599748' || $result->payment_mode == 'website-1520598979' || $result->payment_mode == 'tez-1532604466'){
                      $bankArray = $wpdb->get_results( 'SELECT `name` FROM wp_banks_details WHERE `id`='.$result->payment_bank);
                      if(!empty($bankArray)){
                      foreach ($bankArray as $bank) {
                        echo $bank->name;
					  }
                      }else{
						echo   $result->payment_bank;
						  
					  }
                    }else if( $result->payment_mode == 'cheque' ){
                        echo $result->payment_bank.'('.$result->chq_card_no.')';
                    }
                    else if($result->payment_mode == 'edc-1520599748'){								  
						  echo "EDC";								  
					  }else if($result->payment_mode == 'tez-1532604466'){
						   $bankArray = $wpdb->get_results( 'SELECT `name` FROM wp_banks_details WHERE `id`='.$result->payment_bank);
                        foreach ($bankArray as $bank) {
                          echo $bank->name;
                        }
						  
					  }else if($result->payment_mode=='googlepay'){
						  
						  echo $result->payment_bank;
						  
					  }else{
						  	echo $result->payment_bank;
						  
					  }
                  ?>
              </td>

              <td style="text-align:left;">

                <?php

                  $courseList = $wpdb->get_results( 'SELECT `course` FROM wp_courses_details WHERE `id`='.$result->courses);

                  foreach( $courseList as $course ) {



                    echo $course->course;

                  }

                ?>

              </td>

              <td style="text-align:left;">

                <?php

                  $trainerList = $wpdb->get_results('SELECT `name` FROM wp_trainers_details WHERE `id`='.$result->trainer);

                  if( count($trainerList) > 0 ) {



                    foreach( $trainerList as $trainer ) {



                      echo $trainer->name;

                    }

                  }

                ?>

              </td>
                <td style="text-align:left;"><?php echo $collector[$result->counsellor]; ?></td>
              <td style="text-align:left;"><?php echo $collector[$result->collector_id]; ?></td>
                
              <td style="text-align:left;"><?php echo $result->remark; ?></td>

            </tr>

            <?php

          }
                      $ownerArr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'trainers_details WHERE 1=1', ARRAY_A);
                      $owner = array();
                      foreach ($ownerArr as $value) {
                        $owner[$value['id']] = $value['name'];
                      }
                      $bankArr = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'banks_details WHERE 1=1', ARRAY_A); 
                      $bank = array();
                      foreach ($bankArr as $value) {
                        $bank[$value['id']] = $value['name'];
                      }   
                      if(!empty($add_pay_results)){
                      foreach ($add_pay_results as $result) {
                        ++$count;
                        ?>
                        <tr>
                          <td style="text-align:center;"><?php echo $count; ?></td>
                          <td style="text-align:center;"><?php echo $result->ap_invoice; ?></td>
                          <td style="text-align:center;"><?php
                            $date = date_create($result->paid_on);
                            echo date_format($date, "d-M-Y");
                          ?></td>
                          <td style="text-align:center;"><?php echo "NA"; ?></td>
                          <td style="text-align:left;"><?php echo $result->name; ?></td>
                          <td style="text-align:left;"></td>
                          <td style="text-align:left;"></td>
                         <!-- <td style="text-align:center;"><?php echo "NA"; ?></td>
                          <td style="text-align:left;"><?php echo "NA"; ?></td>  -->                        
                          <td style="text-align:right;"><?php echo $result->paid_amt-$result->service_tax; ?></td>
                          <td style="text-align:right;"><?php echo $result->service_tax; ?></td>
                          <td style="text-align:right;"><?php echo $result->paid_amt; ?></td>
                           <td style="text-align:center;"><?php if($result->payment_mode=='edc-1520599748' || $result->payment_mode=='edc'){   echo "EDC"; }else if($result->payment_mode=='website-1520598979'){ echo "Website"; }else if($result->payment_mode=='paytmqr-1527937037'){ echo "PaytmQr"; }else if($result->payment_mode=='tez-1532604466'){ echo "GooglePay"; }else{ echo ucfirst($result->payment_mode); } ?> </td>
                          <td style="text-align:center;">
                            <?php
                              if( $result->payment_mode == 'cash' || $result->payment_mode == 'online' ){
                                echo "NA";
                              }else if( $result->payment_mode == 'bank' || $result->payment_mode == 'neft' || $result->payment_mode == 'paytm' || $result->payment_mode == 'paytmqr-1527937037' || $result->payment_mode == 'edc-1520599748' || $result->payment_mode == 'website-1520598979' || $result->payment_mode == 'tez-1532604466'){
                                $bankArray = $wpdb->get_results( 'SELECT `name` FROM wp_banks_details WHERE `id`='.$result->payment_bank);
                                if(!empty($bankArray)){
                                foreach ($bankArray as $bank) {
                                echo $bank->name;
                                }
                                }else{
                                echo   $result->payment_bank;
                                
                                }
                              }else if( $result->payment_mode == 'cheque' ){
                                echo $result->payment_bank.'('.$result->chq_card_no.')';
                              }
                              else if($result->payment_mode == 'edc-1520599748'){								  
								  echo "EDC";								  
							  }else if($result->payment_mode == 'tez-1532604466'){
								   $bankArray = $wpdb->get_results( 'SELECT `name` FROM wp_banks_details WHERE `id`='.$result->payment_bank);
                                foreach ($bankArray as $bank) {
                                  echo $bank->name;
                                }
								  
							  }else if($result->payment_mode=='googlepay'){
								  
								  echo $result->payment_bank;
								  
							  }else{
								  	echo $result->payment_bank;
								  
							  }
                            ?>
                          </td>
                                    
                                    <td style="text-align:left;"><?php  if(!empty($result->course)){ echo $result->course; }else{ echo "N.A";} ?></td>
                                    <td style="text-align:left;"><?php echo $result->source; ?></td>
                                       <td><?php echo (isset($collector[$result->counsellor])?$collector[$result->counsellor]:""); ?></td>
                                       <td><?php echo (isset($collector[$result->collector_id])?$collector[$result->collector_id]:""); ?></td>
                                     
                                    <td style="text-align:left;"><?php echo $result->remark; ?> </td> 
                                     
                        </tr>
                        <?php
                      }
                      
                          
                      }
                      
                      if(!empty($expense_results)){
                      foreach ($expense_results as $result) {
                        ++$count;
                        ?>
                        <tr>
                          <td style="text-align:center;"><?php echo $count; ?></td>
                          <td style="text-align:center;"><?php echo $result->detailed_id; ?></td>
                          <td style="text-align:center;"><?php
                            $date = date_create($result->paid_on);
                            echo date_format($date, "d-M-Y");
                          ?></td>
                          <td style="text-align:center;"><?php echo "NA"; ?></td>
                          <td style="text-align:left;"><?php echo $result->name; ?></td>
                          <td style="text-align:left;"></td>
                          <td style="text-align:left;"></td>
                          <!--<td style="text-align:center;"><?php echo "NA"; ?></td>
                          <td style="text-align:left;"><?php echo "NA"; ?></td>-->
                          <td style="text-align:right;"><?php echo "-" . $result->paid_amt; ?></td>
						  <td style="text-align:center;">0</td>
						  <td style="text-align:right;"><?php echo "-" . $result->paid_amt; ?></td>
                           <td style="text-align:center;"><?php if($result->payment_mode=='edc-1520599748' || $result->payment_mode=='edc'){   echo "EDC"; }else if($result->payment_mode=='website-1520598979'){ echo "Website"; }else if($result->payment_mode=='paytmqr-1527937037'){ echo "PaytmQr"; }else if($result->payment_mode=='tez-1532604466'){ echo "GooglePay"; }else{ echo ucfirst($result->payment_mode); } ?> </td>
                            <td style="text-align:center;"> <?php echo $result->bypaymoney; ?>  </td>          
                                     
                             <td style="text-align:left;"><?php  if(!empty($result->detail)){ echo $result->detail; }else{ echo "N.A";} ?></td>       
                            <td style="text-align:left;"><?php echo $result->source; ?></td>
                                    <td style="text-align:left;"><?php echo "NA"; ?></td>
                                    <td style="text-align:left;"><?php echo "NA"; ?></td>
                                    <td style="text-align:left;"><?php echo $result->remark; ?> </td>   
                                     
                        </tr>
                        <?php
                      }
                      }
                      /*$totalFees = 0;
                      $totalAP = 0;
                      $totalExp = 0;
                      $totalFeesCash = 0;
                      $totalAP*/
                      $totalArr = array();
					//  echo "<pre>";print_r($total_add_pay_results);
                      if(!empty($total_results)){
                      foreach ($total_results as $result) {

                                  $totalArr['fees'][$result->payment_mode]['coll'] = $result->total;
                                  $totalArr['fees']['total']['coll'] += $result->total;
                                  $totalArr['fees'][$result->payment_mode]['st'] = $result->st;
                                  $totalArr['fees']['total']['st'] += $result->st;
                                  $totalArr['fees']['total']['gst'] = ($totalArr['fees']['total']['coll']-$totalArr['fees']['total']['st']);
								  
								  
								  
                                }       
                        }
                        if(!empty($total_add_pay_results)){
                      foreach ($total_add_pay_results as $result) {
 
                                  $totalArr['ap'][$result->payment_mode]['coll'] = $result->total;
                                  $totalArr['ap']['total']['coll'] += $result->total;
                                  $totalArr['ap'][$result->payment_mode]['st'] = $result->st;
                                  $totalArr['ap']['total']['st'] += $result->st;                                  
                                }          
                        }
                        if(!empty($total_expense_results)){
                      foreach ($total_expense_results as $result) {
                                  
                                  $totalArr['exp'][$result->payment_mode] = $result->total;
                                  $totalArr['exp']['total'] += $result->total;                                  
                                }   
                        }
                               
                      ?>
                      <tr></tr>
                      <tr></tr>
                      <tr>
                          <th colspan="2" style="text-align:left;">Total Collection</th>
                          <!--<td><?php echo (integer)$totalArr['fees']['total']['coll']+(integer)$totalArr['ap']['total']['coll'];?></td>-->
                          <td><?php echo ((integer)$totalArr['fees']['total']['coll']+(integer)$totalArr['ap']['total']['coll'])-((integer)$totalArr['fees']['total']['st']+(integer)$totalArr['ap']['total']['st']);?></td>
                      </tr>
                      <tr>
                          <th colspan="2" style="text-align:left;">Total GST</th>
                          <td><?php echo (integer)$totalArr['fees']['total']['st']+(integer)$totalArr['ap']['total']['st'];?></td>
                      </tr>
					  <tr>
                          <th colspan="2" style="text-align:left;">Total(Collection+GST)</th>
                          <!--<td><?php echo (integer)$totalArr['fees']['total']['coll']+(integer)$totalArr['ap']['total']['coll']+(integer)$totalArr['fees']['total']['st']+(integer)$totalArr['ap']['total']['st'];?></td>-->
						  
                          <td><?php echo ((((integer)$totalArr['fees']['total']['coll']+(integer)$totalArr['ap']['total']['coll'])-((integer)$totalArr['fees']['total']['st']+(integer)$totalArr['ap']['total']['st']))+((integer)$totalArr['fees']['total']['st']+(integer)$totalArr['ap']['total']['st'])); ?></td>
                      </tr>
					  <!--tr>
                          <th colspan="2" style="text-align:left;">test1</th>
                          <td><?php //echo json_encode($total_results);?></td>
                      </tr>
					  <tr>
                          <th colspan="2" style="text-align:left;">test2</th>
                          <td><?php //echo json_encode($total_add_pay_results);?></td>
                      </tr-->
                      <tr>
                          <th colspan="2" style="text-align:left;">Total Expense</th>
                          <td><?php echo "-" . (integer)$totalArr['exp']['total'];?></td>
                      </tr>                      
                      <tr>
                          <th colspan="2" style="text-align:left;">Total Balance</th>
                        <!--  <td><?php echo (integer)$totalArr['fees']['total']['coll']+(integer)$totalArr['ap']['total']['coll']+(integer)$totalArr['fees']['total']['st']+(integer)$totalArr['ap']['total']['st']-(integer)$totalArr['exp']['total'];?></td>-->
                        <td><?php echo (((((integer)$totalArr['fees']['total']['coll']+(integer)$totalArr['ap']['total']['coll'])-((integer)$totalArr['fees']['total']['st']+(integer)$totalArr['ap']['total']['st']))+((integer)$totalArr['fees']['total']['st']+(integer)$totalArr['ap']['total']['st']))- ((integer)$totalArr['exp']['total'])); ?></td>
                      </tr>                                            
                      <tr></tr>
                      <tr>
                          <th colspan="2" style="text-align:left;">Total Cash Collection</th>
						   <td><?php echo (integer)$totalArr['fees']['cash']['coll']+(integer)$totalArr['ap']['cash']['coll'];?></td>
                       
                      </tr>
                      <tr>
                          <th colspan="2" style="text-align:left;">Total Cash Expense</th>
                          <td><?php echo "-" . (integer)$totalArr['exp']['cash'];?></td>
                      </tr>                      
                      <tr>
                          <th colspan="2" style="text-align:left;">Total Cash Balance</th>
                         <td><?php echo (integer)$totalArr['fees']['cash']['coll']+(integer)$totalArr['ap']['cash']['coll']-(integer)$totalArr['exp']['cash'];?></td>
                         
                      </tr>                                            
                      <?php                                                         

      }

    ?>                

    </tbody>

    </table>