
<?php
$page = $page ?? 1; 
?>
<link rel="stylesheet" href="../css/footer.css">
<footer>
    <div>
        <?php if ($page == 1): ?> <a href="page2.php" class="btn">Next</a>
        <?php endif; ?>
        <?php if ($page == 2): ?>
            <a href="page1.php" class="btn">Previous</a>
            <a href="page3.php" class="btn">Next</a>
        <?php endif; ?>
        <?php if ($page == 3): ?> <a href="page2.php" class="btn">Previous</a>
        <?php endif; ?>
        
        

    </div>
    <p>An-Najah National University · Computer Networks 2 · Spring 2026</p>
</footer>