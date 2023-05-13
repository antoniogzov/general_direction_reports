/*$(document).ready(function() {
    $(document).on('change', 'input[type=radio]', function() {
        var radioClicked = $(this).attr('id');
        unclickRadio();
        removeActive();
        clickRadio(radioClicked);
        makeActive(radioClicked);
    });
    $(".card-slct").click(function() { //Clicking the card
        var inputElement = $(this).find('input[type=radio]').attr('id');
        unclickRadio();
        removeActive();
        makeActive(inputElement);
        clickRadio(inputElement);
    });
});

function unclickRadio() {
    $("input:radio").prop("checked", false);
}

function clickRadio(inputElement) {
    $("#" + inputElement).prop("checked", true);
}

function removeActive() {
    $(".card-slct").removeClass("active-cd-slct");
}

function makeActive(element) {
    $("#" + element + "-card").addClass("active-cd-slct");
}*/
$(document).ready(function() {
    $(".card-slct").click(function() { //Clicking the card
        var inputElement = $(this).attr('id');
        disableAllButtons();
        removeActive();
        makeActive(inputElement);
        clickRadio(inputElement);
    });
});

function clickRadio(inputElement) {
    $("#" + inputElement).prop("checked", true);
}

function removeActive() {
    $(".card-slct").removeClass("active-cd-slct");
}

function makeActive(element) {
    $("#" + element).addClass("active-cd-slct");
    document.getElementById(element).getElementsByClassName('btn_apply')[0].disabled = false;
    document.getElementById(element).getElementsByClassName('btn_apply')[0].style.display = 'block';
}

function disableAllButtons() {
    var btns = document.getElementsByClassName("btn_apply");
    for (var i = 0; i < btns.length; i++) {
        btns[i].disabled = true;
        btns[i].style.display = 'none';
    }
}