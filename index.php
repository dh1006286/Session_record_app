<?php
session_start();

require_once __DIR__ . '/data/functions.php';

$view   = filter_input(INPUT_GET, 'view') ?: 'list';
$action = filter_input(INPUT_POST, 'action');

function require_login(): void
{
    if(empty($_SESSION['user_id']))
    {
        header('Location: ?view=login');
    }
}

$public_views = ['login', 'register'];
$public_actions = ['login', 'register'];

if ($action && !in_array($action, $public_actions, true)) {
    //If the session empty, go back to the login page
    require_login();
}

if (!$action && !in_array($view, $public_views, true)) {
    require_login();
}

switch ($action) {

    //case login finds the username name in the db and checks if the hash passwords is correct
    case 'login':
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username && $password) {
        $user = user_find_by_username($username);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $view = 'list';
        } else {
            $login_error = "Invalid username or password.";
            $view = 'login';
        }
    } else {
        $login_error = "Enter both fields.";
        $view = 'login';
    }
    break;
    //in case register if checks if user already exists. If not then hash the password and make a new record into the db. It then make new session values and send user to the list view
    case 'register':
        $username  = trim((string)($_POST['username'] ?? ''));
        $full_name = trim((string)($_POST['full_name'] ?? ''));
        $password  = (string)($_POST['password'] ?? '');
        $confirm   = (string)($_POST['confirm_password'] ?? '');

        if ($username && $full_name && $password && $password === $confirm) {
            $existing = user_find_by_username($username);
            if ($existing) {
                $register_error = "That username already exists.";
                $view = 'register';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                user_create($username, $full_name, $hash);

                $user = user_find_by_username($username);
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $view = 'list';
            }
        } else {
            $register_error = "Complete all fields and match passwords.";
            $view = 'register';
        }
        break;
    case 'logout':
        $_SESSION = [];
        session_destroy();
        session_start();
        $view = 'login';
        break;


    //$_SESSION['cart'] store the record id. 
    case 'add_to_cart':
        
        require_login();
        $record_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($record_id) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            $_SESSION['cart'][] = $record_id;
        }
        $view = 'list';
        break;
    
    //if there is carts ready for checkout then foreach cart make a new record in the purchases table
    case 'checkout':
        require_login();
        $cart_ids = $_SESSION['cart'] ?? [];
        
        if ($cart_ids) {
            foreach ($cart_ids as $rid) {
                purchase_create((int)$_SESSION['user_id'], (int)$rid);
            }
            $_SESSION['cart'] = [];
        }
        $view = 'checkout_success';
        break;
    case 'create':
        $title    = trim((string)(filter_input(INPUT_POST, 'title') ?? ''));
        $artist   = trim((string)(filter_input(INPUT_POST, 'artist') ?? ''));
        $price    = (float)(filter_input(INPUT_POST, 'price') ?? 0);
        $format_id = (int)(filter_input(INPUT_POST, 'format_id') ?? 0);

        if ($title && $artist && $format_id) {
            record_insert($title, $artist, $price, $format_id);
            $view = 'created';
        } else {
            $view = 'create';
        }
        break;

    case 'delete':
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $deleted = record_delete($id);
        }
        $view = 'deleted';
        break;

    case 'edit':
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $record = record_get($id);
        }
        $view = 'create';
        break;

    case 'update':
        $id        =         filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $title     = (string)filter_input(INPUT_POST, 'title',  FILTER_UNSAFE_RAW);
        $artist    = (string)filter_input(INPUT_POST, 'artist', FILTER_UNSAFE_RAW);
        $price_in  =         filter_input(INPUT_POST, 'price',   FILTER_UNSAFE_RAW);
        $format_id =         filter_input(INPUT_POST, 'format_id', FILTER_VALIDATE_INT);

        $price = is_numeric($price_in) ? (float)$price_in : null;

        if ($id && $title !== '' && $artist !== '' && $price !== null && $format_id) {
            record_update($id, $title, $artist, $price, (int)$format_id);
        }
        $view = 'updated';
        break;

   

} 
if($view == 'cart')
{   
    $cart_ids = $_SESSION['cart'] ?? [];
    $records_in_cart = records_by_ids($cart_ids);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W. Viktor Gray - Record Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/styles.css">
</head>

<body class="container py-4">
    <?php include __DIR__ . '/components/nav.php'; ?>
    <br>
    <?php
    if ($view === 'login') {
        include __DIR__ . '/partials/login-form.php';
    }
    elseif ($view === 'register') {
        include __DIR__ . '/partials/register-form.php';
    }
    elseif ($view === 'cart') {
        include __DIR__ . '/partials/cart.php';
    }
    elseif ($view === 'checkout_success') {
        include __DIR__ . '/partials/checkout-success.php';
    }
    elseif ($view === 'list') {
        include __DIR__ . '/partials/records-list.php';
    }
    elseif ($view === 'create') {
        include __DIR__ . '/partials/record-form.php';
    }
    elseif ($view === 'created') {
        include __DIR__ . '/partials/record-created.php';
    }
    elseif ($view === 'deleted') {
        include __DIR__ . '/partials/record-deleted.php';
    }
    ?>

</body>

</html>