<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Question extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        frontendcheck();
    }

    public function index()
    {
        $question = $this->Common_model->get('', 'question', 'id', 'DESC');
        $questions = [];
        foreach ($question as $key => $value) {

            $whereCategory = 'id=' . $value->category_id;
            $category = $this->Common_model->getById($whereCategory, 'category');
            $value->category_name = '';
            if (isset($category['name']) && $category['name'] != '') {
                $value->category_name = $category['name'];
            }

            $whereLevel = 'id=' . $value->level_id;
            $level = $this->Common_model->getById($whereLevel, 'level');
            $value->level_name = '';
            if (isset($level['name']) && $level['name'] != '') {
                $value->level_name = $level['name'];
            }

            $questions[] = $value;

        }

        $data['question'] = $questions;
        $this->load->view("admin/question/list", $data);
    }

    public function add()
    {
        $data['level'] = $this->Common_model->get('', 'level');
        $data['category'] = $this->Common_model->get('', 'category');
        $this->load->view("admin/question/add", $data);
    }

    public function save()
    {
        try {
            $this->form_validation->set_rules('category_id', 'category', 'required');
            $this->form_validation->set_rules('question', 'question', 'required');
            $this->form_validation->set_rules('answer', 'answer', 'required');
            $this->form_validation->set_rules('level_id', 'level', 'required');

            if ($this->form_validation->run() == false) {
                $errors = $this->form_validation->error_array();
                sort($errors);
                $array = array('status' => 400, 'message' => $errors);
                echo json_encode($array);exit;
            } else {
                $category_id = $_POST['category_id'];
                $question = $_POST['question'];
                $question_type = $_POST['question_type'];
                $option_a = $_POST['option_a'];
                $option_b = $_POST['option_b'];
                $option_c = $_POST['option_c'];
                $option_d = $_POST['option_d'];
                $answer = $_POST['answer'];
                $note = $_POST['note'];
                $level_id = $_POST['level_id'];

                $question_image = '';
                if ($_FILES['image']['name'] != '') {
                    $question_image = $this->Common_model->imageupload($_FILES['image'], 'image', FCPATH . 'assets/images/question');
                }

                $data = array(
                    'category_id' => $category_id,
                    'question' => $question,
                    'question_type' => $question_type,
                    'option_a' => $option_a,
                    'option_b' => $option_b,
                    'option_c' => $option_c,
                    'option_d' => $option_d,
                    'answer' => $answer,
                    'note' => $note,
                    'level_id' => $level_id,
                    'image' => $question_image,

                );

                $cat_id = $this->Common_model->insert($data, 'question');

                if ($cat_id) {
                    $res = array('status' => '200', 'message' => 'New question Create.', 'id' => $cat_id);
                    echo json_encode($res);exit;
                } else {
                    $res = array('status' => '400', 'message' => 'fail');
                    echo json_encode($res);exit;
                }
            }
        } catch (Exception $e) {
            $res = array('status' => 400, 'message' => $this->lang->line('error'));
            echo json_encode($res);exit;
        }
    }

    public function edit()
    {
        $data['level'] = $this->Common_model->get('', 'level');
        $data['category'] = $this->Common_model->get('', 'category');
        $id = $_GET['id'];
        $where = 'id="' . $id . '"';
        $data['question'] = (object) $this->Common_model->getById($where, 'question');
        $this->load->view("admin/question/edit", $data);
    }

    public function update()
    {

        try {
            $this->form_validation->set_rules('category_id', 'category', 'required');
            $this->form_validation->set_rules('question', 'question', 'required');
            $this->form_validation->set_rules('answer', 'answer', 'required');
            $this->form_validation->set_rules('level_id', 'level', 'required');

            if ($this->form_validation->run() == false) {
                $errors = $this->form_validation->error_array();
                sort($errors);
                $array = array('status' => 400, 'message' => $this->lang->line('required_field'));
                echo json_encode($array);exit;
            } else {
                $category_id = $_POST['category_id'];
                $id = $_POST['id'];
                $question = $_POST['question'];
                $question_type = $_POST['question_type'];
                $option_a = $_POST['option_a'];
                $option_b = $_POST['option_b'];
                $option_c = $_POST['option_c'];
                $option_d = $_POST['option_d'];
                $answer = $_POST['answer'];
                $note = $_POST['note'];
                $level_id = $_POST['level_id'];

                if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                    $question_image = $this->Common_model->imageupload($_FILES['image'], 'image', FCPATH . 'assets/images/question');
                } else {
                    $question_image = $_POST['question_image'];
                }

                $data = array(
                    'category_id' => $category_id,
                    'level_id' => $level_id,
                    'question' => $question,
                    'question_type' => $question_type,
                    'option_a' => $option_a,
                    'option_b' => $option_b,
                    'option_c' => $option_c,
                    'option_d' => $option_d,
                    'answer' => $answer,
                    'note' => $note,
                    'image' => $question_image,
                );

                $questionId = $this->Common_model->update($id, 'id', $data, 'question');

                if ($questionId) {
                    $res = array('status' => '200', 'message' => 'Update question Sucessfully.');
                    echo json_encode($res);exit;
                } else {
                    $res = array('status' => '400', 'message' => 'fail');
                    echo json_encode($res);exit;
                }
            }
        } catch (Exception $e) {
            $res = array('status' => 400, 'message' => $this->lang->line('error'));
            echo json_encode($res);exit;
        }
    }
}