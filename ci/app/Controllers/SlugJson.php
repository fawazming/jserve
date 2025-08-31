<?php namespace App\Controllers;

use App\Models\JsonModel;
use CodeIgniter\RESTful\ResourceController;

class SlugJson extends ResourceController
{
    protected $modelName = 'App\Models\JsonModel';
    protected $format = 'json';

    /**
     * Serve JSON data for a slug by slug from URL segment
     * e.g. /slugjson/johndoe
     */
    public function show($slug = null)
    {
        if (!$slug) {
            return $this->failNotFound('Slug is required');
        }

        $slugJson = $this->model->getLiveJsonBySlug($slug);

        if (!$slugJson) {
            return $this->failNotFound('No JSON data found for slug: ' . $slug);
        }

        // json_data is stored as string, decode it before sending
        $jsonData = json_decode($slugJson['json_data'], true);
        header("Access-Control-Allow-Origin: *");

        return $this->respond($jsonData);
    }

    /**
     * Optional: Endpoint to update JSON data for a slug (e.g. POST /slugjson/johndoe)
     */
    public function update($slug = null)
    {
        if (!$slug) {
            return $this->fail('Slug is required', 400);
        }

        $jsonInput = $this->request->getJSON();

        if (!$jsonInput) {
            return $this->fail('Invalid JSON input', 400);
        }

        $jsonString = json_encode($jsonInput);

        $saved = $this->model->saveNewJson($slug, $jsonString);

        if (!$saved) {
            return $this->failServerError('Failed to save JSON data');
        }
        // header("Access-Control-Allow-Origin: *");
        // return $this->respondCreated(['message' => 'JSON data updated for slug: ' . $slug]);

        $response = service('response');
        // Set a custom header
        $response->setHeader('Access-Control-Allow-Origin', '*');
        // Set the body content
        $response->setBody(['message' => 'JSON data updated for slug: ' . $slug]);

        return $response;
    }
}
