--TEST--
DB-4497
--FILE--
<?php 
date_default_timezone_set('America/New_York');

require("testdb.inc");
global $db;  
open_db();

try {  
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql0 = "DROP TABLE TEST_NULL CASCADE IF EXISTS";
  $db->query($sql0);

  $sql1 = "CREATE TABLE TEST_NULL (
	ID INT GENERATED BY DEFAULT AS IDENTITY,
	STR1 VARCHAR(255) NULL DEFAULT '',
	INT1 INT NULL DEFAULT 0,
        B1 BOOLEAN NULL DEFAULT NULL,
	LONG1 BIGINT NULL DEFAULT NULL,
	DATE1 DATE NULL DEFAULT NULL,
	TIME1 TIME NULL DEFAULT NULL,
	TIMESTAMP1 TIMESTAMP NULL NULL,
	BLOB1 BLOB NULL DEFAULT NULL,
	CLOB1 CLOB NULL DEFAULT NULL,
	PRIMARY KEY (id),
	CONSTRAINT TEST_NULL_STR1_KEY UNIQUE (STR1))";
  $db->query($sql1);

  $sql2 = "CREATE INDEX TEST_NULL_INT1_IDX ON TEST_NULL (`int1`)";
  $db->query($sql2);

  $sql3 = "INSERT INTO TEST_NULL (STR1, INT1) VALUES (:p1, :p2)";
  $stmt3 = $db->prepare($sql3);
  $str3 = "Kermit";
  $int3 = "25";
  $stmt3->bindParam(':p1', $str3, PDO::PARAM_STR);
  $stmt3->bindParam(':p2', $int3, PDO::PARAM_STR);
  $stmt3->execute();

  $sql4 = "UPDATE TEST_NULL SET STR1=:p1, INT1=:p2 WHERE (ID = 1)";
  $stmt4 = $db->prepare($sql4);
  $int4 = null;
  $str4 = null;
  $stmt4->bindParam(':p1', $str4, PDO::PARAM_STR);
  $stmt4->bindParam(':p2', $int4, PDO::PARAM_STR);
  $stmt4->execute();
  
  $sql5 = "SELECT * FROM TEST_NULL";
  $stmt5 = $db->prepare($sql5);
  $stmt5->execute();
  $result = $stmt5->fetchAll(PDO::FETCH_ASSOC);
  print_r($result);
  if ($result[0]['STR1'] !== NULL) {
     echo "\nFAIL: string not NULL\n";
  }
  if ($result[0]['INT1'] !== NULL) {
     echo "\nFAIL: integer not NULL\n";
  }
  if ($result[0]['B1'] !== NULL) {
     echo "\nFAIL: boolean not NULL\n";
  }
  if ($result[0]['LONG1'] !== NULL) {
     echo "\nFAIL: bigint not NULL\n";
  }
  if ($result[0]['DATE1'] !== NULL) {
     echo "\nFAIL: date not NULL\n";
  }
  if ($result[0]['TIME1'] !== NULL) {
     echo "\nFAIL: time not NULL\n";
  }
  if ($result[0]['TIMESTAMP1'] !== NULL) {
     echo "\nFAIL: timestamp not NULL\n";
  }
  if ($result[0]['BLOB1'] !== NULL) {
     echo "\nFAIL: blob not NULL\n";
  }
  if ($result[0]['CLOB1'] !== NULL) {
     echo "\nFAIL: clob not NULL\n";
  }

  $db = NULL;
} catch(PDOException $e) {  
  echo $e->getMessage();  
}
$db = NULL;  
echo "\ndone\n";
?>
--EXPECT--
Array
(
    [0] => Array
        (
            [ID] => 1
            [STR1] => 
            [INT1] => 
            [B1] => 
            [LONG1] => 
            [DATE1] => 
            [TIME1] => 
            [TIMESTAMP1] => 
            [BLOB1] => 
            [CLOB1] => 
        )

)

done
