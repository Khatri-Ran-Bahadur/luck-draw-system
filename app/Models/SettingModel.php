<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['key', 'value', 'description'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'key' => 'required|is_unique[settings.key,id,{id}]',
        'value' => 'required',
    ];

    protected $validationMessages = [
        'key' => [
            'required' => 'Setting key is required',
            'is_unique' => 'Setting key already exists'
        ],
        'value' => [
            'required' => 'Setting value is required'
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getSetting($key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        return $setting ? $setting['value'] : $default;
    }

    public function setSetting($key, $value, $description = null)
    {
        $existing = $this->where('key', $key)->first();
        
        if ($existing) {
            return $this->update($existing['id'], [
                'value' => $value,
                'description' => $description ?? $existing['description']
            ]);
        } else {
            return $this->insert([
                'key' => $key,
                'value' => $value,
                'description' => $description
            ]);
        }
    }

    public function getDrawFrequency()
    {
        return (int) $this->getSetting('draw_frequency', 7);
    }

    public function setDrawFrequency($days)
    {
        return $this->setSetting('draw_frequency', $days, 'Lucky draw frequency in days');
    }

    public function getEntryFee()
    {
        return (float) $this->getSetting('entry_fee', 10.00);
    }

    public function setEntryFee($amount)
    {
        return $this->setSetting('entry_fee', $amount, 'Default entry fee for lucky draws');
    }

    public function getMaxEntries()
    {
        return (int) $this->getSetting('max_entries', 100);
    }

    public function setMaxEntries($count)
    {
        return $this->setSetting('max_entries', $count, 'Maximum entries per lucky draw');
    }

    public function getSiteSettings()
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }
        
        return $result;
    }
}
