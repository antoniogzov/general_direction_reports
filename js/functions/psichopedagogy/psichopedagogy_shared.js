$(document).ready(function () {
  console.log("psichopedagogy_shared.js");
  $(".seeTrackingInterview").trigger("click");

  $("#seguimientoInterview").modal({
    backdrop: "static",
    keyboard: false,
  });
  $(".closeTracking").click(function () {
    loading();
    location.replace('alumnos.php');

  });
  function cerrar() {
    window.open("", "_parent", "");
    window.close();
  }
});
