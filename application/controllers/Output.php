<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Output extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        user();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->library('pdfgenerator');
    }

    public function pembayaran($id)
    {
        supreme();
        $pemData = $this->db->get_where('pembayaran', ['id' => $id])->row();
        $pasData = $this->db->get_where('pasien', ['id' => $pemData->id_pasien])->row();

        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'invoice_' . strtolower($pemData->invoice),
        ];
        $data['onePem'] = $pemData;
        $data['onePas'] = $pasData;


        $file_pdf = 'invoice_' . strtolower($pemData->invoice);
        $paper = 'A4';
        $orientation = 'Portrait';
        $html = $this->load->view('output/pembayaran/index', $data, true);


        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function datapembayaran()
    {

        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Pembayaran',
            'data1' => $this->db->select('pembayaran.*, pasien.nama, pasien.nik, pasien.id_rm')->join('pasien', 'pembayaran.id_pasien = pasien.id', 'left')->get('pembayaran')->result(),
        ];

        $file_pdf = strtolower($data['title']);
        $paper = 'A4';
        $orientation = 'Portrait';
        $html = $this->load->view('output/pembayaran/data', $data, true);


        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function datapengguna()
    {

        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Pengguna',
            'data1' => $this->db->get('pengguna')->result(),
        ];


        $file_pdf = strtolower($data['title']);
        $paper = 'A4';
        $orientation = 'Portrait';
        $html = $this->load->view('output/pengguna/data', $data, true);


        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function datapasien()
    {

        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Pasien',
            'data1' => $this->db->get('pasien')->result(),
        ];

        $file_pdf = strtolower($data['title']);
        $paper = 'A4';
        $orientation = 'Landscape';
        $html = $this->load->view('output/pasien/data', $data, true);


        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function datadokter()
    {

        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Dokter',
            'data1' => $this->db->select('dokter.*, pengguna.email')->join('pengguna', 'dokter.id_user = pengguna.id', 'left')->get('dokter')->result(),
        ];

        $file_pdf = strtolower($data['title']);
        $paper = 'A4';
        $orientation = 'Landscape';
        $html = $this->load->view('output/dokter/data', $data, true);


        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function dataregistrasi()
    {

        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Registrasi Pasien',
            'data1' => $this->db->select('registrasi.*, pasien.nama, pasien.id_rm, pasien.nik, dokter.nama as dokter')->join('pasien', 'registrasi.id_pasien = pasien.id', 'left')->join('dokter', 'registrasi.id_dokter = dokter.id', 'left')->get('registrasi')->result(),
        ];

        $file_pdf = strtolower($data['title']);
        $paper = 'A4';
        $orientation = 'Landscape';
        $html = $this->load->view('output/registrasi/data', $data, true);


        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function datamedik()
    {

        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Medik Pasien',
            'data1' => $this->db->select('medik.*, pasien.nama')->join('pasien', 'medik.id_pasien = pasien.id', 'left')->get('medik')->result(),
        ];

        $file_pdf = strtolower($data['title']);
        $paper = 'A4';
        $orientation = 'Landscape';
        $html = $this->load->view('output/medik/data', $data, true);


        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function dataresiko()
    {

        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Resiko Penyakit',
            'data1' => $this->db->get('resiko')->result(),
        ];

        $file_pdf = strtolower($data['title']);
        $paper = 'A4';
        $orientation = 'Portrait';
        $html = $this->load->view('output/resiko/data', $data, true);


        $this->print($html, $file_pdf, $paper, $orientation);
    }

    private function print($html, $filename = '', $paper = '', $orientation = '')
    {
        $this->pdfgenerator->generate($html, $filename, $paper, $orientation);
    }

    public function dataPemasukan()
    {
        // Ambil data pengguna dan judul
        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Pemasukan'
        ];

        // Ambil start_date dan end_date dari input form
        $start_date_input = $this->input->post('start_date');
        $end_date_input = $this->input->post('end_date');

        // Konversi ke timestamp Unix jika ada input tanggal
        $start_date = $start_date_input ? strtotime($start_date_input) : null;
        $end_date = $end_date_input ? strtotime($end_date_input) + 86400 : null; // Tambah satu hari untuk memasukkan tanggal akhir

        // Ambil data pemasukan dalam rentang tanggal atau semua data jika tanggal tidak diinputkan
        $this->db->select('pemasukan.*, asrama.nama as asrama');
        $this->db->join('asrama', 'pemasukan.id_asrama = asrama.id', 'LEFT');
        if ($start_date !== null) {
            $this->db->where('pemasukan.tanggal_masuk >=', $start_date);
        }
        if ($end_date !== null) {
            $this->db->where('pemasukan.tanggal_masuk <', $end_date);
        }
        $this->db->order_by('pemasukan.tanggal_masuk', 'ASC');
        $data['dataPemasukan'] = $this->db->get('pemasukan')->result();

        // Gabungkan data pemasukan untuk ditampilkan
        $data['dataTransaksi'] = array_map(function ($item) {
            return [
                'tanggal' => $item->tanggal_masuk,
                'jumlah' => $item->jumlah,
                'sumber' => $item->sumber,
                'keterangan' => $item->keterangan,
                'asrama' => $item->asrama
            ];
        }, $data['dataPemasukan']);

        // Sort dataTransaksi by date
        usort($data['dataTransaksi'], function ($a, $b) {
            return $a['tanggal'] - $b['tanggal'];
        });

        // Pass start_date dan end_date untuk ditampilkan di tampilan
        $data['start_date'] = $start_date_input ? date('Y-m-d', $start_date) : null;
        $data['end_date'] = $end_date_input ? date('Y-m-d', $end_date - 86400) : null;

        // Hitung total jumlah pemasukan
        $this->db->select_sum('jumlah');
        if ($start_date !== null && $end_date !== null) {
            $this->db->where('tanggal_masuk >=', $start_date)->where('tanggal_masuk <', $end_date);
        }
        $data['totalJumlah'] = $this->db->get('pemasukan')->row()->jumlah;

        // Nama file PDF
        $file_pdf = strtolower(str_replace(' ', '_', $data['title']));
        // Ukuran kertas dan orientasi
        $paper = 'A4';
        $orientation = 'Portrait';

        // Muat tampilan dan render HTML
        $html = $this->load->view('output/pemasukan/data', $data, true);

        // Panggil fungsi untuk mencetak PDF
        $this->print($html, $file_pdf, $paper, $orientation);
    }



    public function dataPengeluaran()
    {
        // Ambil data pengguna dan judul
        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Pengeluaran'
        ];

        // Ambil start_date dan end_date dari input form
        $start_date_input = $this->input->post('start_date');
        $end_date_input = $this->input->post('end_date');

        // Konversi ke timestamp Unix jika ada input tanggal
        $start_date = $start_date_input ? strtotime($start_date_input) : null;
        $end_date = $end_date_input ? strtotime($end_date_input) + 86400 : null; // Tambah satu hari untuk memasukkan tanggal akhir

        // Ambil data pengeluaran dalam rentang tanggal atau semua data jika tanggal tidak diinputkan
        $this->db->select('pengeluaran.*, asrama.nama as asrama');
        $this->db->join('asrama', 'pengeluaran.id_asrama = asrama.id', 'LEFT');
        if ($start_date !== null) {
            $this->db->where('pengeluaran.tanggal_keluar >=', $start_date);
        }
        if ($end_date !== null) {
            $this->db->where('pengeluaran.tanggal_keluar <', $end_date);
        }
        $this->db->order_by('pengeluaran.tanggal_keluar', 'ASC');
        $data['dataPengeluaran'] = $this->db->get('pengeluaran')->result();

        // Gabungkan data pengeluaran untuk ditampilkan
        $data['dataTransaksi'] = array_map(function ($item) {
            return [
                'tanggal' => $item->tanggal_keluar,
                'jumlah' => $item->jumlah,
                'kategori' => ucfirst($item->kategori),
                'keterangan' => $item->keterangan,
                'asrama' => $item->asrama
            ];
        }, $data['dataPengeluaran']);

        // Sort dataTransaksi by date
        usort($data['dataTransaksi'], function ($a, $b) {
            return $a['tanggal'] - $b['tanggal'];
        });

        // Pass start_date dan end_date untuk ditampilkan di tampilan
        $data['start_date'] = $start_date_input ? date('Y-m-d', $start_date) : null;
        $data['end_date'] = $end_date_input ? date('Y-m-d', $end_date - 86400) : null;

        // Hitung total jumlah pengeluaran
        $this->db->select_sum('jumlah');
        if ($start_date !== null && $end_date !== null) {
            $this->db->where('tanggal_keluar >=', $start_date)->where('tanggal_keluar <', $end_date);
        }
        $data['totalJumlah'] = $this->db->get('pengeluaran')->row()->jumlah;

        // Nama file PDF
        $file_pdf = strtolower(str_replace(' ', '_', $data['title']));
        // Ukuran kertas dan orientasi
        $paper = 'A4';
        $orientation = 'Portrait';

        // Muat tampilan dan render HTML
        $html = $this->load->view('output/pengeluaran/data', $data, true);

        // Panggil fungsi untuk mencetak PDF
        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function prediksiPengeluaran()
    {
        // Ambil data pengguna dan judul
        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Prediksi Pengeluaran',
            'prediksi' => $this->predict_expenses()
        ];


        // Nama file PDF
        $file_pdf = strtolower(str_replace(' ', '_', $data['title']));
        // Ukuran kertas dan orientasi
        $paper = 'A4';
        $orientation = 'Portrait';

        // Muat tampilan dan render HTML
        $html = $this->load->view('output/pengeluaran/prediksi', $data, true);

        // Panggil fungsi untuk mencetak PDF
        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function predict_expenses()
    {
        // Get current month's expenses
        $current_month = date('n'); // current month as an integer
        $current_month_expenses = $this->db
            ->select_sum('jumlah')
            ->where('MONTH(tanggal_keluar)', $current_month)
            ->get('pengeluaran')
            ->row()->jumlah;

        // Calculate the average of the last 3 months' expenses
        $last_three_months = strtotime(date('Y-m-d', strtotime('-3 months'))); // 3 months ago
        $last_three_months_expenses = $this->db
            ->select_sum('jumlah')
            ->where('tanggal_keluar >=', $last_three_months)
            ->where('tanggal_keluar <=', strtotime(date('Y-m-d')))
            ->get('pengeluaran')
            ->result_array();

        $jumlah_values = array_column($last_three_months_expenses, 'jumlah');
        $average_expenses = array_sum($jumlah_values) / count($jumlah_values);

        // Predict next month's expenses using Exponential Smoothing (alpha = 0.2)
        $alpha = 0.2;
        $predicted_expenses = $average_expenses + ($current_month_expenses - $average_expenses) * $alpha;

        // Round the predicted value to 2 decimal places
        $predicted_expenses = round($predicted_expenses, 2);

        // Return the predicted value
        return $predicted_expenses;
    }


    public function dataPembukuan()
    {
        // Ambil data pemasukan dan pengeluaran dari database
        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Pembukuan',
            'pemasukan' => $this->db->get('pemasukan')->result(),
            'pengeluaran' => $this->db->get('pengeluaran')->result(),
            'start_date' => strtotime('2024-01-01'), // Ganti dengan tanggal mulai yang sesuai
            'end_date' => strtotime('2024-12-31'),   // Ganti dengan tanggal akhir yang sesuai
            'totalPemasukan' => $this->db->select_sum('jumlah')->get('pemasukan')->row()->jumlah,
            'totalPengeluaran' => $this->db->select_sum('jumlah')->get('pengeluaran')->row()->jumlah,
            'netto' => $this->db->select_sum('jumlah')->get('pemasukan')->row()->jumlah -
                $this->db->select_sum('jumlah')->get('pengeluaran')->row()->jumlah
        ];

        // Nama file PDF
        $file_pdf = strtolower($data['title']);
        // Kertas dan orientasi
        $paper = 'A4';
        $orientation = 'Portrait';
        // Load view dan render HTML
        $html = $this->load->view('output/pembukuan/data', $data, true);

        // Panggil fungsi untuk mencetak PDF
        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function dataSantri()
    {
        // Ambil data santri dari database dengan join ke table asrama
        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Santri',
            'data1' => $this->db->select('santri.nama, santri.tgl_lahir, santri.jk, asrama.nama as nama_asrama')
                ->join('asrama', 'santri.id_asrama = asrama.id', 'LEFT')
                ->get('santri')
                ->result(),
            'start_date' => strtotime('2024-01-01'), // Ganti dengan tanggal mulai yang sesuai
            'end_date' => strtotime('2024-12-31')   // Ganti dengan tanggal akhir yang sesuai
        ];

        // Nama file PDF
        $file_pdf = strtolower($data['title']);
        // Kertas dan orientasi
        $paper = 'A4';
        $orientation = 'Portrait';
        // Load view dan render HTML
        $html = $this->load->view('output/santri/data', $data, true);

        // Panggil fungsi untuk mencetak PDF
        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function dataYayasan()
    {
        // Ambil data ustadz dari database dengan join ke table asrama dan pengguna
        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Yayasan',
            'data1' => $this->db->select('ustadz.id_user, ustadz.nama, ustadz.bidang, ustadz.jk, asrama.nama as nama_asrama, pengguna.id as id_pengguna')
                ->join('asrama', 'ustadz.id_asrama = asrama.id', 'LEFT')
                ->join('pengguna', 'ustadz.id_user = pengguna.id', 'LEFT')
                ->get('ustadz')
                ->result(),
            'start_date' => strtotime('2024-01-01'), // Ganti dengan tanggal mulai yang sesuai
            'end_date' => strtotime('2024-12-31')   // Ganti dengan tanggal akhir yang sesuai
        ];

        // Nama file PDF
        $file_pdf = strtolower($data['title']);
        // Kertas dan orientasi
        $paper = 'A4';
        $orientation = 'Portrait';
        // Load view dan render HTML
        $html = $this->load->view('output/yayasan/data', $data, true);

        // Panggil fungsi untuk mencetak PDF
        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function dataAsrama()
    {
        // Ambil data asrama dari database dengan join ke table ustadz
        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Asrama',
            'data1' => $this->db->select('asrama.*, ustadz.nama as musyrif')
                ->join('ustadz', 'asrama.id_musyrif = ustadz.id', 'LEFT')
                ->get('asrama')
                ->result(),
            'start_date' => strtotime('2024-01-01'), // Ganti dengan tanggal mulai yang sesuai
            'end_date' => strtotime('2024-12-31')   // Ganti dengan tanggal akhir yang sesuai
        ];

        // Nama file PDF
        $file_pdf = strtolower($data['title']);
        // Kertas dan orientasi
        $paper = 'A4';
        $orientation = 'Portrait';
        // Load view dan render HTML
        $html = $this->load->view('output/asrama/data', $data, true);

        // Panggil fungsi untuk mencetak PDF
        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function dataJurnal()
    {
        // Ambil data pengguna dan judul
        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Jurnal Umum'
        ];

        // Ambil start_date dan end_date dari input form
        $start_date_input = $this->input->post('start_date');
        $end_date_input = $this->input->post('end_date');

        // Konversi ke timestamp Unix jika ada input tanggal
        $start_date = $start_date_input ? strtotime($start_date_input) : null;
        $end_date = $end_date_input ? strtotime($end_date_input) + 86400 : null; // Tambah satu hari untuk memasukkan tanggal akhir

        // Ambil data jurnal dalam rentang tanggal atau semua data jika tanggal tidak diinputkan
        $this->db->select('*');
        $this->db->order_by('tanggal', 'ASC');
        $this->db->from('jurnal_umum');
        if ($start_date !== null) {
            $this->db->where('tanggal >=', $start_date);
        }
        if ($end_date !== null) {
            $this->db->where('tanggal <', $end_date);
        }
        $data['dataJurnal'] = $this->db->get()->result();

        // Gabungkan data jurnal untuk ditampilkan
        $data['dataTransaksi'] = array_map(function ($item) {
            return [
                'tanggal' => $item->tanggal,
                'keterangan' => $item->keterangan,
                'ref' => $item->ref,
                'debet' => $item->debet,
                'kredit' => $item->kredit
            ];
        }, $data['dataJurnal']);

        // Sort dataTransaksi by date
        usort($data['dataTransaksi'], function ($a, $b) {
            return $a['tanggal'] - $b['tanggal'];
        });

        // Pass start_date dan end_date untuk ditampilkan di tampilan
        $data['start_date'] = $start_date_input ? date('Y-m-d', $start_date) : null;
        $data['end_date'] = $end_date_input ? date('Y-m-d', $end_date - 86400) : null;

        // Hitung total debet dan kredit
        if ($start_date !== null && $end_date !== null) {
            $this->db->where('tanggal >=', $start_date)->where('tanggal <', $end_date);
        }
        $data['total_debet'] = $this->db->select_sum('debet')->get('jurnal_umum')->row()->debet;
        $data['total_kredit'] = $this->db->select_sum('kredit')->get('jurnal_umum')->row()->kredit;

        // Nama file PDF
        $file_pdf = strtolower(str_replace(' ', '_', $data['title']));
        // Ukuran kertas dan orientasi
        $paper = 'A4';
        $orientation = 'Portrait';

        // Muat tampilan dan render HTML
        $html = $this->load->view('output/jurnal/data', $data, true);

        // Panggil fungsi untuk mencetak PDF
        $this->print($html, $file_pdf, $paper, $orientation);
    }





    public function dataNeraca()
    {
        // Ambil data neraca_saldo dari database dengan join ke table pengguna
        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Neraca Saldo',
            'data1' => $this->db->select('neraca_saldo.*, pengguna.nama as pengguna_nama')
                ->join('pengguna', 'neraca_saldo.nama_pengguna = pengguna.nama', 'LEFT')
                ->get('neraca_saldo')
                ->result(),
            'start_date' => strtotime('2024-01-01'), // Ganti dengan tanggal mulai yang sesuai
            'end_date' => strtotime('2024-12-31')    // Ganti dengan tanggal akhir yang sesuai
        ];

        // Calculate total debit and credit
        $this->db->select_sum('debit');
        $total_debit = $this->db->get('neraca_saldo')->row()->debit;

        $this->db->select_sum('kredit');
        $total_kredit = $this->db->get('neraca_saldo')->row()->kredit;

        // Add total values to data array
        $data['total_debet'] = $total_debit;
        $data['total_kredit'] = $total_kredit;

        // Nama file PDF
        $file_pdf = strtolower(str_replace(' ', '_', $data['title']));
        // Kertas dan orientasi
        $paper = 'A4';
        $orientation = 'Portrait';
        // Load view dan render HTML
        $html = $this->load->view('output/neraca/data', $data, true);

        // Panggil fungsi untuk mencetak PDF
        $this->print($html, $file_pdf, $paper, $orientation);
    }

    public function dataLaporan()
    {
        // Ambil data pengguna dan judul
        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Laporan Keuangan'
        ];

        // Ambil start_date dan end_date dari input form
        $start_date_input = $this->input->post('start_date');
        $end_date_input = $this->input->post('end_date');

        // Konversi ke timestamp Unix jika ada input tanggal
        $start_date = $start_date_input ? strtotime($start_date_input) : null;
        $end_date = $end_date_input ? strtotime($end_date_input) + 86400 : null; // Tambah satu hari untuk memasukkan tanggal akhir

        // Ambil data pemasukan dalam rentang tanggal atau semua data jika tanggal tidak diinputkan
        $this->db->select('pemasukan.*, asrama.nama as asrama');
        $this->db->join('asrama', 'pemasukan.id_asrama = asrama.id', 'LEFT');
        if ($start_date !== null) {
            $this->db->where('pemasukan.tanggal_masuk >=', $start_date);
        }
        if ($end_date !== null) {
            $this->db->where('pemasukan.tanggal_masuk <', $end_date);
        }
        $this->db->order_by('pemasukan.tanggal_masuk', 'ASC');
        $data['dataPemasukan'] = $this->db->get('pemasukan')->result();

        // Ambil data pengeluaran dalam rentang tanggal atau semua data jika tanggal tidak diinputkan
        $this->db->select('pengeluaran.*, asrama.nama as asrama');
        $this->db->join('asrama', 'pengeluaran.id_asrama = asrama.id', 'LEFT');
        if ($start_date !== null) {
            $this->db->where('pengeluaran.tanggal_keluar >=', $start_date);
        }
        if ($end_date !== null) {
            $this->db->where('pengeluaran.tanggal_keluar <', $end_date);
        }
        $this->db->order_by('pengeluaran.tanggal_keluar', 'ASC');
        $data['dataPengeluaran'] = $this->db->get('pengeluaran')->result();

        // Hitung Saldo Awal
        $saldoAwalPemasukan = $this->db->select_sum('jumlah')->where('tanggal_masuk <', $start_date ?? time())->get('pemasukan')->row()->jumlah ?? 0;
        $saldoAwalPengeluaran = $this->db->select_sum('jumlah')->where('tanggal_keluar <', $start_date ?? time())->get('pengeluaran')->row()->jumlah ?? 0;
        $saldoAwal = $saldoAwalPemasukan - $saldoAwalPengeluaran;

        // Hitung Saldo Akhir
        $totalPemasukan = array_sum(array_column($data['dataPemasukan'], 'jumlah'));
        $totalPengeluaran = array_sum(array_column($data['dataPengeluaran'], 'jumlah'));
        $saldoAkhir = $saldoAwal + $totalPemasukan - $totalPengeluaran;

        // Gabungkan data pemasukan dan pengeluaran untuk ditampilkan
        $data['dataTransaksi'] = array_merge(
            array_map(function ($item) {
                return [
                    'tanggal' => $item->tanggal_masuk,
                    'jumlah' => $item->jumlah,
                    'tipe' => 'pemasukan',
                    'asrama' => $item->asrama
                ];
            }, $data['dataPemasukan']),
            array_map(function ($item) {
                return [
                    'tanggal' => $item->tanggal_keluar,
                    'jumlah' => $item->jumlah,
                    'tipe' => 'pengeluaran',
                    'asrama' => $item->asrama
                ];
            }, $data['dataPengeluaran'])
        );

        // Sort dataTransaksi by date
        usort($data['dataTransaksi'], function ($a, $b) {
            return $a['tanggal'] - $b['tanggal'];
        });

        // Hitung saldo awal dan akhir untuk setiap transaksi
        $saldoAwal = $this->db->select_sum('jumlah')->get('pemasukan')->row()->jumlah -
            $this->db->select_sum('jumlah')->get('pengeluaran')->row()->jumlah;

        foreach ($data['dataTransaksi'] as &$transaksi) {
            $transaksi['saldo_awal'] = $saldoAwal;
            $saldoAwal += ($transaksi['tipe'] === 'pemasukan' ? $transaksi['jumlah'] : -$transaksi['jumlah']);
            $transaksi['saldo_akhir'] = $saldoAwal;
        }

        // Pass start_date dan end_date untuk ditampilkan di tampilan
        $data['start_date'] = $start_date_input ? date('Y-m-d', $start_date) : 'Semua';
        $data['end_date'] = $end_date_input ? date('Y-m-d', $end_date - 86400) : 'Semua'; // Kurangi satu hari untuk mencocokkan tanggal akhir

        // Nama file PDF
        $file_pdf = strtolower(str_replace(' ', '_', $data['title']));
        // Ukuran kertas dan orientasi
        $paper = 'A4';
        $orientation = 'Portrait';

        // Muat tampilan dan render HTML
        $html = $this->load->view('output/laporan/data', $data, true);

        // Panggil fungsi untuk mencetak PDF
        $this->print($html, $file_pdf, $paper, $orientation);
    }
}
