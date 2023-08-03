<?php

namespace App\Api\DigitalOcean;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Http\File;

class Space
{
    protected S3Client $client;
    protected string $bucketName;
    
    public function __construct()
    {
        $this->bucketName = 'cd1';
        
        $this->client = new S3Client([
            'version' => 'latest',
            'region'  => 'fra1',
            'endpoint' => 'https://fra1.digitaloceanspaces.com',
            'use_path_style_endpoint' => false,
            'credentials' => [
                'key'    => env('DIGITALOCEAN_PUB'),
                'secret' => env('DIGITALOCEAN_SECRET'),
            ]
        ]);
    }
    
    public function getClient(): S3Client
    {
        return $this->client;
    }
    
    public function uploadFile(string $filename, string $data): bool
    {
        try {
            $this->client->putObject([
                'Bucket' => $this->bucketName,
                'Key' => $filename,
                'Body' => $data,
                'ACL' => 'public-read',
                'ContentType' => 'image/jpeg'
            ]);
            
            return true;
        } catch (AwsException $e) {
            // Handle the exception
            return false;
        }
    }
    
    public function checkFolder($folderName): bool
    {
    
    }
}