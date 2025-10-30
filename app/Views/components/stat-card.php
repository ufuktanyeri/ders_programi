<!-- Statistics Card Component -->
<div class="col-md-<?php echo $size ?? '3'; ?>">
  <div class="card stat-card">
    <div class="card-body text-center">
      <div class="stat-icon mb-3">
        <i class="<?php echo $icon ?? 'fas fa-info-circle'; ?> fa-3x" style="color: <?php echo $color ?? '#3498db'; ?>;"></i>
      </div>
      <h3 class="stat-value mb-2"><?php echo $this->e($value ?? '0'); ?></h3>
      <p class="stat-label text-muted mb-0"><?php echo $this->e($label ?? 'Label'); ?></p>
      <?php if (isset($description)): ?>
        <small class="text-muted d-block mt-2"><?php echo $this->e($description); ?></small>
      <?php endif; ?>
    </div>
    <?php if (isset($link)): ?>
      <div class="card-footer bg-transparent border-0 text-center">
        <a href="<?php echo $this->e($link); ?>" class="btn btn-sm btn-outline-primary">
          Detaylar <i class="fas fa-arrow-right ms-1"></i>
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>

<style>
  .stat-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15) !important;
  }

  .stat-value {
    font-size: 2.5rem;
    font-weight: bold;
    color: #2c3e50;
    margin: 0;
  }

  .stat-label {
    font-size: 1rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .stat-icon {
    margin-bottom: 15px;
  }
</style>