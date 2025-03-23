window.submit_on_enter = function (e, button_id) {

    if (e.code === "NumpadEnter" || e.code === "Enter") {
        console.log("Enter key pressed", e);
        document.getElementById(button_id).click();
    }
}

function updateISBNLength(input) {
    let span = document.getElementById(input.id + "-length");
    let l = input.value.length;
    span.innerHTML = l > 0 ? l : "<em>-</em>";
}
