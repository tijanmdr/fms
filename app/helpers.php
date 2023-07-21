<?php

// Extracting first error message from array
function validateErrorMessage($validate)
{
    foreach ($validate->messages()->getMessages() as $field_name => $messages) {
        $errors[] = $messages[0];
    }
    if (!empty($errors)) {
        return $errors[0];
    }
}

// return function
function returnMessage($status, $message, $data = [], $statusCode = 0) {
    if ($statusCode !== 0) {
        if ($data === [])
           return response()->json(['status' => $status, 'message' => $message], $statusCode);
        else 
            return response()->json(['status' => $status, 'message' => $message, 'data'=>$data], $statusCode);
    } else {
        if ($data === []) 
            return response()->json(['status' => $status, 'message' => $message]);
        else 
            return response()->json(['status' => $status, 'message' => $message, 'data'=>$data]);
    }
}

function allergicJSON() {
    $list = [
        ["Lactose"], ["Gluten"], ["Soy"]
    ];
    return $list;
}

function uploadPhotos($file, $name='') {
    $destination_path = 'uploads';
    $file_name = bin2hex(date('YmdHis')).'.'.$file->getClientOriginalExtension();
    if (!file_exists($destination_path)) {
        mkdir($destination_path, 0777, true);
    }
    $file->move($destination_path, $file_name);
    return $destination_path.'/'.$file_name;
}


function backupCheckID() {
    if (!array_key_exists('id', $req)) {
        return returnMessage(false, 'The ID field is required!');
    }
}
