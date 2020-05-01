<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class FileController extends Controller
{
    private function getOAuthUserFile($domain)
    {
        $result = DB::collection('users')->where('domain', $domain)->get();
        if (!empty($result->all())) {
            $oauth_token = $result->all()[0];
            unset($oauth_token['_id']);
            unset($oauth_token['domain']);
            return $oauth_token;
        }
        return false;
    }

    private function getOAuthCredentialsFile()
    {
        $oauth_creds['client_id'] = '234976662969-l4se97utpvrm5m9833us0jfvvbdlb6ij.apps.googleusercontent.com';
        $oauth_creds['client_secret'] = 'hf9PZ7nM0L6fFg_6rYAIRgLd';
        $oauth_creds['redirect_uris'] = 'http://localhost';
        $oauth_creds['auth_provider_x509_cert_url'] = 'https://www.googleapis.com/oauth2/v1/certs';
        $oauth_creds['token_uri'] = 'https://oauth2.googleapis.com/token';
        $oauth_creds['auth_uri'] = 'https://accounts.google.com/o/oauth2/auth';

        return $oauth_creds;
    }

    private function setOAuthUserFile($oauth_token, $domain)
    {
        $r = DB::collection('users')
            ->where('domain', $domain)
            ->update($oauth_token);
    }

    private function refresh_token($domain)
    {

        if (!$oauth_credentials = $this->getOAuthCredentialsFile()) {
            return;
        }

        $client = new \Google_Client($oauth_credentials);
        $client->setAccessType('offline');
        $client->addScope("https://www.googleapis.com/auth/drive");
        $service = new \Google_Service_Drive($client);

        if ($oauth_token = $this->getOAuthUserFile($domain)) {
            $client->setAccessToken($oauth_token);
            $token = $client->refreshToken($oauth_token['refresh_token']);
            $this->setOAuthUserFile($token, $domain);
            $client->setAccessToken($token);
        }
    }

    private function gClient()
    {
        $domain = 'imghost.localhost';
        
        $client = new \Google_Client();
        $oauth_token = $this->getOAuthUserFile($domain);
        $client->setAccessToken($oauth_token);

        if ($client->isAccessTokenExpired()) {
            $this->refresh_token($domain);
        }

        if (!$oauth_credentials = $this->getOAuthCredentialsFile()) {
            return;
        }

        $client = new \Google_Client($oauth_credentials);
        $client->setAccessType('offline');
        $client->addScope("https://www.googleapis.com/auth/drive");
        $service = new \Google_Service_Drive($client);

        if ($oauth_token = $this->getOAuthUserFile($domain)) {
            $client->setAccessToken($oauth_token);
            $token = $client->refreshToken($oauth_token['refresh_token']);
            $this->setOAuthUserFile($token, $domain);
            $client->setAccessToken($token);
        }

        $service = new \Google_Service_Drive($client);

        return $service;
    }

    public function store(Request $request)
    {
        $service = $this->gClient();

        $realFilename = $request->file('file')->getRealPath();
        if (@getimagesize($realFilename) != 0) {
            $content = file_get_contents($realFilename);
            $fileMetadata = new \Google_Service_Drive_DriveFile(array(
                'parents' => array(env('GD_IMGDIR')),
                'name' => time() . '-' . md5_file($realFilename) . '.' . $request->file('file')->getClientOriginalName()));
            $file = $service->files->create($fileMetadata, array(
                'data' => $content,
                'uploadType' => 'multipart',
                'fields' => 'id'));

            $result = array('result' => 'success', 'url' => 'https://imghost.tech/i/' . $file->id);
            return $result;
        }
    }

    public function get(Request $request)
    {
        try {
            $service = $this->gClient();
            $content = $service->files->get($request->route('id'));
            header('Content-type: ' . $content->mimeType);
            $content = $service->files->get($request->route('id'), array("alt" => "media"));
            echo $content->getBody()->getContents();
        } catch (\Google_Service_Exception $e) {
            $result = array('result' => 'fail', 'url' => 'https://www.taterli.com/');
            return $result;
        }
    }
}
