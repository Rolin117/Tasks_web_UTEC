let contadorTareas = 1;

function actualizarDashboard() {
  const todasLasTareas = document.querySelectorAll(".task-item");
  const completadas = document.querySelectorAll(".task-item input[type='checkbox']:checked");
  
  const total = todasLasTareas.length;
  const totalCompletadas = completadas.length;
  const totalPendientes = total - totalCompletadas;

  const elTotal = document.getElementById("total-tareas");
  const elComp = document.getElementById("tareas-completadas");
  const elPend = document.getElementById("tareas-pendientes");

  if (elTotal) elTotal.textContent = total;
  if (elComp) elComp.textContent = totalCompletadas;
  if (elPend) elPend.textContent = totalPendientes;

  const conteoMaterias = {};
  todasLasTareas.forEach(item => {
    const isDone = item.querySelector("input[type='checkbox']").checked;
    const materia = item.querySelector(".tag")?.textContent || "General";
    
    if (!conteoMaterias[materia]) {
      conteoMaterias[materia] = { total: 0, pendientes: 0 };
    }
    conteoMaterias[materia].total++;
    if (!isDone) {
      conteoMaterias[materia].pendientes++;
    }
  });

  const contenedorMaterias = document.getElementById("lista-materias-conteo");
  if (contenedorMaterias) {
    contenedorMaterias.innerHTML = "";
    const colores = [
      "dot-blue",
      "dot-amber",
      "dot-green",
      "dot-red",
      "dot-purple",
      "dot-cyan",
      "dot-orange",
      "dot-pink"
    ];
    let colorIdx = 0;

    for (const [materia, datos] of Object.entries(conteoMaterias)) {
      const li = document.createElement("li");
      const colorNom = obtenerColorMateria(materia);

      li.innerHTML = `
        <span class="subject-name"><span class="dot dot-${colorNom}"></span>${materia}</span>
        <span class="subject-count">${datos.pendientes} pend.</span>
      `;
      contenedorMaterias.appendChild(li);
      colorIdx++;
    }
  }

  const statsPorDia = {
    1: { total: 0, completadas: 0 },
    2: { total: 0, completadas: 0 },
    3: { total: 0, completadas: 0 },
    4: { total: 0, completadas: 0 },
    5: { total: 0, completadas: 0 },
    6: { total: 0, completadas: 0 },
    7: { total: 0, completadas: 0 }
  };

  todasLasTareas.forEach(item => {
    const textoFecha = item.querySelector(".fecha")?.textContent || "";
    const match = textoFecha.match(/\d{4}-\d{2}-\d{2}/);

    if (match) {
      const fechaObj = new Date(match[0] + "T00:00:00");
      let diaSemana = fechaObj.getDay();
      diaSemana = diaSemana === 0 ? 7 : diaSemana;

      const isDone = item.querySelector("input[type='checkbox']").checked;
      if (statsPorDia[diaSemana]) {
        statsPorDia[diaSemana].total++;
        if (isDone) {
          statsPorDia[diaSemana].completadas++;
        }
      }
    }
  });

  const barras = document.querySelectorAll(".bar-chart .bar");
  barras.forEach(b => {
    const numDia = parseInt(b.getAttribute("data-dia"));
    const data = statsPorDia[numDia];

    if (data && data.total > 0) {
      const pct = Math.round((data.completadas / data.total) * 100);
      b.style.height = pct + "%";
    } else {
      b.style.height = "0%";
    }
  });
}

function agregarTarea() {
  const lista = document.getElementById("lista-tareas");
  const selectTipo = document.getElementById("tipo-tarea");
  const inputTexto = document.getElementById("nueva-tarea");
  const inputMateria = document.getElementById("materia-tarea");
  const inputFecha = document.getElementById("fecha-tarea");

  const tipo = selectTipo.value;
  const texto = inputTexto.value.trim();
  const materia = inputMateria.value.trim() || "General";
  const fecha = inputFecha.value;

  if (texto === "" || fecha === "") return;

  const li = document.createElement("li");
  li.className = "task-item";

  const checkbox = document.createElement("input");
  checkbox.type = "checkbox";
  checkbox.id = "tarea-" + contadorTareas;
  checkbox.onchange = actualizarDashboard;

  const label = document.createElement("label");
  label.setAttribute("for", "tarea-" + contadorTareas);
  label.textContent = texto;

  const spanTipo = document.createElement("span");
  spanTipo.className = "badge-tipo tipo-" + tipo.toLowerCase();
  spanTipo.textContent = tipo;

  const spanMateria = document.createElement("span");
  const colorNom = obtenerColorMateria(materia);
  spanMateria.className = `tag tag-${colorNom}`;
  spanMateria.textContent = materia;

  const spanFecha = document.createElement("span");
  spanFecha.className = "fecha";
  spanFecha.textContent = "Fecha: " + fecha;

  const btnEliminar = document.createElement("button");
  btnEliminar.textContent = "✕";
  btnEliminar.className = "btn-eliminar";
  btnEliminar.onclick = function() {
    li.remove();
    actualizarDashboard();
  };

  li.appendChild(checkbox);
  li.appendChild(label);
  li.appendChild(spanTipo);
  li.appendChild(spanMateria);
  li.appendChild(spanFecha);
  li.appendChild(btnEliminar);

  lista.appendChild(li);
  contadorTareas++;

  inputTexto.value = "";
  inputMateria.value = "";
  inputFecha.value = "";

  actualizarDashboard();
}

const NOMBRES_COLORES = ["blue", "amber", "green", "red", "purple", "cyan", "orange", "pink"];

function obtenerColorMateria(materia) {
  let hash = 0;
  const str = materia.toLowerCase().trim();
  for (let i = 0; i < str.length; i++) {
    hash = str.charCodeAt(i) + ((hash << 5) - hash);
  }
  const index = Math.abs(hash) % NOMBRES_COLORES.length;
  return NOMBRES_COLORES[index];
}

document.addEventListener("DOMContentLoaded", actualizarDashboard);