<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Task;
use App\Models\ProgressReport;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== USERS (1 per role) =====
        $direktur = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'direktur@fenomena.com',
            'password' => Hash::make('password'),
            'role' => 'direktur',
        ]);

        $marketing = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'marketing@fenomena.com',
            'password' => Hash::make('password'),
            'role' => 'marketing',
        ]);

        $teamLeader = User::create([
            'name' => 'Aris Pratama',
            'email' => 'teamleader@fenomena.com',
            'password' => Hash::make('password'),
            'role' => 'team_leader',
        ]);

        $tenagaAhli = User::create([
            'name' => 'Dewi Anggraini',
            'email' => 'ahli@fenomena.com',
            'password' => Hash::make('password'),
            'role' => 'tenaga_ahli',
        ]);

        $admin = User::create([
            'name' => 'Rizky Admin',
            'email' => 'admin@fenomena.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // ===== CLIENTS (3) =====
        $client1 = Client::create([
            'name' => 'PT. Maju Bersama',
            'phone' => '021-55512345',
            'address' => 'Jl. Sudirman No. 45, Jakarta Selatan',
            'type' => 'instansi',
        ]);

        $client2 = Client::create([
            'name' => 'Lumina Group',
            'phone' => '031-44498765',
            'address' => 'Jl. Darmo Permai No. 12, Surabaya',
            'type' => 'instansi',
        ]);

        $client3 = Client::create([
            'name' => 'Bapak Hendra Wijaya',
            'phone' => '08123456789',
            'address' => 'Jl. Cendana Raya No. 8, Bandung',
            'type' => 'perorangan',
        ]);

        // ===== PROJECT 1: Aktif =====
        $project1 = Project::create([
            'client_id' => $client1->id,
            'name' => 'Arsitektur Gedung A1',
            'description' => 'Proyek perencanaan dan perancangan gedung kantor 10 lantai untuk PT. Maju Bersama di kawasan Sudirman CBD. Meliputi desain arsitektur, struktur, dan MEP.',
            'start_date' => '2024-08-01',
            'end_date' => '2025-02-28',
            'status' => 'aktif',
            'team_leader_id' => $teamLeader->id,
        ]);

        ProjectMember::create(['project_id' => $project1->id, 'user_id' => $teamLeader->id, 'role_in_project' => 'Team Leader']);
        ProjectMember::create(['project_id' => $project1->id, 'user_id' => $tenagaAhli->id, 'role_in_project' => 'Structural Engineer']);

        Task::create([
            'project_id' => $project1->id,
            'assigned_to' => $tenagaAhli->id,
            'title' => 'Analisis Struktur Fondasi',
            'description' => 'Melakukan analisis struktur fondasi gedung menggunakan SAP2000.',
            'deadline' => '2024-10-15',
            'status' => 'proses',
        ]);
        Task::create([
            'project_id' => $project1->id,
            'assigned_to' => $tenagaAhli->id,
            'title' => 'Gambar Denah Lantai 1-5',
            'description' => 'Membuat gambar kerja denah lantai 1 sampai 5.',
            'deadline' => '2024-11-01',
            'status' => 'belum',
        ]);
        Task::create([
            'project_id' => $project1->id,
            'assigned_to' => $teamLeader->id,
            'title' => 'Review Dokumen RAB',
            'description' => 'Review rencana anggaran biaya proyek.',
            'deadline' => '2024-10-20',
            'status' => 'selesai',
        ]);

        ProgressReport::create([
            'project_id' => $project1->id,
            'reported_by' => $teamLeader->id,
            'week_number' => 8,
            'description' => 'Pekerjaan fondasi selesai 80%. Tim struktur sedang menyelesaikan analisis beban lantai 3-5. Dokumen RAB telah disetujui klien.',
            'percentage' => 55,
        ]);
        ProgressReport::create([
            'project_id' => $project1->id,
            'reported_by' => $teamLeader->id,
            'week_number' => 10,
            'description' => 'Progress naik signifikan. Gambar kerja lantai 1-3 sudah final. Koordinasi MEP berjalan lancar dengan sub-kontraktor.',
            'percentage' => 65,
        ]);

        Schedule::create(['project_id' => $project1->id, 'phase_name' => 'Desain Konsep', 'start_date' => '2024-08-01', 'end_date' => '2024-09-15', 'status' => 'done']);
        Schedule::create(['project_id' => $project1->id, 'phase_name' => 'Gambar Kerja', 'start_date' => '2024-09-16', 'end_date' => '2024-12-31', 'status' => 'ontrack']);
        Schedule::create(['project_id' => $project1->id, 'phase_name' => 'Dokumen Tender', 'start_date' => '2025-01-01', 'end_date' => '2025-02-28', 'status' => 'ontrack']);

        // ===== PROJECT 2: Tertunda =====
        $project2 = Project::create([
            'client_id' => $client2->id,
            'name' => 'Interior Lobby Hotel Lumina',
            'description' => 'Perancangan interior lobby utama Hotel Lumina bintang 5, termasuk area resepsionis, lounge, dan koridor utama.',
            'start_date' => '2024-06-01',
            'end_date' => '2024-12-31',
            'status' => 'tertunda',
            'team_leader_id' => $teamLeader->id,
        ]);

        ProjectMember::create(['project_id' => $project2->id, 'user_id' => $teamLeader->id, 'role_in_project' => 'Team Leader']);
        ProjectMember::create(['project_id' => $project2->id, 'user_id' => $tenagaAhli->id, 'role_in_project' => 'Interior Designer']);

        Task::create([
            'project_id' => $project2->id,
            'assigned_to' => $tenagaAhli->id,
            'title' => 'Moodboard Konsep Lobby',
            'description' => 'Menyiapkan moodboard dan referensi desain lobby hotel.',
            'deadline' => '2024-07-15',
            'status' => 'selesai',
        ]);
        Task::create([
            'project_id' => $project2->id,
            'assigned_to' => $tenagaAhli->id,
            'title' => '3D Rendering Lobby',
            'description' => 'Membuat 3D rendering area lobby utama.',
            'deadline' => '2024-09-30',
            'status' => 'proses',
        ]);

        ProgressReport::create([
            'project_id' => $project2->id,
            'reported_by' => $teamLeader->id,
            'week_number' => 4,
            'description' => 'Konsep desain disetujui klien. Mulai tahap pengembangan desain detail. Material sample sudah dikirim.',
            'percentage' => 20,
        ]);
        ProgressReport::create([
            'project_id' => $project2->id,
            'reported_by' => $teamLeader->id,
            'week_number' => 6,
            'description' => 'Proyek ditunda menunggu approval budget tambahan dari klien. 3D rendering 60% selesai.',
            'percentage' => 25,
        ]);

        Schedule::create(['project_id' => $project2->id, 'phase_name' => 'Konsep Desain', 'start_date' => '2024-06-01', 'end_date' => '2024-07-31', 'status' => 'done']);
        Schedule::create(['project_id' => $project2->id, 'phase_name' => 'Desain Detail', 'start_date' => '2024-08-01', 'end_date' => '2024-10-31', 'status' => 'delayed']);

        // ===== PROJECT 3: Selesai =====
        $project3 = Project::create([
            'client_id' => $client3->id,
            'name' => 'Renovasi Rumah Tinggal Hendra',
            'description' => 'Proyek renovasi total rumah tinggal 2 lantai milik Bapak Hendra Wijaya, termasuk perluasan area belakang dan pembaruan fasad.',
            'start_date' => '2024-03-01',
            'end_date' => '2024-08-15',
            'status' => 'selesai',
            'team_leader_id' => $teamLeader->id,
        ]);

        ProjectMember::create(['project_id' => $project3->id, 'user_id' => $teamLeader->id, 'role_in_project' => 'Team Leader']);
        ProjectMember::create(['project_id' => $project3->id, 'user_id' => $tenagaAhli->id, 'role_in_project' => 'Architect']);

        Task::create([
            'project_id' => $project3->id,
            'assigned_to' => $tenagaAhli->id,
            'title' => 'Survey Kondisi Eksisting',
            'description' => 'Survey dan dokumentasi kondisi rumah saat ini.',
            'deadline' => '2024-03-15',
            'status' => 'selesai',
        ]);
        Task::create([
            'project_id' => $project3->id,
            'assigned_to' => $teamLeader->id,
            'title' => 'Finalisasi Gambar As-Built',
            'description' => 'Membuat gambar as-built drawing setelah proyek selesai.',
            'deadline' => '2024-08-15',
            'status' => 'selesai',
        ]);

        ProgressReport::create([
            'project_id' => $project3->id,
            'reported_by' => $teamLeader->id,
            'week_number' => 12,
            'description' => 'Pekerjaan struktur dan arsitektur selesai. Finishing interior dalam tahap akhir.',
            'percentage' => 85,
        ]);
        ProgressReport::create([
            'project_id' => $project3->id,
            'reported_by' => $teamLeader->id,
            'week_number' => 16,
            'description' => 'Proyek selesai 100%. Serah terima kunci dan dokumen telah dilakukan kepada pemilik.',
            'percentage' => 100,
        ]);

        Schedule::create(['project_id' => $project3->id, 'phase_name' => 'Perencanaan', 'start_date' => '2024-03-01', 'end_date' => '2024-04-15', 'status' => 'done']);
        Schedule::create(['project_id' => $project3->id, 'phase_name' => 'Pelaksanaan', 'start_date' => '2024-04-16', 'end_date' => '2024-07-31', 'status' => 'done']);
        Schedule::create(['project_id' => $project3->id, 'phase_name' => 'Serah Terima', 'start_date' => '2024-08-01', 'end_date' => '2024-08-15', 'status' => 'done']);
    }
}
