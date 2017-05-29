<?php
class DangKy extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        if($this->session->userdata('logged_in'))
            redirect(base_url());
    }
    
    public function index()
    {
        $this->form_validation->set_rules('tenDangNhap', 'Tên đăng nhập', 'trim|required|max_length[100]|min_length[2]|is_unique[NGUOIDUNG.TenDangNhap]');
        $this->form_validation->set_rules('matKhau', 'Mật khẩu', 'trim|required|max_length[50]|min_length[6]');
        $this->form_validation->set_rules('matKhauAgain', 'Mật khẩu nhập lại', 'trim|required|max_length[50]|min_length[6]|matches[matKhau]');
        $this->form_validation->set_rules('tenNguoiDung', 'Tên người dùng', 'trim|required|max_length[100]|min_length[10]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('gioiThieuBanThan', 'Giới thiệu bản thân', 'trim|required');

        if ($this->form_validation->run() == false) {
            $data['main_content'] = 'dangKy';
            $this->load->view('layouts/main-register', $data);
        } else {

			//upload avatar
			$this->upload();

			//insert information
            $this->load->model('NguoiDung_model');
            $this->session->set_flashdata('registered', 'Bạn đã đăng ký thành công. Giờ bạn có thể đăng nhập.');
            if ($this->NguoiDung_model->insert()) {
                redirect('DangNhap');
            }
        }
    }

    public function upload()
    {
        $config['upload_path']          = "./assets/images/db";
        $config['allowed_types']        = 'gif|jpg|png';
        $config['overwrite']            = TRUE;
        $config['file_name']            = "user_avatar_".$_POST["tenDangNhap"];

        $this->load->library('upload', $config);

        if (! $this->upload->do_upload('anhDaiDien')) {
            $this->session->set_flashdata('upload_error', $this->upload->display_errors());
        }

		$_POST['anhDaiDien'] = $this->upload->data('file_name');
    }
}
