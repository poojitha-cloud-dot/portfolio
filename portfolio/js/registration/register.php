<?php
// registration/register.php
// Basic, minimal validation and file-based storage
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$course = trim($_POST['course'] ?? '');

if ($name === '' || $email === '' || $course === '') {
    echo "Missing required fields. <a href='index.html'>Go back</a>";
    exit;
}

// sanitize to avoid break of our file format
$name = str_replace("|", "-", $name);
$email = str_replace("|", "-", $email);
$phone = str_replace("|", "-", $phone);
$course = str_replace("|", "-", $course);

$line = implode('|', [$name, $email, $phone, $course, date('Y-m-d H:i:s')]) . PHP_EOL;

$file = __DIR__ . '/registrations.txt';

// ensure writable; create if not exists
if (!file_exists($file)) {
    // attempt to create
    if (false === file_put_contents($file, '')) {
        echo "Could not create data file. Check folder permissions.";
        exit;
    }
}

// append new registration
if (file_put_contents($file, $line, FILE_APPEND | LOCK_EX) === false) {
    echo "Failed to save registration. Please try again later.";
    exit;
}

// show a simple success page
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/><title>Registered</title></head>
<body style="font-family:Arial,Helvetica,sans-serif">
  <div style="max-width:600px;margin:2rem auto;padding:1rem;border-radius:8px;background:#f7fafc">
    <h2>Registration Successful</h2>
    <p>Thank you, <?php echo htmlspecialchars($name); ?>. Your registration for <strong><?php echo htmlspecialchars($course); ?></strong> has been received.</p>
    <p><a href="index.html">Register another</a> | <a href="view.php">View registered students</a></p>
  </div>
</body>
</html>
