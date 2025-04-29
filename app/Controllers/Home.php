<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('v_login');
    }
    public function userDashboard()
    {
        // Pastikan hanya user yang bisa mengakses halaman ini
        if (session()->get('role') !== 'user') {
            return redirect()->to('/user');
        }
        

        // Mengirim data user (username) ke view
        $userData = [
            'username' => session()->get('username'), // Nama pengguna
            'role' => session()->get('role')          // Role pengguna
        ];

        return view('v_dashboard_user', $userData);
    }

    public function adminDashboard()
    {
        // Pastikan hanya admin yang bisa mengakses halaman ini
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin');
        }

        // Mengambil data pengguna dari file JSON
        $users = json_decode(file_get_contents(WRITEPATH . 'users.json'), true);

        // Menghitung total jumlah pengguna
        $userCount = count($users);

        // Menghitung jumlah pengguna berdasarkan role
        $adminCount = 0;
        $userRoleCount = 0;

        // Loop untuk menghitung jumlah user berdasarkan role
        foreach ($users as $user) {
            if ($user['role'] === 'admin') {
                $adminCount++;
            } elseif ($user['role'] === 'user') {
                $userRoleCount++;
            }
        }

        // Data yang akan dikirim ke view
        $adminData = [
            'username' => session()->get('username'),
            'role' => session()->get('role'),
            'userCount' => $userCount,       // Total pengguna
            'adminCount' => $adminCount,     // Jumlah admin
            'userRoleCount' => $userRoleCount // Jumlah user
        ];

        return view('v_dashboard_admin', $adminData);
    }



    public function users()
    {
        // Hanya admin yang bisa mengakses halaman pengguna
        if (session()->get('role') !== 'admin') {
            return redirect()->to('dashboard');
        }
        // Mengambil data pengguna dari file JSON
        $users = json_decode(file_get_contents(WRITEPATH . 'users.json'), true);

        // Mengirim data ke view
        return view('v_users', ['users' => $users]);
    }

        public function create()
    {
        return view('v_create_user');  // View untuk form tambah user
    }

    public function store()
    {
        // Ambil data dari form (misalnya)
        $newUser = [
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role')
        ];

        // Membaca data pengguna yang ada
        $filePath = WRITEPATH . 'users.json';
        $users = json_decode(file_get_contents($filePath), true);

        // Menambahkan user baru ke dalam array
        $users[] = $newUser;

        // Menyimpan data kembali ke file JSON
        file_put_contents($filePath, json_encode($users, JSON_PRETTY_PRINT));

        return redirect()->to('/users');
    }

    // Edit form user
    public function edit($username)
    {
        $users = json_decode(file_get_contents(WRITEPATH . 'users.json'), true);

        // Cari user yang ingin diubah
        $user = null;
        foreach ($users as $u) {
            if ($u['username'] == $username) {
                $user = $u;
                break;
            }
        }

        if ($user === null) {
            return redirect()->to('/users'); // User tidak ditemukan
        }

        return view('v_edit_user', ['user' => $user]);
    }

    // Update data user
    public function update($username)
    {
        $users = json_decode(file_get_contents(WRITEPATH . 'users.json'), true);

        // Cari dan update data user berdasarkan username
        foreach ($users as $key => $user) {
            if ($user['username'] == $username) {
                $users[$key]['username'] = $this->request->getPost('username');
                $users[$key]['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
                $users[$key]['role'] = $this->request->getPost('role');
                break;
            }
        }

        // Menyimpan data yang diperbarui ke dalam file JSON
        file_put_contents(WRITEPATH . 'users.json', json_encode($users, JSON_PRETTY_PRINT));

        return redirect()->to('/users');
    }

    // Hapus user
    public function delete($username)
    {
        $users = json_decode(file_get_contents(WRITEPATH . 'users.json'), true);

        // Hapus user berdasarkan username
        foreach ($users as $key => $user) {
            if ($user['username'] == $username) {
                unset($users[$key]);
                break;
            }
        }

        // Menyimpan data yang sudah diperbarui ke dalam file JSON
        file_put_contents(WRITEPATH . 'users.json', json_encode(array_values($users), JSON_PRETTY_PRINT));

        return redirect()->to('/users');
    }
}
