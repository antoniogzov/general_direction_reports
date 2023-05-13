function habilitar(value) {
    if (value == 1) {
            document.getElementById("LabelAditionalName").style.display = "block";
            document.getElementById("AditionalName").style.display = "block";
        } else {
            document.getElementById("LabelAditionalName").style.display = "none";
            document.getElementById("AditionalName").style.display = "none";
        }
    }

    function activeButtons() {
        var contenedor = document.getElementById("buttonActions");
        contenedor.style.display = "block";
        return true;
    }