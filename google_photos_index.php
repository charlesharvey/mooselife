<?php


$scopes = array(
    'https://www.googleapis.com/auth/photoslibrary.readonly',
    'https://www.googleapis.com/auth/photoslibrary',
    'https://www.googleapis.com/auth/photoslibrary.sharing'
);

$passwords =  [
    'client_id' => "1068045923615-94a7np1q0mb3dfbooturpi64alfm1e4f.apps.googleusercontent.com",
    'client_secret' => "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",
    'refresh_token' => "ya29.A0ARrdaM827z7nHyhvLTfU7jI60OH2VkrTGzadlzMnpn0hhzIcrRWsCtFWVY8mG9kcZJLqC83joJSvKW9uP7OSsem5nklt9Zph9jfDZKmC7SXZ4NjXcPJFVfBHplLH65CgFf0UeiC_C9FE7uJHUT7RjT4BW-rn"
];




require './vendor/autoload.php';



use Google\Auth\Credentials\UserRefreshCredentials;
use Google\Photos\Library\V1\PhotosLibraryClient;
use Google\Photos\Library\V1\PhotosLibraryResourceFactory;

try {
    // Use the OAuth flow provided by the Google API Client Auth library
    // to authenticate users. See the file /src/common/common.php in the samples for a complete
    // authentication example.
    $authCredentials = new UserRefreshCredentials($scope, $passwords);

    // Set up the Photos Library Client that interacts with the API
    $photosLibraryClient = new PhotosLibraryClient(['credentials' => $authCredentials]);

    // Create a new Album object with at title
    $newAlbum = PhotosLibraryResourceFactory::album("My First Album");

    // Make the call to the Library API to create the new album
    $createdAlbum = $photosLibraryClient->createAlbum($newAlbum);

    // The creation call returns the ID of the new album
    $albumId = $createdAlbum->getId();
} catch (\Google\ApiCore\ApiException $exception) {
    // Error during album creation
} catch (\Google\ApiCore\ValidationException $e) {
    // Error during client creation
    echo $exception;
}
