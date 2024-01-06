<?php


use MongoDB\BSON\ObjectID;


function get_db()
{
    $mongo = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]);

    $db = $mongo->wai;

    return $db;
}

function get_photos()
{
    $db = get_db();
    return $db->photos->find()->toArray();
}

function get_users() {
    $db = get_db();
    return $db->users>find()->toArray();
}

function get_photo($id)
{
    $db = get_db();

    try {
        $objectId = new MongoDB\BSON\ObjectID($id);
    } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
        return null;
    }

    return $db->photos->findOne(['_id' => $objectId]);
}

function get_user($id)
{
    $db = get_db();

    try {
        $objectId = new MongoDB\BSON\ObjectID($id);
    } catch (MongoDB\Driver\Exception\InvalidArgumentException $e) {
        return null;
    }

    return $db->users->findOne(['_id' => $objectId]);
}

function save_photo($id, $photos)
{
    $db = get_db();

    if ($id === "") {
        $db->photos->insertOne($photos);
    } else {
        $db->photos->updateOne(['_id' => $id], ['$set' => $photos]);
    }

    return true;
}

function save_user($login, $users)
{
    $db = get_db();

    $existingUser = $db->users->findOne(['login' => $login]);

    if ($existingUser === null) {
        if ($login !== null) {
            $db->users->insertOne($users);
        } else {
            $db->users->replaceOne(['login' => $login], $users);
        }
        return true;
    } else {
        return false;
    }
}

function delete_photo($id)
{
    $db = get_db();
    $db->photos->deleteOne(['_id' => new ObjectID($id)]);
}

function authenticate_user($login, $password)
{
    $db = get_db();
    $user = $db->users->findOne(['login' => $login]);

    if ($user !== null && password_verify($password, $user['password'])) {
        return true;
    } else {
        return false;
    }
}

