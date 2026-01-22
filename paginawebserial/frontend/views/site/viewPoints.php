<?php

use frontend\assets\ChartAsset;
use yii\web\View;

ChartAsset::register($this);



?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <select id="origin" onchange="changeOrigin(this.value)">
                    <option value=1>Base</option>
                    <option value=2>Martillo</option>
                    <option value=3>Motor</option>
                    <option value=4>Taladro</option>
                    <option value=5>Taladro - Perforando</option>
                    <option value=6>Test</option>

                </select>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <canvas id="myChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>

</div>


