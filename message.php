<?php
    if(isset($_SESSION['message'])) :
?>

    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Hey!</strong> <?= $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

<?php 
    unset($_SESSION['message']);
    endif;
?>|

<?php
    if(isset($_SESSION['message register'])) :
        $alertType = ($_SESSION['registration_successful']) ? 'success' : 'warning';
?>

    <div class="alert alert-<?= $alertType; ?> alert-dismissible fade show" role="alert">
        <strong><?php echo ($_SESSION['registration_successful']) ? '¡Registro exitoso!' : '¡Error en el registro!'; ?></strong> <?= $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

<?php 
    unset($_SESSION['message']);
    unset($_SESSION['registration_successful']);
    endif;
?>



