// common 함수
function setPosition(el, execute) {
    if (execute) {
        const len = Math.max(el.value.length - execute, 0);
        el.setSelectionRange(len, len);
    } else {
        const startPosition = el.value.length - el.selectionEnd;
        return startPosition;
    }
}

let targetCha = null;

function addChaPeople(el) {
    targetCha = el;
    var peoples = $("#people-all").find("span");
    $("#modalRow").html("")

    peoples.each((e, el) => {
        var $clone = $("#new-people-modal-tmp").children().clone();
        $clone.find("label").text($(el).text());
        $clone.find("input").attr("id", "p" + e);
        $clone.find("label").attr("for", "p" + e);

        var vali = $(targetCha).find("button");
        var check = false;
        vali.each((ee, eel) => {
            if ($(eel).text() == $(el).text()) {
                check = true;
            }
        })

        $clone.find("input").attr("checked", check);

        $("#modalRow").append($clone)
    })
    var modal = new bootstrap.Modal(document.getElementById("peopleModal"), { backdrop: true, keyboard: false, focus: true })
    modal.show();
}



function modalAddPeopleComp() {
    var target = $("#modalRow").find(".people-checkbox:checked+label");
    $(targetCha).html("");
    target.each((e, el) => {
        var name = $(el).text();
        var $clone = $("#add-people-tmp").children().clone();
        $clone.find("button").find("span.name").text(name);
        $(targetCha).append($clone)
    })

    var modal = bootstrap.Modal.getInstance(document.getElementById("peopleModal"))
    modal.hide();
}

function peopleAdd() {
    var name = $("#people-add").val();
    if (!name) {
        return;
    }
    var chk = true;
    $("#people-all").find("span.name").each((e, el) => {
        var nchk = $(el).text();
        if (nchk == name) {
            chk = false;
        }
    })
    if (chk) {
        var newel = $("#new-people-tmp").children().clone();
        $(newel).find("span.name").text(name)
        $("#people-all").append(newel)
        $("#people-add").val("")
    }
}

function addChaBox() {
    var $box = $("#add-peoplebox-tmp").children().clone();
    $box.insertBefore("#cha-addbtn");
}

function removePeople(el) {
    el.remove();
}

$(document).on("keyup", "input[name=price]", function (e) {
    var $target = $(e.target);
    var s = setPosition($target[0]);
    var price = $target.val();
    price = price.replace(/[^0-9]/g, "");
    price = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    $target.val(price);
    setPosition($target[0], s);
})


function submitForm() {
    var obj = [];
    var target = $(".container .cha-boxinner");
    target.each((e, el) => {
        var $t = $(el);
        var name = $t.find("input[name=name]").val();
        if (!name) {
            name = (e + 1) + "차";
        }
        var price = $t.find("input[name=price]").val().replace(/[^0-9]/g, "");
        if (!price) {
            price = 0;
        }
        var peoples = [];
        $t.find(".targetpeople").each((ee, eel) => {
            peoples.push($(eel).text());
        })
        obj.push({ name: name, price, price, people: peoples });
    })

    $("#formVal").val(JSON.stringify(obj));
    // $("#bungForm").submit();
    $("#bungSubmit").click();
}

function removeCha(el) {
    $(el).closest(".cha-boxinner").remove();
}

function testFunc() {
    alert("개발중");
}

function itrim(el) {
    var val = $(el).val().replace(/\s/g, "");
    $(el).val(val);
}

function passwordConfirm() {
    var passwordConfirm = $("#passwordConfirm").val();
    $.ajax({
        url: "./sha256.php",
        type: "POST",
        data: { pw: passwordConfirm },
    })
        .done(function (d) {
            var pw = d;
            var passwordConfirm2 = $("#passwordConfirm2").val();
            if (pw == passwordConfirm2) {
                $("input[name=password]").val(passwordConfirm);
                $("#updateDiv").remove();
                $("#mainContainer").removeClass("d-none");
            } else {
                alert("패스워드가 틀림.")
            }
        })
}