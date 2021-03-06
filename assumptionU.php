<?php include "header.php"; ?>
        <nav>
            <!-- http://codepen.io/himanshu/pen/syLAh -->
            <div class="progresss pull-right">
              <div class="circle done">
                <span class="label"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></span>
                <span class="title">Import</span>
              </div>
              <span class="bar done"></span>
              <div class="circle done">
                <span class="label"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></span>
                <span class="title">Calculate</span>
              </div>
              <span class="bar done"></span>
              <div class="circle active">
                <span class="label">3</span>
                <span class="title">Result</span>
              </div>
        </nav>
        <h3 class="text-muted">Data Analyst for Business</h3>
    </div>
    	<h2>ผลทดสอบสมมตฐานเกี่ยวกับค่าเฉลี่ยประชากรชุดเดียว (µ)</h2>
    	<?php 
    		$file= fopen("uploads/data.csv", "r");
    		$data = fgetcsv($file);
    		//show data list
            $row = 1;
            $group = array();
            if (($handle = fopen("uploads/data.csv", "r")) !== FALSE) {
                //$data = fgetcsv($handle, 1000, ",");
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    $row++;
                    for ($c=0; $c < $num; $c++) {
                        array_push($group, $data[$c]);
                    }
                }
                fclose($handle);
            }
            //print_r($group);
    		echo "<br>";
            function sd_square($x, $mean) { return pow($x - $mean,2); }
            function sd($array) { return sqrt(array_sum(array_map("sd_square", $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)-1) );   }

            
            set_error_handler('exceptions_error_handler');

            //case n more than 200
            function exceptions_error_handler($severity, $message, $filename, $lineno) {
              if (error_reporting() == 0) {
                return;
              }
              if (error_reporting() & $severity) {
                throw new ErrorException($message, 0, $severity, $filename, $lineno);
              }
            }

            $tcsv = file_get_contents("uploads/t-table.csv");
            $lines = explode(PHP_EOL, $tcsv);
            $tArray = array();
            foreach ($lines as $line) {
                $tArray[] = str_getcsv($line);
            }
    	?>
            <table class="table table-bordered">
                <tr class="active">
                    <th rowspan="2">N</th>
                    <th rowspan="2">Mean</th>
                    <th rowspan="2">SD</th>
                    <th rowspan="2">Reliability</th>
                    <th rowspan="2">t</th>
                    <th colspan="3">α</th>
                </tr>
                <tr class="active">
                    <th>1-tail</th>
                    <th>Lower (2-tails)</th>
                    <th>Upper (2-tails)</th>
                </tr>
                <tr>
                    <?php

                    $rel = array("90%", "95%", "98%", "99%", "99.8%" ,"99.9%");
                    $sig = array(0.1, 0.05, 0.02, 0.01, 0.002 ,0.001);
                    $onetail = array(1=>0,3=>2);
                    $n = count($group);
                    $mean = array_sum($group)/$n;
                    $sd = sd($group);
                    $t=($mean-$_POST['testValue'])/($sd/sqrt($n));

                    try {
                        $Ttable2=$tArray[$n-2][$_POST["sig"]];
                        $Ttable1=$tArray[$n-2][$onetail[$_POST["sig"]]];
                    } catch (Exception $e) {
                        $Ttable2=$tArray[200][$_POST["sig"]];
                        $Ttable1=$tArray[200][$onetail[$_POST["sig"]]];
                    }

                    // $t=$tArray[$n-2][$_POST["sig"]];
                    echo "<td>".$n."</td>";
                    echo "<td>".number_format($mean,4,'.',',')."</td>";
                    echo "<td>".number_format($sd,4,'.',',')."</td>";
                    echo "<td>".$rel[$_POST["sig"]]."</td>";
                    echo "<td>".number_format($t,4,'.',',')."</td>";
                    echo "<td>".$Ttable1."</td>";
                    echo "<td>"."-".$Ttable2."</td>";
                    echo "<td>".$Ttable2."</td>";
                    echo "<br>";
                     ?>
                </tr>
                
            </table>
        <?php
    		fclose($file);
    	?>
        <div style="margin-bottom: 20px;">
            <a class="btn btn-success" href="/dataanalyst">HOME</a>
            <button class="btn btn-success" onclick="goBack()">Other Calculation</button>
        </div>
        <script>
            function goBack(){
                window.history.back();
            }
        </script>
<?php include "footer.php"; ?>