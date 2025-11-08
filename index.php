<?php
require __DIR__ . '/config/db.php';
include __DIR__ . '/includes/header.php';

$q = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');

$sql = "SELECT r.*, u.username AS author FROM recipes r 
        LEFT JOIN users u ON r.author_id = u.id WHERE 1=1";
$params = [];

if ($q !== '') {
    $sql .= " AND (r.title LIKE :q OR r.ingredients LIKE :q OR r.steps LIKE :q)";
    $params[':q'] = "%$q%";
}
if ($category !== '') {
    $sql .= " AND r.category = :c";
    $params[':c'] = $category;
}

$sql .= " ORDER BY r.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$recipes = $stmt->fetchAll();
?>

<div class="container py-4">
  <h1 class="mb-4 text-center fw-bold">Latest Recipes</h1>

  <?php if (!$recipes): ?>
    <div class="alert alert-info text-center">No recipes found.</div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($recipes as $r): ?>
        <div class="col-md-4 col-sm-6">
          <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
            <?php if ($r['image']): ?>
              <img src="<?php echo url('uploads/' . $r['image']); ?>" class="card-img-top" alt="Recipe image" style="height:220px;object-fit:cover;">
            <?php else: ?>
              <div class="bg-secondary d-flex align-items-center justify-content-center text-white" style="height:220px;">
                <span>No image</span>
              </div>
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title mb-2"><?php echo htmlspecialchars($r['title']); ?></h5>
              <?php if ($r['category']): ?>
                <span class="badge bg-primary mb-2"><?php echo htmlspecialchars(ucfirst($r['category'])); ?></span>
              <?php endif; ?>
              <p class="card-text text-muted small">
                <?php echo htmlspecialchars(mb_strimwidth(strip_tags($r['steps']), 0, 100, '...')); ?>
              </p>
            </div>
            <div class="card-footer bg-white border-0 pb-3">
              <a href="<?php echo url('recipe.php?id=' . $r['id']); ?>" class="btn btn-outline-primary w-100">View Recipe</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
