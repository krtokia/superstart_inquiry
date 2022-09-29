
/*
* ###########################################
* iframe side
* ###########################################
*/

function keyPressSet(event) {
    CTRL_KEY = event.ctrlKey;
    ALT_KEY = event.altKey;
    SHIFT_KEY = event.shiftKey;
}

function getEQ(el) {
    var eq = undefined;
    const target = el.parentElement.children;
    for (var i = 0; i < target.length; i++) {
        var t = target[i];
        if (t == el) {
            eq = i + 1;
            break;
        }
    }
    return eq;
}

function __initStart() {
    // IS_LOAD_END = false;

    var all = _document.body.getElementsByTagName("*");
    for (var i = 1; i <= all.length; i++) {
        try {
            var element = all[i];

            if (element.tagName == "IFRAME") {
                element.innerHTML = "";
                continue;
            }
            element.dataset.ss_id = "ss-" + i;
        } catch (e) { }
    }

    var clicks = _document.querySelectorAll("a,*[onClick],*[onclick]");
    for (var c of clicks) {
        var href = c.getAttribute("href");
        var onclick = c.getAttribute("onclick") ?? c.getAttribute("onClick");
        if (onclick !== null && onclick !== undefined) {
            c.removeAttribute("onclick");
        }
        if (href !== null && href !== undefined) {
            c.dataset.sshref = href;
            // c.href = "javascript:window.parent.message('sshref', '"+href.replace(/'/g, "\\'")+"')";
            // c.setAttribute("href", "javascript:window.parent.postMessage({href: '"+href.replace(/'/g,"\\'")+"'}, '*')")
            c.setAttribute("href", "javascript:void(0)")
        }
    }

    document.addEventListener("keydown", keyPressSet)
    _document.addEventListener("keydown", keyPressSet)
    document.addEventListener("keyup", keyPressSet)
    _document.addEventListener("keyup", keyPressSet)
}

function _parsingStart() {

    // _toggleClickData();
    // renderResult();
    try {
        _document.addEventListener("mouseover", _parsingStart_over);
        _document.addEventListener("click", _parsingStart_click);
    } catch (e) {

    }
}
function _parsingStop() {
    // _toggleClickData();
    // renderResult();
    cancleSelect();
    try {
        document.querySelector("input[type=radio]:checked").checked = false;
        _document.removeEventListener("mouseover", _parsingStart_over);
        _document.removeEventListener("click", _parsingStart_click);
    } catch (e) { }

}
function _parsingStart_over(e) {
    var exclude = ["HTML", "BODY", "IFRAME", "TABLE"];
    if (exclude.includes(e.target.tagName)) {
        return;
    }
    try {
        _document.querySelector("*[data-sshighlight]:not([data-ss_id='" + e.target.dataset.ss_id + "'])").removeAttribute("data-sshighlight")
    } catch (e) { }
    MOUSE_OVERED = e.target.dataset.ss_id;
    e.target.dataset.sshighlight = "true";
}

function _parsingStart_click(e) {
    if (!e.target.dataset.sshighlight) {
        return;
    }
    _clickDataSet(null, false);

    var tempqry = TEMP_QUERY;
    var tempqry_s = TEMP_QUERY_SINGLE;

    // console.log(ALT_KEY)
    // console.log(SHIFT_KEY)
    // var chk = e.target.dataset.ssclick;
    // console.log(e.target.dataset)
    // let selector;
    // if (!chk) {
    //     e.target.dataset.ssclick = "true";
    //     selector = _getSelector(e.target);
    // } else {
    //     var check = document.querySelector("[data-page='" + SELECTED_COL + "'] input[name=query]");
    //     if (check) {
    //         _clickDataSet(null, false);
    //     }
    // }

    e.target.dataset.ssclick = "true";
    TEMP_QUERY = _getSelector(e.target);
    TEMP_QUERY_SINGLE = _getSelector(e.target, 5, true);

    if (CTRL_KEY) {
        TEMP_QUERY = tempqry + "," + TEMP_QUERY;
        TEMP_QUERY_SINGLE = tempqry_s + "," + TEMP_QUERY_SINGLE;
    }

    CONFIRM_QUERY = TEMP_QUERY_SINGLE;
    if (SELECT_MODE == "plist") {
        renderResultList(TEMP_QUERY);
        var box = document.getElementById("plist-box");
        box.querySelector("p").innerText = TEMP_QUERY;
        box.classList.remove("d-none");
    } else {
        _parsingNext(TEMP_QUERY);
    }
    // showQsbox();
    // insertSelector(selector);
}

function _parsingNext(query, update = null) {
    let multi = false;
    let href = false;
    let target = _document.querySelectorAll(query)
    if (target.length > 1) {
        multi = true;
    }
    if (target[0].dataset.sshref) {
        href = true;
    }
    QS_AVAILABLE = `${multi.toString()},${href.toString()}`;

    console.log(query);

    // if (UPDATE_MODE !== false) {
    //     var t = document.querySelector("*[data-page='" + UPDATE_MODE + "']");
    //     var tt = t.querySelector("input[name=dataType]");
    //     if (tt) {
    //         update = tt.value;
    //     }
    // }

    initQsbox(update, multi, href);
    showQsbox();
}

function _getSelector(el, depth = 2, strict = false) {
    // var check = document.querySelector("[data-page='" + SELECTED_COL + "'] input[name=query]");
    // if (check) {
    //     _clickDataSet(null, false);
    // }
    var target = el;

    var classes = [];
    var cnt = 0;
    var safe = 0;
    while (cnt < depth) {
        if (safe > 10) {
            break;
        };
        var clsname = target.classList.item(0);
        if (!clsname) {
            var tagname = target.tagName.toString().toLowerCase();
            // clsname = tagname + ":nth-child(" + getEQ(target) + ")";
            clsname = tagname;
            if (cnt == 0) {
                classes.unshift(clsname + ":nth-child(" + getEQ(target) + ")");
                cnt++;
            } else {
                if (tagname == "body") {
                    classes.unshift(tagname);
                    break;
                }
                if (strict) {
                    clsname += ":nth-child(" + getEQ(target) + ")"
                }
                classes.unshift(clsname);
            }
            if (strict) {
                cnt++;
            }
        } else {
            if (cnt == 0) {
                clsname = target.tagName.toString().toLowerCase() + "." + clsname + ":nth-child(" + getEQ(target) + ")";
            } else {
                clsname = target.tagName.toString().toLowerCase() + "." + clsname;
            }
            if (strict) {
                clsname += ":nth-child(" + getEQ(target) + ")"
            }
            classes.unshift(clsname);
            cnt++;
        }
        target = target.parentElement;
        safe++;
    }
    var query = classes.join(">");
    // _clickDataSet(query);
    return query;
}

function _showSelectedItems(type) {
    _clickDataSet(null, false);
    var typeMatch = type.match(/M$/);
    if (type == "child") typeMatch = null;
    else if (type == "list") typeMatch = true;
    if (typeMatch) {
        // _document.querySelectorAll(TEMP_QUERY).forEach(e => {
        //     if (!e.dataset.ssclick) {
        //         e.dataset.ssclick = "true"
        //     }
        // })
        CONFIRM_QUERY = TEMP_QUERY;
    } else {
        // var target = _document.querySelector("[data-ssclick=true]");
        // var query = _getSelector(target, 5, true);
        CONFIRM_QUERY = TEMP_QUERY_SINGLE;
    }
    _clickDataSet(CONFIRM_QUERY, true);
}

function _clickDataSet(query, insert = true) {
    if (!query) {
        query = "*[data-ssclick],*[data-sshighlight]";
    }
    try {
        var query_target = _document.querySelectorAll(query);
        for (var qt of query_target) {
            qt.removeAttribute("data-ssclick");
            qt.removeAttribute("data-sshighlight");
            if (insert) {
                qt.dataset.ssclick = "true";
            }
        }
    } catch (e) { };
}

function _toggleClickData() {
    try {
        _document.querySelectorAll("*[data-ssclick]").forEach((e) => {
            e.removeAttribute("data-ssclick");
        })
    } catch (e) { };

    var query;
    if (SELECT_MODE != "plist") {
        query = document.querySelector("[data-page='" + SELECTED_COL + "'] input[name=query]");
    } else {
        query = document.querySelector("#plistQuery");
    }

    if (!query || !query.value) {
        return;
    }
    _document.querySelectorAll(query.value).forEach(e => {
        e.dataset.ssclick = "true";
    })
}