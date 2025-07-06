<?php require_once 'db_connect.php' ?>
<!DOCTYPE html>
<html>
<head>
    <title>file upload</title>
    <link rel="stylesheet" href="bootStrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootStrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <style>
        .topnav{
            padding: 10px;
            background-color: #171717;
            color: white;
        }
    </style>
</head>
<body>
    <div class="row justify-content-center topnav">
        <p>file upload</p>
    </div>
    <?php 
        $result = $connect->query("SELECT * FROM tbl_uploads") or die($connect->error);
     ?>
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>file</th>
                    <th>type</th>
                    <th>size</th>
                    <th>action</th>
                </tr>
            </thead>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['file'] ?></td>
                <td><?php echo $row['type'] ?></td>
                <td><?php echo $row['size'] ?></td>
                <td>
                    <a href="uploads/<?php echo $row['file'] ?>" target="_blank">view file</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
