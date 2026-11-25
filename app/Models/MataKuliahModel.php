<?php

namespace App\Models;

use CodeIgniter\Model;

class MataKuliahModel extends Model
{
    protected $table = 'mata_kuliah';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'kode_matkul',
        'nama_matkul',
        'semester',
        'tahun_akademik'
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';
}