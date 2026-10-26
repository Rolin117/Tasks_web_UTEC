<?php
// Obtenemos el nombre del archivo actual (ej. "materias.php")
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<!-- Menú lateral -->
<div class="sidebar">
    <h2>TaskManager</h2>
    <a href="index.php" class="nav-item <?php echo ($pagina_actual == 'index.php') ? 'active' : ''; ?>">Inicio</a>
    <a href="formulario_tarea.php" class="nav-item <?php echo ($pagina_actual == 'formulario_tarea.php') ? 'active' : ''; ?>">Tareas</a>
    <a href="#" class="nav-item">Calendario</a>
    <a href="materias.php" class="nav-item <?php echo ($pagina_actual == 'materias.php') ? 'active' : ''; ?>">Materias</a>
</div>