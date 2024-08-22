<?php
defined('BASEPATH') or exit('No direct script access allowed');

function supreme()
{
    $access = get_instance();
    if (!$access->session->userdata('email') || $access->session->userdata('role') != 1) {
        redirect('dashboard');
    }
}

function super()
{
    $access = get_instance();
    if (!$access->session->userdata('email') || $access->session->userdata('role') > 2) {
        redirect('dashboard');
    }
}

function admin()
{
    $access = get_instance();
    if (!$access->session->userdata('email') || $access->session->userdata('role') > 3) {
        redirect('dashboard');
    }
}

function user()
{
    $access = get_instance();
    if (!$access->session->userdata('email')) {
        $access->session->set_flashdata('welcome', '<div class="alert alert-danger"><strong>Silahkan Login Terlebih Dahulu!!</strong></div>');
        redirect('auth');
    }
}

function sumput($email)
{
    $em   = explode("@", $email);
    $name = implode('@', array_slice($em, 0, count($em) - 1));
    $len  = floor(strlen($name) / 2);

    return substr($name, 0, $len) . str_repeat('*', $len) . "@" . end($em);
}

function perbedaan_waktu($waktu)
{
    $tes = date('Y-m-d H:i:s', time());
    $tes2 = date('Y-m-d H:i:s', $waktu);
    $awal  = date_create($tes2);
    $akhir = date_create($tes); // waktu sekarang
    $diff  = date_diff($awal, $akhir);

    if ($diff->d == 0) {
        if ($diff->h == 0) {
            if ($diff->i == 0) {
                $selisih = $diff->s . ' dtk';
            } else {
                $selisih = $diff->i . ' mnt';
            }
        } else {
            $selisih = $diff->h . ' jam';
        }
    } else {
        $selisih = $diff->days . ' hari';
    }

    echo $selisih;
    // $diff->y . ' tahun, ';
    // $diff->m . ' bulan, ';
    // $diff->d . ' hari, ';
    // $diff->h . ' jam, ';
    // $diff->i . ' menit, ';
    // $diff->s . ' detik, ';
}

function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}

function maintain()
{
    $access = get_instance();
    if ($access->session->userdata('role') != 1) {
        $cek = $access->db->get_where('maintenance')->last_row();

        if ($cek == true) {


            if (time() > $cek->mulai && time() < $cek->akhir) {
                redirect('maintenance');
            }
        }
    }
}

function terbilang($nilai)
{
    if ($nilai < 0) {
        $hasil = "minus " . trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }
    return $hasil;
}

function histori($aksi, $oleh, $oleh2, $isi, $waktu, $warna)
{
    $akses = get_instance();
    $aktivitasUser = [
        'name_act' => $aksi,
        'act_by' => $oleh,
        'email_act' => $oleh2,
        'act_content' =>  $isi,
        'time_act' => $waktu,
        'act_color' => $warna,
    ];
    $akses->db->insert('histori_aktivitas', $aktivitasUser);
}

function tanggal_indonesia_dari_timestamp($timestamp)
{
    // Konversi Unix timestamp ke format tanggal YYYY-MM-DD
    $tanggal = date('Y-m-d', $timestamp);

    // Format tanggal Indonesia
    return tanggal_indonesia($tanggal);
}

function tanggal_indonesia($tanggal)
{
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );

    // Pastikan format tanggal valid dan berisi 3 bagian
    $pecahkan = explode('-', $tanggal);
    if (count($pecahkan) === 3) {
        $tahun = (int)$pecahkan[0];
        $bulan_num = (int)$pecahkan[1];
        $hari = (int)$pecahkan[2];

        // Validasi bulan dan hari
        if ($bulan_num >= 1 && $bulan_num <= 12 && $hari >= 1 && $hari <= 31) {
            return $hari . ' ' . $bulan[$bulan_num] . ' ' . $tahun;
        } else {
            return 'Tanggal tidak valid';
        }
    } else {
        return 'Format tanggal tidak valid';
    }
}



function tanggal_indonesia2($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    if (count($pecahkan) == 2) {
        return $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }

    return 'Format Tanggal Tidak Valid';
}



function kodeRM($name, $nik)
{
    $access = get_instance();

    $initial = strtoupper(substr($name, 0, 1));


    $randomChar = strtoupper(substr(str_shuffle(substr($name, 1)), 0, 1));

    $nikLastTwoDigits = substr($nik, -2);

    $defaultCode = '01';

    $code = $initial . $randomChar . '.' . $nikLastTwoDigits . '.' . $defaultCode;

    $query = $access->db->get_where('pasien', ['id_rm' => $code]);
    if ($query->num_rows > 0) {
        return kodeRM($name, $nik);
    } else {
        return $code;
    }
}

function invoiceKode($name, $noRM)
{
    $access = get_instance();

    $tanggalSekarang = date('YmdHis');

    // Gabungkan nama pasien, no RM, dan tanggal sekarang
    $acakNama = strtoupper(substr(str_shuffle($name), 0, 1));
    $acakNoRM = strtoupper(substr(str_shuffle($noRM), 0, 1));
    $kode =  $acakNama . $acakNoRM . $tanggalSekarang;

    $kode = md5($kode);

    // Ambil 10 digit pertama dari hasil hash
    $invoiceCode = substr($kode, 0, 10);

    $query = $access->db->get_where('pembayaran', ['invoice' => $invoiceCode]);
    if ($query->num_rows > 0) {
        return invoiceKode($name, $noRM);
    } else {
        return $invoiceCode;
    }
}

function money($money)
{
    $pulus = 'Rp. ' . number_format($money, 0, ',', '.');
    return $pulus;
}
