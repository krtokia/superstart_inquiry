<?php
include_once "./_header.php";
?>

<body>
    <div class="w-100 h-100 row justify-content-center mt-5">

        <div class="col-2" style="min-height: 500px">
            <form id="form" action="/API/loginAPI.php" method="POST" class="h-100 w-100 needs-validation" data-type="login">
                <input type="hidden" name="type" value="login">
                <div class="w-100 h-100 shadow p-3">
                    <h2 class="text-center">로그인</h2>
                    <div>
                        <div class="form-group mb-3">
                            <input class="form-control" type="text" name="id" placeholder="IDENTIFIER" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="password" name="pw" placeholder="PASSWORD" required>
                        </div>
                        <div class="form-group mt-2 d-none" id="pw-confirm">
                            <input class="form-control" type="password" name="pwconfirm" placeholder="PASSWORD CONFIRM" disabled>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div>
                        <div class="row justify-content-end">
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success" id="loginbtn">로그인</button>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-4" id="registertxt">
                            <div class="col-auto">
                                <p><span style="font-size: 0.8rem; margin-right: 1rem;">또는</span><a href="javascript:register(true)">가입</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</body>

<script type="text/javascript" src="/js/login.js"></script>

<?php
include_once "./_footer.php";
?>