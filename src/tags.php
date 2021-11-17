<?php
include "include/header.php";
?>

    <div class="container-fluid">
        <div class="text-left">
            <div class="btn-group dropright" style="padding-top: 15px">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Tags
                </button>

            <?php

                //echo "<div class='dropdown-menu'>";
                getTagFunc()->create_tag_list();
                //$var = "ABBB";

                $conn = null;

            ?>
            </div>
        </div>
    </div>



<?php
include "include/footer.php";
?>