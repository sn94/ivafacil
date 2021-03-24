<?php if (isset($success)) : ?>
    <div class="alert alert-success">
        <h5><?= $success ?></h5>
    </div>
<?php else :  if (isset($warning)) : ?>

        <div class="alert alert-warning">
            <h5><?= $warning ?></h5>
        </div>
<?php elseif( isset($error) ) :  ?>

    <div class="alert alert-danger">
    <b>No permitido </b>
    <ul>
    <?php 
    
    if( gettype($error) == "string"){
    ?>
    <li><?= $error ?></li>

    <?php
    }else{
    foreach ($error as $errr) : ?>
        <li><?= esc($errr) ?></li>
    <?php 
    endforeach;
    } 
    ?>
    </ul>

        </div>

<?php endif;
endif;  ?>