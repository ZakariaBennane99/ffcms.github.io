<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_api_model');
        $this->lang->load('english_lang', 'english');
    }

    public function get_category()
    {
        $category = $this->Common_api_model->get('', 'category');
        $result = array();
        foreach ($category as $row) {
            $row['image'] = get_image_path($row['image'], 'category');
            $result[] = $row;
        }

        if (sizeof($result) > 0) {
            $response = array('status' => 200, 'message' => $this->lang->line('successfully_get'), 'result' => $result);
        } else {
            $response = array('status' => 400, 'message' => $this->lang->line('record_not_found'));
        }
        echo json_encode($response);
    }

    public function genaral_setting()
    {
        $setting = $this->Common_api_model->get('', 'general_setting', '', '', '500');
        if (!empty($setting)) {
            $response = array('status' => 200, 'result' => $setting, 'message' => $this->lang->line('successfully_get'));
        } else {
            $setting = array();
            $response = array('status' => 400, 'result' => $setting, 'message' => $this->lang->line('record_not_found'));
        }
        echo json_encode($response);
    }

    public function getLavelByCategoryId()
    {
        try {
            $this->form_validation->set_rules('category_id', 'category_id', 'required');
            if ($this->form_validation->run() == false) {
                $errors = $this->form_validation->error_array();
                sort($errors);
                $array = array('status' => 400, 'message' => $this->lang->line('required_field'));
                echo json_encode($array);exit;
            } else {
                $categoryId = trim($_REQUEST['category_id']);
                $table = 'question';
                $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
                $data['page_limit'] = $this->config->item('page_limit');
                $data['page_no'] = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
                $data['order_by'] = 'ASC';
                $data['group_by'] = 'level_id';
                $data['order_by_field'] = 'level.level_order';

                // Join data
                $joinData = ['level'];
                foreach ($joinData as $value) {
                    $joinArray[] = ['join' => $value . '.id=' . $table . '.' . $value . '_id', 'table' => $value];
                }

                $where = array($table . '.category_id="' . $categoryId . '"');
                $data['field'] = 'level.*,count(question.id) as total_question,question.category_id';
                $data['table'] = $table;
                $data['joins'] = $joinArray;
                $data['where'] = $where;
                $result = $this->Common_api_model->get_join($data);
                

                if (count($result) > 0) {

                    $levelCat = [];
                    foreach ($result as $value) {
                        $levelCat[$value->level_order] = $value;
                    }

                    $i = 0;
                    foreach ($levelCat as $key => $value) {
                        $value->is_unlock = isset($value->is_unlock) ? $value->is_unlock : 0;

                        $whereContestLeaderboard = 'user_id="' . $user_id . '" AND level_id=' . $value->id . ' AND category_id=' . $categoryId . ' AND is_unlock=1';
                        $orderByField = 'id';

                        $resultData = $this->Common_api_model->get($whereContestLeaderboard, 'contest_leaderboard', $orderByField, 'DESC', '1');

                        
                        if (count($resultData) > 0) {

                            $keySearchResult = array_search($key, array_column($result, 'level_order'));
                            
                            if ($keySearchResult) {
                                $result[$keySearchResult]->is_unlock = 1;
                            }
                        }else
                        {
                            if($i == 0)
                            {
                                $keySearchResult = array_search($key, array_column($result, 'level_order'));
                            
                                if ($keySearchResult) {
                                    $result[$keySearchResult]->is_unlock = 1;
                                }
                                $i++;
                            }
                        }
                    }
                    
                    if ($result[0]->level_order) {
                        $result[0]->is_unlock = 1;
                    }
                }


                if (isset($result) && $result > 0) {
                    $response = array('status' => 200, 'result' => $result, 'message' => $this->lang->line('successfully_get'));
                } else {
                    $response = array('status' => 400, 'message' => $this->lang->line('record_not_found'));
                }
                echo json_encode($response);
            }
        } catch (Exception $e) {
            $response = array('status' => 400, 'message' => $this->lang->line('error'));
            echo json_encode($response);exit;
        }
    }

    public function getQuestionByLavel()
    {
        try {
            $this->form_validation->set_rules('level_id', 'level_id', 'required');
            if ($this->form_validation->run() == false) {
                $errors = $this->form_validation->error_array();
                sort($errors);
                $response = array('status' => 400, 'message' => $this->lang->line('required_field'));
                echo json_encode($response);exit;
            } else {
                $levelId = trim($_REQUEST['level_id']);
                $categoryId = trim($_REQUEST['category_id']);

                $table = 'question';
                $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
                $data['page_limit'] = '10';
                $data['page_no'] = isset($_REQUEST['page_no']) ? $_REQUEST['page_no'] : 1;
                $data['order_by'] = 'RANDOM';
                $data['order_by_field'] = $table . '.id';

                // Join data
                $joinArray = [];
                $where = array($table . '.category_id="' . $categoryId . '" AND ' . $table . '.level_id="' . $levelId . '"');
                $data['field'] = $table . '.*';
                $data['table'] = $table;
                $data['joins'] = $joinArray;
                $data['where'] = $where;

                $result = $this->Common_api_model->get_join($data);

                if (count($result) > 0) {
                    $dataResult = [];
                    foreach ($result as $key => $value) {
                        if ($value->image) {
                            $value->image = base_url() . 'assets/images/question/' . $value->image;
                        }
                        $dataResult[] = $value;
                    }

                    $response = array('status' => 200, 'result' => $dataResult, 'message' => $this->lang->line('successfully_get'));
                } else {
                    $response = array('status' => 400, 'message' => $this->lang->line('record_not_found'));
                }
                echo json_encode($response);
            }
        } catch (Exception $e) {
            $response = array('status' => 400, 'message' => $this->lang->line('error'));
            echo json_encode($response);exit;
        }
    }

    public function saveQuestionReport()
    {
        try {
            $this->form_validation->set_rules('level_id', 'level_id', 'required');
            $this->form_validation->set_rules('questions_attended', 'questions_attended', 'required');
            $this->form_validation->set_rules('total_questions', 'total_questions', 'required');
            $this->form_validation->set_rules('correct_answers', 'correct_answers', 'required');
            $this->form_validation->set_rules('category_id', 'category_id', 'required');

            $this->form_validation->set_rules('user_id', 'user_id', 'required');
            if ($this->form_validation->run() == false) {
                $errors = $this->form_validation->error_array();
                sort($errors);
                $array = array('status' => 400, 'message' => $this->lang->line('required_field'));
                echo json_encode($array);exit;
            } else {
                $levelId = trim($_REQUEST['level_id']);
                $where = 'id=' . $levelId;
                $result = $this->Common_api_model->getById($where, 'level');

                if (isset($result['id'])) {
                    $score = $result['score'];

                    $totalQuestions = $_POST['total_questions'];

                    $per = (100 * $_POST['correct_answers']) / $totalQuestions;

                    $finalScore = (100 * $per) / $score;

                    if ($result['win_question_count'] > $_POST['correct_answers']) {
                        $isUnlock = 0;
                    } else {
                        
                        $isUnlock = 1;
                    }

                    $data['level_id'] = $levelId;
                    $data['questions_attended'] = $_POST['questions_attended'];
                    $data['total_questions'] = $_POST['total_questions'];
                    $data['correct_answers'] = $_POST['correct_answers'];
                    $data['user_id'] = $_POST['user_id'];
                    $data['category_id'] = $_POST['category_id'];
                    $data['score'] = $finalScore;
                    $data['is_unlock'] = $isUnlock;
                    $data['date'] = date('Y-m-d');
                    $result = $this->Common_api_model->insert($data, 'contest_leaderboard');

                    $setting = $this->Common_api_model->settings_data();
                    foreach ($setting as $set) {
                        $setn[$set->key] = $set->value;
                    }

                    $total_score = $setn['total_score'];

                    $total_score_point = $setn['total_score_point'];
                    $totalPointArray = ($finalScore * $total_score_point) / $total_score;

                    $total_points = (int) $totalPointArray;

                    $this->Common_api_model->updateByIdWithcount($_REQUEST['user_id'], 'total_points', $total_points, 'users');

                    $this->Common_api_model->updateByIdWithcount($_REQUEST['user_id'], 'total_score', $finalScore, 'users');

                    if (isset($result) && $result > 0) {
                        $response = array('status' => 200, 'message' => "Record add Successfully");
                    } else {
                        $response = array('status' => 400, 'message' => $this->lang->line('record_not_found'));
                    }
                    echo json_encode($response);
                } else {
                    $response = array('status' => 400, 'message' => 'Level not found');
                    echo json_encode($response);
                }
            }
        } catch (Exception $e) {
            $response = array('status' => 400, 'message' => $this->lang->line('error'));
            echo json_encode($response);exit;
        }
    }

    public function getTodayLeaderBoard()
    {
        try {
            $this->form_validation->set_rules('level_id', 'level_id', 'required');
            if ($this->form_validation->run() == false) {
                $errors = $this->form_validation->error_array();
                sort($errors);
                $array = array('status' => 400, 'message' => $this->lang->line('required_field'));
                echo json_encode($array);exit;
            } else {
                $date = date('Y-m-d');
                $levelId = $_REQUEST['level_id'];
                $where = 'date="' . $date . '" AND level_id=' . $levelId;
                $resultTodayRank = $this->Common_api_model->getTodayTenRankByDate($where);

                $data = [];
                foreach ($resultTodayRank as $key => $value) {

                    $where = 'id=' . $value['user_id'];
                    $user = $this->Common_api_model->getById($where, 'users');
                    $value['score'] = $value['score'];

                    $value['profile_img'] = '';
                    if (isset($user['id'])) {
                        $value['profile_img'] = get_image_path($user['profile_img'], 'users');
                        $value['name'] = $user['fullname'];
                        $value['user_total_score'] = (int) round($user['total_score']);
                    }
                    $data[] = $value;
                }

                $currentUser = (object) [];
                if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '') {
                    $whereContestLeaderboard = 'date="' . $date . '" AND user_id=' . $_REQUEST['user_id'];
                    $orderByField = 'id';
                    $result = $this->Common_api_model->get($whereContestLeaderboard, 'contest_leaderboard', $orderByField, 'DESC', '1');

                    $score = 0;
                    if (isset($result[0]['id'])) {
                        $score = $result[0]['score'];
                    }

                    $isUnlock = 0;
                    if (isset($result[0]['is_unlock'])) {
                        $isUnlock = $result[0]['is_unlock'];
                    }

                    $currentUser = $this->Common_api_model->getCurrentRankByUserId($_REQUEST['user_id']);
                    if (isset($currentUser->id)) {
                        $currentUser->profile_img = get_image_path($currentUser->profile_img, 'users');
                        $currentUser->total_score = (int) round($currentUser->total_score);
                        $currentUser->score = $score;
                        $currentUser->is_unlock = $isUnlock;
                    } else {
                        $currentUser = (object) array();
                    }
                }

                $response = array('status' => 200, 'message' => $this->lang->line('successfully_get'), 'result' => $data, 'user' => $currentUser);
                echo json_encode($response);exit;
            }
        } catch (Exception $e) {
            $response = array('status' => 400, 'message' => $this->lang->line('error'));
            echo json_encode($response);exit;
        }
    }

    public function getLeaderBoard()
    {
        try {
            $type = isset($_POST['type']) ? $_POST['type'] : '';
            if ($type == 'today') {
                $date = date('Y-m-d');
                $where = 'date="' . $date . '"';
                $result = $this->Common_api_model->getTodayTenRankByDate($where, 'contest_leaderboard');
                $data = [];
                foreach ($result as $key => $value) {
                    $whereUsersById = 'id=' . $value['user_id'];
                    $user = $this->Common_api_model->getById($whereUsersById, 'users');
                    $value['score'] = $value['score'];
                    $value['profile_img'] = '';
                    $value['rank'] = $key + 1;
                    if (isset($user['id'])) {
                        $value['profile_img'] = get_image_path($user['profile_img'], 'users');
                        $value['name'] = $user['fullname'];
                        $value['user_total_score'] = round($user['total_score']);
                    }
                    $data[] = $value;
                }

                $currentUser = (object) [];
                if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '') {
                    $date = date('Y-m-d');
                    $whereTodayRank = 'date="' . $date . '"';
                    $currentUser = $this->Common_api_model->getTodayOwnRankByDate($whereTodayRank, $_REQUEST['user_id']);
                    if (isset($currentUser->id)) {
                        $whereUsers = 'id=' . $_REQUEST['user_id'];
                        $user = $this->Common_api_model->getById($whereUsers, 'users');

                        unset($currentUser->user_id);
                        unset($currentUser->id);
                        $currentUser->id = $_REQUEST['user_id'];
                        if (isset($user['profile_img'])) {
                            $currentUser->profile_img = get_image_path($user['profile_img'], 'users');
                            $currentUser->fullname = $user['fullname'];
                        } else {
                            $currentUser->profile_img = '';
                            $currentUser->fullname = '';
                        }
                    }
                }
            } elseif ($type == 'month') {

                $first_day = date('Y-m-01'); // hard-coded '01' for first day
                $last_day = date('Y-m-t');
                $where = 'date >="' . $first_day . '" AND date <= "' . $last_day . '"';
                $order_by_field = 'max_score';
                $result = $this->Common_api_model->getTodayTenRankByDate($where, 'contest_leaderboard');

                $data = [];
                foreach ($result as $key => $value) {
                    $whereUsersById = 'id=' . $value['user_id'];
                    $user = $this->Common_api_model->getById($whereUsersById, 'users');
                    $value['score'] = $value['score'];
                    unset($value['max_score']);
                    $value['profile_img'] = '';
                    if (isset($user['id'])) {
                        $value['profile_img'] = get_image_path($user['profile_img'], 'users');
                        $value['name'] = $user['fullname'];
                        $value['user_total_score'] = round($user['total_score']);
                    }
                    $data[] = $value;
                }

                $currentUser = (object) [];
                if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '') {
                    $first_day = date('Y-m-01'); // hard-coded '01' for first day
                    $last_day = date('Y-m-t');
                    $wheredfgdg = 'date >="' . $first_day . '" AND date <= "' . $last_day . '"';

                    $currentUser = $this->Common_api_model->getTodayOwnRankByDate($wheredfgdg, $_REQUEST['user_id']);
                    $whereGetById = 'id=' . $_REQUEST['user_id'];
                    $user = $this->Common_api_model->getById($whereGetById, 'users');
                    unset($currentUser->user_id);
                    unset($currentUser->id);
                    $currentUser->id = $_REQUEST['user_id'];
                    $currentUser->profile_img = get_image_path($user['profile_img'], 'users');
                    $currentUser->fullname = $user['fullname'];
                }

            } else {

                $where = '';
                $result = $this->Common_api_model->getAllRankByUserId($where);

                $data = [];
                foreach ($result as $key => $value) {
                    $where = 'id=' . $value['id'];
                    $user = $this->Common_api_model->getById($where, 'users');
                    $value['score'] = $value['total_score'];
                    $value['profile_img'] = '';
                    if (isset($user['id'])) {
                        $value['profile_img'] = get_image_path($user['profile_img'], 'users');
                        $value['name'] = $user['fullname'];
                        $value['user_total_score'] = round($user['total_score']);
                    }
                    $data[] = $value;
                }

                $currentUser = (object) [];
                if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '') {
                    $currentUser = $this->getCurrentRankByUserId($_REQUEST['user_id']);
                }
            }

            $res = array('status' => 200, 'message' => $this->lang->line('successfully_get'), 'result' => $data, 'user' => $currentUser);
            echo json_encode($res);exit;

        } catch (Exception $e) {
            $res = array('status' => 400, 'message' => $this->lang->line('error'));
            echo json_encode($res);exit;
        }
    }

    public function RecentQuizByUser()
    {
        try {
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';

            if ($user_id) {
                $result = $this->Common_api_model->RecentQuizByUser($user_id);

                $data = [];
                foreach ($result as $key => $value) {

                    $win_status = 'lose';
                    if ($value->correct_answers >= $value->win_question_count) {
                        $win_status = 'win';
                    }

                    $value->profile_img = get_image_path($value->profile_img, 'users');
                    $value->win_status = $win_status;
                    $data[] = $value;
                }

                $res = array('status' => 200, 'message' => $this->lang->line('successfully_get'), 'result' => $data);
                echo json_encode($res);exit;
            }
        } catch (Exception $e) {
            $res = array('status' => 400, 'message' => $this->lang->line('error'));
            echo json_encode($res);exit;
        }
    }

    public function getCurrentRankByUserId($user_id)
    {
        if ($user_id) {
            $result = $this->Common_api_model->getCurrentRankByUserId($user_id);
            $result->profile_img = get_image_path($result->profile_img, 'users');
            return $result;
        } else {
            return false;
        }
    }

    public function checkStatus()
    {
        if ((isset($_POST['purchase_code']) && $_POST['purchase_code'] != '') and (isset($_POST['package_name']) && $_POST['package_name'] != '')) {
            $url = 'https://divinetechs.com/envato/secureapi/checkStatus.php';
            $envento['purchase_code'] = $_POST['purchase_code'];
            $envento['package_name'] = $_POST['package_name'];
            $envento['base_url'] = $this->config->item('base_url');
            $response = curl($url, $envento);
            if ($response->status == 200) {
                $res = array('status' => 200, 'message' => $response->message);
                echo json_encode($res);exit;
            } else {
                $res = array('status' => 400, 'message' => $response->message);
                echo json_encode($res);exit;
            }
        } else {
            $res = array('status' => 400, 'message' => 'Please enter required field');
            echo json_encode($res);exit;
        }
    }
}