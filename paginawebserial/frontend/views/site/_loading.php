<?php
use yii\bootstrap4\Modal;

Modal::begin([
    'id' => 'loading-modal',
    'size' => 'modal-sm',
    'closeButton' => false,
    'footer' => 'Procesando...',
    'footerOptions' => ['style' => ['display'=> 'flow-root', 'text-align' => 'center']],
    'options' => ['style' => ['top' => '25%']],
]);



echo '<div id="modalContent">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; display: block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                <circle cx="50" cy="50" r="32" stroke-width="8" stroke="#85a2b6" stroke-dasharray="50.26548245743669 50.26548245743669" fill="none" stroke-linecap="round" transform="rotate(263.924 50 50)">
                    <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform>
                </circle>
            </svg>
          </div>';


Modal::end();

?>
