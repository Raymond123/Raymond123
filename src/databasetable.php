<!DOCTYPE html>
<html>
    <body>

        <?php
            include "include/header.php";

            echo "<table style='border: solid 1px black;'>";
            echo "<tr>
                    <th>idGalleryObjs</th>
                    <th>objName</th>
                    <th>objLinkCreated</th>
                    <th>objThumbnail</th>
                    <th>objTags</th>
                </tr>";

            class TableRows extends RecursiveIteratorIterator {

                function __construct($it){
                    parent::__construct($it, self::LEAVES_ONLY);
                }
              
                function current() {
                  return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
                }
              
                function beginChildren() {
                  echo "<tr>";
                }
              
                function endChildren() {
                  echo "</tr>" . "\n";
                }
            }

            try{

                $conn = mySqlCon();
                $stmt = $conn->prepare("SELECT * FROM galleryobjs");
                $stmt->execute();

                $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v){

                    echo $v;

                }


            } catch ( PDOException $e ){

                echo "Connection failed : " . e.getMessage();

            }

            $conn = null;
            echo "</table>";

            //echo "My first PHP script!";


        include "include/footer.php";
        ?>

    </body>
</html>