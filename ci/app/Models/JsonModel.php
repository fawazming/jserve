<?php namespace App\Models;

use CodeIgniter\Model;

class JsonModel extends Model
{
    protected $table = 'json_data';
    protected $primaryKey = 'id';
    protected $allowedFields = ['slug', 'json_data', 'live', 'created_at'];
    protected $useTimestamps = false; // We use DB default timestamp

    /**
     * Get the live JSON data for a given slug
     */
    public function getLiveJsonBySlug(string $slug)
    {
        return $this->where('slug', $slug)
                    ->where('live', 1)
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }

    /**
     * Save new JSON data for a user and mark it live, set old live to 0
     */
    public function saveNewJson(string $slug, string $jsonData)
    {
        // Start transaction
        $this->db->transStart();

        // Set old live json to 0
        $this->where('slug', $slug)
             ->where('live', 1)
             ->set(['live' => 0])
             ->update();

        // Insert new live json
        $this->insert([
            'slug' => $slug,
            'json_data' => $jsonData,
            'live' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
