<?php // pas grang chose à expliquer
session_start();
session_destroy();
echo "<script>window.location.href='index.php'</script>";
?>