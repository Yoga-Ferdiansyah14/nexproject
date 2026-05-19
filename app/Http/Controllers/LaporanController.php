<?php

namespace App\Http\Controllers;

use App\Models\ProgressReport;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Menampilkan semua progress report dari seluruh proyek,
     * diurutkan dari yang terbaru. Khusus untuk direktur & admin.
     */
    public function index(Request $request)
    {
        $query = ProgressReport::with(['project.client', 'reporter'])->latest();

        if (auth()->user()->role === 'team_leader') {
            $query->whereHas('project', function($q) {
                $q->where('team_leader_id', auth()->id());
            });
        }

        $reports = $query->paginate(20);

        return view('laporan.index', compact('reports'));
    }
}
