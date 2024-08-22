<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        user();
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        // Retrieve user data and title
        $data = [
            'user' => $this->db->get_where('pengguna', ['email' => $this->session->userdata('email')])->row(),
            'title' => 'Dashboard',
        ];

        // Calculate total pemasukan
        $data['total_pemasukan'] = $this->db->select_sum('jumlah')->get('pemasukan')->row()->jumlah;

        // Calculate total pengeluaran
        $data['total_pengeluaran'] = $this->db->select_sum('jumlah')->get('pengeluaran')->row()->jumlah;

        // Calculate total number of transactions (pemasukan + pengeluaran)
        $data['total_transaksi'] = $this->db->count_all('pemasukan') + $this->db->count_all('pengeluaran');

        // Load views
        $this->load->view('templates/dash/header', $data);
        $this->load->view('templates/dash/sidenav', $data);
        $this->load->view('dash/index', $data);
        $this->load->view('templates/dash/footer');
    }
}
