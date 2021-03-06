<?php
class DangNhap extends CI_Controller
{
    /**
     * Hiển thị trang Đăng nhập và xử lý đăng nhập
     *
     * @return void
     */
    public function index()
    {
        if($this->session->userdata('logged_in'))
            redirect();

        $this->form_validation->set_rules('tenDangNhap', 'Tên đăng nhập', 'trim|required|max_length[100]|min_length[2]');
        $this->form_validation->set_rules('matKhau', 'Mật khẩu', 'trim|required|max_length[50]|min_length[6]');

        if ($this->form_validation->run() == FALSE) {
            $data['main_content'] = 'dangNhap';
            $data['title'] = 'Đăng nhập';
            $this->load->view('layouts/main-register', $data);
        } else {
            $tenDangNhap = $this->input->post('tenDangNhap');
            $matKhau = $this->input->post('matKhau');

            $this->load->model('NguoiDung_model');
            $user = $this->NguoiDung_model->login($tenDangNhap, $matKhau);

            if ($user) {
                $info = $this->NguoiDung_model->select($tenDangNhap);
                $userdata = array(
                    'tenDangNhap' => $tenDangNhap,
                    'logged_in'   => TRUE,
                    'maQH'        => $info['MaQH']
                    );
                $this->session->set_userdata($userdata);
                redirect('Home');
            } else {
                $this->session->set_flashdata('login_failed', 'Tên đăng nhập hoặc mật khẩu không chính xác.');
                redirect('DangNhap');
            }
        }
    }

    /**
     * Xử lý đăng xuất
     *
     * @return void
     */
    public function logout() {
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('tenDangNhap');
        $this->session->unset_userdata('maQH');

        $this->session->sess_destroy();

        redirect('dangNhap');      
    }
}
