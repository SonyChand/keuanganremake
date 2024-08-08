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
}
