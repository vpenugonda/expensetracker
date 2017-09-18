<?php
ob_start();
session_start();
//error_reporting();
$server_name = "localhost";
$db_name = "expenses";
$user_name = "root";
$db_pswd = "123456";
$link = mysqli_connect($server_name, $user_name, $db_pswd, $db_name);
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$ed_id = !empty($_GET['ed_id']) ? $_GET['ed_id'] : "";
if (!empty($_GET['del_id'])) {
    $delete = mysqli_query($link, "DELETE FROM `expensesdetails` WHERE `ed_id`='" . $_GET['ed_id'] . "'");
    if ($delete) {
        header("location:" . $_SERVER['HTTP_REFERER']);
    }
}
/*Edit or Insert an expense*/
$fetch_expenses = mysqli_query($link, "SELECT * FROM `expensesdetails` WHERE `ed_id`='" . $ed_id . "'");
$expense = mysqli_fetch_assoc($fetch_expenses);

if (isset($_POST['submitexpense'])) {
    $ed_user = $_POST['user'];
    $ed_store = $_POST['expensename'];
    $ed_amount = $_POST['expenseamount'];
    $ed_type = $_POST['type'];
    $ed_date = $_POST['date'];
    $ed_notes = $_POST['notes'];
    if ($ed_id) {
        $res = mysqli_query($link, "UPDATE `expensesdetails` SET `ed_user`='$ed_user',`ed_store`='$ed_store',`ed_amount`='$ed_amount',`ed_type`='$ed_type',`ed_date`='$ed_date',`ed_notes`='$ed_notes' WHERE `ed_id` = '$ed_id'");
        if ($res == true) {
            $message = "<div class='alert alert-success'> Expense updated Successfully ..!</div>";
            header("refresh:1;url=index.php");
        } else {
            $message = "<div class='alert alert-danger'>Expense unable to update</div>";
        }
    } else {
        $insert = mysqli_query($link, "INSERT INTO `expensesdetails` (`ed_id`, `ed_user`, `ed_store`, `ed_amount`, `ed_type`, `ed_date`, `ed_notes`, `ed_createdon`) VALUES (NULL, '$ed_user','$ed_store','$ed_amount','$ed_type','$ed_date','$ed_notes', CURRENT_TIMESTAMP)");
        if ($insert == true) {
            $message = "<div class='alert alert-success'> Expense Added Successfully ..!</div>";
            header("refresh:1;url=" . $_SERVER['PHP_SELF']);
        } else {
            $message = "<div class='alert alert-danger'>Expense unable to insert</div>";
        }
    }

}
/*Deleting an expense*/
if (!empty($_GET['del_id'])) {
    $delete = mysqli_query($link, "DELETE FROM `expensesdetails` WHERE `ed_id`='" . $_GET['del_id'] . "'");
    if ($delete) {
        header("location:" . $_SERVER['HTTP_REFERER']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>.: Expense Tracker by Vid and Sid :. </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Expense tracker"/>
    <meta name="author" content="Vidya and Sid"/>

    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker3.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
</head>

<body>


<div class="container">
    <div class="row">
        <div class="header clearfix">
            <!-- <nav> 
                <ul class="nav nav-pills float-right">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                </ul>
            </nav> -->
            <h1 class=""><strong> Expense </strong> Tracker</h1>

        </div>
    </div>

    <div class="row">
        <div class="col-md-4 drop-shadow">
            <div class="form-area">
                <h2 style="margin-bottom: 25px;">Add Expense</h2>
                <p class="text-muted" style="padding: 0">Remember, One at a time!</p>
                <div><?php echo !empty($message) ? $message : ""; ?></div>
                <form id="expenseForm" action="" method="post">
                    <input type="hidden" name="ed_id" value="<?php echo !empty($ed_id) ? $ed_id : ""; ?>"/>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1"><i class="fa fa-question"></i></span>
                            <select name="user" id="user" class="form-control"
                                    style="font-family: FontAwesome, sans-serif;"
                                    required value="<?php echo !empty($ed_user) ? $ed_user : ""; ?>">
                                <option value=""> Who is entering?</option>
                                <option value="he"> &#xf183; He</option>
                                <option value="she"> &#xf182; She</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon1"><i
                                        class="fa fa-shopping-cart"></i> </span>
                            <input type="text" class="form-control" id="expensename" name="expensename"
                                   placeholder="Which store?"
                                   value="<?php echo !empty($ed_store) ? $ed_store : ""; ?>"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-usd"></i></div>
                            <input type="text" class="form-control" id="expenseamount" name="expenseamount"
                                   placeholder="How much you spent today?"
                                   value="<?php echo !empty($ed_amount) ? $ed_amount : ""; ?>"
                                   required>
                            <div class="input-group-addon">.00</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon2"><i class="fa fa-question"></i></span>
                            <select name="type" id="type" class="form-control scrollable-menu"
                                    style="font-family: FontAwesome, sans-serif;"
                                    value="<?php echo !empty($ed_type) ? $ed_type : ""; ?>" required>
                                <option value=""> What type of expense?</option>
                                <option value="phonebill"> Phonebill</option>
                                <option value="shopping"> Shopping</option>
                                <option value="vegetables"> Vegetables</option>
                                <option value="rent"> Rent</option>
                                <option value="grocery"> Grocery</option>
                                <option value="uber"> Uber</option>
                                <option value="transportation"> Transportation</option>
                                <option value="dining"> Dining</option>
                                <option value="other"> Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group ">
                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                            <input type="text" class="form-control datepicker" name="date"
                                   placeholder="When did you purchase?"
                                   value="<?php echo !empty($ed_date) ? $ed_date : ""; ?>"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                            <textarea class="form-control" type="textarea" id="notes" name="notes"
                                      placeholder="Short notes on your expense, Please!"
                                      rows="4" maxlength="120"
                                      value="<?php echo !empty($ed_notes) ? $ed_notes : ""; ?>"></textarea>
                        <div id="info" class="text-muted"></div>
                    </div>

                    <button type="submit" id="submit" name="submitexpense" class="btn btn-success pull-right m-b-2">
                        <i class="fa fa-arrow-right"></i> Submit Expense
                    </button>
                    <a href="#">
                        <button type="reset" class="btn btn-danger">
                            <i class="fa fa-refresh"></i> Clear
                        </button>
                    </a>
                </form>
            </div>
        </div>

        <div class="col-md-7 drop-shadow pull-right">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3><strong> Expenses </strong>so far!</h3>
                    </div>
                    <div class="panel-body">
                        <article class="table-list">
                            <table id="expenses" class="display table table-bordered table-hovered" cellspacing="0">
                                <thead>
                                <tr>
                                    <!--                                    <th class="">S.No</th>-->
                                    <th class="">Name</th>
                                    <th class="">Store</th>
                                    <th class="">Amount</th>
                                    <th class="">Type</th>
                                    <th class="">Date</th>
                                    <th class="">Notes</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $expenseslists = mysqli_query($link, "SELECT * FROM `expensesdetails` ORDER BY `ed_id` ASC ");
                                $number = mysqli_num_rows($expenseslists);
                                if ($number == 0) {
                                    echo "<tr> <td colspan='7'> Expenses are not added yet!</td>";
                                } else {
                                    for ($i = 1;
                                         $i <= $number;
                                         $i++) {
                                        $expenseslist = mysqli_fetch_assoc($expenseslists);
                                        ?>

                                        <tr>

                                            <!--<td>// <?php echo $i ?> </td>-->
                                            <td><?php echo $expenseslist['ed_user'] ?></td>
                                            <td><?php echo $expenseslist['ed_store'] ?></td>
                                            <td>$<?php echo $expenseslist['ed_amount'] ?></td>
                                            <td><?php echo $expenseslist['ed_type'] ?></td>
                                            <td><?php echo $expenseslist['ed_date'] ?></td>
                                            <td><?php echo $expenseslist['ed_notes'] ?></td>
                                            <td>
                                                <a href="index.php?ed_id=<?php echo $expenseslist['ed_id'] ?>"
                                                   class="btn btn-md btn-info"><i
                                                            class="fa fa-edit"></i> Edit </a>
                                                <!--                                                <a href="index.php?del_id=-->
                                                <?php //echo $expenseslist['ed_id']; ?><!--"-->
                                                <!--                                                   class="delete btn btn-sm btn-danger"><i-->
                                                <!--                                                            class="fa fa-trash-o"></i></a>-->
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }

                                ?>
                                </tbody>
                            </table>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer text-center navbar-fixed-bottom">
        <a class="button" href="#" target="_blank">Made with <i class="fa fa-heart"></i> on Bootstrap by <strong> &copy; Vid & Sid</strong></a>
        
    </footer>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        WebFontConfig = {
            google: {
                families: ['Roboto:300,400,500']
            }
        };
        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js';
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>
</body>

</html>