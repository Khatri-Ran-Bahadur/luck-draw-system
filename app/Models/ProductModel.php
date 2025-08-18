<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'product_draw_id',
        'name',
        'description',
        'image',
        'value',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'product_draw_id' => 'required|integer|greater_than[0]',
        'name' => 'required|min_length[3]|max_length[255]',
        'description' => 'required|min_length[10]',
        'value' => 'required|numeric|greater_than[0]',
        'status' => 'required|in_list[active,inactive]'
    ];

    // Get products for a specific product draw
    public function getProductsForDraw($drawId)
    {
        return $this->where('product_draw_id', $drawId)
                   ->where('status', 'active')
                   ->findAll();
    }
}
