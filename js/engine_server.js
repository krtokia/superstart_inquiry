/*
* ###########################################
* parent side
* ###########################################
*/

const loadStart = () => {
    loading.show();
}
const loadEnd = () => {
    loading.hide();
}

function timestamp()
{
    return Math.floor(new Date().getTime());
}

function genPage(strarr) {
    var length = strarr[0].toString().length;
    var difflen = 0;
    for (var i = 0; i < strarr.length; i++) {
        var thislen = strarr[i].toString().length;
        if (thislen != length) {
            break;
        } else {
            difflen = i
        }
    }

    var originstr = strarr[0].toString();
    var strsplice = "";

    var diffchk = false;
    var num1 = 0;
    for (var i = 0; i < length; i++) {
        if (diffchk) {
            break;
        }
        strsplice = strsplice + originstr.slice(i, i + 1);
        for (var j = 0; j <= difflen; j++) {
            var thisval = strarr[j].toString().slice(0, i + 1);
            if (strsplice != thisval) {
                diffchk = true;
                num1 = i;
                break;
            }
        }
    }
    if (originstr.slice(0, num1).length < 1) {
        return null;
    }
    var finaldiff = originstr.slice(0, num1) + "[[:page:]]";
    var ismore = "";
    if (length > num1 + 1) {
        var diffchk = false;
        var num2 = 0;
        for (var i = num1; i < length; i++) {
            diffchk = false;
            var morechk = originstr.slice(i, i + 1);
            for (var j = 0; j <= difflen; j++) {
                var thisval = strarr[j].toString().slice(i, i + 1);
                if (morechk == thisval) {
                    num2 = i;
                    diffchk = true;
                } else {
                    num2 = 0;
                    diffchk = false;
                    break;
                }
            }
            if (diffchk) {
                break;
            }
        }
        ismore = originstr.slice(num2, length);
    }
    finaldiff = finaldiff + ismore;
    return finaldiff;
}
// common func end

// Messaging
window.addEventListener('message', function(e) {
    console.log(e.data); // { hello: 'parent' }

    if (e.data.href) {
        messageRecieved(e.data.href);
    }
});

async function messageRecieved(href) {
    loading.show = true;
    // var domain = document.getElementById("urlinput").value.replace(/^(https?\:\/\/[^\/]+).*/, "$1");
    var domain = SAVED_URL.replace(/^(https?\:\/\/[^\/]+).*/, "$1");
    var url = domain + href
    this.document.getElementById("urlinput").value = url;
    await geturl();
    addPageSub(true);
}

// Start
function allInit() {
    STORAGE.clear();
    document.getElementById("pagesBox").innerHTML = "";
    document.querySelector(".savedContainer ul").innerHTML = "";
    document.querySelectorAll("#plist-box input").forEach(e => {
        e.value = "";
    })
    _init();
}

document.addEventListener("DOMContentLoaded", _init)
async function _init() {

    var a = STORAGE.getItem("page");

    // SAVED_URL = document.getElementById("urlinput").value;
    IS_LOAD_END = false;
    checkSession();
    nextPageCheck();
}
function checkSession() {
    var chk = STORAGE.getItem("page");
    if (chk !== null) {
        // session 있음
    }
    for (var i = 0; i < STORAGE.length; i++) {
        console.log(STORAGE.getItem(STORAGE.key(i)))
    }
}

function iframeInit(e) {
    var iframeInner = iframe.contentWindow.document

    var cssObj = iframeInner.createElement("link")
    cssObj.rel = "stylesheet";
    cssObj.href = "/css/front.css";
    iframeInner.head.appendChild(cssObj);

    _window = iframe.contentWindow;
    _document = _window.document;
    // DOMAIN_NAME = document.getElementById("urlinput").value.match(/^https?\:\/\/[^\/]+/)[0];
    DOMAIN_NAME = SAVED_URL;
    _document.addEventListener("mouseup", viewList);
    _document.addEventListener("mouseleave", focusInit)
    __initStart();
    loading.show = false;
}

const focusInit = (e) => {
    var chk = document.getElementById("parsingStart")
    if (chk.checked) {
        _document.querySelectorAll("*[data-sshighlight='true']").forEach(e => {
            e.removeAttribute("data-sshighlight")
        })
    }
}

iframe.onload = function (e) {
    loading.show = true;
    iframeInit();
}

// Auto functions
document.addEventListener("focusout", function (e) {
    if (e.target.classList.contains("c-name")) {
        if (e.target.value === "" || e.target.value === undefined || e.target.value === null) {
            e.target.focus();
        }
    }
})
document.addEventListener("keydown", inputFunc)
document.addEventListener("keyup", inputFunc)
document.addEventListener("focusin", inputFunc)
document.addEventListener("focusout", inputFunc)
document.addEventListener("keypress", function (e) {
    // console.log("hello, world!")
})

document.addEventListener("mouseup", viewList);

function inputFunc(e) {
    if (e.target && e.target.classList.contains("js-number")) {
        var num = e.target.value.replaceAll(/\D/g, "");
        e.target.value = num
    }
}

function viewList(e, show) {
    // if (!e.tagName && IS_MOUSE_DOWN) {
    //     IS_MOUSE_DOWN = false;
    //     _clickDataSet(null, false);
    //     return;
    // }

    if (IS_MOUSE_DOWN) {
        console.log("ACTIVE")
        IS_MOUSE_DOWN = false;
        return;
    }
    try {
        if (e.classList.contains("viewBtn")) {
            IS_MOUSE_DOWN = show;
        }
    } catch (e) {
        if (!parsingStart.checked) {
            
            _clickDataSet(null, false)
        }
        return;
    }

    
    const query = e.closest("li").querySelector("input[name=query]").value;
    const type = e.closest("li").querySelector("input[name=dataType]").value;

    
    _clickDataSet(query, show);
}



document.addEventListener("change", function (e) {
    var target = e.target;
    switch (target.name) {
        case "columnTypeSelect":
            _showSelectedItems(target.value);
            break;
        
    }
    // switch (target.tagName) {
    //     case "INPUT":
    //         switch (target.name) {
    //             case "selectColumn":
    //                 var parent = target.closest("*[data-page]");
    //                 SELECT_MODE = parent.querySelector("select[name='dataType']").value;
    //                 SELECTED_COL = parent.dataset.page;
    //                 columnEmpty();
    //                 renderChildSelect();
    //                 break;
    //         }
    //         break;
    //     case "SELECT":
    //         switch (target.name) {
    //             case "dataType":
    //                 var parent = target.closest("*[data-page]");
    //                 SELECT_MODE = parent.querySelector("select[name='dataType']").value;
    //                 SELECTED_COL = parent.dataset.page;
    //                 modeCheck();
    //                 var checkbox = parent.querySelector("input[name=selectColumn]");
    //                 if (checkbox.checked) {
    //                     renderChildSelect();
    //                 } else {
    //                     checkbox.click();
    //                 }
                    
    //                 // _modeChangedCheck();
    //                 // renderChildSelect();
    //                 break;
    //         }
    //         break;
    // }
});
modalEl.addEventListener("hidden.bs.modal", e => {
        console.log("modal has removed")
    })

// query selector 관련
function initQsbox(selected = null, multi = false, href = false) {
    
    
    var query = "#textS";
    var checked = "#textS";

    // console.log(TEMP_QUERY);
    // console.log(CONFIRM_QUERY)

    var tmp;

    let single = false;

    if (selected !== null) {
        tmp = qsbox.querySelector("input[value='" + selected + "']");
        var chk = _document.querySelectorAll(CONFIRM_QUERY);
        if (multi && chk.length > 1) {
            single = true;
        }
    } else {
        tmp = qsbox.querySelector("input[name='columnTypeSelect']:checked");
    }

    qsbox.querySelectorAll("input[type=radio]").forEach(e => {
        e.disabled = true;
        // e.checked = false;
    })
    
    if (single) {
        query = "#textM";
        if (href) query += ",[id^=href]:not([id$=S]),[value=list]";
    } else {
        if (multi && href) query += ",[id^=href],[id$=M],[value=list]";
        else if (multi) query += ",#textM";
        else if (href) query += ",[id^=href]:not([id$=M]),[value=list]";
        else query += "";
    }
    console.log(query)


    // if (multi !== false) {
    //     query += ",#textM"
    // }
    // if (href !== false) {
    //     query += ",#hrefS";
    //     if (multi !== false) query += ",#hrefM";
    // }
    qsbox.querySelectorAll(query).forEach(e => {
        e.disabled = false;
    })

    
    if (tmp !== null && tmp.disabled !== true) {
        checked = "#"+tmp.id;
    }

    qsbox.querySelector(checked).checked = true;

    

}

function showQsbox() {
    if (SELECTED_COL) {
        console.log(SELECTED_COL);
    }
    qsbox.classList.add("show");
}

function cancleSelect() {
    QS_AVAILABLE = "";
    _clickDataSet(null, false);
    initQsbox();
    qsbox.classList.remove("show");
}
function acceptSelect(update = false) {

    var dataType = qsbox.querySelector("input[name=columnTypeSelect]:checked")
    if (!dataType) {
        return;
    }

    dataType = dataType.value;
    var data = { "dataType": dataType, "query": CONFIRM_QUERY }
    if (UPDATE_MODE) {
        editColumnAfter(data, UPDATE_MODE);
        // return;
    } else {
        addColumn(data, QS_AVAILABLE);
    }
    QS_AVAILABLE = "";
    CONFIRM_QUERY = "";

    qsbox.classList.remove("show");
    _clickDataSet(null, false);
    parsingBtn(null);
}

// Functions
async function geturl(force = false) {
    
    if (force) {
        if (SAVED_URL != "") {
            const yn = confirm("모든 정보가 초기화 됩니다. 계속 하시겠습니까?");
            if (yn) {
                allInit();
            } else {
                document.getElementById("urlinput").value = SAVED_URL;
                return;
            }
        }
    }


    loading.show = true;
    var urlEl = document.getElementById("urlinput");
    var url = urlEl.value;
    var regex = /[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/;
    var valid = regex.test(url);

    if (!valid) {
        alert("URL Error");
        return;
    }
    var response = await fetch("/render.php", {
        method: "POST",
        mode: 'cors',
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({url: url})
    })
        .then((response) => response.text())
        .catch(e => console.log(e));
    console.log(response);
    var iframe = document.getElementById("ss-iframe");
    iframe.contentWindow.document.open();
    iframe.contentWindow.document.write(response);
    iframe.contentWindow.document.close();
    SAVED_URL = url;
}

const parsingStart = document.getElementById("parsingStart");
try {
    parsingStart.addEventListener("change", parsingBtn)
} catch (e) { console.error("Cannot find element parsingStart"); }

function parsingBtn(e) {
    
    if (parsingStart.checked && e !== null) {
        // columnEmpty();
        // if (SELECTED_COL === undefined) {
        //     document.querySelector("li[data-page]:last-child input[name='selectColumn']").click();
        // }
        
        parsingStart.nextElementSibling.innerText = "중지";
        _parsingStart();
    // } else if (e === null) {
    //     parsingStart.checked = true;
    //     parsingStart.nextElementSibling.innerText = "중지";
    //     _parsingStart();
    } else {
        parsingStart.checked = false;
        parsingStart.nextElementSibling.innerText = "시작";
        UPDATE_MODE = false;
        _parsingStop();
    }
}


function isPageList(force = false) {
    if (parsingStart.checked || force) {
        SELECT_MODE = undefined;
        parsingStart.checked = false;
        parsingStart.nextElementSibling.innerText = "시작";
        _parsingStop();
    } else {
        SELECT_MODE = "plist"
        parsingStart.checked = true;
        parsingStart.nextElementSibling.innerText = "중지";
        _parsingStart();
    }
}

function endPageList(boolean = false) {
    var box = document.getElementById("plist-box");
    if (boolean) {
        var hrefList = box.querySelectorAll("ul > li > a")
        var strarr = []
        hrefList.forEach((e) => {
            strarr.push(e.textContent);
        })
        
        var pagePattern = genPage(strarr);
        document.querySelector("#plistlistQuery").value = TEMP_QUERY;
        document.querySelector("#plisturl").value = pagePattern;
        var qs = pagePattern.match(/(\/|\w|\d)\?\w+/);
        var pattern = "";
        if (qs !== null) {
            pattern = pagePattern.match(/[\w\d]+\=\[\[\:page\:\]\]/);
            document.querySelector("#plistpagePattern").value = pattern;
            document.querySelector("#plistpageType").value = "Q";
        } else {
            pattern = pagePattern.match(/[\w\d]+\/\[\[\:page\:\]\]\/?[^\/]*/);
            document.querySelector("#plistpagePattern").value = pattern;
            document.querySelector("#plistpageType").value = "S";
        }
    } else {
        // document.querySelector("#plistQuery").value = "";
        box.querySelectorAll("input").forEach(e => {
            e.value = "";
        })
    }

    box.querySelector("ul").innerHTML = "";
    box.classList.add("d-none");
    isPageList(true);
    TEMP_QUERY = "";
    TEMP_QUERY_SINGLE = "";
    CONFIRM_QUERY = "";
    SELECT_MODE = "";
    SELECTED_COL = "";
}

async function addPage(isComplite = false) {
    const items = document.querySelectorAll("#pagesBox > li");
    const data = [];
    for (var i of items) {
        var column = i.querySelector("input[name=columnName]").value;
        var type = i.querySelector("input[name=dataType]").value;
        var query = i.querySelector("input[name=query]").value;
        if (type.match(/child/)) {
            data.push({ column, type, query, "child": [] });
        } else {
            data.push({ column, type, query });
        }
    }
    
    
    if (PAGE_NUM == 0) {
        var listchk = document.querySelectorAll("input[id^=plist]")
        const listData = {};
        for (var i of listchk) {
            listData[i.id.replace("plist", "")] = i.value;
        }
        let json = listData;
        if (json.url == "") {
            // json.url = document.getElementById("urlinput").value.replace(/^https?\:\/\/[^\/]+/, "")
            json.url = SAVED_URL.replace(/^https?\:\/\/[^\/]+/, "");
        }
        json['queries'] = data;
        // var domain = document.getElementById("urlinput").value.replace(/^(https?\:\/\/[^\/]+).*/, "$1");
        var domain = SAVED_URL.replace(/^(https?\:\/\/[^\/]+).*/, "$1");
        
        STORAGE.setItem("page", JSON.stringify({
            "domain": domain,
            "data": [json]
        }));
        var pageQry = data.find(arr => arr['type'].match(/child/,"child"));
        pageQry = pageQry ? pageQry['query'] : null
        if (pageQry) {
            _document.querySelectorAll(pageQry).forEach((e) => {
                e.parentNode.classList.toggle("nextClickAble")
                if (e.parentNode.classList.contains("nextClickAble")) {
                    e.setAttribute("href", "javascript:window.parent.postMessage({href: '"+e.dataset.sshref.replace(/'/g,"\\'")+"'}, '*')")
                } else {
                    e.setAttribute("href", "javascript:void(0)")
                }
                
            })
        }

        document.querySelectorAll("#compliteBtn, .isListed").forEach(e => {
            e.disabled = !e.disabled;
        })
        
    } else {
        let originData = STORAGE.getItem("page");
        originData = JSON.parse(originData);
        var findchild = originData['data'][0]['queries'].findIndex(e => {
            return e['child']
        })
        
        originData['data'][0]['queries'][findchild]['child'] = { queries: data };
        originData['data'][1] = { "queries": data };
        STORAGE.setItem("page", JSON.stringify(originData));
    }


    if (isComplite) {
        var body = STORAGE.getItem("page");
        var res = await fetch("./json.php", {
            method: "POST",
            headers: {
            "Content-Type": "application/json",
            },
            body: JSON.stringify(body)
        })
            .then((response) => response.text())
            .catch((err) => console.error(err));
        console.log(res);
    }
}

function addPageSub(boolean) {
    
    if (boolean) {
        var box = document.querySelector(".savedContainer > ul");
        var li = document.createElement("li");
        var btn = document.createElement("button");
        btn.innerText = (PAGE_NUM + 1) + "번 페이지";
        btn.classList.add("btn", "btn-outline-secondary");
        btn.dataset.pagenum = PAGE_NUM;
        btn.setAttribute("onclick", "showPageInfo(this)");
        li.append(btn);
        box.append(li);
        document.getElementById("pagesBox").innerHTML = "";
        document.getElementById("query").innerHTML = "";
        document.getElementById("resultBox").innerHTML = "";
        _clickDataSet(null, false);
        PAGE_NUM = 1
        document.getElementById("nextBtn").classList.add("d-none");
        // document.getElementById("compliteBtn").classList.remove("d-none");
        document.querySelectorAll("#compliteBtn, .isListed").forEach(e => {
            e.disabled = false
        })
    } else {
        console.log("False")
    }

    nextPageCheck();
}

function addColumn(data) {
    const ul = document.getElementById("pagesBox");
    const cnt = ul.children.length;
    const child = document.getElementById("pagesInner_001").cloneNode(true);
    child.id = child.id.replace("001", cnt);
    child.dataset.page = timestamp();
    // child.querySelector("p.title").innerText = parseInt(cnt)+1;
    child.querySelector("input[type='text'].c-name").value = data['column'] ? data['column'] : "컬럼" + (parseInt(cnt) + 1);
    child.querySelector("input[name=query]").value = data['query'];
    child.querySelector("input[name=dataType]").value = data['dataType'];
    // child.querySelector("input[name=typeAvailable]").value = QS_AVAILABLE;
    ul.append(child);
    child.querySelector("input[type='text'].c-name").focus();
    nextPageCheck();
}

function editColumn(e) {
    const query = e.closest("li").querySelector("input[name=query]").value;
    // const type = e.closest("li").querySelector("input[name=dataType]").value;
    const page = e.closest("li").dataset.page;
    UPDATE_MODE = page;
    CONFIRM_QUERY = query;
    TEMP_QUERY = query;
    TEMP_QUERY_SINGLE = query;
    _clickDataSet(query, true);

    var update;
    if (UPDATE_MODE !== false) {
        var t = document.querySelector("*[data-page='" + UPDATE_MODE + "']");
        var tt = t.querySelector("input[name=dataType]");
        if (tt) {
            update = tt.value;
        }
    }

    _parsingNext(query, update);
    if (!parsingStart.checked) {
        parsingStart.click();
    } 
}

function editColumnAfter(data, updateval) {
    UPDATE_MODE = false;
    const child = document.querySelector("*[data-page='" + updateval + "']");
    child.querySelector("input[name=query]").value = data['query'];
    child.querySelector("input[name=dataType]").value = data['dataType'];
    nextPageCheck();
}

function removeColumn(_this) {
    const ul = _this.closest("ul");
    // if (ul.children.length > 1) {
    // var chk = _this.closest("li").querySelector("input[name=selectColumn]").checked
    // if (chk) {
    //     var query = _this.closest("li").querySelector("input[name=query]");
    //     query = query.value;
    //     if (query) {
    //         _clickDataSet(query, false);
    //     }
    //     SELECTED_COL = undefined
    //     parsingStart.nextElementSibling.click();
        
    // }
    _this.closest("li").remove();
    columnSorting(ul);
    nextPageCheck()
}

function columnSorting(el) {
    for (var i = 0; i < el.children.length; i++) {
        var item = el.children[i];
        item.id = "pagesInner_" + i;
    }
}



function columnEmpty() {
    var chk = document.querySelectorAll("*[data-page]:not(#pagesInner_000)").length;
    if (chk > 0) {
        return true;
    }
    // addColumn(null, true);
}

function renderChildSelect() {
    if (SELECTED_COL === undefined) {
        return;
    }
    
    var find = document.querySelector("*[data-page='" + SELECTED_COL + "']")
    if (find === null || find === undefined) {
        return;
    }
    parsingBtn(null);
}

function insertSelector(reVal) {
    if (SELECT_MODE != "plist") {
        var targetbox = document.querySelector("*[data-page='" + SELECTED_COL + "'] .databox");
        var chk = targetbox.querySelector("input[name=query]");
    
        if (!chk) {
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "query";
            targetbox.append(input);
            chk = input;
        }
        if (reVal) {
            chk.value = reVal
        
        } else {
            chk.remove();
        }
        renderResult();
    } else {
        renderResultList(reVal);
        TEMP_QUERY = reVal;
        var box = document.getElementById("plist-box");
        box.querySelector("p").innerText = reVal;
        // document.querySelector("#plistQuery").value = reVal;
        box.classList.remove("d-none");
    }
    
}

function renderResultList(query) {
    _clickDataSet(null, false)
    
    var box = document.getElementById("plist-box");
    var resultArr = iframe.contentWindow.document.querySelectorAll(query);
    var cnt = 0;
    var resultbox = box.querySelector("ul");
    resultbox.innerHTML = "";
    for (var r of resultArr) {
        if (cnt > 10) {
            break;
        }
        // var rend = r.textContent.replace(/(\n| )/g, ' ');
        var href = r.dataset.sshref;
        var li = document.createElement("li");
        var a = document.createElement("a");
        a.innerText = href;
        a.title = href;
        a.href = DOMAIN_NAME + href;
        a.target = "_blank"
        li.append(a);
        resultbox.append(li);
        cnt++;
    }
    _clickDataSet(query);
}

function renderResult() {
    nextPageCheck()
    // modeCheck();
    var querybox = document.getElementById("query");
    var resultbox = document.getElementById("resultBox");
    querybox.innerText = "";
    resultbox.innerHTML = "";
    if (SELECTED_COL === undefined) {
        return;
    }
    var query = document.querySelector("*[data-page='" + SELECTED_COL + "'] .databox input[name=query]");
    if (!query) {
        return;
    }
    query = query.value;
    querybox.innerText = query.replace(/\>/g, ' > ');
    var resultArr = iframe.contentWindow.document.querySelectorAll(query);
    var cnt = 0;
    for (var r of resultArr) {
        if (cnt > 10) {
            break;
        }
        var type = SELECT_MODE == "url" || SELECT_MODE == "page";
        var rend = "";
        // if (SELECT_MODE == "url" || SELECT_MODE == "next") {
        //     rend = DOMAIN_NAME+r.dataset.sshref;
        // } else {
            rend = r.textContent.replace(/(\n| )/g, ' ');
        // }
        var li = document.createElement("li");
        var p = document.createElement(type ? "a" : "p");
        p.innerText = rend;
        p.title = rend;
        if (type) {
            p.href = DOMAIN_NAME + r.dataset.sshref;
            p.target = "_blank"
        }
        
        li.append(p);
        resultbox.append(li);
        cnt++;
    }
}

// function modeCheck() {
//     var qrychk = document.querySelector("*[data-page='" + SELECTED_COL + "'] .databox input[name=query]");
//     if (!qrychk) {
//         return;
//     }
//     var query = qrychk.value;
//     var items = _document.querySelectorAll(query);
//     if (SELECT_MODE == "text") {
//         return;
//     }
//     var chk = items[0];
//     if (chk.dataset.sshref) {
//         return;
//     }
//     var find = findHref(chk);
//     if (!find) {
//         _clickDataSet(null, false);
//         insertSelector(null);
//         return;
//     }
//     var newQuery = _getSelector(find);
//     insertSelector(newQuery);
// }

function findHref(el) {
    var target = el;
    // child
    var search = target.querySelector("*[data-sshref]");
    if (search) {
        return search;
    }
    // parent
    for (var i = 0; i < 2; i++) {
        target = target.parentElement;
        if (!target) {
            break;
        }
        if (target.dataset.sshref) {
            search = target;
            break;
        }
    }
    return search;
}

function nextPageCheck() {
    var chk = document.querySelectorAll("#pagesBox > li");
    var next = false;
    var complete = false;
    for (var i of chk) {
        complete = true;
        var query = i.querySelector("input[name=query]");
        var type = i.querySelector("input[name=dataType]")
        if (!query) {
            next = false;
        } else {
            if (query.value && type.value.match(/child/)) {
                next = true;
                break;
            }
        }
    }
    var nextbtn = document.getElementById("nextBtn");
    if (next && PAGE_NUM == 0) {
        nextbtn.classList.remove("d-none");
        nextbtn.removeAttribute("disabled");
    } else {
        nextbtn.classList.add("d-none");
        nextbtn.setAttribute("disabled", "true");
    }
    var completebtn = document.getElementById("completeBtn");
    var completechk = document.querySelectorAll("input[name=dataType][value^=child]").length
    if (complete && completechk < 1) {
        completebtn.classList.remove("d-none");
        completebtn.removeAttribute("disabled");
    } else {
        completebtn.classList.add("d-none");
        completebtn.setAttribute("disabled", "true");
    }

    if (PAGE_NUM == 0) {
        document.getElementById("listbtn").classList.remove("d-none");
    } else {
        document.getElementById("listbtn").classList.add("d-none");
    }
}

function goNextPage(el) {
    console.log(el);
}

function parseComplete() {
    let originData = STORAGE.getItem("page");
    console.log(originData);
}

function showPageInfo(pagenum) {
    const yn = confirm(pagenum.innerText + "로 돌아가시겠습니까?")
    if (!yn) {
        return;
    }
    loading.show = true;
    var storage = STORAGE.getItem("page");
    storage = JSON.parse(storage);
    var domain = storage.domain;
    var data = storage.data[0];
    var href = data.url;
    
    var url = domain + href
    this.document.getElementById("urlinput").value = url;
    PAGE_NUM = 0;

    document.getElementById("pagesBox").innerHTML = "";
    document.getElementById("query").innerHTML = "";
    document.getElementById("resultBox").innerHTML = "";
    
    

    geturl();
    var queries = data['queries'];
    for (var i of queries) {
        addColumn(i);
    }
    nextPageCheck();
    document.querySelector(".savedContainer ul").innerHTML = "";

}

/**
 * 
 * @param {HTMLElement} el 
 */
function morebtn(el) {
    var target = el.previousElementSibling;
    console.log(target);
}