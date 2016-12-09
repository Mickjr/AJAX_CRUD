<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Skills extends CI_Controller {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('skills_model','skills');
    }
 
    public function index()
    {

        $this->load->helper('url');
        $this->load->library('session');
        // $this->session->set_userdata('some_name', 'some_value');
        $this->load->view('list_view');
    }
 
    public function ajax_list()
    {
        $list = $this->skills->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $skills) {
            $no++;
            $row = array();
            // $row[] = $skills->id;
            $row[] = $skills->skill;
            $row[] = $skills->something;
            $row[] = $skills->dob;
 
            //add html for action
            $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$skills->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$skills->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
         
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->skills->count_all(),
                        "recordsFiltered" => $this->skills->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->skills->get_by_id($id);
        $data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        $this->_validate();
        $data = array(
                'skill' => $this->input->post('skill'),
                'something' => $this->input->post('something'),
                'dob' => $this->input->post('dob'),
            );
        $insert = $this->skills->save($data);
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_update()
    {
        $this->_validate();
        $data = array(
                'skill' => $this->input->post('skill'),
                'something' => $this->input->post('something'),
                'dob' => $this->input->post('dob'),
            );
        $this->skills->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }
 
    public function ajax_delete($id)
    {
        $this->skills->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
 
 
    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('skill') == '')
        {
            $data['inputerror'][] = 'skill';
            $data['error_string'][] = 'Skill is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('something') == '')
        {
            $data['inputerror'][] = 'something';
            $data['error_string'][] = 'something is required';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('dob') == '')
        {
            $data['inputerror'][] = 'dob';
            $data['error_string'][] = 'Date of Birth is required';
            $data['status'] = FALSE;
        }
 
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
 
}