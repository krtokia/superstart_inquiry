<?php
include_once "./_header.php";
include_once "./_commonHeader.php";

$mysqli = new mysqli('host.docker.internal', 'ss_inquiry', '2017tbtm!1004', 'superstart_inquiry', 3306) or die("Cannot connet");
$id = $_SESSION['logedin'];

$sql = "SELECT pKey, title, domain, cdt, status, udt FROM queries WHERE author = ? ORDER BY pKey DESC";
$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $id);

$stmt->execute();

$response = $stmt->get_result();

?>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <p><?=$id?>님의 리스트</p>
        </div>
        <div class="col-2 d-grid gap-2">
            <button class="btn btn-success"  data-bs-toggle="modal" data-bs-target="#addPageModal"><i class="fas fa-plus-circle"></i> &nbsp;페이지 추가</button>
        </div>
        
    </div>

    <table class="table" id="parsingTable">
        <thead>
            <th>페이지 이름</th>
            <th>도메인 주소</th>
            <th>작성 일시</th>
            <th></th>
            <th>최근 실행</th>
        </thead>
        <tbody>
            <?php

            while($row = $response->fetch_assoc()) {
            ?>
            <tr data-pkey="<?=$row['pKey']?>">
                <td class="titlename">
                    <div><span><?=$row['title']?></span></div>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="페이지 이름" value="<?=$row['title']?>">
                        <button class="btn btn-outline-secondary changenamebtn" type="button" >수정</button>
                    </div>
                </td>
                <td><?=$row['domain']?></td>
                <td><?=date("Y-m-d H:i:s", strtotime($row['cdt']))?></td>
                
                <td>
                    <div class="row g-3">
                    <?php
                        if($row['status'] == 1) {
                    ?>
                        <div class="col-12 d-grid gap-2">
                            <button class="btn btn-sm btn-light" disabled>실행중</button>
                        </div>
                    <?php
                        } else {
                    ?>
                        <div class="col-3 d-grid gap-2">
                            <button class="btn btn-sm btn-success" onclick="preExecute(<?=$row['pKey']?>)">실행</button>
                        </div>
                        <div class="col-3 d-grid gap-2">
                            <button class="btn btn-sm btn-primary" <?=$row['status']<2 ? "disabled": "onclick=\"download($row[pKey], 'json')\""?>>JSON</button>
                        </div>
                        <div class="col-3 d-grid gap-2">
                            <button class="btn btn-sm btn-primary" <?=$row['status']<2 ? "disabled": "onclick=\"download($row[pKey], 'excel')\""?>>EXCEL</button>
                        </div>
                        <div class="col-3 d-grid gap-2">
                            <button class="btn btn-sm btn-danger" onclick="remove(<?=$row['pKey']?>)">삭제</button>
                        </div>
                    <?php
                        }
                    ?>
                    </div>
                </td>
                <td><?=$row['udt'] ? $row['status'] > 1 ? date("Y-m-d H:i:s", strtotime($row['udt'])) : "실패함" : "-"?></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

</div>

<div class="modal fade" id="addPageModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addPageModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row flex-column g-2">
                    <div class="col-12">
                        <p>추가 할 페이지의 이름을 입력 해 주세요.</p>
                        <input type="text" id="pageTitle" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary" onclick="goParse()">적용</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pageListModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="pageListModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row flex-column g-2">
                    <div class="col-12">
                        <p class="mb-0">이 페이지는 리스트가 있습니다.</p>
                        <p class="mb-0">원하는 페이지 번호를 입력 하세요.</p>
                        <p class="mb-0">(미입력시 1페이지만 가져옵니다)</p>
                    </div>
                    <div class="col-12">
                       <div class="row justify-content-center">
                            <div class="col-5">
                                시작
                                <input type="text" id="pliststartPage" name="pliststartPage" placeholder="ex) 1" class="form-control js-number">
                            </div>
                            <div class="col-5">
                                끝
                                <input type="text" id="plistendPage" name="plistendPage" placeholder="ex) 100" class="form-control  js-number">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary" id="pageList" onclick="void(0)">적용</button>
            </div>
        </div>
    </div>
</div>



</body>

<script type="text/javascript" src="/js/parsing.js"></script>
<?php
include_once "./_footer.php";
?>