function mostrarFechas(){
  const tipo = document.getElementById('tipo').value;
  document.getElementById('fechaIdaDiv').style.display = (tipo !== "") ? "block" : "none";
  document.getElementById('fechaVueltaDiv').style.display = (tipo === "ida_vuelta") ? "block" : "none";
}

function verDetalles(id, tipo){
  fetch('modal_detalle_vuelo.php?id=' + id + '&tipo=' + tipo)
  .then(res => res.text())
  .then(html => {
    document.getElementById('contenidoModal').innerHTML = html;
    new bootstrap.Modal(document.getElementById('modalDetalle')).show();
  });
}
