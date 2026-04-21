<?php
@include '../includes/config.php';
session_start();

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Create furniture_designs table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS furniture_designs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    design_name VARCHAR(200),
    length_cm DECIMAL(8,2),
    width_cm DECIMAL(8,2),
    height_cm DECIMAL(8,2),
    material_type VARCHAR(100),
    estimated_cost DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->exec($create_table);

// Save design to database
$save_success = false;
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_design'])) {
    if($user_id == ''){
        header('location:../auth/login.php');
        exit();
    }
    
    $design_name = $_POST['design_name'];
    $length = $_POST['length'];
    $width = $_POST['width'];
    $height = $_POST['height'];
    $material = $_POST['material'];
    $estimated_cost = $_POST['estimated_cost'];
    
    $sql = "INSERT INTO furniture_designs (user_id, design_name, length_cm, width_cm, height_cm, material_type, estimated_cost) 
            VALUES (:user_id, :design_name, :length, :width, :height, :material, :cost)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':design_name' => $design_name,
        ':length' => $length,
        ':width' => $width,
        ':height' => $height,
        ':material' => $material,
        ':cost' => $estimated_cost
    ]);
    $save_success = true;
}

// Get user's saved designs
$designs = [];
if($user_id != '') {
    $designs_query = "SELECT * FROM furniture_designs WHERE user_id = :user_id ORDER BY created_at DESC";
    $stmt = $conn->prepare($designs_query);
    $stmt->execute([':user_id' => $user_id]);
    $designs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>BuildMart - Furniture Designer</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../assets/css/style.css">
   <style>
      .designer-container {
          max-width: 1200px;
          margin: 0 auto;
          padding: 20px;
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 30px;
      }
      .design-form, .saved-designs {
          background: white;
          padding: 25px;
          border-radius: 8px;
          box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      }
      .design-form h3, .saved-designs h3 {
          margin-bottom: 20px;
          color: #2c3e50;
      }
      .design-card {
          background: #f9f9f9;
          padding: 15px;
          margin-bottom: 15px;
          border-radius: 8px;
          border-left: 4px solid #27ae60;
      }
      @media (max-width: 768px) {
          .designer-container {
              grid-template-columns: 1fr;
          }
      }
   </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<?php if($save_success): ?>
   <div class="message" style="background:#2ecc71; color:white; text-align:center; padding:10px;">
      Design saved successfully!
   </div>
<?php endif; ?>

<div class="heading">
   <h3>Furniture Designer</h3>
   <p><a href="home.php">home</a> / Furniture Designer</p>
</div>

<section class="designer-container">
   <div class="design-form">
      <h3>Create Your Custom Furniture Design</h3>
      <form id="designForm" method="POST">
         <div class="box" style="margin-bottom:15px;">
            <p>Design Name</p>
            <input type="text" name="design_name" id="design_name" required placeholder="e.g., My Coffee Table" class="box" style="width:100%; padding:10px;">
         </div>
         
         <div class="box" style="margin-bottom:15px;">
            <p>Length (cm)</p>
            <input type="number" id="length" name="length" step="1" required oninput="calculateCost()" class="box" style="width:100%; padding:10px;">
         </div>
         
         <div class="box" style="margin-bottom:15px;">
            <p>Width (cm)</p>
            <input type="number" id="width" name="width" step="1" required oninput="calculateCost()" class="box" style="width:100%; padding:10px;">
         </div>
         
         <div class="box" style="margin-bottom:15px;">
            <p>Height (cm)</p>
            <input type="number" id="height" name="height" step="1" required oninput="calculateCost()" class="box" style="width:100%; padding:10px;">
         </div>
         
         <div class="box" style="margin-bottom:15px;">
            <p>Material Type</p>
            <select id="material" name="material" onchange="calculateCost()" class="box" style="width:100%; padding:10px;">
               <option value="wood">Wood - $25/m³</option>
               <option value="metal">Metal - $45/m³</option>
               <option value="plastic">Plastic - $15/m³</option>
               <option value="glass">Glass - $35/m³</option>
            </select>
         </div>
         
         <div class="box" style="margin-bottom:15px;">
            <p>Estimated Cost</p>
            <input type="text" id="estimated_cost" name="estimated_cost" readonly style="width:100%; padding:10px; background:#f0f0f0; font-size:1.2em; font-weight:bold;">
         </div>
         
         <input type="submit" value="Save Design" name="save_design" class="btn" style="width:100%;">
      </form>
   </div>
   
   <div class="saved-designs">
      <h3>Your Saved Designs</h3>
      <?php if(count($designs) > 0): ?>
         <?php foreach($designs as $design): ?>
            <div class="design-card">
               <h4><?php echo htmlspecialchars($design['design_name']); ?></h4>
               <p>Dimensions: <?php echo $design['length_cm']; ?> x <?php echo $design['width_cm']; ?> x <?php echo $design['height_cm']; ?> cm</p>
               <p>Material: <?php echo ucfirst($design['material_type']); ?></p>
               <p class="price">Estimated: $<?php echo number_format($design['estimated_cost'], 2); ?></p>
               <small>Created: <?php echo date('M d, Y', strtotime($design['created_at'])); ?></small>
            </div>
         <?php endforeach; ?>
      <?php else: ?>
         <p>No designs saved yet. Create your first design above!</p>
      <?php endif; ?>
   </div>
</section>

<script>
function calculateCost() {
    let length = parseFloat(document.getElementById('length').value) || 0;
    let width = parseFloat(document.getElementById('width').value) || 0;
    let height = parseFloat(document.getElementById('height').value) || 0;
    let material = document.getElementById('material').value;
    
    let volume = (length * width * height) / 1000000;
    
    let prices = {
        'wood': 25,
        'metal': 45,
        'plastic': 15,
        'glass': 35
    };
    
    let pricePerM3 = prices[material] || 20;
    let estimatedCost = volume * pricePerM3;
    
    document.getElementById('estimated_cost').value = '$' + estimatedCost.toFixed(2);
}

window.onload = function() {
    calculateCost();
};
</script>

<?php include '../includes/footer.php'; ?>
<script src="../assets/js/script.js"></script>
</body>
</html>