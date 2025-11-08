<?php
require __DIR__ . '/config/db.php';
include __DIR__ . '/includes/header.php';

$cats = $pdo->query("SELECT category, COUNT(*) AS cnt FROM recipes WHERE category IS NOT NULL AND category<>'' GROUP BY category ORDER BY category")->fetchAll();
echo '<h2 class="text" style="text-align:center; margin-top:20px; text-decoration:underline;">RECIPES</h2>';

foreach($cats as $row){
  $cat = $row['category'];
  echo '<h3 class="text" style="margin-top:30px;">'.e(ucfirst($cat)).'</h3>';
  $st = $pdo->prepare("SELECT id, title, image, steps, category FROM recipes WHERE category = ? ORDER BY created_at DESC LIMIT 12");
  $st->execute([$cat]);
  $items = $st->fetchAll();
  echo '<div class="grid">';
  foreach($items as $r){
    echo '<div class="recipe-card">';
    if ($r['image']) echo '<img src="'.e(url('uploads/' . $r['image'])).'" alt="img">';
    echo '<h3>'.e($r['title']).'</h3>';
    echo '<a class="btn" href="'.e(url('recipe.php?id=' . $r['id'])).'">View Recipe</a>';
    echo '</div>';
  }
  echo '</div>';
}
include __DIR__ . '/includes/footer.php';
?>
