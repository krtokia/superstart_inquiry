

<!DOCTYPE html>
<html>

<head>
    <title>TITLE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="./inb.js" type="text/javascript" defer></script>
    <style>
        .result-tr {
            font-weight: bold;
            text-align: right;
        }
        .price-table, .pr-name {
            text-align: right;
        }
        .resulttbl {
            /* table-layout: fixed; */
            white-space: nowrap;
        }
        .resulttbl th {
            width: max-content;
        }
    </style>
</head>
<body>
    <?php
require_once "./common.php";

$insert_id = isset($insert_id) ? $insert_id : $_GET['pkey'];

$qry = mysqli_query($mysqli, "SELECT * FROM test WHERE pKey = $insert_id");
$row = mysqli_fetch_assoc($qry);

$date = $row['bdt'];
$title = $row['title'];
$val = $row['json'];
$val = json_decode($val, TRUE);

$cha = array_column($val, "name");

$people = array();

foreach($val as $vv) {
    $people = array_merge($people, $vv['people']);
}
$people2 = array_unique($people);
$people = $people2;

?>
    <div class="container p-3">
        <h3><?=$date." ".$title?> 정산</h3>
        <div style="overflow-x: auto; padding: 1rem">
        <table class="table table-bordered resulttbl">
            <thead>
                <th></th>
                <th>금액</th>
                <?php
                    foreach($people as $k) {
                ?>
                    <th class="text-center"><?=$k?></th>
                <?php
                    }
                ?>
                
            </thead>
            <tbody id="result-target">
                <?php
                    foreach($val as $k) {
                        $price = $k['price'];
                        $peoples = count($k['people']);
                        $price = ceil($price / $peoples);
                ?>
                    <tr>
                        <td class="text-center"><?=$k['name']?></td>
                        <td class="text-end"><?=number_format($k['price'])?>원</td>
                        <?php 
                            foreach($people as $p) {
                        ?>
                            <td class="price-table" data-people="<?=$p?>">
                            <?php
                                if(array_search($p, $k['people']) !== false) {
                                    echo number_format($price)."원";
                                }
                            ?>
                            </td>
                        <?php
                            }
                        ?>
                    </tr>
                <?php
                    }
                ?>
                <tr class="result-tr">
                    <td colspan="2">합계</td>
                    <?php
                    foreach($people as $k) {
                    ?>
                        <td class="result-table" data-people="<?=$k?>"></td>
                    <?php
                        }
                    ?>
                    
                </tr>
            </tbody>
        </table>
        </div>
        <div class="mt-5 <?=isset($copyCode) ? "" : "d-none"?>">
            <input id="url" type="text" class="form-control" value="https://inquiry.co.kr/somoim_test/board.php?pkey=<?=$insert_id?>" readonly>
            <button class="btn btn-success" onclick="copyUrl()">주소 복사</button>
        </div>
        <div class="mt-5 <?=isset($copyCode) ? "d-none" : ""?>">
            <!-- <input id="url" type="text" class="form-control" value= readonly> -->
            <a class="btn btn-outline-primary" href="https://inquiry.co.kr/somoim_test/new.php?id=<?=$insert_id?>">수정</a>
        </div>
    </div>
</body>
</html>