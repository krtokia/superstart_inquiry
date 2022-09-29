
let loginbtn;
let form;

document.addEventListener("DOMContentLoaded", function () {
    loginbtn = document.getElementById("loginbtn");
    form = document.getElementById("form");
    formInit();
})

function formInit() {
    form.addEventListener("submit", function (event) {
        sendForm();
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            console.log("asdf")
        }
        form.classList.add('was-validated')
    }, false)
}

function register() {
    form.querySelector("input[name='type']").value = "register";
    let pwconfirm = document.getElementById("pw-confirm");
    pwconfirm.classList.remove("d-none");
    pwconfirm.querySelector("input").disabled = false;
    pwconfirm.querySelector("input").required = true;
    pwconfirm.querySelector("input").value = "";
    loginbtn.innerText = "가입";
    document.getElementById("registertxt").classList.add("d-none");

}

function sendForm() {
    var type = form.querySelector("input[name='type']").value;
    validateForm(type);
}

function validateForm(type) {
    var idinput = document.querySelector("input[name='id']");
    var pwinput = document.querySelector("input[name='pw']");
    var pw2input = document.querySelector("input[name='pwconfirm']");

    idinput.required = true;
    idinput.setAttribute("minlength", "4");
    idinput.setAttribute("maxlength", "12");
    idinput.setAttribute("pattern", "[A-Za-z0-9_@\.]{4,12}");

    pwinput.required = true;
    pwinput.setAttribute("minlength", "4");
    pwinput.setAttribute("maxlength", "20");
    pwinput.setAttribute("pattern", "[A-Za-z0-9_@\.]{4,20}");

    if (type == "register") {
        pw2input.required = true;
        pw2input.setAttribute("minlength", "4");
        pw2input.setAttribute("maxlength", "20");
        pw2input.setAttribute("pattern", "[A-Za-z0-9_@\.]{4,20}");
    }
}