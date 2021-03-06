--TEST--
Select AS
--FILE--
<?php 

require("testdb.inc");
global $db;  
open_db();

// Test Select as
try {  
//  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "DROP TABLE TEST CASCADE IF EXISTS";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $sql = "CREATE TABLE TEST (NAME VARCHAR(255) NOT NULL DEFAULT '', NUM INTEGER NOT NULL DEFAULT -1)";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $name = 'foo';
  $sql = "INSERT INTO TEST (NAME) VALUES (:name)";
  $stmt = $db->prepare($sql);

  $stmt->bindParam(':name', $name, PDO::PARAM_STR);
  $affected_rows = $stmt->execute();
  if ($affected_rows != 1) {
     echo "ERROR: affected_rows != 1\n";
  }

  $sql = "SELECT NAME AS USERNAME, NUM FROM TEST WHERE NAME = :name";
  $stmt = $db->prepare($sql);
  $stmt->bindParam(':name', $name, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach ($result as $row) {
     print_r ($row);
  }
  $db = NULL;
} catch(PDOException $e) {  
  echo $e->getMessage();  
}
$db = NULL;  
echo "done\n";
?>
--EXPECT--
Array
(
    [USERNAME] => foo
    [0] => foo
    [NUM] => -1
    [1] => -1
)
done