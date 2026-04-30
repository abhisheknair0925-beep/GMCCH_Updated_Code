<?php
namespace Dompdf;
require_once 'dompdf/dompdf/autoload.inc.php';
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$date = $_GET['date'];
?>
<html>
<head>
    <style>
        header {
            margin-top: -20px;
            background-color: #03a9f4;
            color: white;
            text-align: center;
            line-height: 35px;
        }

        .table {
            margin-top: 70px;
        }
    </style>
</head>
    <body>
    <header>
        Booking Details
    </header>
    <table class="table" border="1px">
        <thead class="thead-dark">
        <tr>
            <th>SI NO</th>
            <th>Cr number</th>
            <th>User name</th>
            <th>Unit</th>
            <th>Token</th>
            <th>Date</th><th  >Booking status</th>
        </tr>
        </thead>
        <tbody>
        <?php
        include('connect.php');
        $view_users="SELECT * FROM tbl_booking WHERE tbl_booking_status='Approved' AND tbl_booking_date='$date'";
        $users_view=mysqli_query($dbconnect,$view_users);
        $i=1;
        while($rows=mysqli_fetch_array($users_view))
        {
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $rows['tbl_booking_crno']; ?></td>
                <td><?php echo $rows['tbl_booking_name']; ?></td>
                <td><?php echo $rows['tbl_booking_unit']; ?></td>
                <td><?php echo $rows['tbl_booking_token']; ?></td>
                <td><?php echo $rows['tbl_booking_date']; ?></td>
                <td><?php echo $rows['tbl_booking_status'];?></td>
            </tr>
            <?php
            $i++;
        }
        ?>
        </tbody>
    </table>
    </body>
</html>

<?php
$html = ob_get_clean();
$dompdf = new Dompdf();
$dompdf->set_option('enable_html5_parser', TRUE);
$dompdf->setPaper('A4', 'landscape');
$dompdf->load_html($html);


$dompdf->render();
$dompdf->stream("booking_details.'.$date.'",array("Attachment" => false));
?>
