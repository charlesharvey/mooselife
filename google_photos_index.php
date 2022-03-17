<?php




require './vendor/autoload.php';


use Google\Auth\Credentials\UserRefreshCredentials;
use Google\Photos\Library\V1\PhotosLibraryClient;
use Google\Photos\Library\V1\PhotosLibraryResourceFactory;

$authCredentials = json_decode(
    file_get_contents('./photos_credentials.json'),
    true
);
$scopes = $authCredentials['scopes'];
$cred = $authCredentials['credentials'];
$auth = array($scopes, $cred);


try {
    // Use the OAuth flow provided by the Google API Client Auth library
    // to authenticate users. See the file /src/common/common.php in the samples for a complete
    // authentication example.
    // $authCredentials =  $_SESSION['credentials'];





    // Set up the Photos Library Client that interacts with the API
    $photosLibraryClient = new PhotosLibraryClient(['credentials' => $auth]);

    // CREATE AN ALBUM
    // CREATE AN ALBUM
    // // Create a new Album object with at title
    // $newAlbum = PhotosLibraryResourceFactory::album("My First Album");
    // // Make the call to the Library API to create the new album
    // $createdAlbum = $photosLibraryClient->createAlbum($newAlbum);
    // // The creation call returns the ID of the new album
    // $albumId = $createdAlbum->getId();
    // CREATE AN ALBUM
    // CREATE AN ALBUM


    // LIST SHARED ALBUMS
    // LIST SHARED ALBUMS
    $pagedResponse = $photosLibraryClient->listSharedAlbums();
    var_dump($pagedResponse);

    // LIST SHARED ALBUMS
    // LIST SHARED ALBUMS



} catch (\Google\ApiCore\ApiException $exception) {
    // Error during album creation
    var_dump($exception);
} catch (\Google\ApiCore\ValidationException $e) {
    // Error during client creation
    var_dump($_SESSION['credentials']);
    var_dump($e);
}
