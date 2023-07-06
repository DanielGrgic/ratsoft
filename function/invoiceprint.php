<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <title>RatSoft Invoice</title>
    <link href="../css/style.css" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        .invoice .table > tbody > tr > td {
            padding: 0px 8px;
        }
    </style>
</head>
<body>
    
    

    <div id="generatePdf" class="container bootstrap snippets bootdeys">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default invoice" id="invoice">
                    <div class="panel-body">

                        <div class="row">

                            <table style="width:100%;">
                                <tbody>
                                    <tr>
                                        <td style="text-align:left;padding-left:20px;"><img src="../images/logo.png" height="70"></td>
                                        <td style="text-align:right;padding-right:15px;">
                                            <h3 class="marginright">INVOICE: <?php echo $_REQUEST['invoicenum']; ?></h3>
                                            <span class="marginright"><?php $date = date_create(); print($date->format("D, d M Y"))?></span>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <hr>
                        <div class="row">

<?php

    $invoicenum = $_REQUEST['invoicenum'];

    include '../connection/connection.php'; 

    $check = 0;
    $sql = "SELECT * 
    FROM [rms].[sparksystems].[ratsoft_customers]
    INNER JOIN [rms].[sparksystems].[ratsoft_invoice] ON [rms].[sparksystems].[ratsoft_customers].id=[rms].[sparksystems].[ratsoft_invoice].customer_id WHERE [rms].[sparksystems].[ratsoft_invoice].invoicenumber ='$invoicenum'";
    $stmt = sqlsrv_query( $conn, $sql);
    if( $stmt === false ) { 
        die( print_r( sqlsrv_errors(), true));
        
    }

    $obj = sqlsrv_fetch_object( $stmt);

    if ($obj==null){
    echo "{\"error\":1,\"result\":\"false\",\"message\":\"invoice with invoice number: $invoicenum does not exist.\"}"; 
    echo "<style>body{background:white; padding-top:10px;}</style>";
    exit();
    }

    $date = $obj->createdate; 
    $time=$date->format("D, d M Y H:i:s"); 


    $all_invoices = array();

    $check = 0;
    $sql1="SELECT * FROM [rms].[sparksystems].[ratsoft_customers]
    INNER JOIN [rms].[sparksystems].[ratsoft_invoice] ON [rms].[sparksystems].[ratsoft_customers].id=[rms].[sparksystems].[ratsoft_invoice].customer_id
    WHERE [rms].[sparksystems].[ratsoft_invoice].invoicenumber LIKE '%".$invoicenum."%'";

    $stmt1 = sqlsrv_query( $conn, $sql1);
    if( $stmt1 === false ) { 
        die( print_r( sqlsrv_errors(), true));
        
    }

    while( $obj1 = sqlsrv_fetch_object( $stmt1)) {
        $all_invoices[] = $obj1;
    }
    sqlsrv_close($conn);
?>

                            <table style="width:100%;">
                                <tbody>
                                    <tr>
                                        <td style="text-align:left;padding-left:20px;">
                                            <p class="lead marginbottom">Rat Software Developers Private Limited</p>
                                            <p>5D/2/3 Ramjeet Nagar Albatiya Road</p>
                                            <p>Shahganj, Agra,U.P., India</p>
                                            <p>Pin No. : 282010</p>
                                            <p>GST : 0000000 </p>
                                        </td>
                                        <td style="text-align:right;padding-right:15px;">
                                            <p class="lead marginbottom">To : <?php echo $obj->firstname; ?> &nbsp;<?php echo $obj->lastname?> </p>
                                            <p>Address : <?php echo $obj->address; ?></p>
                                            <p>Country : <?php echo $obj->country; ?>, Zip : </p>
                                            <p>Phone: <?php echo $obj->phone; ?></p>
                                            <p>Email: <?php echo $obj->email; ?></p>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>


                        </div>

                        <div class="row table-row">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:5%">#</th>
                                        <th style="width:15%">Item Name</th>
                                        <th style="width:20%">Description</th>
                                        <th class="text-right" style="width:15%">Tax</th>
                                        <th class="text-right" style="width:15%">Unit Price</th>
                                        <th class="text-right" style="width:15%">Discount</th>
                                        <th class="text-right" style="width:15%">Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                <?php
                                    $total_price = 0;
                                    $total_tax = 0;
                                    $total_discount = 0;
                                    for ($i=0; $i < count($all_invoices); $i++) {
                                        if($all_invoices[$i]->description==0){
                                            $all_invoices[$i]->description = '';
                                        }
                                        echo "<tr>";
                                        echo "<td class='text-center'>".number_format($i+1)."</td>";
                                        echo "<td>".$all_invoices[$i]->itemname."</td>";
                                        echo "<td>".$all_invoices[$i]->description."</td>";
                                        echo "<td class='text-right'>".number_format(floatval($all_invoices[$i]->tax), 0)."%</td>";
                                        echo "<td class='text-right'>".number_format(floatval($all_invoices[$i]->itemprice), 2).'&nbsp;'.$obj->currency."</td>";
                                        echo "<td class='text-right'>".number_format(floatval($all_invoices[$i]->discount), 2).'&nbsp;'.$obj->currency."</td>";
                                        echo "<td class='text-right'>".number_format(floatval($all_invoices[$i]->itemprice*(1+floatval($all_invoices[$i]->tax)/100)-($all_invoices[$i]->discount)), 2).'&nbsp;'.$obj->currency."</td>";
                                        $total_price = $total_price +  floatval($all_invoices[$i]->itemprice);
                                        $total_tax = $total_tax + floatval($all_invoices[$i]->itemprice)*floatval($all_invoices[$i]->tax)/100;
                                        $total_discount = $total_discount + floatval($all_invoices[$i]->discount);
                                    }
                                    $total = $total_price + $total_tax - $total_discount;
                                ?>
                                    


                                </tbody>
                            </table>

                        </div>

                        <div class="row">
                            <div class="col-xs-6 margintop">
                                <p class="lead marginbottom">THANK YOU!</p>
                                <button class="btn btn-success" id="invoice-print"><i class="fa fa-print"></i> Print Invoice</button>
                            </div>
                            <div class="col-xs-6 text-right pull-right invoice-total">
                                <p>Total : <?php echo number_format(floatval($total), 2).'&nbsp;'.$obj->currency; ?></p>
                            </div>



                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>var button = document.getElementById("invoice-print");
      button.addEventListener("click", function () {
         window.print();
      });</script>



</body>
</html>