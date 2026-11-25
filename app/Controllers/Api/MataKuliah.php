<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\MataKuliahModel;
use CodeIgniter\API\ResponseTrait;

class MataKuliah extends BaseController
{
    use ResponseTrait;

    protected $matkul;

    public function __construct()
    {
        $this->matkul = new MataKuliahModel();
    }

    // GET: Semua mata kuliah
    public function index()
    {
        $data = $this->matkul
            ->orderBy('semester', 'ASC')
            ->orderBy('kode_matkul', 'ASC')
            ->findAll();

        return $this->respond([
            'status' => true,
            'message' => 'Data mata kuliah berhasil diambil',
            'total' => count($data),
            'data' => $data
        ]);
    }

    // GET: Mata kuliah berdasarkan semester
    public function semester($semester)
    {
        if (!in_array((int) $semester, [1, 3, 5, 7], true)) {
            return $this->failValidationError(
                'Semester harus 1, 3, 5, atau 7'
            );
        }

        $data = $this->matkul
            ->where('semester', (int) $semester)
            ->orderBy('kode_matkul', 'ASC')
            ->findAll();

        return $this->respond([
            'status' => true,
            'message' => 'Data mata kuliah ditemukan',
            'semester' => (int) $semester,
            'total' => count($data),
            'data' => $data
        ]);
    }

    // GET: Detail mata kuliah
    public function detail($kode)
    {
        $data = $this->matkul
            ->where('kode_matkul', $kode)
            ->first();

        if (!$data) {
            return $this->failNotFound(
                'Mata kuliah tidak ditemukan'
            );
        }

        return $this->respond([
            'status' => true,
            'message' => 'Detail mata kuliah berhasil diambil',
            'data' => $data
        ]);
    }

    // POST: Tambah mata kuliah
    public function create()
    {
        $input = $this->request->getJSON(true);

        if (!is_array($input)) {
            return $this->failValidationError(
                'Format JSON tidak valid'
            );
        }

        $rules = [
            'kode_matkul' => 'required|max_length[20]|is_unique[mata_kuliah.kode_matkul]',
            'nama_matkul' => 'required|min_length[3]|max_length[150]',
            'semester' => 'required|in_list[1,3,5,7]'
        ];

        if (!$this->validateData($input, $rules)) {
            return $this->failValidationErrors(
                $this->validator->getErrors()
            );
        }

        $this->matkul->insert([
            'kode_matkul' => $input['kode_matkul'],
            'nama_matkul' => $input['nama_matkul'],
            'semester' => (int) $input['semester'],
            'tahun_akademik' => $input['tahun_akademik'] ?? '2026/2027'
        ]);

        return $this->respondCreated([
            'status' => true,
            'message' => 'Mata kuliah berhasil ditambahkan'
        ]);
    }

    // PUT: Mengubah mata kuliah
    public function update($kode)
    {
        $data = $this->matkul
            ->where('kode_matkul', $kode)
            ->first();

        if (!$data) {
            return $this->failNotFound(
                'Mata kuliah tidak ditemukan'
            );
        }

        $input = $this->request->getJSON(true);

        if (!is_array($input) || empty($input)) {
            return $this->failValidationError(
                'Data perubahan tidak boleh kosong'
            );
        }

        $allowed = [
            'nama_matkul',
            'semester',
            'tahun_akademik'
        ];

        $update = array_intersect_key(
            $input,
            array_flip($allowed)
        );

        if (empty($update)) {
            return $this->failValidationError(
                'Tidak ada data yang dapat diperbarui'
            );
        }

        $rules = [];

        if (array_key_exists('nama_matkul', $update)) {
            $rules['nama_matkul'] =
                'required|min_length[3]|max_length[150]';
        }

        if (array_key_exists('semester', $update)) {
            $rules['semester'] = 'required|in_list[1,3,5,7]';
            $update['semester'] = (int) $update['semester'];
        }

        if (array_key_exists('tahun_akademik', $update)) {
            $rules['tahun_akademik'] = 'required|max_length[20]';
        }

        if (!$this->validateData($update, $rules)) {
            return $this->failValidationErrors(
                $this->validator->getErrors()
            );
        }

        $this->matkul->update($data['id'], $update);

        return $this->respond([
            'status' => true,
            'message' => 'Mata kuliah berhasil diperbarui'
        ]);
    }

    // DELETE: Menghapus mata kuliah
    public function delete($kode)
    {
        $data = $this->matkul
            ->where('kode_matkul', $kode)
            ->first();

        if (!$data) {
            return $this->failNotFound(
                'Mata kuliah tidak ditemukan'
            );
        }

        $this->matkul->delete($data['id']);

        return $this->respondDeleted([
            'status' => true,
            'message' => 'Mata kuliah berhasil dihapus'
        ]);
    }
}