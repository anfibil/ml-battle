<?PHP

//Basic includes
require_once "functions.php";

//Basic global variables
global $projectname, $pagetitle;

$projectname = "Classification Battle";
$pagetitle = "Results";

$allSubmissions = array();

if (!file_exists('super-secure-database.json')){
    file_put_contents('super-secure-database.json', '{"data":[]}');
}

$database = json_decode(file_get_contents('super-secure-database.json'));

// Handler the new model action
$alertMesssage = "";
$alertClass = "alert-success";
if (isset($_GET['action']) && strcmp($_GET['action'], 'submitmodel') === 0 ){

    $data = array();

    // Check the student name
    if (!isset($_POST['studentName']) || empty($_POST['studentName']) || strlen($_POST['studentName']) < 5 ){ // Need improvement
        $alertClass = "alert-warning";
        $alertMesssage = '<i class="fa-fw fa fa-warning"></i><strong>Error!</strong> Please provide your full name.';
    } else {
        $data['studentName'] = trim(strtoupper($_POST['studentName']));
    }

    // Check the dataset file
    if (!isset($_POST['dataset']) || empty($_POST['dataset']) || strlen($_POST['dataset']) < 1 || strpos($_POST['dataset'], '..') !== FALSE ){ // Need improvement
        $alertClass = "alert-warning";
        $alertMesssage = '<i class="fa-fw fa fa-warning"></i><strong>Error!</strong> Please make sure you selected a dataset.';        
    } else {
        if (!file_exists('./datasets/'.$_POST['dataset'].'.x.npy' ) || !file_exists('./datasets/'.$_POST['dataset'].'.y.npy') ){
            $alertClass = "alert-danger";
            $alertMesssage = '<i class="fa-fw fa fa-times"></i><strong>Error!</strong> The dataset you selected does not exist.';
        } else {
            $data['dataset'] = $_POST['dataset'];
        }
    }

    $data['date'] = date("Y-m-d H:i:s");

    $data['ip'] = isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR']: 'ghost';

    if (empty($alertMesssage)){

        if (!isset($_FILES['model'])){
            $alertClass = "alert-warning";
            $alertMesssage = '<i class="fa-fw fa fa-warning"></i><strong>Error!</strong> Please upload your model file. Make sure the file ends with .joblib';  
        } else {

            $uploadfile = './uploads/' . md5($_FILES['model']['name'] . rand(1, 1000) ).'.joblib';

            if (move_uploaded_file($_FILES['model']['tmp_name'], $uploadfile)){

                // This line of code is very insecure! This is a very dumb idea :)
                $cmdLine = "python process-model.py " . escapeshellarg($uploadfile) . ' ' . escapeshellarg($data['dataset'])  ;
                $outputString = shell_exec($cmdLine);
                // die($cmdLine); // Just kidding, just a debug line!
                $outputObj = json_decode(trim($outputString));

                $goodExecution = false;
                if ($outputObj && is_object($outputObj)){
                    if (isset($outputObj->error) && !empty($outputObj->error) ){
                        $alertClass = "alert-danger";
                        $alertMesssage = '<i class="fa-fw fa fa-times"></i><strong>Error!</strong> An error occurred during model execution. Your score wasn\'t saved. Details: '. htmlentities($outputObj->error) ;
                    } else {
                        if (!isset($outputObj->accuracy) || $outputObj->accuracy == null){
                            $alertClass = "alert-danger";
                            $alertMesssage = '<i class="fa-fw fa fa-times"></i><strong>Error!</strong> An error occurred during model execution. Your score wasn\'t saved. Details: '. htmlentities($outputString) ;
                        } else {
                            $data['accuracy'] = (float) $outputObj->accuracy;
                        }                        
                    }
                } else {
                    $alertClass = "alert-danger";
                    $alertMesssage = '<i class="fa-fw fa fa-times"></i><strong>Error!</strong> An error occurred during model execution. Your score wasn\'t saved. Details: Honestly, I have no idea what happened ;) <br> '. htmlentities($outputString);
                }

                // Try to delete the file
                unlink($uploadfile);

                if (empty($alertMesssage)){

                    // Lets save the data into the 'database'
                    if (!$database || !isset($database->data)){
                        $database = array('data'=> array( (object) $data) );
                    } else {

                        // Add the data
                        $database->data[] = (object) $data;

                        // Filter duplicate students and let only the best score
                        $scores = [];

                        foreach ($database->data as $item) {

                            $key = strtoupper(trim($item->studentName)).strtoupper($item->dataset); // Unique key (student name and dataset)

                            if (isset($scores[$key])){
                                if (isset($item->accuracy) && ( !isset($scores[$key]->accuracy) ||  $item->accuracy > $scores[$key]->accuracy  ) ){
                                    $scores[$key] = $item;
                                }
                            } else {
                                $scores[$key] = $item;
                            }
                        }

                        $database->data = array_values($scores);

                    }

                    file_put_contents('super-secure-database.json', json_encode((object) $database, JSON_PRETTY_PRINT ) );

                    $alertClass = "alert-success";
                    $alertMesssage = '<i class="fa-fw fa fa-times"></i><strong>Success!</strong> The data was saved. Your accuracy score was '. number_format($data['accuracy'], 4, '.', ''). "%." ;

                }

            } else {
                $alertClass = "alert-danger";
                $alertMesssage = '<i class="fa-fw fa fa-times"></i><strong>Error!</strong> Impossible to move the uploaded file.';
            }
        }
    }


}

// Collect statistics about the database
$totalSubmissions = 0;
$totalStudents = 0;

if ( $database && isset($database->data) && count($database->data) > 0){
    $totalSubmissions = count($database->data);

    $studentsList = [];
    foreach($database->data as $item){
        if (isset($item->studentName) && !empty($item->studentName)){
            $studentsList[] = trim(strtolower($item->studentName));
        }
    }
    $studentsList = array_unique($studentsList);
    $totalStudents = count($studentsList);
}

?><!DOCTYPE html>
<html lang="en-us">
    <head>
        <meta charset="utf-8">

        <title><?PHP echo $projectname ?></title>

        <meta name="description" content="<?PHP echo $projectname ?>">

        <!-- #CSS Links -->
        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">

        <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production-plugins.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-skins.min.css">

        <!-- CSS custom style -->
        <link rel="stylesheet" type="text/css" media="screen" href="css/custom.css">

        <!-- #FAVICONS -->
        <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">

        <!-- #GOOGLE FONT -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

    </head>
    <body class="no-menu">

        <!-- MAIN PANEL -->
        <div id="main" role="main">

            <!-- MAIN CONTENT -->
            <div id="content">

                <!-- PAGE HEADER --> 
                <div class="row"  style="margin-top:20px">
                    <!-- col -->
                    <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
                        <h1 class="page-title txt-color-blueDark">
                            <a href='.' title='<?PHP echo $projectname ?>'><img src="../img/logo.png" alt="<?PHP echo $projectname ?>" width="200" height="40" /></a>
                            <span>
                                &nbsp;&GT;&nbsp;                                
                                <span style="text-transform: none; font-weight: 500;"><?PHP echo $pagetitle ?></span>
                            </span>
                        </h1>
                    </div>
                    <!-- end col -->

                    <!-- right side of the page with the sparkline graphs -->
                    <!-- col -->
                    <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">
                        <!-- sparks -->
                        <ul id="sparks" style="margin-top:20px">
                            <li class="sparks-info">
                                <button type="button" class="btn"><span class="txt-color-blue" data-toggle="modal" data-target="#submitModal" ><i class="fa fa-plus"></i>&nbsp;Submit Model</span></button>
                            </li>
                            <li class="sparks-info">
                                <h5> Submissions <span class="txt-color-blue"><i class="fa fa-cubes"></i>&nbsp;<?PHP echo $totalSubmissions ?></span></h5>
                            </li>
                            <li class="sparks-info">
                                <h5> Students <span class="txt-color-red"><i class="fa fa-user"></i>&nbsp;<?PHP echo $totalStudents ?></span></h5>
                            </li>
                        </ul>
                        <!-- end sparks -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- END PAGE HEADER --> 

                <!-- widget grid -->
                <div id="widget-grid">

                    <!-- Show the errors and alerts -->
                    <?PHP  if (!empty($alertMesssage)){ ?>
                        <div class="alert <?PHP echo $alertClass ?> fade in">
                        <button class="close" data-dismiss="alert"> &times; </button>
                        <?PHP echo $alertMesssage ?>
                        </div>
                    <?PHP } ?>

                    <!-- row -->
                    <div class="row">

                        <!-- NEW WIDGET START -->
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            <!-- Widget ID (each widget will need unique ID)-->
                            <div class="jarviswidget well" id="wid-id-0">
                                <header>
                                    <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
                                    <h2>Widget Title </h2>
                                </header>

                                <!-- widget div-->
                                <div>

                                    <!-- widget edit box -->
                                    <div class="jarviswidget-editbox">
                                        <!-- This area used as dropdown edit box -->
                                        <input class="form-control" type="text">	
                                    </div>
                                    <!-- end widget edit box -->

                                    <!-- widget content -->
                                    <div class="widget-body no-padding">

                                        <table id="resultstable" class="display projects-table table table-striped table-bordered table-hover" >
                                            <thead>
                                                <tr>
                                                    <th class=""><i class="fa fa-fw fa-user"></i>&nbsp;Student Name</th>
                                                    <th class=""><i class="fa fa-fw fa-calendar"></i>&nbsp;Date</th>
                                                    <th class=""><i  class="fa fa-fw fa-tag"></i>&nbsp;Dataset</th>
                                                    <th class=""><i class="fa fa-fw fa-trophy"></i>&nbsp;Accuracy</th>
                                                    <th class=""><i class="fa fa-fw fa-globe"></i>&nbsp;IP Address</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                                    </div>
                                    <!-- end widget content -->

                                </div>
                                <!-- end widget div -->

                            </div>
                            <!-- end widget -->

                        </article>
                        <!-- WIDGET END -->

                    </div>

                    <!-- end row -->

                    <!-- row -->

                    <div class="row">

                        <!-- a blank row to get started -->
                        <div class="col-sm-12">
                            <!-- your contents here -->
                        </div>

                    </div>

                    <!-- end row -->

                </div>
                <!-- end widget grid -->

            </div>
            <!-- END MAIN CONTENT -->

        </div>
        <!-- END MAIN PANEL -->

        <!-- Modal window -->

        <div class="modal fade" tabindex="-1" role="dialog" id="submitModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><span class="txt-color-blue"><i class="fa fa-cubes"></i>&nbsp; Submit Your Model File</span></h4>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Please upload your model as a joblib file
                    </div>
                    <div class="widget-body">
                        <form action="index.php?action=submitmodel" class="smart-form" novalidate="novalidate" method="POST"  enctype="multipart/form-data" >
                            <fieldset>
                                <section>
                                    <label class="input"> <i class="icon-append fa fa-user"></i>
                                        <input type="text" name="studentName" placeholder="Full Name" />
                                        <b class="tooltip tooltip-bottom-right">Please insert your full name</b> </label>
                                </section>
                                <section>
                                        <label class="select">
                                            <select name="dataset">
                                                <option value="" selected="" disabled="">Select the Dataset</option>
                                                <?PHP 
                                                    if ($handle = opendir('./datasets')) {
                                                        while (false !== ($entry = readdir($handle))) {

                                                            // Detect files that ends with '.x.npy' ignore the file '.y.npy' but it must exist
                                                            if ($entry != "." && $entry != ".." && $entry != 'index.html' && strpos($entry, '.x.npy') !== FALSE) {
                                                                $datasetName =  str_replace('.x.npy', '', $entry);
                                                                echo '<option value="'.$datasetName.'">'.$datasetName.'</option> ';
                                                            }
                                                        }
                                                        closedir($handle);
                                                    }
                                                ?>
                                            </select> <i></i>
                                        </label>
                                </section>
                                <section>                                   
                                    <input type="file" name="model" accept=".joblib,.JOBLIB" />  <br />                                   
                                </section>
                            </fieldset>
                            <footer>                                
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </footer>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
        <script data-pace-options='{ "restartOnRequestAfter": true }' src="js/plugin/pace/pace.min.js"></script>

        <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script>
            if (!window.jQuery) {
                document.write('<script src="js/libs/jquery-2.1.1.min.js"><\/script>');
            }
        </script>

        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
        </script>

        <!-- IMPORTANT: APP CONFIG -->
        <script src="js/app.config.js"></script>

        <!-- BOOTSTRAP JS -->
        <script src="js/bootstrap/bootstrap.min.js"></script>

        <!-- JARVIS WIDGETS -->
        <script src="js/smartwidgets/jarvis.widget.min.js"></script>

        <!-- EASY PIE CHARTS -->
        <script src="js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

        <!-- SPARKLINES -->
        <script src="js/plugin/sparkline/jquery.sparkline.min.js"></script>

        <!-- JQUERY VALIDATE -->
        <script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>

        <!-- JQUERY MASKED INPUT -->
        <script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>

        <!-- JQUERY SELECT2 INPUT -->
        <script src="js/plugin/select2/select2.min.js"></script>

        <!-- JQUERY UI + Bootstrap Slider -->
        <script src="js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

        <!-- browser msie issue fix -->
        <script src="js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

        <!--[if IE 8]>

        <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

        <![endif]-->

        <!-- MAIN APP JS FILE -->
        <script src="js/app.min.js"></script>

        <!-- PAGE RELATED PLUGIN(S) -->
        <script src="js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="js/plugin/datatables/dataTables.colVis.min.js"></script>
        <script src="js/plugin/datatables/dataTables.tableTools.min.js"></script>
        <script src="js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

        <script>

            $(document).ready(function () {
                pageSetUp();/* DO NOT REMOVE : GLOBAL FUNCTIONS! */
                loadScript("js/submissions.js"); // include our custom JS after the page is loaded
            });

        </script>

    </body>

</html>