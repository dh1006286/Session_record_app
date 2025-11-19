<?php

require_once __DIR__ . '/db.php';



function formats_all(): array
{
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT * 
        FROM formats
        ORDER BY name DESC
    ");

    $stmt->execute();
    return $stmt->fetchAll();
}

function records_all(): array
{
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT r.title, r.artist, r.price, f.name, r.id
        FROM records AS r
        JOIN formats AS f ON f.id = r.format_id
    ");

    $stmt->execute();
    return $stmt->fetchAll();
}

function record_insert(string $title, string $artist, float $price, int $format_id): void
{
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        INSERT INTO records (title, artist, price, format_id)
        VALUES (:title, :artist, :price, :format_id)
    ");
    $stmt->execute([
        ':title'     => $title,
        ':artist'    => $artist,
        ':price'     => $price,
        ':format_id' => $format_id
    ]);
}

function formats_all_query(): array
{
    $pdo = get_pdo();
    return $pdo->query("SELECT id, name FROM formats ORDER BY name")->fetchAll();
}

function record_delete(int $id): int
{
    $pdo = get_pdo();
    echo($id);
    $stmt = $pdo->prepare("DELETE FROM records WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->rowCount();
}

function record_get(int $id): ?array
{
    $pdo = get_pdo();
    $sql = "SELECT r.id, r.title, r.artist, r.price, r.format_id, f.name AS format_name
                FROM records r
                JOIN formats f ON f.id = r.format_id
                WHERE r.id = :id
                LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}


function record_update(int $id, string $title, string $artist, float $price, int $format_id): int
{
    $pdo = get_pdo();
    $sql = "UPDATE records
                SET title = :title,
                artist = :artist,
                price = :price,
                format_id = :format_id
                WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title'     => $title,
        ':artist'    => $artist,
        ':price'     => $price,
        ':format_id' => $format_id,
        ':id'        => $id
    ]);
    return $stmt->rowCount();
}

//1. This function is to create a new user record. 
//2. It takes the user full name, username, and the hash password. 
//3. Does not return anything. It just creates new records in the db
function user_create(string $username, string $full_name, string $hash): void {
    $pdo = get_pdo();
    $sql = "INSERT INTO users (username, full_name, password_hash)
            VALUES (:u, :f, :p)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u'=>$username, ':f'=>$full_name, ':p'=>$hash]);
}
//1. This function finds user but their username. 
//2. the parameter is the Username of the user
//3. It returns all user info or null
function user_find_by_username(string $username): ?array {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u");
    $stmt->execute([':u'=>$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

//1. this function finds records by there id
//2. the parameter is an array of ids
//3. this function returns an array of record. that has the id, title, artist, price and format name.
function records_by_ids(array $ids): array {
    if (empty($ids)) return [];
    $pdo = get_pdo();
    $ph = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT r.id, r.title, r.artist, r.price, f.name
            FROM records r
            JOIN formats f ON r.format_id = f.id
            WHERE r.id IN ($ph)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($ids);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//1. This function insert into purchases table
//2. the parameters are user id and record id
//3. This function does not return anything
function purchase_create(int $user_id, int $record_id): void {
    $pdo = get_pdo();
    $sql = "INSERT INTO purchases (user_id, record_id, purchase_date)
            VALUES (:u, :r, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u'=>$user_id, ':r'=>$record_id]);
}
