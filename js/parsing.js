document.addEventListener("keydown", inputFunc)
document.addEventListener("keyup", inputFunc)
document.addEventListener("focusin", inputFunc)
document.addEventListener("focusout", inputFunc)

function inputFunc(e) {
    if (e.target && e.target.classList.contains("js-number")) {
        var num = e.target.value.replaceAll(/\D/g, "");
        e.target.value = num
    }
}

const modalOptions = {
    backdrop: "static",
    keyboard: false
}
const pageListEl = document.getElementById("pageListModal")
const pageListModal = new bootstrap.Modal(pageListEl, modalOptions)

pageListEl.addEventListener("show.bs.modal", function (e) {
    // pageListEl.querySelectorAll("input").forEach(el => {
    //     el.value = "";
    // })
    // pageListEl.setAttribute("onclick", "");
})

const preExecute = async (pKey) => {
    var response = await fetch("/API/parsing.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ pKey: pKey, action: "listInfo" })
    })
        .then((response) => response.json())
        .catch((err) => console.error(err));
    if (response['isList'] > 0) {

        document.getElementById("pliststartPage").value = response['pageStart'] > 0 ? response['pageStart'] : "";
        document.getElementById("plistendPage").value = response['pageEnd'] > 0 ? response['pageEnd'] : "";
        document.getElementById("pageList").setAttribute("onclick", "execute(" + pKey + ", true)")
        pageListModal.show()
    } else {
        execute(pKey, false);
    }

}


const execute = async (pKey, list = false) => {
    const yn = confirm("실행 하시겠습니까?");
    if (!yn) {
        return;
    }
    let body;
    if (list) {
        var s = document.getElementById("pliststartPage").value;
        var e = document.getElementById("plistendPage").value;
        if (!s) {
            s = "1";
        }
        if (!e) {
            e = "-1";
        }
        body = { pKey: pKey, action: "execute", s: s, e: e }
    } else {
        body = { pKey: pKey, action: "execute", s: "1", e: "-1" }
    }

    var pid = await fetch("/API/parsing.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(body)
    })
        .then((response) => response.text())
        .catch((err) => console.error(err));
    console.log(pid);
    location.reload();
}

const download = async (pKey, type) => {
    var res = await fetch("/API/parsing.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ pKey: pKey, action: "download", type: type })
    })
        .then((response) => response.json())
        .catch((err) => console.error(err));
    var a = document.createElement("a");
    a.href = res['href'];
    a.setAttribute("download", res['filename']);
    document.body.append(a);
    a.click();
    a.remove();
}

const remove = async (pKey) => {
    const yn = confirm("삭제 하시겠습니까?");
    if (!yn) {
        return;
    }
    var res = await fetch("/API/parsing.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ pKey: pKey, action: "delete" })
    })
        .then((response) => response.text())
        .catch((err) => console.error(err));
    console.log(res);
    location.reload();
}

document.addEventListener("DOMContentLoaded", function (e) {
    document.querySelectorAll(".titlename").forEach(ele => {
        ele.addEventListener("click", () => changeName(ele));
    })
    document.querySelectorAll(".changenamebtn").forEach(ele => {
        ele.addEventListener("click", () => changeNameSubmit(e, ele));
    })
})

const changeName = (ele) => {
    // var chk = ele.querySelector("div:first-child").classList.contains("d-none");
    // if (chk) {
    //     return;
    // }
    // var input = ele.querySelectorAll("div").forEach(e => {
    //     e.classList.toggle("d-none")
    // })
    // if (!chk) {
    //     ele.querySelector("input").select();
    // }
    var chk = ele.classList.contains("active");
    if (chk) {
        return;
    } else {
        ele.closest("tbody").querySelectorAll(".active").forEach(e => {

            e.classList.remove("active");
        })
        ele.classList.add("active");
    }
}

const changeNameSubmit = async (e, ele) => {
    var pKey = ele.closest("tr").dataset.pkey;
    var name = ele.closest("td").querySelector("input").value;
    var res = await fetch("/API/parsing.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ pKey: pKey, action: "changeName", name: name })
    })
        .then((response) => response.text())
        .catch((err) => console.error(err));
    location.reload();
}


const goParse = () => {
    var title = document.getElementById("pageTitle");
    var titleval = title.value ? title.value : "새 페이지";

    location.href = "/parse.php?title=" + titleval;
}
