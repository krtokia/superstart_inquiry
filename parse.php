<?php
include_once "./_header.php";
include_once "./_commonHeader.php";

?>

<body>
    <div style="width: 100%; padding: 30px">
        <div class="row">
            <div class="col-auto">
                <a href="./parsingList.php" class="btn btn-outline-secondary">리스트 페이지로</a>
            </div>
            <div class="col-5">
                <!-- <input type="text" id="urlinput" class="form-control" value="https://www.yna.co.kr/search/index?query=제주%20태풍&ctype=A&from=20120101&to=20211231&period=diy"> -->
                    <input type="text" id="urlinput" class="form-control" value="https://finance.naver.com/item/board.naver?code=252670">
            </div>
            <div class=" col-auto">
                <button type="button" class="btn btn-secondary" onclick="geturl(true)">GO</button>
            </div>
            <div class="col-auto">
                <!-- <button type="button" class="btn btn-outline-success" onclick="addPage()">시작 페이지</button> -->
            </div>
                <div class="col-auto d-none beforestart">
                    <input type="checkbox" id="parsingStart">
                    <label for="parsingStart" class="btn btn-outline-secondary">
                        &nbsp;&nbsp;
                    </label>
                </div>
            <div class="col-auto">
                <button type="button" id="loadQueriBtn" onclick="loadQuerieList()" class="btn btn-outline-primary">가져오기</button>
            </div>
                
        </div>
    </div>
    <div class="d-flex">
        <div class="col-2 position-relative">
            <div class="savedContainer">
                <ul>
                </ul>
            </div>
            <div class="pageContainer">
                <div class="title-box">
                    <p>페이지 이름</p>
                    <div class="col-12 input-group">
                        <input type="text" placeholder="페이지 이름을 입력하세요." id="pageTitle" name="pageTitle" class="form-control" value="<?=$_GET['title']?>">
                    </div>
                </div>

                <ul id="pagesBox">
                    
                </ul>
                <div class=" d-grid gap-2">
                    <button class="btn btn-info  d-none beforestart" onclick="addColumn2(false)">컬럼 추가</button>

                    <!-- <button class="btn btn-primary isListed beforestart" id="listbtn" onclick="isPageList()">리스트</button> -->
                    <button class="btn btn-primary isListed d-none beforestart" id="listbtn" onclick="test2()">리스트</button>
                    <button class="btn btn-success d-none beforestart" id="completeBtn" onclick="addPage(true)">완료</button>
                    <button class="btn btn-success d-none" id="nextBtn" onclick="addPage()">다음페이지</button>
                    <button class="btn btn-primary d-none" id="compBtn" onclick="addPage()">페이지 완료</button>
                    <div class="shadow rounded p-3 d-flex flex-column d-none" id="plist-box">
                        <input type="hidden" id="plistlistQuery">
                        <input type="hidden" id="plisturl">
                        <input type="hidden" id="plistpagePattern">
                        <input type="hidden" id="plistpageType" value="">
                        <div class="col">
                            <div>
                                <p>쿼리</p>
                                <ul>
                                </ul>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-5">
                                    시작 페이지
                                    <input type="text" id="pliststartPage" name="pliststartPage" placeholder="ex) 1" class="form-control js-number">
                                </div>
                                <div class="col-5">
                                    끝 페이지
                                    <input type="text" id="plistendPage" name="plistendPage" placeholder="ex) 100" class="form-control  js-number">
                                </div>
                            </div>
                        </div>
                        <div class="align-self-end col-auto">
                            <button class="btn btn-danger" onclick="endPageList(false)">삭제</button>
                            <button class="btn btn-primary" onclick="endPageList(true)">적용</button>
                        </div>
                    </div>
                </div>

            </div>
            <div style="max-width: 100%">
                <div id="query"></div>
                <div style="height: 30px"></div>
                <!-- <p>result:</p> -->
                <ul id="resultBox"></ul>
            </div>
        </div>
        <div class="col-9">
            <iframe id="ss-iframe" style="width: 100%; height: 800px" src="" sandbox="allow-scripts allow-same-origin"></iframe>
            <iframe id="ss-iframe2" style="width: 100%; height: 800px" src="" sandbox="allow-scripts allow-same-origin"></iframe>
        </div>
        <div class="col-1 position-relative">
            <div class="qs-box" id="qs-box">
                <div class="row g-2">
                    <div class="col-12">
                        <div class="row flex-wrap g-2">
                            <div class="col-auto">
                                <input type="radio" class="ctsinput d-none" name="columnTypeSelect" value="textS" id="textS" disabled />
                                <label for="textS" class="ctslabel">단건 텍스트</label>
                            </div>
                            <div class="col-auto">
                                <input type="radio" class="ctsinput d-none" name="columnTypeSelect" value="textM" id="textM" disabled />
                                <label for="textM" class="ctslabel">다중 텍스트</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row flex-wrap g-2">
                            <div class="col-auto">
                                <input type="radio" class="ctsinput d-none" name="columnTypeSelect" value="hrefS" id="hrefS" disabled />
                                <label for="hrefS" class="ctslabel">단건 링크</label>
                            </div>
                            <div class="col-auto">
                                <input type="radio" class="ctsinput d-none" name="columnTypeSelect" value="hrefM" id="hrefM" disabled />
                                <label for="hrefM" class="ctslabel">다중 링크</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row flex-wrap g-2">
                            <div class="col-auto">
                                <input type="radio" class="ctsinput d-none" name="columnTypeSelect" value="childS" id="hrefchildS" disabled />
                                <label for="href" class="ctslabel">단건 페이지</label>
                            </div>
                            <div class="col-auto">
                                <input type="radio" class="ctsinput d-none" name="columnTypeSelect" value="childM" id="hrefchildM" disabled />
                                <label for="hrefchildM" class="ctslabel">다중 페이지</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <!-- <div class="row flex-wrap g-2">
                            <div class="col-auto">
                                <input type="radio" class="ctsinput d-none" name="columnTypeSelect" value="list" id="list" disabled />
                                <label for="list" class="ctslabel">리스트</label>
                            </div>
                        </div> -->
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-auto">
                                <button type="button" class="btn btn-secondary" onclick="parsingBtn(null)">취소</button>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary" onclick="acceptSelect()">적용</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sel-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="selectModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">완료 하시겠습니까?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row flex-column g-2">
                        <div class="col-12">
                            <p>이 페이지의 이름을 입력 해 주세요.</p>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                    <button type="button" class="btn btn-primary">적용</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="lModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loadModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">이전 작업에서 가져오기</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row flex-column g-2">
                        <div class="col-12">
                            <ul id="s-box" class="p-1">
                                <li>
                                    <label>
                                        <div class="col">
                                            1번
                                        </div>
                                        <input type="radio" name="s-item">
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                    <button type="button" class="btn btn-primary" onclick="getSavedQuerie()">적용</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="list-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="listModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row flex-column g-2">
                        <div class="col-12">
                            <p>방식을 선택하세요.</p>
                        </div>
                        <div class="col-12 d-none">
                            <input type="radio" id="type1" name="listtype" value="select" class="d-none">
                            <label for="type1" class="btn btn-outline-primary">페이지에서 선택</label>
                            <input type="radio" id="type2" name="listtype" value="input" class="d-none">
                            <label for="type2" class="btn btn-outline-primary">직접 입력</label>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <textarea rows="5" id="listta" class="form-control" style="resize: none"></textarea>
                                </div>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-auto">
                                    시작 페이지
                                    <input type="text" id="modalpliststartPage" name="modalpliststartPage" placeholder="ex) 1" class="form-control js-number">
                                </div>
                                <div class="col-auto">
                                    끝 페이지
                                    <input type="text" id="modalplistendPage" name="modalplistendPage" placeholder="ex) 100" class="form-control  js-number">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="test3(false)">취소</button>
                    <button type="button" class="btn btn-primary" onclick="test3(true)">적용</button>
                </div>
            </div>
        </div>
    </div>

    

    <div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loaddingModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered justify-content-center">
            <div class="d-flex justify-content-center align-items-center">
                <div class="spinner-border text-light" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>



    <ul id="templates" class="d-none">
        <li id="pagesOuter_000">
            <div class="d-flex flex-wrap position-relative">
                <!-- <div class="position-absolute del-btnBox">
                                <button class="pageDelBtn"></button>
                            </div> -->
                <div class="col-12 textscroll">
                    https://finance.naver.com/sise/sise_market_sum.naver
                </div>
                <div class="col-12">
                    <div class="input-group">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="radio" name="selectPage">
                        </div>
                        <input type="text" class="form-control" name="p-name" placeholder="페이지 이름">
                        <button class="btn btn-danger input-group-text">삭제</button>
                    </div>
                </div>

            </div>
        </li>
        <!-- <li id="pagesOuter_000">

        </li> -->
        <li id="pagesInner_000" class="itemListBox">
            <div class="d-flex flex-wrap gap-2">
                <div class="col-12 input-group">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="selectColumn">
                    </div>
                    <input type="text" placeholder="컬럼명 입력" name="columnName" class="c-name form-control">
                    <select name="dataType" class="form-select">
                        <option value="text">텍스트</option>
                        <option value="url">URL</option>
                        <option value="page">페이지</option>
                    </select>
                    <button type="button" onclick="removeColumn(this)" class="btn btn-danger">X</button>
                </div>
                <div class="col-12">
                    <!-- <button class="btn btn-primary" onclick="goNextPage(this)">다음페이지 선택</button> -->
                </div>
                <!-- <div class="col-12">
                <select class="selbox form-select">
                    <option value="text">텍스트</option>
                </select>
            </div> -->
                <div class="col-12 databox">

                </div>
            </div>
        </li>
        <li id="pagesInner_001" class="itemListBox">
            <div class="d-flex flex-wrap">
                <!-- <p class="mb-0 title">Column 1</p> -->
                <div class="col-12 input-group">
                    <input type="text" placeholder="컬럼명 입력" name="columnName" class="c-name form-control">

                </div>
                <div class="col-12 mt-1">
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <button type="button" onmousedown="viewList(this, true)" onmouseup="viewList(this, false)" class="btn btn-success"><i class="fas fa-eye"></i></button>
                        </div>
                        <div class="col-auto">
                            <button type="button" onclick="editColumn(this)" class="btn btn-primary"><i class="fas fa-pen"></i></button>
                        </div>
                        <div class="col-auto">
                            <button type="button" onclick="removeColumn(this)" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                        </div>


                    </div>

                    <!-- <div class="d-none">
                                    Hello WOrld
                                </div>
                                <a class="more-btn" onclick="morebtn(this)" href="javascript:void(0)">
                                    <i class="fas fa-angle-down"></i>
                                </a> -->
                </div>
                <div class="col-12 ">

                </div>
                <div class="col-12 databox">
                    <input type="hidden" name="query" value="">
                    <input type="hidden" name="dataType" value="">
                    <input type="hidden" name="typeAvailable" value="">
                </div>
            </div>
        </li>
        <li id="pagesInner_002" class="itemListBox">
            <div class="d-flex flex-wrap">
                <!-- <p class="mb-0 title">Column 1</p> -->
                <div class="col-12 input-group">
                    <input type="text" placeholder="컬럼명 입력" name="columnName" class="c-name form-control" value="새 컬럼">

                </div>
                <div class="col-12 mt-1">
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <button type="button" onclick="parseClick(this)" class="btn btn-primary"><i class="fas fa-mouse-pointer"></i></button>
                        </div>
                        <div class="col-auto">
                            <button type="button" onmousedown="viewList(this, true)" onmouseup="viewList(this, false)" class="btn btn-success"><i class="fas fa-eye"></i></button>
                        </div>
                        <!-- <div class="col-auto">
                            <button type="button" onclick="editColumn(this)" class="btn btn-primary"><i class="fas fa-pen"></i></button>
                        </div> -->
                        <div class="col-auto">
                            <button type="button" onclick="removeColumn(this)" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                        </div>


                    </div>
                </div>
                <div class="col-12 ">

                </div>
                <div class="col-12 databox">
                    <input type="hidden" name="query" value="">
                    <input type="hidden" name="dataType" value="">
                    <input type="hidden" name="typeAvailable" value="">
                </div>
            </div>
        </li>
        <li id="pagesInner_003" class="itemListBox childBox">
            <div class="d-flex flex-wrap">
                <!-- <p class="mb-0 title">Column 1</p> -->
                <div class="col-12 input-group">
                    <input type="text" placeholder="컬럼명 입력" name="columnName" class="c-name form-control" value="새 컬럼">

                </div>
                <div class="col-12 mt-1">
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <button type="button" onclick="parseClick(this)" class="btn btn-primary"><i class="fas fa-mouse-pointer"></i></button>
                        </div>
                        <div class="col-auto">
                            <button type="button" onmousedown="viewList(this, true)" onmouseup="viewList(this, false)" class="btn btn-success"><i class="fas fa-eye"></i></button>
                        </div>
                        <!-- <div class="col-auto">
                            <button type="button" onclick="editColumn(this)" class="btn btn-primary"><i class="fas fa-pen"></i></button>
                        </div> -->
                        <div class="col-auto">
                            <button type="button" onclick="removeColumn(this)" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                        </div>


                    </div>
                </div>
                <div class="col-12 ">

                </div>
                <div class="col-12 databox">
                    <input type="hidden" name="query" value="">
                    <input type="hidden" name="dataType" value="">
                    <input type="hidden" name="typeAvailable" value="">
                </div>
            </div>
        </li>
    </ul>
</body>
<script type="text/javascript" src="/js/engine_vars.js"></script>
<script type="text/javascript" src="/js/engine_server.js"></script>
<script type="text/javascript" src="/js/engine_client.js"></script>
<script type="text/javascript">
    const stt = () => {
        // loading.show = !loading.status;
        // modal.show();
    }

    

    const test2 = () => {
        listModal.show();
    }

    const test3 = (bool) => {
        if(bool) {
            var ta = document.getElementById("listta");
            var taval = ta.value;
            var tavalarr = taval.split("\n");
            if(tavalarr.length < 3) {
                alert("3개 이상 작성 해 주세요.");
                return;
            }

            var startp = document.getElementById("modalpliststartPage");
            var starte = document.getElementById("modalplistendPage");

            if(!startp.value && !starte.value) {
                alert("시작 페이지와 끝 페이지는 필수 값입니다.");
                return;
            }

            var ul = document.querySelector("#plist-box ul");
            ul.innerHTML = "";

            tavalarr.forEach(v => {
                var li = document.createElement("li");
                var a = document.createElement("a");
                a.innerText = v;
                a.title = v;
                a.href = v;
                a.target = "_blank"
                li.append(a);
                ul.append(li);
            })

            document.getElementById("pliststartPage").value = startp.value;
            document.getElementById("plistendPage").value = starte.value;

            listModal.hide();
        }
        endPageList(bool);

        console.log(document.getElementById("pliststartPage").value);
    }


    const st = () => {
        initQsbox(null, false, false);
        showQsbox();
    }
</script>

<?php
include_once "./_footer.php";
?>