<?php

namespace App\Models;

use CodeIgniter\Model;

class WarrantyClaimModel extends Model
{
    protected $table            = 'warranty_claims';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'warranty_id',
        'complaint',
        'damage_start_date',
        'damage_photo_path',
        'damage_video_path',
        'whatsapp',
        'handover_method',
        'shipping_address',
        'terms_agreed',
        'status',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'terms_agreed' => 'boolean',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getClaimsWithRelations(): array
    {
        return $this->select(
            'warranty_claims.*,
             warranties.warranty_number,
             warranties.sales_id,
             customers.name AS customer_name,
             customers.whatsapp AS customer_whatsapp'
        )
            ->join('warranties', 'warranties.id = warranty_claims.warranty_id')
            ->join('sales', 'sales.id = warranties.sales_id')
            ->join('customers', 'customers.id = sales.customer_id')
            ->orderBy('warranty_claims.created_at', 'DESC')
            ->findAll();
    }

    public function getClaimWithRelations(int $id): ?array   // ← TANPA 's'
    {
        return $this->select(
            'warranty_claims.*,
             warranties.warranty_number,
             warranties.sales_id,
             customers.name AS customer_name,
             customers.whatsapp AS customer_whatsapp'
        )
            ->join('warranties', 'warranties.id = warranty_claims.warranty_id')
            ->join('sales', 'sales.id = warranties.sales_id')
            ->join('customers', 'customers.id = sales.customer_id')
            ->where('warranty_claims.id', $id)
            ->first();
    }
}
