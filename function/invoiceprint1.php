<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1"> 
  <title>Invoice</title> 
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Titillium+Web&display=swap" rel="stylesheet">

  <style>
    body{
      background-image:url(../images/invoice_background.jpg);
      background-size: 100% 100%;  
      font-family: Arial, Helvetica, sans-serif; 
      margin: 0;
      padding: 0;
      height:100%;
    }
    .container{
      width:70%;
      margin:auto;
    }
    header{
      width:100%;
      margin:0;
      padding:0;
    } 
    .title{
      width:100%;
      text-align:left;
      padding-top:40px;
    }
    .title h1{
      font-size:82px; 
      font-family: 'Titillium Web', sans-serif;
      font-weight: bold;
      margin: 0;
      color: rgb(37 33 101);
    }
    h1, h2, h3, h4, h5, h6{
      margin:0;
      padding:0;
    }
    .costumer .fullname{
      display:flex;
      padding:10px 0;
    }
    .costumer .fullname h3:first-letter{ 
      text-transform: uppercase; 
    }
    .costumer .email{
      display:flex;
      padding:10px 0;
    }
    .costumer .phone{
      display:flex; 
    }
    main{
      width:100%;
      margin:0;
      padding:0;
    } 
    main .invoicedetails{
      width:100%;
      text-align:left;
      padding-top:40px;
    }
    main .invoicedetails .invoicenumber{
      display:flex;
    }
    main .invoicedetails div{
      padding: 2px;
    }
    .head_table{
      display:flex;
      width:100%;
      margin-top:30px;
      border-top: 2px solid rgb(50 203 132);
      border-bottom: 2px solid rgb(50 203 132);
      padding-top: 10px;
      padding-bottom: 10px;
    }
    .head_table .col16{ 
      width:16%;
      text-align:center;
      color: rgb(37 33 101);
    }
    .head_table .col52{ 
      width:52%;
      text-align:center;
      color: rgb(37 33 101);
    }
    .head_table .third h3{ 
      text-align:right;
    }
    .head_table .forth h3{ 
      text-align:right;
      padding-right:10px;
    }
    .body_table{
      display:block;
      width:100%;  
      border-bottom: 2px solid rgb(50 203 132);
      padding-top: 10px;
      padding-bottom: 10px;
    }
    .body_table .col16{ 
      width:16%;
      text-align:center;
    }
    .body_table .col52{ 
      width:52%;
      text-align:center;
    }
    .body_table p{ 
      margin:0;
      padding:0;
      font-weight:bold;
    }
    .body_table .first p{ 
      text-align:center;
    }
    .body_table .second p{ 
      text-align:left;
    }
    .body_table .third p{ 
      text-align:right;
    }
    .body_table .forth p{ 
      text-align:right;
      padding-right:10px;
    }
    .body_table .one_row{
      display:flex;
      width:100%;
      padding: 10px 0;
    }
    .span_color{
      color: rgb(37 33 101);
    }

    .footer_table{
      display:block;
      width:100%;   
      padding-top: 10px;
      padding-bottom: 10px;
    }
    .footer_table .col16{ 
      width:16%;
      text-align:center;
    }
    .footer_table .col52{ 
      width:52%;
      text-align:center;
    }
    .footer_table p{ 
      margin:0;
      padding:0; 
      font-weight:bold;
    }
    .footer_table .first p{ 
      text-align:center;
    }
    .footer_table .second p{ 
      text-align:left;
    }
    .footer_table .third p{ 
      text-align:right;
    }
    .footer_table .forth p{ 
      text-align:right;
      padding-right:10px;
    }
    .footer_table .one_row{
      display:flex;
      width:100%;
      padding: 10px 0;
    }
    .footer_table .one_row h2{
      text-align: right;
      color: rgb(37 33 101);
    }
  </style>
    

</head>

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
  
// for ($i=0; $i < count($all_invoices); $i++) { 
//     echo "{\"error\":0,\"result\":\"Invoice id: ".$all_invoices[$i]->id."\"} \n";
//     echo "{\"error\":0,\"result\":\"Invoice number: ".$all_invoices[$i]->invoicenumber."\"} \n";
//     echo "{\"error\":0,\"result\":\"Full Name of Costumer: ".$all_invoices[$i]->firstname.' '.$all_invoices[$i]->lastname."\"} \n\n";
// }

if ($obj==null){
  echo "{\"error\":1,\"result\":\"false\",\"message\":\"invoice with invoice number: $invoicenum does not exist.\"}"; 
  echo "<style>body{background:white; padding-top:10px;}</style>";
  sqlsrv_close($conn);
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


<body class="container">
  <div class="check" <?php if ($obj==null){?>style="display:none"<?php } ?>>
    <header>
      <div class="title">
        <h1>INVOCE</h1>
      </div>
      <div class="costumer">
        <div class="fullname">
          <?php echo "<h3>Full name: </h3><h3> &nbsp;".$obj->firstname."</h3>"; ?> 
          &nbsp;
          <?php echo "<h3>".$obj->lastname."</h3>"; ?> 
        </div>
        <div class="email" <?php if ($obj->email===NULL){?>style="display:none"<?php } ?>>
          <?php echo "<h4>Email:&nbsp;".$obj->email."</h4>"; ?> 
        </div>
        <div class="phone" <?php if ($obj->phone===NULL){?>style="display:none"<?php } ?>>
          <?php echo "<h4>Phone:&nbsp;".$obj->phone."</h4>"; ?> 
        </div>
        <div class="country" <?php if ($obj->country===NULL){?>style="display:none"<?php } ?>>
          <?php echo "<h4>Country:&nbsp;".$obj->country."</h4>"; ?> 
        </div>
      </div>
    </header>
    <main>
      <div class="invoicedetails">
        <div class="invoicenumber">
          <?php echo "<h3><span class='span_color'>Invoice #</span> &nbsp; ".$obj->invoicenumber."</h3>"; ?> 
        </div>
        <div class="time">
          <?php echo "<h3><span class='span_color'>Invoice Date</span> &nbsp; ".$time."</h3>"; ?> 
        </div>
      </div>

      <div class="head_table">

        <div class="col16">
          <h3>QTY</h3>
        </div>
        <div class="col52">
          <h3>DESCRIPTION</h3>
        </div>
        <div class="col16 third">
          <h3>AMOUNT</h3>
        </div>
        <div class="col16 forth">
          <h3>PRICE</h3>
        </div>

      </div>

      <div class="body_table">
        <?php
        $total_price = 0;
        $total_tax = 0;
          for ($i=1; $i < count($all_invoices); $i++) {
            echo "<div class='one_row'>";
            echo "<div class='col16 first'>";
            echo "<p>".$i."</p>";
            echo "</div>"; 
            echo "<div class='col52 second'>";
            echo "<p>".$all_invoices[$i]->description."</p>";
            echo "</div>";
            echo "<div class='col16 third'>";
            echo "<p>".intval($all_invoices[$i]->amount)."</p>";
            echo "</div>"; 
            echo "<div class='col16 forth'>";
            echo "<p>".number_format(floatval($all_invoices[$i]->itemprice), 2)."</p>";
            echo "</div>"; 
            echo "</div>";
            $total_price = $total_price +  floatval($all_invoices[$i]->itemprice);
            $total_tax = $total_tax + floatval($all_invoices[$i]->itemprice)*floatval($all_invoices[$i]->tax)/100;
          } 
          $total = $total_price + $total_tax;
        ?>
            
 

      </div>

      <div class="footer_table">
        <div class='one_row'>
          <div class='col16 first'></div>
          <div class='col52 second'></div>
          <div class='col16 third'>
            <p>Subtotal</p>
          </div>
          <div class='col16 forth'>
            <?php echo "<p>".number_format(floatval($total_price), 2)."</p>" ?>
          </div>
        </div>
        <div class='one_row'>
          <div class='col16 first'></div>
          <div class='col52 second'></div>
          <div class='col16 third'>
            <p>GST</p>
          </div>
          <div class='col16 forth'>
          <?php echo "<p>".number_format(floatval($total_tax), 2)."</p>" ?>
          </div>
        </div>
        <div class='one_row'>
          <div class='col16 first'></div>
          <div class='col52 second'></div>
          <div class='col16 third'>
            <h2>TOTAL</h2>
          </div>
          <div class='col16 forth'>
          <?php echo "<h2>".number_format(floatval($total), 2)."</h2>" ?>
          </div>
        </div>
      </div>
    </main>
  </div>
  

 
</body>
</html>
