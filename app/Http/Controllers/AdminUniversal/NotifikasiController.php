<?php

namespace App\Http\Controllers\AdminUniversal;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    // 1. Saat Notifikasi Diklik (Tandai dibaca lalu alihkan ke halaman detail)
    public function klik($id)
    {
        $notif = Notifikasi::findOrFail($id);
        $notif->update(['is_read' => true]);
        return redirect($notif->tautan ?? route('admin_universal.beranda'));
    }

    // 2. Tandai Semua Dibaca
    public function bacaSemua()
    {
        Notifikasi::where('user_id', Auth::id())->update(['is_read' => true]);
        return back();
    }

    // 3. Hapus Satu Notifikasi
    public function hapus($id)
    {
        Notifikasi::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('sukses', 'Notifikasi berhasil dihapus.');
    }

    // 4. Hapus Semua Notifikasi
    public function hapusSemua()
    {
        Notifikasi::where('user_id', Auth::id())->delete();
        return back()->with('sukses', 'Semua notifikasi dibersihkan.');
    }
}
