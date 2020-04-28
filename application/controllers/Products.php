<?php defined('BASEPATH') or exit('No direct script access allowed');

class Products extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('product_model');
    $this->load->model('categonries_model');
  }
  public function index()
  {
    $search = $this->input->get('search');
    $name = $this->input->get('name');
    $categonries_id = $this->input->get('categonries_id');
    $condition = [];
    if (!empty($search)) {
      if (!empty($name)) {
        $condition['name'] = array('$regex' => $name);
      }
      if (!empty($categonries_id)) {
        $condition['categonries'] = $this->mongo_db->create_document_id($categonries_id);
      }
    }
    $data['categonries'] = $this->categonries_model->findAll();
    $data['products'] = $this->product_model->findAll($condition);
    $data['name'] = $name;
    $data['search'] = $search;
    $data['categories_id'] = $categonries_id;

    $this->load->view('layout/head');
    $this->load->view('layout/header');
    $this->load->view('products/content', $data);
    // $this->load->view('layout/left-menu');
    $this->load->view('layout/footer');
    $this->load->view('layout/foot');
  }

  public function create()
  {
    $data['categories'] = $this->categonries_model->findAll();
    $this->load->view('layout/head');
    $this->load->view('layout/header');
    $this->load->view('products/create/content', $data);
    $this->load->view('layout/footer');
    $this->load->view('layout/foot');
  }

  public function save()
  {
    $name = $this->input->post('name');
    $price = $this->input->post('price');
    $categonries = $this->input->post('categonries');

    $data = array(
      "name" => $name,
      "price" => $price,
      "categonries" => $this->mongo_db->create_document_id($categonries),
    );

    $id = $this->product_model->insert($data);
    if (!empty($id)) {
      $this->session->set_flashdata('success-msg', 'Product Added');
      redirect('products');
    } else {
      echo "error";
    }
  }
}
