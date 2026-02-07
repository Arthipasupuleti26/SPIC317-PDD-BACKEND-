<?php
require "../../db.php";
require "../projects/helper.php";

add_history($pdo, $projectId, "merge_started", "Merging video and audio");

// after merge finished...
add_history($pdo, $projectId, "merged", "Merged video successfully");
?>
