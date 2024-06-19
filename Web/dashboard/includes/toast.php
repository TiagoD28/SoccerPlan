<link href="../../css/index.css" rel="stylesheet">

<!-- Overlay -->
<div id="overlay" style="visibility: hidden;"></div>

<!-- Mostrar Alerta -->
<script src="../../js/toast.js"></script>

<div id="toast">
    <div class="toast-content">

        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-exclamation check" viewBox="0 0 16 16">
            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.553.553 0 0 1-1.1 0L7.1 4.995z" />
        </svg>

        <div class="message">
            <span class="text text-1">Warn</span>
            <span id="textMessage" class="text"></span>
        </div>

    </div>

    <svg onclick="closeToast()" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-x close" viewBox="0 0 16 16">
        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
    </svg>

    <div class="progress"></div>
</div>


<?php function toastShow($message, $type)
{
    if (isset($message)) {
        echo "<script> showToast('$message', '$type') </script>";
    }
}