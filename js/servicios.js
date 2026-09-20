let contadorActividades = 1;
let contadorExamenes = 1;

function agregarActividad() {
  const lista = document.getElementById("lista-actividades");
  const texto = document.getElementById("nueva-actividad").value;
  const tag = document.getElementById("tag-actividad").value;
  const fecha = document.getElementById("fecha-actividad").value;

  if (texto.trim() === "" || fecha.trim() === "") return;

  const li = document.createElement("li");
  li.className = "task-item";

  const checkbox = document.createElement("input");
  checkbox.type = "checkbox";
  checkbox.id = "actividad-" + contadorActividades;

  const label = document.createElement("label");
  label.setAttribute("for", "actividad-" + contadorActividades);
  label.textContent = texto;

  const spanTag = document.createElement("span");
  spanTag.className = "tag";
  spanTag.textContent = tag || "General";

  const spanFecha = document.createElement("span");
  spanFecha.className = "fecha";
  spanFecha.textContent = "Entrega: " + fecha;

  // Botón de eliminar
  const btnEliminar = document.createElement("button");
  btnEliminar.textContent = "✕";
  btnEliminar.className = "btn-eliminar";
  btnEliminar.onclick = function() {
    li.remove();
  };

  li.appendChild(checkbox);
  li.appendChild(label);
  li.appendChild(spanTag);
  li.appendChild(spanFecha);
  li.appendChild(btnEliminar);

  lista.appendChild(li);

  contadorActividades++;
  document.getElementById("nueva-actividad").value = "";
  document.getElementById("tag-actividad").value = "";
  document.getElementById("fecha-actividad").value = "";
}

function agregarExamen() {
  const lista = document.getElementById("lista-examenes");
  const texto = document.getElementById("nuevo-examen").value;
  const tag = document.getElementById("tag-examen").value;
  const fecha = document.getElementById("fecha-examen").value;

  if (texto.trim() === "" || fecha.trim() === "") return;

  const li = document.createElement("li");
  li.className = "task-item";

  const checkbox = document.createElement("input");
  checkbox.type = "checkbox";
  checkbox.id = "examen-" + contadorExamenes;

  const label = document.createElement("label");
  label.setAttribute("for", "examen-" + contadorExamenes);
  label.textContent = texto;

  const spanTag = document.createElement("span");
  spanTag.className = "tag";
  spanTag.textContent = tag || "General";

  const spanFecha = document.createElement("span");
  spanFecha.className = "fecha";
  spanFecha.textContent = "Fecha: " + fecha;

  // Botón de eliminar
  const btnEliminar = document.createElement("button");
  btnEliminar.textContent = "✕";
  btnEliminar.className = "btn-eliminar";
  btnEliminar.onclick = function() {
    li.remove();
  };

  li.appendChild(checkbox);
  li.appendChild(label);
  li.appendChild(spanTag);
  li.appendChild(spanFecha);
  li.appendChild(btnEliminar);

  lista.appendChild(li);

  contadorExamenes++;
  document.getElementById("nuevo-examen").value = "";
  document.getElementById("tag-examen").value = "";
  document.getElementById("fecha-examen").value = "";
}