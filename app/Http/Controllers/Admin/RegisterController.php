<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\User;
use App\Services\LogService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }
    public function index(Request $request)
    {
        $user = User::when($request->filled('q'), function ($query) use ($request) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('nip', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('role', 'like', "%{$q}%");
            });
        })->get();

        return view('pages.admin.register.index', compact('user'));
    }

    public function create()
    {
        return view('pages.admin.register.create', [
            'unitkerja' => UnitKerja::all(),
            'golongan'  => Golongan::all(),
            'jabatan'   => Jabatan::all(),
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name'               => $request->name,
                'nip'                => $request->nip,
                'email'              => $request->email,
                'password'           => Hash::make($request->password),
                'role'               => $request->role,
                'status_akun'        => $request->status_akun,
                'catatan_verifikasi' => $request->catatan_verifikasi,
            ]);

            Pegawai::create([
                'user_id'        => $user->id,
                'unitkerja_id'   => $request->unitkerja_id,
                'golongan_id'    => $request->golongan_id,
                'jabatan_id'     => $request->jabatan_id,
                'status_pegawai' => $request->status_pegawai,
                'data_diri_id'   => null,
            ]);

            DB::commit();

            // Log aktivitas
            $this->logService->logAction(
                "Data pengguna {$user->name} berhasil dibuat"
            );

            return redirect()
                ->route('admin.register.index')
                ->with('success', 'User, Pegawai & Riwayat Kepegawaian berhasil ditambahkan');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->logService->logAction('Gagal membuat user baru', [
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        $pegawai = Pegawai::where('user_id', $user->id)->first();

        // Ensure these queries return collections
        $unitkerja = UnitKerja::all();
        $golongan = Golongan::all();
        $jabatan = Jabatan::all();

        return view('pages.admin.register.edit', [
            'user'      => $user,
            'pegawai'   => $pegawai,
            'unitkerja' => $unitkerja,
            'golongan'  => $golongan,
            'jabatan'   => $jabatan,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        DB::beginTransaction();

        try {
            $user->update([
                'name'               => $request->name,
                'nip'                => $request->nip,
                'email'              => $request->email,
                'role'               => $request->role,
                'status_akun'        => $request->status_akun,
                'catatan_verifikasi' => $request->catatan_verifikasi,
            ]);

            $pegawai = Pegawai::where('user_id', $user->id)->first();

            $isMutasi = false;

            if ($pegawai) {
                $isMutasi =
                    $pegawai->unitkerja_id != $request->unitkerja_id ||
                    $pegawai->golongan_id  != $request->golongan_id ||
                    $pegawai->jabatan_id   != $request->jabatan_id;
            }

            Pegawai::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'unitkerja_id'   => $request->unitkerja_id,
                    'golongan_id'    => $request->golongan_id,
                    'jabatan_id'     => $request->jabatan_id,
                    'status_pegawai' => $request->status_pegawai,
                ]
            );

            DB::commit();

            $this->logService->logAction(
                "Data pengguna {$user->name} berhasil diperbarui"
            );

            return redirect()
                ->route('admin.register.index')
                ->with('success', 'User berhasil diperbarui');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->logService->logAction('Gagal memperbarui data user', [
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        // Simpan informasi user sebelum dihapus untuk keperluan logging
        $userData = [
            'name' => $user->name,
            'role' => $user->role
        ];

        $user->delete();

        // Log aktivitas penghapusan user
        $this->logService->logAction('Menghapus user', $userData);

        return redirect()
            ->route('admin.register.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $request->ids)->get();
        $count = 0;

        foreach ($users as $user) {
            if ($user->id === auth()->id()) {
                continue; // Jangan hapus diri sendiri
            }

            $userData = [
                'name' => $user->name,
                'role' => $user->role
            ];

            $user->delete();
            $this->logService->logAction('Menghapus user massal', $userData);
            $count++;
        }

        return redirect()
            ->route('admin.register.index')
            ->with('success', "{$count} User berhasil dihapus secara massal");
    }
}
