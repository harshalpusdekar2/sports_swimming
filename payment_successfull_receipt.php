<?php
session_start();

// // if (isset($_SESSION['photo'])) {
// //     $photo = $_SESSION['photo'];
// //     echo "Photo Path: $photo";
// // } else {
// //     echo 'Photo path not found in session.';
// // }

if (isset($_POST['submit'])) {
    if (isset($_SESSION['UID'])) {
        $uid = $_SESSION['UID'];

        include 'connection.php'; // Include database connection

        // Prepare and bind the SQL statement with a parameterized query
        $sql = "SELECT * FROM signup WHERE uid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $uid);

        // Execute the query
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "ID: " . $row["id"] . " - Name: " . $row["first_name"] . " " . $row["last_name"] . " - Email: " . $row["email"] . "<br>";
                // $_SESSION['photo'] = $row["photo_path"]; // Set the session variable for the photo path
                $_SESSION['first_name'] = $row["first_name"];
                $_SESSION['last_name'] = $row["last_name"];
                $_SESSION['permenent_address'] = $row["permenent_address"];
            }
        } else {
            echo "0 results";
        }

        // Close the prepared statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Session variable 'UID' not set.";
    }
}

// Get the current date
$currentDate = new DateTime();

// Increase the current date by one month
$currentDate->modify('+1 month');

// Format the date as desired
$formattedDate = $currentDate->format('d/m/Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .mt25 {
            margin-top: 25px;
        }
        .per70 {
            width: 70%;
        }
        .per25 {
            width: 25%;
        }
        .invoice-footer {
            margin-top: 25px;
            text-align: center;
        }
        .invoice-footer p {
            margin: 0;
        }
        .invoice-footer .btn {
            margin-top: 15px;
        }
        .well {
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            padding: 15px;
            border-radius: 4px;
        }
    </style>
    <?php include("head_ref.php"); ?>
</head>
<body>
    <?php include("header.php"); ?>
    <div class="container">
        <div class="col-lg-12">
            <div class="invoice-details mt25">
                <div class="well">
                    <ul class="list-unstyled mb0">
                        <li>
                            <!-- <div class="invoice-logo">
                                <img width="100" src="//
                                  echo "../" . $_SESSION['photo']; ?>" alt="Invoice logo">
                            </div> -->
                        </li>
                        <ul class="list-unstyled text-right">
                            <li>District Sports Office, Yavatmal</li>
                            <li>Neharu Stadium</li>
                            <li>Godhani Road Yavatmal</li>
                            <li>contact number: 9637553436</li>
                        </ul>
                        <li><strong>UID Number</strong><br><?php echo $_SESSION['UID']; ?></li>
                        <li><strong>Status:</strong> <span class="label label-danger">PAID</span></li>
                    </ul>

                    <div class="invoice-to mt25">
                        <ul class="list-unstyled">
                            <li><strong>Invoiced To</strong><br><?php echo $_SESSION['first_name']; ?> <?php echo $_SESSION['last_name']; ?></li>
                            <li><strong>Permanent Address:</strong><br><?php echo $_SESSION['permenent_address']; ?></li>
                        </ul>
                    </div>
                    <div class="invoice-items mt25">
                        <div class="table-responsive" style="overflow: hidden; outline: none;" tabindex="0">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="per70 text-center">Description</th>
                                        <th class="per25 text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Registration Fee For Form</td>
                                        <td class="text-center">Rs. 100/-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="invoice-footer mt25">
                        <p>Generated on <label id="datetime"></label><span> Your Subscription expires on = <?php echo $formattedDate; ?></span><br>
                        <a class="btn btn-primary" id="button"><i class="fa fa-print mr5"></i> Print</a></p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById("datetime").innerHTML = new Date().toLocaleString();

            document.getElementById("button").onclick = function() {
                window.print();
            }
        </script>

    </div>
</body>
</html>
