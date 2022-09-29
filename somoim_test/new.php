<?php
require_once "./common.php";
$pkey = $_GET['id'];
$qry = mysqli_query($mysqli, "SELECT * FROM test WHERE pKey = $pkey");
$row = mysqli_fetch_assoc($qry);

$mysqli->close();

$isUpdate = count($row) > 0 ? TRUE : FALSE;


$date = $isUpdate ? $row['bdt'] :  date("Y-m-d", strtotime("-1 days"));
$title = $isUpdate ? $row['title'] : "";

$val = $row['json'];
$val = json_decode($val, TRUE);

$people = array();

foreach($val as $vv) {
    $people = array_merge($people, $vv['people']);
}
$people2 = array_unique($people);
$people = $people2;
?>

<!DOCTYPE html>
<html>

<head>
    <title>TITLE</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        .item-box {
            margin-top: .5rem;
            border: 1px dashed #dee2e6!important;
            min-height: 3rem;
            position: relative;
            padding: 1rem;
        }
        .item-box::before {
            content: "눌러서 인원 추가/삭제";
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #dee2e6!important;
            z-index: -1;
        }
        .cha-box > div:not(:last-child) {
            padding-bottom: .5rem;
            border-bottom: 1px solid #000;
            margin-bottom: .5rem;
        }
        .people-checkbox+label {
            color: #212529;
            background-color: #FFF;
            /* border-color: #212529;
            border-width: 1.5px; */
            border: 1.5px solid #212529;
            font-size: .875rem;
            padding: .1rem .3rem;
        }
        .people-checkbox:checked+label {
            color: #FFF;
            background-color: #212529;
            border-color: #212529;
        }
        /* * {
            cursor: pointer;
        } */
    </style>
    <script src="./core.min.js"></script>
    <script src="./sha256.min.js"></script>
    <script src="./new.js?<?=time()?>" type="text/javascript" defer></script>
    
</head>
<body>
    <?php if($isUpdate) {
        ?>
    <div class="container p-3" id="updateDiv">
        <input type="hidden" id="passwordConfirm2" value="<?=$row['pw']?>">
        <input type="hidden" value="<?=$pw_hash?>">
        <input type="hidden" id="passwordConfirm3" value="">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <h5><?=$row['bdt']." ".$row['title']?></h5>
                <p>패스워드 입력</p>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" id="passwordConfirm">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-outline-success" onclick="passwordConfirm()">입력</button>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div class="container p-3 <?=$isUpdate ? "d-none" : ""?>" id="mainContainer">
        <h3>새 벙</h3>
        <form id="bungForm" method="post" action="./insert.php">
        <div class="row  gx-3">
             <div class="col-auto">
                <input class="form-control" name="date" type="date" value="<?=$date?>">
            </div>
            <div class="col">
                <input class="form-control" placeholder="벙 이름" name="title" value="<?=$title?>">
                <input class="form-control mt-2" placeholder="비밀번호" name="password" value="<?=$title?>" minlength="4" maxlength="30" onkeyup="itrim(this)" required>
            </div>
            <input type="hidden" name="val" id="formVal">
            <input type="hidden" name="update" value="<?=$pkey?>">
            <button class="d-none" type="submit" id="bungSubmit"></button>
        </div>
        </form>
        <div class="row mt-3">
            <div class="col-12">
                <h6>참여 인원</h6>
                <div class="row gx-1 flex-nowrap">
                    <div class="col-6"><input type="text" class="form-control" placeholder="이름" id="people-add"></div>
                    <div class="col-auto"><button class="btn btn-success" onclick="peopleAdd()">추가</button></div>
                    <!-- <div class="col-auto"><button class="btn btn-outline-success" onclick="testFunc()">목록</button></div> -->
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col">
                <div class="row g-2" id="people-all">
                    <?php
                        foreach($people as $p) {
                    ?>
                            <div class="col-auto">
                                <button class="btn btn-outline-dark btn-sm rounded-pill" onclick="removePeople(this)"><span class="name"><?=$p?></span> <i class="fas fa-minus-circle"></i></button>
                            </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="row mt-3 gy-2 cha-box" id="cha-container">
            <h6>정산 대상</h6>
            <?php
                    if($isUpdate) {
                        foreach($val as $v) {
            ?>
                    <div class="col-12 cha-boxinner">
                        <div class="row flex-nowrap gx-1">
                            <div class="col-5">
                                <input type="text" class="form-control" name="name" placeholder="이름(선택)" value="<?=$v['name']?>">
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control" name="price" placeholder="금액" value="<?=number_format($v['price'])?>">
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-danger text-nowrap" onclick="removeCha(this)">삭제</button>
                            </div>
                        </div>
                        <div class="item-box row gx-1" onclick="addChaPeople(this)">
                            <?php
                                foreach($v['people'] as $p) {
                            ?>
                                <div class="col-auto">
                                    <button class="btn btn-outline-dark btn-sm rounded-pill bg-white targetpeople" onclick="void()"><span class="name"><?=$p?></span></button>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
            <?php
                        }
                    } else {
                ?>
            <div class="col-12  cha-boxinner">
                

                
                <div class="row flex-nowrap gx-1">
                    <div class="col-5">
                        <input type="text" class="form-control" name="name" placeholder="가게이름(선택)">
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control" name="price" placeholder="금액">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger text-nowrap" onclick="removeCha(this)">삭제</button>
                    </div>
                </div>
                <div class="item-box row gx-1" onclick="addChaPeople(this)">
                </div>
            </div>
            <?php
                    }
                ?>
            <div class="col-12" id="cha-addbtn">
                <div class="d-grid gap-2">
                    <button class="btn btn-success" onclick="addChaBox()">차수 추가</button>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 d-grid gap-2">
                <button class="btn btn-danger" onclick="submitForm()">저장</button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="peopleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">인원 추가</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2" id="modalRow">
                    
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                <button type="button" class="btn btn-success" onclick="modalAddPeopleComp()">추가</button>
            </div>
            </div>
        </div>
    </div>
    <div class="d-none">
        
        <div id="new-people-tmp">
            <div class="col-auto">
                <button class="btn btn-outline-dark btn-sm rounded-pill" onclick="removePeople(this)"><span class="name"></span> <i class="fas fa-minus-circle"></i></button>
            </div>
        </div>
        <div id="new-people-modal-tmp">
            <div class="col-auto">
                <input type="checkbox" class="d-none people-checkbox" id="p0">
                <label class="rounded-pill" for="p0"></label>
            </div>
        </div>
        <div id="add-people-tmp">
            <div class="col-auto">
                <button class="btn btn-outline-dark btn-sm rounded-pill bg-white targetpeople" onclick="void()"><span class="name"></span></button>
            </div>
        </div>
        <div id="add-peoplebox-tmp">
            <div class="col-12 cha-boxinner">
                <div class="row flex-nowrap gx-1">
                    <div class="col-5">
                        <input type="text" class="form-control" name="name" placeholder="이름(선택)">
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control" name="price" placeholder="금액">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger text-nowrap" onclick="removeCha(this)">삭제</button>
                    </div>
                </div>
                <div class="item-box row gx-1" onclick="addChaPeople(this)">
                </div>
            </div>
        </div>
    </div>
</body>

</html>