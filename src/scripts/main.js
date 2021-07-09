"use strict";
/**
 * Validates if a password has at least 6 characters
 * @param {*} inputtxt
 */
function CheckPassword(inputtxt)
{
    var passw = /^.{6,}$/;
    if (inputtxt.match(passw)) {
        return true;
    } else {
        return false;
    }
}

function searchTable() {
    let value = document.getElementById("search").value;
    Array.from(document.querySelectorAll('td[data-label="title"]')).forEach((ele) => {
        if (value !== "") {
        ele.style.display = "table-cell";
        if (ele.innerHTML.toLowerCase().includes(value.toLowerCase())) {
            ele.style.display = "table-cell";
        } else {
            ele.style.display = "none";
            ele.previousElementSibling.style.display = "none";
            ele.nextElementSibling.style.display = "none";
            ele.nextElementSibling.nextElementSibling.style.display = "none";
        }
        } else {
        ele.style.display = "table-cell";
        ele.previousElementSibling.style.display = "table-cell";
        ele.nextElementSibling.style.display = "table-cell";
        ele.nextElementSibling.nextElementSibling.style.display = "table-cell";
        }
    });
}

/* Modal Delete */
$("#confirm-delete").on("show.bs.modal", function(e) {
    $(this).find(".btn-ok").attr("href", $(e.relatedTarget).data("href"));
});