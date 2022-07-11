// Variables
var iframe = document.getElementById("ss-iframe");
let _document = null;
let _window = null;

let diff_arr = [];
let diff_names = [];
let column_cnt = 0;
let product = {};

let MOUSE_OVERED = "";

let SELECTED_COL;
let SELECT_MODE = "text";

let PAGE_NUM = 0;

let DOMAIN_NAME = "";

const STORAGE = window.sessionStorage;

let CTRL_KEY = false
let SHIFT_KEY = false
let ALT_KEY = false

let TEMP_QUERY = "";
let TEMP_QUERY_SINGLE = "";
let CONFIRM_QUERY = "";
let QS_AVAILABLE = "";

let IS_LOAD_END = false;

let IS_MOUSE_DOWN = false;

let SAVED_URL = "";

let UPDATE_MODE = false;


const modalOptions = {
            backdrop: "static",
            keyboard: false
        }
const modalEl = document.getElementById("sel-modal");
const modal = new bootstrap.Modal(modalEl, modalOptions)

const loadingEl = document.getElementById("loadingModal")
const loadingModal = new bootstrap.Modal(loadingEl, modalOptions)
const loading = {
    /**
     * @param {boolean} isLoading
     */
    set show(isLoading) {
        this._isLoading = isLoading;
        IS_LOAD_END = isLoading;
        if (isLoading) {
            loadingModal.show()
        } else {
            setTimeout(() => {
                loadingModal.hide()
            },100)
        }
    },
    get status() {
        return this._isLoading || false;
    }
}

const qsbox = document.getElementById("qs-box");
