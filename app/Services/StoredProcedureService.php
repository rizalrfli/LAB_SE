<?php

namespace App\Services;

use App\Models\Penelitian;
use App\Models\Publikasi;
use Illuminate\Support\Facades\DB;
use Throwable;

class StoredProcedureService
{
    public function insertPenelitian(array $data): int
    {
        try {
            $res = DB::selectOne(
                "SELECT sp_insert_penelitian(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS id",
                [
                    $data['judul'],
                    (int) $data['tahun'],
                    $data['deskripsi'] ?? null,
                    $data['status'] ?? 'ongoing',
                    (int) ($data['jumlah_tim'] ?? 0),
                    $data['peneliti_utama'] ?? null,
                    $data['anggota_tim'] ?? null,
                    $data['dosen_pembimbing'] ?? null,
                    $data['kategori'] ?? null,
                    $data['metodologi'] ?? null,
                    !empty($data['tanggal_mulai']) ? $data['tanggal_mulai'] : null,
                    !empty($data['tanggal_selesai']) ? $data['tanggal_selesai'] : null,
                    (int) ($data['progress'] ?? 0),
                    $data['keywords'] ?? null,
                    $data['link_dokumen'] ?? null,
                    $data['output_hasil'] ?? null,
                ]
            );
            if ($res && isset($res->id)) {
                return (int) $res->id;
            }
        } catch (Throwable $e) {
            // Fallback to Eloquent if SP not yet created
        }

        $p = Penelitian::create($data);
        return (int) $p->id;
    }

    public function updatePenelitian(int $id, array $data): bool
    {
        try {
            $res = DB::selectOne(
                "SELECT sp_update_penelitian(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS updated",
                [
                    $id,
                    $data['judul'],
                    (int) $data['tahun'],
                    $data['deskripsi'] ?? null,
                    $data['status'] ?? 'ongoing',
                    (int) ($data['jumlah_tim'] ?? 0),
                    $data['peneliti_utama'] ?? null,
                    $data['anggota_tim'] ?? null,
                    $data['dosen_pembimbing'] ?? null,
                    $data['kategori'] ?? null,
                    $data['metodologi'] ?? null,
                    !empty($data['tanggal_mulai']) ? $data['tanggal_mulai'] : null,
                    !empty($data['tanggal_selesai']) ? $data['tanggal_selesai'] : null,
                    (int) ($data['progress'] ?? 0),
                    $data['keywords'] ?? null,
                    $data['link_dokumen'] ?? null,
                    $data['output_hasil'] ?? null,
                ]
            );
            if ($res !== null) {
                return (bool) $res->updated;
            }
        } catch (Throwable $e) {
            // Fallback to Eloquent
        }

        return Penelitian::where('id', $id)->update($data) > 0;
    }

    public function deletePenelitian(int $id): bool
    {
        try {
            $res = DB::selectOne("SELECT sp_delete_penelitian(?) AS deleted", [$id]);
            if ($res !== null) {
                return (bool) $res->deleted;
            }
        } catch (Throwable $e) {
            // Fallback to Eloquent
        }

        return Penelitian::where('id', $id)->delete() > 0;
    }

    public function insertPublikasi(array $data): int
    {
        try {
            $res = DB::selectOne(
                "SELECT sp_insert_publikasi(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS id",
                [
                    $data['judul'],
                    $data['tipe'],
                    (int) $data['tahun'],
                    $data['penulis_utama'] ?? null,
                    $data['daftar_penulis'] ?? null,
                    $data['nama_journal_conference'] ?? null,
                    $data['volume'] ?? null,
                    $data['issue'] ?? null,
                    $data['halaman'] ?? null,
                    $data['doi'] ?? null,
                    $data['abstract'] ?? null,
                    $data['keywords'] ?? null,
                    $data['link_url'] ?? null,
                    $data['status'] ?? 'published',
                    !empty($data['scopus_indexed']),
                    !empty($data['quartile']) ? $data['quartile'] : null,
                    !empty($data['tanggal_publikasi']) ? $data['tanggal_publikasi'] : null,
                    (int) ($data['sitasi'] ?? 0),
                    !empty($data['tautan_scholar']) ? $data['tautan_scholar'] : null,
                ]
            );
            if ($res && isset($res->id)) {
                return (int) $res->id;
            }
        } catch (Throwable $e) {
            // Fallback to Eloquent
        }

        $pub = Publikasi::create($data);
        return (int) $pub->id;
    }

    public function updatePublikasi(int $id, array $data): bool
    {
        try {
            $res = DB::selectOne(
                "SELECT sp_update_publikasi(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) AS updated",
                [
                    $id,
                    $data['judul'],
                    $data['tipe'],
                    (int) $data['tahun'],
                    $data['penulis_utama'] ?? null,
                    $data['daftar_penulis'] ?? null,
                    $data['nama_journal_conference'] ?? null,
                    $data['volume'] ?? null,
                    $data['issue'] ?? null,
                    $data['halaman'] ?? null,
                    $data['doi'] ?? null,
                    $data['abstract'] ?? null,
                    $data['keywords'] ?? null,
                    $data['link_url'] ?? null,
                    $data['status'] ?? 'published',
                    !empty($data['scopus_indexed']),
                    !empty($data['quartile']) ? $data['quartile'] : null,
                    !empty($data['tanggal_publikasi']) ? $data['tanggal_publikasi'] : null,
                    (int) ($data['sitasi'] ?? 0),
                    !empty($data['tautan_scholar']) ? $data['tautan_scholar'] : null,
                ]
            );
            if ($res !== null) {
                return (bool) $res->updated;
            }
        } catch (Throwable $e) {
            // Fallback to Eloquent
        }

        return Publikasi::where('id', $id)->update($data) > 0;
    }

    public function deletePublikasi(int $id): bool
    {
        try {
            $res = DB::selectOne("SELECT sp_delete_publikasi(?) AS deleted", [$id]);
            if ($res !== null) {
                return (bool) $res->deleted;
            }
        } catch (Throwable $e) {
            // Fallback to Eloquent
        }

        return Publikasi::where('id', $id)->delete() > 0;
    }
}
