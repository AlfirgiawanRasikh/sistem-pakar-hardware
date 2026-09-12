<?php

include 'views/layouts/header.php';
include 'views/layouts/navbar.php';

?>

<div class="d-flex">

    <?php
    include 'views/layouts/sidebar.php';
    ?>

    <div class="container-fluid p-4">

        <?php
        include $content;
        ?>

    </div>

</div>

<?php

include 'views/layouts/footer.php';

?>