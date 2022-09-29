document.addEventListener("DOMContentLoaded", function (e) {
    var people = {};
    $("#result-target").find(".price-table").each((e, el) => {
        var pname = $(el).data("people");
        var price = parseInt($(el).text().replace(/[^0-9]/g, ""));
        if (isNaN(price)) {
            price = 0;
        }
        if (!people[pname]) {
            people[pname] = 0;
        }
        people[pname] = people[pname] + price;
    })

    $(".result-table").each((e, el) => {
        var price = people[$(el).data("people")];

        price = price.toString().replace(/[^0-9]/g, "");
        // price = price.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
        price = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        var text = price + "원";
        $(el).text(text);
    })
})

function copyUrl() {
    var content = document.getElementById("url")
    content.select();
    document.execCommand('copy');
    alert("복사 완료")
}